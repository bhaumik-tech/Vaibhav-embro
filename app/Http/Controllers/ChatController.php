<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {
        // Get all users except the currently logged-in user
        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();
        return view('chat.index', compact('users'));
    }
}
