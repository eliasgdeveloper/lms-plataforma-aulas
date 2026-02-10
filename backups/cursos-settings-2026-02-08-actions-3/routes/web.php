<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConteudoController;
use App\Http\Controllers\CursoController;
use App\Models\User;

/**
 * Web Routes
 *
 * Aqui definimos as rotas públicas e os grupos de rotas protegidas.
 * 
 * Grupos:
 * - Public: rotas sem autenticação
 * - Authenticated: rotas que exigem login
 * - Admin: rotas administrativas (middleware role:admin)
 */

// ===== PUBLIC ROUTES =====
Route::get('/', function () {
    return view('pages.welcome.index');
});

require __DIR__ . '/auth.php';

// ===== AUTHENTICATED ROUTES =====
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard genérico
    Route::get('/dashboard', function () {
        return view('pages.dashboard.index');
    })->name('dashboard');

    // Professor area
    Route::prefix('professor')->middleware('role:professor')->group(function () {
        Route::get('/', function () {
            return view('pages.professor_dashboard.index');
        })->name('professor.dashboard');

        Route::get('aulas', function () {
            return view('pages.professor_aulas.index');
        })->name('professor.aulas');

        Route::get('cursos', [CursoController::class, 'professorIndex'])
            ->name('professor.cursos');

        Route::get('cursos/novo', [CursoController::class, 'professorCreate'])
            ->name('professor.cursos.create');

        Route::post('cursos/novo', [CursoController::class, 'professorStore'])
            ->name('professor.cursos.store');

        Route::get('cursos/{curso}', [CursoController::class, 'professorShow'])
            ->name('professor.cursos.show');

        Route::get('cursos/{curso}/editar', [CursoController::class, 'professorEdit'])
            ->name('professor.cursos.edit');

        Route::put('cursos/{curso}', [CursoController::class, 'professorUpdate'])
            ->name('professor.cursos.update');

        Route::post('cursos/{curso}/acoes/{acao}', [CursoController::class, 'professorAction'])
            ->name('professor.cursos.action');

        Route::post('cursos/{curso}/conteudos', [CursoController::class, 'professorStoreConteudo'])
            ->name('professor.cursos.conteudos.store');

        Route::post('cursos/{curso}/aulas', [CursoController::class, 'professorStoreAula'])
            ->name('professor.cursos.aulas.store');

        Route::put('cursos/{curso}/aulas/{aula}', [CursoController::class, 'professorUpdateAula'])
            ->name('professor.cursos.aulas.update');

        Route::patch('cursos/{curso}/aulas/{aula}/toggle', [CursoController::class, 'professorToggleAula'])
            ->name('professor.cursos.aulas.toggle');

        Route::delete('cursos/{curso}/aulas/{aula}', [CursoController::class, 'professorDestroyAula'])
            ->name('professor.cursos.aulas.destroy');

        Route::put('cursos/{curso}/conteudos/{conteudo}', [CursoController::class, 'professorUpdateConteudo'])
            ->name('professor.cursos.conteudos.update');

        Route::patch('cursos/{curso}/conteudos/{conteudo}/toggle', [CursoController::class, 'professorToggleConteudo'])
            ->name('professor.cursos.conteudos.toggle');

        Route::delete('cursos/{curso}/conteudos/{conteudo}', [CursoController::class, 'professorDestroyConteudo'])
            ->name('professor.cursos.conteudos.destroy');

        Route::get('cursos/{curso}/preview', [CursoController::class, 'professorPreview'])
            ->name('professor.cursos.preview');

        Route::get('materiais', function () {
            return view('pages.professor_materiais.index');
        })->name('professor.materiais');

        Route::get('alunos', function () {
            return view('pages.professor_alunos.index');
        })->name('professor.alunos');
    });

    // Aluno area
    Route::prefix('aluno')->middleware('role:aluno')->group(function () {
        Route::get('/', function () {
            return view('pages.aluno_dashboard.index');
        })->name('aluno.dashboard');

        Route::get('aulas', function () {
            return view('pages.aluno_aulas.index');
        })->name('aluno.aulas');

        Route::get('cursos', [CursoController::class, 'alunoIndex'])
            ->name('aluno.cursos');

        Route::get('materiais', function () {
            return view('pages.aluno_materiais.index');
        })->name('aluno.materiais');

        Route::get('notas', function () {
            return view('pages.aluno_notas.index');
        })->name('aluno.notas');

        // Rotas de conteúdos de aula
        Route::get('/aulas/{aula}/conteudos', [ConteudoController::class, 'index'])
            ->name('conteudos.index');

        Route::get('/conteudos/{conteudo}', [ConteudoController::class, 'show'])
            ->name('conteudos.show');
    });
});

// ===== ADMIN ROUTES =====
require __DIR__ . '/admin.php';

