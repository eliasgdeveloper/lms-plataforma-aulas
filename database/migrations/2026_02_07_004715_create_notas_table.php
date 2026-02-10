<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de notas
 *
 * Este arquivo cria:
 * - Tabela `notas` → armazena o desempenho dos alunos em cursos e aulas.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação da tabela).
     */
    public function up(): void
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade'); 
            // aluno avaliado
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); 
            // curso relacionado
            $table->foreignId('aula_id')->nullable()->constrained('aulas')->onDelete('cascade'); 
            // aula específica (opcional)
            $table->decimal('valor', 5, 2); // nota numérica (ex.: 8.50)
            $table->text('observacao')->nullable(); // comentários do professor
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (remove a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};

