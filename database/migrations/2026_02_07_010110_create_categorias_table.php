<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de categorias
 *
 * Este arquivo cria:
 * - Tabela `categorias` → organiza cursos por áreas/temas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->string('nome'); // nome da categoria
            $table->text('descricao')->nullable(); // descrição opcional
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};

