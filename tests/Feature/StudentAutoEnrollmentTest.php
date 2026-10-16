<?php

namespace Tests\Feature;

use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\Curso;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentAutoEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_is_enrolled_in_all_active_courses_when_opening_course_list(): void
    {
        $teacher = User::factory()->create(['role' => 'professor']);
        $student = User::factory()->create(['role' => 'aluno']);

        $activeCourses = collect([
            $this->createCourse($teacher, 'Curso ativo 1', 'ativo'),
            $this->createCourse($teacher, 'Curso ativo 2', 'ativo'),
        ]);
        $unavailableCourse = $this->createCourse($teacher, 'Curso em rascunho', 'rascunho');
        $hiddenCourse = $this->createCourse($teacher, 'Curso oculto', 'oculto');

        $this->actingAs($student)
            ->get(route('aluno.cursos'))
            ->assertOk();

        foreach ($activeCourses as $course) {
            $this->assertDatabaseHas('matriculas', [
                'aluno_id' => $student->id,
                'curso_id' => $course->id,
                'status' => 'ativo',
            ]);
        }

        $this->assertDatabaseMissing('matriculas', [
            'aluno_id' => $student->id,
            'curso_id' => $unavailableCourse->id,
        ]);
        $this->assertDatabaseMissing('matriculas', [
            'aluno_id' => $student->id,
            'curso_id' => $hiddenCourse->id,
        ]);

        $lesson = Aula::create([
            'curso_id' => $activeCourses->first()->id,
            'titulo' => 'Primeira aula',
            'ordem' => 1,
            'is_hidden' => false,
        ]);
        $content = Conteudo::create([
            'aula_id' => $lesson->id,
            'titulo' => 'Primeiro conteúdo',
            'tipo' => 'texto',
            'url' => 'Conteúdo da aula',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        $this->get(route('aluno.cursos.show', $activeCourses->first()))
            ->assertOk()
            ->assertSee('data-content-id="'.$content->id.'"', false)
            ->assertSee('Primeiro conteúdo');

        $this->postJson(route('conteudos.progress', $content), ['progress' => 50])
            ->assertOk()
            ->assertJson(['progress' => 50]);

        $this->actingAs($student)->get(route('aluno.cursos'));

        $this->assertSame(
            2,
            Enrollment::query()->where('aluno_id', $student->id)->count()
        );
        $this->get(route('aluno.cursos.show', $hiddenCourse))->assertNotFound();
    }

    public function test_switching_to_student_profile_enrolls_user_in_active_courses(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse(
            User::factory()->create(['role' => 'professor']),
            'Curso disponível',
            'ativo'
        );

        $this->actingAs($admin)
            ->post(route('view-as.update'), ['role' => 'aluno'])
            ->assertRedirect(route('aluno.cursos'))
            ->assertSessionHas('view_as_role', 'aluno');

        $this->assertDatabaseHas('matriculas', [
            'aluno_id' => $admin->id,
            'curso_id' => $course->id,
            'status' => 'ativo',
        ]);

        $lesson = Aula::create([
            'curso_id' => $course->id,
            'titulo' => 'Aula de demonstração',
            'ordem' => 1,
            'is_hidden' => false,
        ]);
        $content = Conteudo::create([
            'aula_id' => $lesson->id,
            'titulo' => 'Conteúdo disponível',
            'tipo' => 'texto',
            'url' => 'Texto da aula',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        $this->get(route('aluno.cursos.show', $course))
            ->assertOk()
            ->assertSee('data-content-id="'.$content->id.'"', false);
    }

    public function test_student_course_list_has_no_unopenable_demo_cards_when_empty(): void
    {
        $student = User::factory()->create(['role' => 'aluno']);

        $this->actingAs($student)
            ->get(route('aluno.cursos'))
            ->assertOk()
            ->assertSee('Ainda não há cursos ativos disponíveis para matrícula.')
            ->assertDontSee('JavaScript Moderno');
    }

    private function createCourse(User $teacher, string $title, string $status): Curso
    {
        return Curso::create([
            'titulo' => $title,
            'descricao' => 'Descrição do curso',
            'categoria' => 'Tecnologia',
            'professor_id' => $teacher->id,
            'status' => $status,
        ]);
    }
}
