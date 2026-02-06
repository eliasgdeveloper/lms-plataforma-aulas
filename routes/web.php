<?php

use Illuminate\Support\Facades\Route;

/**
 * Web Routes
 *
 * Aqui definimos as rotas públicas e as rotas protegidas por autenticação.
 * Comentários abaixo descrevem os grupos principais (perfil, admin, professor, aluno).
 */

// Public welcome
Route::get('/', function () {
	return view('welcome');
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
		return view('dashboard');
	})->name('dashboard');

	// Admin area: todas rotas aqui usam middleware 'role:admin'
	Route::prefix('admin')->middleware('role:admin')->group(function () {
		Route::get('/', function () {
			return view('admin.dashboard');
		})->name('admin.dashboard');

		// Sub-páginas administrativas referenciadas pela view admin/dashboard.blade.php
		Route::get('usuarios', function () {
			return view('admin.usuarios');
		})->name('admin.usuarios');

		Route::get('cursos', function () {
			return view('admin.cursos');
		})->name('admin.cursos');

		Route::get('configuracoes', function () {
			return view('admin.configuracoes');
		})->name('admin.configuracoes');
	});

	// Professor area
	Route::prefix('professor')->middleware('role:professor')->group(function () {
		Route::get('/', function () {
			return view('professor.dashboard');
		})->name('professor.dashboard');

		Route::get('aulas', function () {
			return view('professor.aulas');
		})->name('professor.aulas');

		Route::get('materiais', function () {
			return view('professor.materiais');
		})->name('professor.materiais');

		Route::get('alunos', function () {
			return view('professor.alunos');
		})->name('professor.alunos');
	});

	// Aluno area
	Route::prefix('aluno')->middleware('role:aluno')->group(function () {
		Route::get('/', function () {
			return view('aluno.dashboard');
		})->name('aluno.dashboard');

		Route::get('aulas', function () {
			return view('aluno.aulas');
		})->name('aluno.aulas');

		Route::get('materiais', function () {
			return view('aluno.materiais');
		})->name('aluno.materiais');

		Route::get('notas', function () {
			return view('aluno.notas');
		})->name('aluno.notas');
	});
});
