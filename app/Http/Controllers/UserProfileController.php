<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function edit()
    {
        $currentUser = Auth::user();
        return view('profile.edit-profile', compact('currentUser'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();
            $user->avatar = "data:{$mimeType};base64,{$imageData}";
        }

        $user->save();

        return redirect()->route('user-profile.edit')->with('success', 'Perfil actualizado');
    }
}
