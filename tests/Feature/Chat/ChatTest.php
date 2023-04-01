<?php

namespace Tests\Feature\Chat;

use App\Models\Chat;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
    }

    public function test_a_user_can_list_messages_when_authenticated()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        
        Chat::factory(2)->for($user)->create();
        $response = $this->actingAs($user)->get(route('chat.show', 2));

        $response->assertOk();
        $response->assertInertia(function (Assert $page) {
            return $page->component('Chat/Index')->has('messages', 2);
        });
    }
}