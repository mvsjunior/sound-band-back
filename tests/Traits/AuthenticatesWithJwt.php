<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait AuthenticatesWithJwt
{
    protected function authenticate(array $overrides = []): array
    {
        $user = User::factory()->create(array_merge([
            'password' => Hash::make('password'),
            'active' => true,
        ], $overrides));

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        return [
            'user' => $user,
            'token' => $response->json('token'),
        ];
    }
}
