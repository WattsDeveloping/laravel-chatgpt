<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::first(),
            'context' => ['role' => 'user', 'content' => 'What is Laravel?'],
        ];
    }
}
