<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $contacts = User::where('id', '!=', $currentUser->id)->orderBy('name')->get();

        $selectedContact = null;
        $messages = collect();

        if ($request->has('contact')) {
            $selectedContact = User::find($request->contact);
            if ($selectedContact) {
                $messages = Message::betweenUsers($currentUser->id, $selectedContact->id)
                    ->orderBy('created_at', 'asc')->get();
            }
        }

        return view('chat.index', compact('contacts', 'selectedContact', 'messages', 'currentUser'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content'     => 'required|string|max:5000',
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content'     => $request->content,
        ]);

        return response()->json(['ok' => true]);
    }

    // Recibe la URL ya subida desde Cloudinary en el browser
    public function sendMedia(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'media_url'   => 'required|string',
            'media_type'  => 'required|string',
            'media_path'  => 'nullable|string',
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content'     => null,
            'media_path'  => $request->media_path,
            'media_type'  => $request->media_type,
            'media_url'   => $request->media_url,
        ]);

        return response()->json(['ok' => true]);
    }

    public function getMessages(int $contactId)
    {
        $me = Auth::user();
        $messages = Message::betweenUsers($me->id, $contactId)
            ->orderBy('created_at', 'asc')->get()
            ->map(fn($m) => [
                'id'         => $m->id,
                'content'    => $m->content ?? '',
                'sender_id'  => $m->sender_id,
                'is_mine'    => $m->sender_id === $me->id,
                'created_at' => $m->created_at->format('H:i'),
                'date'       => $m->created_at->format('d/m/Y'),
                'media_url'  => $m->media_url,
                'media_type' => $m->media_type,
            ]);

        return response()->json($messages);
    }
}