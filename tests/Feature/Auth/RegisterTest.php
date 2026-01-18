<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Novo Usuário',
            'email' => 'novo@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id', 
                'name', 
                'email'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'novo@teste.com',
        ]);
    }

    public function test_register_requires_valid_data(): void
    {
        $this->postJson('/api/auth/register', [])
            ->assertStatus(422);
    }
}
