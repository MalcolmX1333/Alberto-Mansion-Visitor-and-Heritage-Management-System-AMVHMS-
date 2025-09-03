<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MattDaneshvar\Survey\Models\Entry;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminReservationController extends Controller
{
    public function index()
    {
        // Fetch all survey entries with related data
        $reservations = Entry::with(['survey', 'answers.question', 'participant'])
            ->whereHas('answers.question') // Only entries with valid questions
            ->get()
            ->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'survey_name' => $entry->survey->name,
                    'visit_date' => $this->getAnswerValue($entry, 'Visit Date and Time'),
                    'registration_type' => $this->getAnswerValue($entry, 'Registration Type'),
                    'full_name' => $this->getAnswerValue($entry, 'Full name'),
                    'participant_name' => $entry->participant ? $entry->participant->name : 'Guest',
                    'participant_email' => $entry->participant ? $entry->participant->email : '-',
                    'created_at' => $entry->created_at->format('Y-m-d H:i:s'),
                    'status' => $entry->isVisited ? 'Visited' : 'Pending'
                ];
            });

        return view('admin.reservation.index', compact('reservations'));
    }

    public function details($id)
    {
        try {
            $entry = Entry::with(['survey', 'answers.question', 'participant'])
                ->where('id', $id)
                ->firstOrFail();

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
                'status' => $entry->isVisited ? 'Visited' : 'Pending'
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

        $reservations = Entry::with(['survey', 'answers.question', 'participant'])
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
            ->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'survey_name' => $entry->survey->name,
                    'visit_date' => $this->getAnswerValue($entry, 'Visit Date and Time'),
                    'registration_type' => $this->getAnswerValue($entry, 'Registration Type'),
                    'full_name' => $this->getAnswerValue($entry, 'Full name'),
                    'participant_name' => $entry->participant ? $entry->participant->name : 'Guest',
                    'participant_email' => $entry->participant ? $entry->participant->email : '-',
                    'created_at' => $entry->created_at->format('Y-m-d H:i:s'),
                    'status' => $entry->isVisited ? 'Visited' : 'Pending'
                ];
            });

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
}
