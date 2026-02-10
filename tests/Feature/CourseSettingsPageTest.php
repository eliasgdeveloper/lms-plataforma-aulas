<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseSettingsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_course_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $curso = Curso::create([
            'titulo' => 'Curso Admin',
            'descricao' => 'Descricao do curso',
            'categoria' => 'TI',
            'professor_id' => $admin->id,
            'status' => 'ativo',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.cursos.show', $curso))
            ->assertStatus(200)
            ->assertSee('Curso')
            ->assertSee('Configuracoes');
    }

    public function test_professor_can_view_own_course_settings(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $curso = Curso::create([
            'titulo' => 'Curso Professor',
            'descricao' => 'Descricao do curso',
            'categoria' => 'TI',
            'professor_id' => $professor->id,
            'status' => 'ativo',
        ]);

        $this->actingAs($professor)
            ->get(route('professor.cursos.show', $curso))
            ->assertStatus(200)
            ->assertSee('Curso')
            ->assertSee('Configuracoes');
    }

    public function test_professor_cannot_view_other_course_settings(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $other = User::factory()->create(['role' => 'professor']);
        $curso = Curso::create([
            'titulo' => 'Curso Bloqueado',
            'descricao' => 'Descricao do curso',
            'categoria' => 'TI',
            'professor_id' => $other->id,
            'status' => 'ativo',
        ]);

        $this->actingAs($professor)
            ->get(route('professor.cursos.show', $curso))
            ->assertStatus(403);
    }
}
