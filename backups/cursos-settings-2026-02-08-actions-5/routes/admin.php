<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CursoController;

/**
 * Admin Routes
 *
 * Rotas do painel administrativo
 * Middleware: 'auth', 'role:admin'
 *
 * Organização por recurso (usuarios, cursos, etc)
 */

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

    // ===== DASHBOARD =====
    Route::get('/', function () {
        return view('pages.admin_dashboard.index');
    })->name('admin.dashboard');

    // ===== GESTÃO DE USUÁRIOS - CRUD COMPLETO =====
    Route::resource('usuarios', UserController::class)->names([
        'index' => 'admin.usuarios.index',
        'create' => 'admin.usuarios.create',
        'store' => 'admin.usuarios.store',
        'show' => 'admin.usuarios.show',
        'edit' => 'admin.usuarios.edit',
        'update' => 'admin.usuarios.update',
        'destroy' => 'admin.usuarios.destroy',
    ]);

    // ===== AÇÕES EXTRAS DE USUÁRIOS =====
    Route::prefix('usuarios/{usuario}')->group(function () {
        // Toggle status (ativo/inativo)
        Route::patch('toggle-status', [UserController::class, 'toggleStatus'])
            ->name('admin.usuarios.toggleStatus');

        // Alterar senha
        Route::patch('change-password', [UserController::class, 'changePassword'])
            ->name('admin.usuarios.changePassword');
    });

    // ===== BUSCA E EXPORT =====
    Route::get('usuarios/search/api', [UserController::class, 'search'])
        ->name('admin.usuarios.search');

    Route::get('usuarios/export/csv', [UserController::class, 'export'])
        ->name('admin.usuarios.export');

    // ===== OUTROS MÓDULOS (PLACEHOLDERS) =====
    Route::get('cursos', [CursoController::class, 'adminIndex'])
        ->name('admin.cursos');

    Route::get('cursos/relatorio', [CursoController::class, 'adminReport'])
        ->name('admin.cursos.report');

    Route::get('cursos/visao-geral', [CursoController::class, 'adminOverview'])
        ->name('admin.cursos.overview');

    Route::get('cursos/novo', [CursoController::class, 'adminCreate'])
        ->name('admin.cursos.create');

    Route::post('cursos/novo', [CursoController::class, 'adminStore'])
        ->name('admin.cursos.store');

    Route::get('cursos/{curso}', [CursoController::class, 'adminShow'])
        ->name('admin.cursos.show');

    Route::get('cursos/{curso}/editar', [CursoController::class, 'adminEdit'])
        ->name('admin.cursos.edit');

    Route::put('cursos/{curso}', [CursoController::class, 'adminUpdate'])
        ->name('admin.cursos.update');

    Route::post('cursos/{curso}/acoes/{acao}', [CursoController::class, 'adminAction'])
        ->name('admin.cursos.action');

    Route::post('cursos/{curso}/conteudos', [CursoController::class, 'adminStoreConteudo'])
        ->name('admin.cursos.conteudos.store');

    Route::post('cursos/{curso}/aulas', [CursoController::class, 'adminStoreAula'])
        ->name('admin.cursos.aulas.store');

    Route::put('cursos/{curso}/aulas/{aula}', [CursoController::class, 'adminUpdateAula'])
        ->name('admin.cursos.aulas.update');

    Route::patch('cursos/{curso}/aulas/{aula}/toggle', [CursoController::class, 'adminToggleAula'])
        ->name('admin.cursos.aulas.toggle');

    Route::delete('cursos/{curso}/aulas/{aula}', [CursoController::class, 'adminDestroyAula'])
        ->name('admin.cursos.aulas.destroy');

    Route::put('cursos/{curso}/conteudos/{conteudo}', [CursoController::class, 'adminUpdateConteudo'])
        ->name('admin.cursos.conteudos.update');

    Route::patch('cursos/{curso}/conteudos/{conteudo}/toggle', [CursoController::class, 'adminToggleConteudo'])
        ->name('admin.cursos.conteudos.toggle');

    Route::delete('cursos/{curso}/conteudos/{conteudo}', [CursoController::class, 'adminDestroyConteudo'])
        ->name('admin.cursos.conteudos.destroy');

    Route::get('cursos/{curso}/preview', [CursoController::class, 'adminPreview'])
        ->name('admin.cursos.preview');

    Route::get('configuracoes', function () {
        return view('pages.admin_configuracoes.index');
    })->name('admin.configuracoes');

});
