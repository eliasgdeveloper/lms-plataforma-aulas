<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de matrículas
 *
 * Este arquivo cria:
 * - Tabela `matriculas` → armazena as inscrições dos alunos nos cursos.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação da tabela).
     */
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade'); 
            // aluno matriculado (ligado à tabela users)
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); 
            // curso em que o aluno está matriculado
            $table->date('data_matricula')->default(now()); // data da matrícula
            $table->enum('status', ['ativo', 'concluido', 'cancelado'])->default('ativo'); 
            // situação da matrícula
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (remove a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
