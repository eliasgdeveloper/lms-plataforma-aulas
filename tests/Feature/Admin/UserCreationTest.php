<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $payload = [
            'name' => 'Carlos Teste',
            'email' => 'carlos.teste@example.com',
            'role' => 'aluno',
            'status' => 'active',
            'password' => 'Senha@2026',
            'password_confirmation' => 'Senha@2026',
        ];

        $response = $this->actingAs($admin)->post(route('admin.usuarios.store'), $payload);

        $this->assertDatabaseHas('users', [
            'email' => 'carlos.teste@example.com',
            'role' => 'aluno',
            'status' => 'active',
        ]);

        $createdUser = User::where('email', 'carlos.teste@example.com')->first();

        $response
            ->assertRedirect(route('admin.usuarios.show', $createdUser))
            ->assertSessionHas('success');
    }

    public function test_non_admin_cannot_create_user(): void
    {
        $user = User::factory()->create([
            'role' => 'aluno',
            'status' => 'active',
        ]);

        $payload = [
            'name' => 'Aluno Bloqueado',
            'email' => 'aluno.bloqueado@example.com',
            'role' => 'aluno',
            'status' => 'active',
            'password' => 'Senha@2026',
            'password_confirmation' => 'Senha@2026',
        ];

        $response = $this->actingAs($user)->post(route('admin.usuarios.store'), $payload);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'email' => 'aluno.bloqueado@example.com',
        ]);
    }
}
