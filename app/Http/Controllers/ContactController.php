<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Store in DB
        $contact = ContactMessage::create($validated);

        // Send Email to Admin
        Mail::raw("New Contact Message:\n\nName: {$contact->name}\nEmail: {$contact->email}\nSubject: {$contact->subject}\n\nMessage:\n{$contact->message}", function ($message) {
            $message->to('admin@example.com')
                    ->subject('New Contact Form Submission');
        });

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
