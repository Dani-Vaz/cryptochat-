<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileApiController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar ? url('storage/' . $user->avatar) : null,
            'bio' => $user->bio,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'bio' => 'sometimes|nullable|string|max:500',
            'avatar' => 'sometimes|nullable|image|max:2048',
        ]);

        $user = Auth::user();

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }

        if ($request->hasFile('avatar')) {
            // Borrar avatar anterior
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar ? url('storage/' . $user->avatar) : null,
            'bio' => $user->bio,
            'message' => 'Perfil actualizado',
        ]);
    }
}
