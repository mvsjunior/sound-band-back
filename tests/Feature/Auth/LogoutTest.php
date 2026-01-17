<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AuthenticatesWithJwt;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatesWithJwt;

    public function test_user_can_logout(): void
    {
        $auth = $this->authenticate();

        $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/auth/logout')
            ->assertStatus(200);
    }

    public function test_logged_out_token_is_invalid(): void
    {
        $auth = $this->authenticate();

        $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/auth/logout');

        $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->getJson('/api/auth/me')
            ->assertStatus(401);
    }
}
