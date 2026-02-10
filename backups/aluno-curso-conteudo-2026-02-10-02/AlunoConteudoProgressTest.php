<?php

namespace Tests\Feature;

use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\ConteudoProgresso;
use App\Models\Curso;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlunoConteudoProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_aluno_can_update_conteudo_progress(): void
    {
        $professor = User::factory()->create([
            'role' => 'professor',
            'status' => 'active',
        ]);

        $aluno = User::factory()->create([
            'role' => 'aluno',
            'status' => 'active',
        ]);

        $curso = Curso::create([
            'titulo' => 'Curso Teste',
            'descricao' => 'Descricao do curso',
            'categoria' => 'Tecnologia',
            'professor_id' => $professor->id,
            'status' => 'ativo',
        ]);

        $aula = Aula::create([
            'curso_id' => $curso->id,
            'titulo' => 'Aula 1',
            'descricao' => 'Descricao da aula',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        $conteudo = Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'Video 1',
            'descricao' => 'Descricao do conteudo',
            'tipo' => 'video',
            'url' => 'https://example.com/video',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        Enrollment::create([
            'aluno_id' => $aluno->id,
            'curso_id' => $curso->id,
            'data_matricula' => now()->toDateString(),
            'status' => 'ativo',
        ]);

        $this->actingAs($aluno)
            ->postJson(route('conteudos.progress', $conteudo), ['progress' => 50])
            ->assertOk()
            ->assertJson([
                'progress' => 50,
                'completed' => false,
            ]);

        $this->assertDatabaseHas('conteudo_progresso', [
            'user_id' => $aluno->id,
            'conteudo_id' => $conteudo->id,
            'progresso' => 50,
            'concluido' => 0,
        ]);

        $this->actingAs($aluno)
            ->postJson(route('conteudos.progress', $conteudo), ['progress' => 95])
            ->assertOk()
            ->assertJson([
                'progress' => 95,
                'completed' => true,
            ]);

        $this->assertDatabaseHas('conteudo_progresso', [
            'user_id' => $aluno->id,
            'conteudo_id' => $conteudo->id,
            'progresso' => 95,
            'concluido' => 1,
        ]);
    }

    public function test_aluno_show_uses_saved_progress(): void
    {
        $professor = User::factory()->create([
            'role' => 'professor',
            'status' => 'active',
        ]);

        $aluno = User::factory()->create([
            'role' => 'aluno',
            'status' => 'active',
        ]);

        $curso = Curso::create([
            'titulo' => 'Curso Progresso',
            'descricao' => 'Descricao do curso',
            'categoria' => 'Tecnologia',
            'professor_id' => $professor->id,
            'status' => 'ativo',
        ]);

        $aula = Aula::create([
            'curso_id' => $curso->id,
            'titulo' => 'Aula 1',
            'descricao' => 'Descricao da aula',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        $conteudo = Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'Video 1',
            'descricao' => 'Descricao do conteudo',
            'tipo' => 'video',
            'url' => 'https://example.com/video',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        Enrollment::create([
            'aluno_id' => $aluno->id,
            'curso_id' => $curso->id,
            'data_matricula' => now()->toDateString(),
            'status' => 'ativo',
        ]);

        ConteudoProgresso::create([
            'user_id' => $aluno->id,
            'conteudo_id' => $conteudo->id,
            'progresso' => 50,
            'concluido' => false,
        ]);

        $response = $this->actingAs($aluno)
            ->get(route('aluno.cursos.show', $curso));

        $response->assertOk();
        $response->assertSee('data-progress="50"', false);
    }

    public function test_aluno_progress_requires_enrollment(): void
    {
        $professor = User::factory()->create([
            'role' => 'professor',
            'status' => 'active',
        ]);

        $aluno = User::factory()->create([
            'role' => 'aluno',
            'status' => 'active',
        ]);

        $curso = Curso::create([
            'titulo' => 'Curso Restrito',
            'descricao' => 'Descricao do curso',
            'categoria' => 'Tecnologia',
            'professor_id' => $professor->id,
            'status' => 'ativo',
        ]);

        $aula = Aula::create([
            'curso_id' => $curso->id,
            'titulo' => 'Aula 1',
            'descricao' => 'Descricao da aula',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        $conteudo = Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'Video 1',
            'descricao' => 'Descricao do conteudo',
            'tipo' => 'video',
            'url' => 'https://example.com/video',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        $this->actingAs($aluno)
            ->postJson(route('conteudos.progress', $conteudo), ['progress' => 40])
            ->assertForbidden();

        $this->assertDatabaseMissing('conteudo_progresso', [
            'user_id' => $aluno->id,
            'conteudo_id' => $conteudo->id,
        ]);
    }

    public function test_only_aluno_role_can_update_progress(): void
    {
        $professor = User::factory()->create([
            'role' => 'professor',
            'status' => 'active',
        ]);

        $curso = Curso::create([
            'titulo' => 'Curso Professor',
            'descricao' => 'Descricao do curso',
            'categoria' => 'Tecnologia',
            'professor_id' => $professor->id,
            'status' => 'ativo',
        ]);

        $aula = Aula::create([
            'curso_id' => $curso->id,
            'titulo' => 'Aula 1',
            'descricao' => 'Descricao da aula',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        $conteudo = Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'Video 1',
            'descricao' => 'Descricao do conteudo',
            'tipo' => 'video',
            'url' => 'https://example.com/video',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        Enrollment::create([
            'aluno_id' => $professor->id,
            'curso_id' => $curso->id,
            'data_matricula' => now()->toDateString(),
            'status' => 'ativo',
        ]);

        $this->actingAs($professor)
            ->postJson(route('conteudos.progress', $conteudo), ['progress' => 40])
            ->assertForbidden();

        $this->assertDatabaseMissing('conteudo_progresso', [
            'user_id' => $professor->id,
            'conteudo_id' => $conteudo->id,
        ]);
    }

    public function test_aluno_show_includes_download_permission_flag(): void
    {
        $professor = User::factory()->create([
            'role' => 'professor',
            'status' => 'active',
        ]);

        $aluno = User::factory()->create([
            'role' => 'aluno',
            'status' => 'active',
        ]);

        $curso = Curso::create([
            'titulo' => 'Curso Download',
            'descricao' => 'Descricao do curso',
            'categoria' => 'Tecnologia',
            'professor_id' => $professor->id,
            'status' => 'ativo',
        ]);

        $aula = Aula::create([
            'curso_id' => $curso->id,
            'titulo' => 'Aula 1',
            'descricao' => 'Descricao da aula',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        $conteudo = Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'Arquivo 1',
            'descricao' => 'Descricao do conteudo',
            'tipo' => 'arquivo',
            'url' => 'https://example.com/arquivo.pdf',
            'ordem' => 1,
            'is_hidden' => false,
            'allow_download_student' => false,
            'allow_download_professor' => true,
        ]);

        Enrollment::create([
            'aluno_id' => $aluno->id,
            'curso_id' => $curso->id,
            'data_matricula' => now()->toDateString(),
            'status' => 'ativo',
        ]);

        $response = $this->actingAs($aluno)
            ->get(route('aluno.cursos.show', $curso));

        $response->assertOk();
        $response->assertSee('data-content-id="' . $conteudo->id . '"', false);
        $response->assertSee('data-allow-download="0"', false);
    }
}
