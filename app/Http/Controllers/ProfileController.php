<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProfileController extends Controller
{

        // Profile Image Upload - WORKING VERSION - PRECROPPER
    public function uploadPhoto(Request $request)
    {
        // 1. Validate the uploaded file
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096', // 4MB max
        ]);

        $user = $request->user();

        // 2. Ensure destination directory exists
        $destinationPath = public_path('uploads/profile');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // 3. Delete old profile photo if exists
        if ($user->profile_picture) {
            $oldPath = public_path('uploads/profile/' . $user->profile_picture);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        // 4. Generate a clean file name
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = 'user-' . $user->id . '-' . time() . '.' . $extension;

        // 5. Move uploaded file to /public/uploads/profile
        $file->move($destinationPath, $fileName);

        // 6. Update user record
        $user->profile_picture = $fileName;
        $user->save();

        // 7. Return public URL
        return response()->json([
            'message' => 'Profile photo updated successfully.',
            'url' => asset('uploads/profile/' . $fileName),
        ], 200);
    }

    // public function show()
    // {
    //     $connections = Connection::where(function($q) {
    //         $q->where('user_a_id', auth()->id())
    //         ->orWhere('user_b_id', auth()->id());
    //     })->get();

    //     return view('profile.show', compact('connections'));
    // }

    // update-connections-form.blade.php

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
