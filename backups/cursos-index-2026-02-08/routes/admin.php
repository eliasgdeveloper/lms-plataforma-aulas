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
 * Organizacao por recurso (usuarios, cursos, etc)
 */

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

    // ===== DASHBOARD =====
    Route::get('/', function () {
        return view('pages.admin_dashboard.index');
    })->name('admin.dashboard');

    // ===== GESTAO DE USUARIOS - CRUD COMPLETO =====
    Route::resource('usuarios', UserController::class)->names([
        'index' => 'admin.usuarios.index',
        'create' => 'admin.usuarios.create',
        'store' => 'admin.usuarios.store',
        'show' => 'admin.usuarios.show',
        'edit' => 'admin.usuarios.edit',
        'update' => 'admin.usuarios.update',
        'destroy' => 'admin.usuarios.destroy',
    ]);

    // ===== ACOES EXTRAS DE USUARIOS =====
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

    // ===== OUTROS MODULOS (PLACEHOLDERS) =====
    Route::get('cursos', [CursoController::class, 'adminIndex'])
        ->name('admin.cursos');

    Route::get('configuracoes', function () {
        return view('pages.admin_configuracoes.index');
    })->name('admin.configuracoes');

});
