<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use App\Models\Chat;
use Inertia\Inertia;

class ChatGptIndexController extends controller
{
    public function __invoke(string $id = null): Response
    {
        return Inertia::render('Chat/Index', [
            'chat' => fn () => $id ? Chat::findOrFail($id) : null,
            'messages' => Chat::latest()->where('user_id', Auth::id())->get()
        ]);
    }
}