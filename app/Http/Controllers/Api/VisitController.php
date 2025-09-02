<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MattDaneshvar\Survey\Models\Entry;

class VisitController extends Controller
{
    public function markVisited($id)
    {
        \Log::info((Entry::findOrFail($id))->toArray());

        try {
            // Find the entry using the Entry model
            $entry = Entry::findOrFail($id);

            // Check if already visited to avoid duplicate processing
            if ($entry->isVisited) {
                return view('layouts.partials.successVisit')->with([
                    'message' => 'This reservation was already marked as visited.',
                    'entry' => $entry
                ]);
            }

            // Update the isVisited status using the model
            $entry->isVisited = true;
            $entry->save();

            \Log::info("Entry {$id} marked as visited successfully");

            // Redirect to success page instead of returning JSON
            return view('layouts.partials.successVisit')->with([
                'message' => 'Reservation marked as visited successfully',
                'entry' => $entry
            ]);

        } catch (\Exception $e) {
            \Log::error("Error marking entry {$id} as visited: " . $e->getMessage());

            // Return error view or redirect to error page
            return view('layouts.partials.successVisit')->with([
                'error' => true,
                'message' => 'Reservation not found or could not be updated',
                'entry' => null
            ]);
        }
    }
}
