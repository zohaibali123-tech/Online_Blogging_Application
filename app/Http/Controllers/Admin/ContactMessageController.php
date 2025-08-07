<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);

        $search = $request->input('search');

        $messages = ContactMessage::when($search, function ($queryBuilder) use ($search) {
                $queryBuilder->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('subject', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('admin.contact.partials.message_list', compact('messages'))->render();
        }

        return view('admin.contact.index', compact('messages', 'search'));
    }

    public function reply(Request $request)
    {
        $request->validate([
            'to_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            Mail::raw($request->body, function ($message) use ($request) {
                $message->to($request->to_email)
                        ->subject($request->subject);
            });

            return response()->json(['message' => 'Reply sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send email.'], 500);
        }
    }

    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return response()->json(['message' => 'Message deleted successfully.']);
    }

}
