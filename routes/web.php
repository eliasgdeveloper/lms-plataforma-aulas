<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConteudoController;

/**
 * Web Routes
 *
 * Aqui definimos as rotas públicas e as rotas protegidas por autenticação.
 * Comentários abaixo descrevem os grupos principais (perfil, admin, professor, aluno).
 */

// Public welcome
Route::get('/', function () {
    return view('pages.welcome.index');
});

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    // Profile routes (edit, update, destroy)
    // O `navigation` do layout usa as rotas nomeadas abaixo (`profile.edit`, ...)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Generic dashboard — rota de fallback quando role não estiver definida
    Route::get('/dashboard', function () {
        return view('pages.dashboard.index');
    })->name('dashboard');

    // Admin area: todas rotas aqui usam middleware 'role:admin'
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/', function () {
            return view('pages.admin_dashboard.index');
        })->name('admin.dashboard');

        Route::get('usuarios', function () {
            return view('pages.admin_usuarios.index');
        })->name('admin.usuarios');

        Route::get('cursos', function () {
            return view('pages.admin_cursos.index');
        })->name('admin.cursos');

        Route::get('configuracoes', function () {
            return view('pages.admin_configuracoes.index');
        })->name('admin.configuracoes');
    });

    // Professor area
    Route::prefix('professor')->middleware('role:professor')->group(function () {
        Route::get('/', function () {
            return view('pages.professor_dashboard.index');
        })->name('professor.dashboard');

        Route::get('aulas', function () {
            return view('pages.professor_aulas.index');
        })->name('professor.aulas');

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

