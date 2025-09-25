<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReservationDetailsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use MattDaneshvar\Survey\Models\Entry;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminReservationController extends Controller
{
    public function index()
    {
        // Get reservations with time_in and time_out data
        $reservations = $this->getReservationsWithTimeTracking();

        return view('admin.reservation.index', compact('reservations'));
    }

    private function getReservationsWithTimeTracking()
    {
        $reservations = DB::select("
        SELECT
            e1.id,
            s.name as survey_name,
            e1.participant_id,
            e1.created_at,
            e1.updated_at,
            e1.isVisited,
            CASE
                WHEN e1.isVisited = 1 THEN e1.updated_at
                ELSE NULL
            END as time_in,
            e2.created_at as time_out,
            e2.id as feedback_entry_id
        FROM entries e1
        LEFT JOIN surveys s ON e1.survey_id = s.id
        LEFT JOIN entries e2 ON e1.participant_id = e2.participant_id
            AND e2.survey_id = 2
        WHERE e1.survey_id = 1
        ORDER BY e1.created_at DESC
    ");

        return collect($reservations)->map(function ($reservation) {
            $entry = Entry::with(['survey', 'answers.question', 'participant'])
                ->find($reservation->id);

            return [
                'id' => $reservation->id,
                'survey_name' => $reservation->survey_name,
                'visit_date' => $this->getAnswerValue($entry, 'Visit Date and Time'),
                'registration_type' => $this->getAnswerValue($entry, 'Registration Type'),
                'full_name' => $this->getAnswerValue($entry, 'Full name'),
                'participant_name' => $entry->participant ? $entry->participant->name : 'Guest',
                'participant_email' => $entry->participant ? $entry->participant->email : '-',
                'created_at' => $reservation->created_at,
                'status' => $reservation->isVisited ? 'Visited' : 'Pending',
                'time_in' => $reservation->time_in ? date('Y-m-d H:i:s', strtotime($reservation->time_in)) : null,
                'time_out' => $reservation->time_out ? date('Y-m-d H:i:s', strtotime($reservation->time_out)) : null,
                'feedback_entry_id' => $reservation->feedback_entry_id,
            ];
        })->toArray();
    }

    public function details($id)
    {
        try {
            $entry = Entry::with(['survey', 'answers.question', 'participant'])
                ->where('id', $id)
                ->firstOrFail();

            // Get time tracking data using raw SQL
            $timeData = DB::selectOne("
            SELECT
                CASE
                    WHEN e1.isVisited = 1 THEN e1.updated_at
                    ELSE NULL
                END as time_in,
                e2.created_at as time_out,
                e2.id as feedback_entry_id
            FROM entries e1
            LEFT JOIN entries e2 ON e1.participant_id = e2.participant_id
                AND e2.survey_id = 2
            WHERE e1.id = ? AND e1.survey_id = 1
        ", [$id]);

            $feedbackAnswers = [];
            if ($timeData && $timeData->feedback_entry_id) {
                $feedbackEntry = Entry::with(['answers.question'])
                    ->find($timeData->feedback_entry_id);
                if ($feedbackEntry) {
                    foreach ($feedbackEntry->answers as $answer) {
                        $feedbackAnswers[] = [
                            'question' => $answer->question ? $answer->question->content : null,
                            'value' => $answer->value,
                        ];
                    }
                }
            }

            Log::info('Feedback Answers: ', $feedbackAnswers);


            $reservation = [
                'id' => $entry->id,
                'survey_name' => $entry->survey->name,
                'visit_date' => $this->getAnswerValue($entry, 'Visit Date and Time'),
                'registration_type' => $this->getAnswerValue($entry, 'Registration Type'),
                'full_name' => $this->getAnswerValue($entry, 'Full name'),
                'cn_bus_number' => $this->getAnswerValue($entry, 'C.N. Bus Number'),
                'address' => $this->getAnswerValue($entry, 'Address/Affliation'),
                'nationality' => $this->getAnswerValue($entry, 'Nationality'),
                'gender' => $this->getAnswerValue($entry, 'Gender'),
                'participant_name' => $entry->participant ? $entry->participant->name : 'Guest',
                'participant_email' => $entry->participant ? $entry->participant->email : '-',
                'created_at' => $entry->created_at->format('Y-m-d H:i:s'),
                'status' => $entry->isVisited ? 'Visited' : 'Pending',
                'time_in' => $timeData && $timeData->time_in ? date('Y-m-d H:i:s', strtotime($timeData->time_in)) : null,
                'time_out' => $timeData && $timeData->time_out ? date('Y-m-d H:i:s', strtotime($timeData->time_out)) : null,
                'feedback_entry_id' => $timeData ? $timeData->feedback_entry_id : null,
                'feedback_answers' => $feedbackAnswers,
            ];

            // Add demographic information for group registrations
            if ($this->getAnswerValue($entry, 'Registration Type') === 'Group') {
                $reservation['demographics'] = [
                    'grade_school' => $this->getAnswerValue($entry, 'No. of Students / Grade School'),
                    'high_school' => $this->getAnswerValue($entry, 'No. of Students / High School'),
                    'college_gradschool' => $this->getAnswerValue($entry, 'No. of Students / College / GradSchool'),
                    'pwd' => $this->getAnswerValue($entry, 'PWD'),
                    'age_17_below' => $this->getAnswerValue($entry, '17 y/o and below'),
                    'age_18_30' => $this->getAnswerValue($entry, '18-30 y/o'),
                    'age_31_45' => $this->getAnswerValue($entry, '31-45 y/o'),
                    'age_60_above' => $this->getAnswerValue($entry, '60 y/o and above')
                ];
            }

            return response()->json([
                'success' => true,
                'reservation' => $reservation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $entry = Entry::findOrFail($id);

            $entry->isVisited = $request->input('status') === 'visited' ? 1 : 0;
            $entry->save();

            return response()->json([
                'success' => true,
                'message' => 'Reservation status updated successfully',
                'new_status' => $entry->isVisited ? 'Visited' : 'Pending'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update reservation status'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $entry = Entry::findOrFail($id);
            $entry->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reservation deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete reservation'
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $usedEntryIds = [];

        $reservations = Entry::with(['survey', 'answers.question', 'participant'])
            ->where('survey_id', 1) // Only Visitor Information entries
            ->whereHas('answers', function ($q) use ($query) {
                $q->whereHas('question', function ($qq) {
                    $qq->where('content', 'Full name');
                })->where('value', 'like', "%{$query}%");
            })
            ->orWhereHas('participant', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->get()
            ->map(function ($entry1) use (&$usedEntryIds) {
                if (in_array($entry1->id, $usedEntryIds)) {
                    return null;
                }

                // Find corresponding feedback entry
                $entry2 = Entry::where('device_identifier', $entry1->device_identifier)
                    ->where('survey_id', 2)
                    ->whereNotIn('id', $usedEntryIds)
                    ->first();

                if ($entry2) {
                    $usedEntryIds[] = $entry1->id;
                    $usedEntryIds[] = $entry2->id;
                }

                // Calculate time_in and time_out
                $timeIn = null;
                if ($entry1->created_at != $entry1->updated_at) {
                    $timeIn = $entry1->updated_at->format('Y-m-d H:i:s');
                }
                $timeOut = $entry2 ? $entry2->created_at->format('Y-m-d H:i:s') : null;

                return [
                    'id' => $entry1->id,
                    'survey_name' => $entry1->survey->name,
                    'visit_date' => $this->getAnswerValue($entry1, 'Visit Date and Time'),
                    'registration_type' => $this->getAnswerValue($entry1, 'Registration Type'),
                    'full_name' => $this->getAnswerValue($entry1, 'Full name'),
                    'participant_name' => $entry1->participant ? $entry1->participant->name : 'Guest',
                    'participant_email' => $entry1->participant ? $entry1->participant->email : '-',
                    'created_at' => $entry1->created_at->format('Y-m-d H:i:s'),
                    'status' => $entry1->isVisited ? 'Visited' : 'Pending',
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                ];
            })->filter()->values();

        return response()->json([
            'success' => true,
            'reservations' => $reservations
        ]);
    }

    public function generateQR($id)
    {
        try {
            $entry = Entry::findOrFail($id);

            // Generate QR code with API route URL
            $apiUrl = url('api/visits/' . $id . '/mark-visited');

            $qrCode = QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->generate($apiUrl);

            return response($qrCode)
                ->header('Content-Type', 'image/png');

        } catch (\Exception $e) {
            abort(404, 'Reservation not found');
        }
    }

    public function markVisited($id)
    {
        try {
            $entry = Entry::findOrFail($id);
            $entry->isVisited = 1;
            $entry->save();

            return redirect()->route('admin.reservation.index')
                ->with('success', 'Reservation marked as visited successfully');

        } catch (\Exception $e) {
            return redirect()->route('admin.reservation.index')
                ->with('error', 'Failed to mark reservation as visited');
        }
    }

    private function getAnswerValue($entry, $questionContent)
    {
        $answer = $entry->answers->first(function ($answer) use ($questionContent) {
            return $answer->question && $answer->question->content === $questionContent;
        });

        return $answer ? $answer->value : '-';
    }

    public function exportReservations(Request $request)
    {
        $period = $request->input('period'); // optional, e.g., 'monthly', 'quarterly', etc.
        return Excel::download(new ReservationDetailsExport($period), 'reservation_details.xlsx');
    }

}
