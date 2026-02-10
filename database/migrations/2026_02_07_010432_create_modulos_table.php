<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de módulos
 *
 * Este arquivo cria:
 * - Tabela `modulos` → organiza cursos em partes menores (módulos).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); 
            // curso relacionado
            $table->string('titulo'); // título do módulo
            $table->text('descricao')->nullable(); // descrição opcional
            $table->integer('ordem')->default(1); // ordem dentro do curso
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
