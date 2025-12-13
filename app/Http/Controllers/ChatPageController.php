<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ChatPageController extends Controller
{
    public function show(User $user)
    {
        // return view('chat', ['otherUser' => $user]);
        $users = User::where('id', '!=', auth()->id())->get();

        return view('chat', [
            'users' => $users,
            'otherUser' => $user,
        ]);
    }

    public function index()
    {
        // List all users except the logged-in one
        $users = User::where('id', '!=', auth()->id())->get();

        return view('chat', [
            'users' => $users,
            'otherUser' => null, // nothing selected yet
        ]);
    }


}
