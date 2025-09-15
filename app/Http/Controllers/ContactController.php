<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\ContactInquiryNotification;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function index()
    {
        return view('guest.contact.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Send notification to the site email
        Notification::route('mail', 'info@albertomansion.ph')
            ->notify(new ContactInquiryNotification($validated));

        return back()->with('success', 'Your inquiry has been sent successfully!');
    }
}
