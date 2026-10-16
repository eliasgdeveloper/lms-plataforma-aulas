<?php

namespace Tests\Feature;

use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\Curso;
use App\Models\User;
use Database\Seeders\AvailableCoursesSeeder;
use Database\Seeders\InitialCourseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailableCoursesTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_course_cards_open_real_courses_with_lessons(): void
    {
        $student = User::factory()->create(['role' => 'aluno']);
        $leandro = User::factory()->create([
            'name' => 'Leandro',
            'email' => 'leandro@codar.local',
            'role' => 'admin',
        ]);
        User::factory()->create(['role' => 'professor']);

        $this->seed(InitialCourseSeeder::class);
        $this->seed(AvailableCoursesSeeder::class);

        $courses = Curso::query()->where('status', 'ativo')->get();
        $this->assertCount(9, $courses);

        foreach ($courses as $course) {
            $this->assertGreaterThan(0, $course->aulas()->count());
            $this->assertGreaterThan(
                0,
                $course->aulas()->first()->conteudos()->count()
            );
        }

        $this->assertDatabaseCount('matriculas', 18);

        $response = $this->actingAs($student)->get(route('aluno.cursos'));
        $response->assertOk();

        foreach ($courses as $course) {
            $response->assertSee($course->titulo);
            $this->actingAs($student)
                ->get(route('aluno.cursos.show', $course))
                ->assertOk()
                ->assertSee($course->aulas()->first()->conteudos()->first()->titulo);
        }

        $this->assertDatabaseCount('matriculas', 18);
        $this->assertSame('admin', $leandro->fresh()->role);
    }

    public function test_available_courses_seeder_is_safe_to_run_more_than_once(): void
    {
        User::factory()->create(['role' => 'professor']);

        $this->seed(InitialCourseSeeder::class);
        $this->seed(AvailableCoursesSeeder::class);
        $this->seed(AvailableCoursesSeeder::class);

        $this->assertSame(9, Curso::query()->where('status', 'ativo')->count());
        $this->assertGreaterThanOrEqual(8, Aula::query()->count());
        $this->assertGreaterThanOrEqual(8, Conteudo::query()->count());
    }
}
