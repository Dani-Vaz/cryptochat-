<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatApiController extends Controller
{
    public function contacts()
    {
        $contacts = User::where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'avatar' => $u->avatar,
                'bio' => $u->bio,
            ]);

        return response()->json($contacts);
    }

    public function messages(int $contactId)
    {
        $me = Auth::user();
        $messages = Message::betweenUsers($me->id, $contactId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'content' => $m->content,
                'sender_id' => $m->sender_id,
                'is_mine' => $m->sender_id === $me->id,
                'created_at' => $m->created_at->format('H:i'),
                'date' => $m->created_at->format('d/m/Y'),
            ]);

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return response()->json([
            'id' => $message->id,
            'content' => $message->content,
            'sender_id' => $message->sender_id,
            'is_mine' => true,
            'created_at' => $message->created_at->format('H:i'),
            'date' => $message->created_at->format('d/m/Y'),
        ], 201);
    }
}
