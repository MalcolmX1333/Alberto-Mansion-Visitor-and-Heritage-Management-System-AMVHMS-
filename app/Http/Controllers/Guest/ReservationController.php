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
  public function index()
  {
      // Get only the current user's reservations with time tracking
      $userId = Auth::id();

      $reservations = \DB::select("
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
            AND e1.participant_id = ?
          ORDER BY e1.created_at DESC
      ", [$userId]);

      $reservations = collect($reservations)->map(function ($reservation) {
          $entry = \MattDaneshvar\Survey\Models\Entry::with(['survey', 'answers.question'])
              ->find($reservation->id);

          return [
              'id' => $reservation->id,
              'survey_name' => $reservation->survey_name,
              'visit_date' => $this->getAnswerValue($entry, 'Visit Date and Time'),
              'registration_type' => $this->getAnswerValue($entry, 'Registration Type'),
              'full_name' => $this->getAnswerValue($entry, 'Full name'),
              'created_at' => $reservation->created_at,
              'status' => $reservation->isVisited ? 'Visited' : 'Pending',
              'time_in' => $reservation->time_in ? date('Y-m-d H:i:s', strtotime($reservation->time_in)) : null,
              'time_out' => $reservation->time_out ? date('Y-m-d H:i:s', strtotime($reservation->time_out)) : null,
              'feedback_entry_id' => $reservation->feedback_entry_id,
          ];
      })->toArray();

      return view('guest.reservation.index', compact('reservations'));
  }

    public function details($id)
    {
        try {
            $entry = Entry::with(['survey', 'answers.question'])
                ->where('participant_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            // Get time tracking and feedback data
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
                'status' => $entry->isVisited ? 'Visited' : 'Pending',
                'time_in' => $timeData && $timeData->time_in ? date('Y-m-d H:i:s', strtotime($timeData->time_in)) : null,
                'time_out' => $timeData && $timeData->time_out ? date('Y-m-d H:i:s', strtotime($timeData->time_out)) : null,
                'feedback_entry_id' => $timeData ? $timeData->feedback_entry_id : null,
                'feedback_answers' => $feedbackAnswers,
            ];

            // Add demographic information for group registrations
            if ($reservation['registration_type'] === 'Group') {
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
