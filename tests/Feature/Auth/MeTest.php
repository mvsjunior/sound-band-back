<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AuthenticatesWithJwt;

class MeTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatesWithJwt;

    public function test_authenticated_user_can_get_own_data(): void
    {
        $auth = $this->authenticate();

        $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->getJson('/api/auth/me')
            ->assertStatus(200)
            ->assertJson([
                'id' => $auth['user']->id,
                'email' => $auth['user']->email,
            ]);
    }

    public function test_guest_cannot_access_me(): void
    {
        $this->getJson('/api/auth/me')
            ->assertStatus(401);
    }
}
