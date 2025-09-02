<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MattDaneshvar\Survey\Models\Entry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservationController extends Controller
{
    // Keep your other methods unchanged
    public function index()
    {
        // Fetch survey entries for the currently logged-in user
        $reservations = Entry::with(['survey', 'answers.question'])
            ->where('participant_id', Auth::id())
            ->get()
            ->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'survey_name' => $entry->survey->name,
                    'visit_date' => $this->getAnswerValue($entry, 'Visit Date and Time'),
                    'registration_type' => $this->getAnswerValue($entry, 'Registration Type'),
                    'full_name' => $this->getAnswerValue($entry, 'Full name'),
                    'created_at' => $entry->created_at->format('Y-m-d H:i:s'),
                    'status' => $entry->isVisited ? 'Visited' : 'Pending'
                ];
            });

        return view('guest.reservation.index', compact('reservations'));
    }

    public function details($id)
    {
        try {
            $entry = Entry::with(['survey', 'answers.question'])
                ->where('participant_id', Auth::id())
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

    private function getAnswerValue($entry, $questionContent)
    {
        $answer = $entry->answers->first(function ($answer) use ($questionContent) {
            return $answer->question->content === $questionContent;
        });

        return $answer ? $answer->value : '-';
    }


}
