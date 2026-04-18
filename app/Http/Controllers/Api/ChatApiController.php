<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatApiController extends Controller
{
    public function contacts()
    {
        $contacts = User::where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get()
            ->map(fn($u) => [
                'id'     => $u->id,
                'name'   => $u->name,
                'email'  => $u->email,
                'avatar' => $u->avatar,
                'bio'    => $u->bio,
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

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content'     => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content'     => $request->content,
        ]);

        return response()->json($this->formatMessage($message), 201);
    }

    public function sendMedia(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'media'       => 'required|file|max:20480',
        ]);

        $file     = $request->file('media');
        $mimeType = $file->getMimeType();
        $path     = $file->store('chat-media', 'public');
        $url      = Storage::url($path);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content'     => null,
            'media_path'  => $path,
            'media_type'  => $mimeType,
            'media_url'   => $url,
        ]);

        return response()->json($this->formatMessage($message), 201);
    }

    public function destroy(int $messageId)
    {
        $message = Message::where('id', $messageId)
            ->where('sender_id', Auth::id())
            ->firstOrFail();

        if ($message->media_path) {
            Storage::disk('public')->delete($message->media_path);
        }

        $message->delete();

        return response()->json(['ok' => true]);
    }

    private function formatMessage(Message $m): array
    {
        return [
            'id'         => $m->id,
            'content'    => $m->content ?? '',
            'sender_id'  => $m->sender_id,
            'is_mine'    => true,
            'created_at' => $m->created_at->format('H:i'),
            'date'       => $m->created_at->format('d/m/Y'),
            'media_url'  => $m->media_url,
            'media_type' => $m->media_type,
        ];
    }
}