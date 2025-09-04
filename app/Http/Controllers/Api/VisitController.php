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
        \Log::info("Admin user ID " . auth()->id() . " attempting to mark entry {$id} as visited");

        try {
            $entry = Entry::findOrFail($id);

            if ($entry->isVisited) {
                \Log::info("Entry {$id} was already marked as visited");
                return redirect()->route('admin.reservation.index')->with([
                    'warning' => 'This reservation was already marked as visited.',
                    'entry' => $entry
                ]);
            }

            $entry->isVisited = true;
            $entry->save();

            \Log::info("Entry {$id} marked as visited successfully by admin user ID " . auth()->id());

            return redirect()->route('admin.reservation.index')->with([
                'success' => 'Reservation marked as visited successfully!',
                'entry' => $entry
            ]);

        } catch (\Exception $e) {
            \Log::error("Error marking entry {$id} as visited by user ID " . auth()->id() . ": " . $e->getMessage());

            return redirect()->route('admin.reservation.index')->with([
                'error' => 'Reservation not found or could not be updated'
            ]);
        }
    }
}
