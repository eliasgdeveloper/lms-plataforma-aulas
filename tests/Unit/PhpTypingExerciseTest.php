<?php

namespace Tests\Unit;

use App\Support\PhpTypingExercise;
use PHPUnit\Framework\TestCase;

class PhpTypingExerciseTest extends TestCase
{
    public function test_each_php_mission_has_a_topic_specific_typing_example(): void
    {
        $lessons = [
            'Ambiente e primeiro script',
            'Variaveis, tipos e operadores',
            'Condicionais e loops',
            'Arrays e strings',
            'Funcoes e escopo',
            'PHP + HTML e saida',
            'Debug e erros basicos',
            'Incluir arquivos',
            'Arrays associativos na pratica',
            'Funcoes de string uteis',
            'Operadores logicos e match',
            'Checkpoint Basico + Arena 1',
            'Formularios GET/POST',
            'Validacao e filtros',
            'Sessoes e cookies',
            'Upload de arquivos',
            'Arquivos e JSON',
            'Introducao a OOP',
            'Heranca e interfaces',
            'Excecoes e erros',
            'MySQL + PDO CRUD',
            'Seguranca web',
            'Mini-projeto notas',
            'Arena Sprint 2 War Room',
            'Composer e PSR-4',
            'Namespaces e estrutura',
            'Router e MVC leve',
            'Auth e senhas',
            'APIs REST JSON',
            'PHP 8+ e qualidade',
            'Testes manuais e logs',
            'Capstone API autenticada',
        ];

        $this->assertCount(32, $lessons);

        foreach ($lessons as $index => $lesson) {
            $exercise = PhpTypingExercise::forLesson(sprintf('%02d · [Basico] %s', $index + 1, $lesson));

            $this->assertNotEmpty($exercise['topic'], $lesson);
            $this->assertStringStartsWith('<?php', $exercise['code'], $lesson);
            $this->assertStringEndsWith('?>', $exercise['code'], $lesson);
            $this->assertStringNotContainsString('$tema =', $exercise['code'], $lesson);
            $this->assertNotEmpty(token_get_all($exercise['code'], TOKEN_PARSE), $lesson);
        }
    }

    public function test_unknown_lesson_gets_a_safe_example_using_its_title(): void
    {
        $exercise = PhpTypingExercise::forLesson('Tópico personalizado');

        $this->assertSame('Tópico personalizado', $exercise['topic']);
        $this->assertStringStartsWith('<?php', $exercise['code']);
        $this->assertStringEndsWith('?>', $exercise['code']);
        $this->assertStringContainsString("'Tópico personalizado'", $exercise['code']);
    }

    public function test_typing_exercise_is_limited_to_php_summary_content(): void
    {
        $exercise = PhpTypingExercise::forSummary(
            'PHP em Missão',
            '01 · [Básico] Ambiente e primeiro script',
            'Resumo da missão',
            'texto'
        );

        $this->assertNotNull($exercise);
        $this->assertSame('ambiente e primeiro script', $exercise['topic']);
        $this->assertNull(PhpTypingExercise::forSummary(
            'Curso de PHP',
            'Ambiente e primeiro script',
            'Material de apoio',
            'texto'
        ));
        $this->assertNull(PhpTypingExercise::forSummary(
            'Curso de JavaScript',
            'Ambiente e primeiro script',
            'Resumo da missão',
            'texto'
        ));
    }
}
