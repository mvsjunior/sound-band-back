<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret123'),
            'active' => true,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['token', 'type', 'expiresIn']
            ]);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret123'),
        ]);

        $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ])->assertStatus(401);
    }

    #@TODO
    // public function test_inactive_user_cannot_login(): void
    // {
    //     $user = User::factory()->create([
    //         'password' => Hash::make('secret123'),
    //         'active' => false,
    //     ]);

    //     $this->postJson('/api/auth/login', [
    //         'email' => $user->email,
    //         'password' => 'secret123',
    //     ])->assertStatus(403);
    // }
}
