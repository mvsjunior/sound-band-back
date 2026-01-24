<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_refresh_with_cookie(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret123'),
            'active' => true,
        ]);

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $loginResponse->assertStatus(200);

        $refreshCookie = $loginResponse->getCookie('refresh_token');

        $this->assertNotNull($refreshCookie);
        $this->assertTrue($refreshCookie->isHttpOnly());

        $response = $this
            ->withCredentials()
            ->withUnencryptedCookie($refreshCookie->getName(), $refreshCookie->getValue())
            ->postJson('/api/auth/refresh');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['token', 'type', 'expiresIn']
            ])
            ->assertCookie('refresh_token');
    }

    public function test_refresh_requires_cookie(): void
    {
        $this->postJson('/api/auth/refresh')
            ->assertStatus(401);
    }
}
