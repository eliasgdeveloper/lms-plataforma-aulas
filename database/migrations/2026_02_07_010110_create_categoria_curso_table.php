<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela pivot categoria_curso
 *
 * Este arquivo cria:
 * - Tabela `categoria_curso` → relaciona cursos a categorias.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categoria_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_curso');
    }
};

