<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\Chat;

class ChatGptStoreController extends Controller
{
    public function __invoke(StoreChatRequest $request, string $id = null)
    {
        $messages = [];

        if ($id) {
            $chat = Chat::findOrFail($id);
            $messages = $chat->context;
        }

        $messages[] = ['role' => 'user', 'content' => $request->input('promt')];

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages
        ]);

        $messages[] = ['role' => 'assistant', 'content' => $response->choices[0]->message->content];

        $chat = Chat::updateOrCreate(['id' => $id], [ 'context' => $messages ]);
        $chat->user()->associate(Auth::id());
        $chat->save();

        return redirect()->route('chat.show', [$chat->id]);
    }
}