<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de cursos
 *
 * Este arquivo cria:
 * - Tabela `cursos` → armazena informações sobre os cursos disponíveis na plataforma.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação da tabela).
     */
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->string('titulo'); // título do curso
            $table->text('descricao')->nullable(); // descrição detalhada do curso
            $table->string('categoria')->nullable(); // categoria (ex.: matemática, programação)
            $table->foreignId('professor_id')->constrained('users')->onDelete('cascade'); 
            // professor responsável pelo curso (ligado à tabela users)
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (remove a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};

