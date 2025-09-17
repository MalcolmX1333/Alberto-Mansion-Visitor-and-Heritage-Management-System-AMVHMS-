<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        return view('event.index');
    }

    public function getEvents()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'start_date' => 'required|date',
        'image_url' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    $imagePath = null;
    if ($request->hasFile('image_url')) {
        // Store the image directly in public/storage/images
        $filename = $request->file('image_url')->getClientOriginalName();
       // Store the image using the default 'public' disk
       $imagePath = $request->file('image_url')->storeAs('', $filename, 'public');
    }

        $startDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_date, 'Asia/Manila');
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d\TH:i', $request->end_date, 'Asia/Manila') : null;


    Event::create([
        'title' => $request->title,
        'description' => $request->description,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'location' => $request->location,
        'image_url' => $imagePath
    ]);

    return response()->json(['success' => true, 'message' => 'Event created successfully']);
}

public function edit(Request $request, $id)
{
    Log::info('Edit function called', ['id' => $id]);

    $request->validate([
        'title' => 'required|string|max:255',
        'start_date' => 'required|date',
        'image_url' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    $event = Event::findOrFail($id);

    $imagePath = $event->image_url;
    if ($request->hasFile('image_url')) {
        // Delete the old image if it exists
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
            Log::info('Previous image deleted', ['image_url' => $imagePath]);
        }
        // Store the new image directly in public/storage/images
        $filename = $request->file('image_url')->getClientOriginalName();
        $imagePath = $request->file('image_url')->storeAs('', $filename, 'public'); // Updated to use your custom disk
    }

     $startDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_date, 'Asia/Manila');
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d\TH:i', $request->end_date, 'Asia/Manila') : null;


    $event->update([
        'title' => $request->title,
        'description' => $request->description,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'location' => $request->location,
        'image_url' => $imagePath
    ]);

    return response()->json(['success' => true, 'message' => 'Event updated successfully']);
}


    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Delete the image if it exists
        if ($event->image_url) {
            Storage::disk('public')->delete($event->image_url);
            Log::info('Image deleted', ['image_url' => $event->image_url]);
        }

        // Delete the event
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ], 200);
    }
}
