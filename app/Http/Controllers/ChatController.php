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

    public function sendMedia(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'media'       => 'required|file|max:10240',
        ]);

        $file     = $request->file('media');
        $mimeType = $file->getMimeType();

        try {
            $uploaded = cloudinary()->upload($file->getRealPath(), [
                'folder'        => 'cryptochat',
                'resource_type' => 'auto',
            ]);

            $url  = $uploaded->getSecurePath();
            $path = $uploaded->getPublicId();

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content'     => null,
            'media_path'  => $path,
            'media_type'  => $mimeType,
            'media_url'   => $url,
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