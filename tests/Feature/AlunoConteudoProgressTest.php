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

    public function test_php_lesson_summary_has_no_inline_typing_and_dedicated_page_has_sidebar(): void
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
            'titulo' => 'PHP em Missao — Do Zero ao Backend',
            'descricao' => 'Trilha de PHP',
            'categoria' => 'Programacao',
            'professor_id' => $professor->id,
            'status' => 'ativo',
        ]);

        $aula = Aula::create([
            'curso_id' => $curso->id,
            'titulo' => '01 · [Basico] Ambiente e primeiro script',
            'descricao' => 'Primeiro script PHP',
            'ordem' => 1,
            'is_hidden' => false,
        ]);

        $conteudo = Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'Resumo da missão',
            'descricao' => 'Explicação introdutória',
            'tipo' => 'texto',
            'url' => 'PHP usa echo para exibir uma mensagem.',
            'ordem' => 1,
            'is_hidden' => false,
        ]);
        Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'Guia do primeiro script',
            'descricao' => 'Guia da missão',
            'tipo' => 'pdf',
            'url' => 'https://example.com/guia.pdf',
            'ordem' => 2,
            'is_hidden' => false,
        ]);

        Enrollment::create([
            'aluno_id' => $aluno->id,
            'curso_id' => $curso->id,
            'data_matricula' => now()->toDateString(),
            'status' => 'ativo',
        ]);

        $this->actingAs($aluno)
            ->get(route('conteudos.show', $conteudo))
            ->assertOk()
            ->assertDontSee('Treino de digitação PHP')
            ->assertDontSee('data-php-typing', false)
            ->assertSee('PHP usa echo para exibir uma mensagem.');

        $this->actingAs($aluno)
            ->get(route('conteudos.typing', $conteudo))
            ->assertOk()
            ->assertSee('<h1>Treino de digitação</h1>', false)
            ->assertSee('data-php-typing', false)
            ->assertSee('class="course-sidebar"', false)
            ->assertSee('Dedo da próxima tecla')
            ->assertSee('data-progress-bar', false)
            ->assertSee('Português (Brasil) · ABNT2')
            ->assertSee('Inglês · Colemak')
            ->assertSee('data-virtual-keyboard', false)
            ->assertSee('Clique no trecho acima e digite diretamente nele.')
            ->assertSee('Cada ↵ marca uma quebra de linha: pressione Enter.')
            ->assertSee('class="php-typing__input-proxy"', false)
            ->assertDontSee('Digite o trecho mostrado acima')
            ->assertSee('aria-current="page"', false);

        $this->actingAs($aluno)
            ->get(route('conteudos.show', $conteudo), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertDontSee('Treino de digitação PHP')
            ->assertDontSee('data-php-typing', false);

        $this->actingAs($aluno)
            ->get(route('conteudos.index', $aula))
            ->assertOk()
            ->assertSee('data-full-page="true"', false);
    }

    public function test_php_course_shows_all_items_in_grouped_modules_and_the_typing_exercise(): void
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
            'titulo' => 'PHP em Missao — Do Zero ao Backend',
            'descricao' => 'Trilha de PHP',
            'categoria' => 'Programacao',
            'professor_id' => $professor->id,
            'status' => 'ativo',
        ]);

        $firstLesson = Aula::create([
            'curso_id' => $curso->id,
            'titulo' => '01 · [Basico] Ambiente e primeiro script',
            'descricao' => 'Primeiro script PHP',
            'ordem' => 1,
            'is_hidden' => false,
        ]);
        $secondLesson = Aula::create([
            'curso_id' => $curso->id,
            'titulo' => '01 · [Basico] Instalação complementar',
            'descricao' => 'Complemento',
            'ordem' => 2,
            'is_hidden' => false,
        ]);

        $summary = Conteudo::create([
            'aula_id' => $firstLesson->id,
            'titulo' => 'Resumo da missão',
            'descricao' => 'Explicação do resumo',
            'tipo' => 'texto',
            'url' => 'Conteúdo completo da aula um.',
            'ordem' => 1,
            'is_hidden' => false,
        ]);
        Conteudo::create([
            'aula_id' => $firstLesson->id,
            'titulo' => 'Guia da aula um',
            'descricao' => 'Material',
            'tipo' => 'pdf',
            'url' => 'https://example.com/aula-um.pdf',
            'ordem' => 2,
            'is_hidden' => false,
        ]);
        Conteudo::create([
            'aula_id' => $secondLesson->id,
            'titulo' => 'Resumo complementar',
            'descricao' => 'Explicação do resumo complementar',
            'tipo' => 'texto',
            'url' => 'Conteúdo completo da aula dois.',
            'ordem' => 1,
            'is_hidden' => false,
        ]);
        Conteudo::create([
            'aula_id' => $secondLesson->id,
            'titulo' => 'Conteúdo oculto',
            'descricao' => 'Não deve aparecer',
            'tipo' => 'texto',
            'url' => 'Conteúdo privado.',
            'ordem' => 2,
            'is_hidden' => true,
        ]);

        Enrollment::create([
            'aluno_id' => $aluno->id,
            'curso_id' => $curso->id,
            'data_matricula' => now()->toDateString(),
            'status' => 'ativo',
        ]);

        $response = $this->actingAs($aluno)
            ->get(route('aluno.cursos.show', $curso));

        $response->assertOk()
            ->assertSee('Resumo da missão')
            ->assertSee('Guia da aula um')
            ->assertSee(route('conteudos.typing', $summary), false)
            ->assertSee('Resumo complementar')
            ->assertSee('Treino de digitação')
            ->assertDontSee('data-php-typing', false)
            ->assertDontSee('Conteúdo oculto');

        $html = $response->getContent();
        $guidePosition = strpos($html, 'data-title="Guia da aula um"');
        $typingPosition = strpos($html, 'item--typing');
        $this->assertNotFalse($guidePosition);
        $this->assertNotFalse($typingPosition);
        $this->assertGreaterThan($guidePosition, $typingPosition);
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
