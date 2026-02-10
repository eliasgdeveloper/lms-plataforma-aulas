<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de comentários
 *
 * Este arquivo cria:
 * - Tabela `comentarios` → armazena feedback e interações dos alunos e professores nas aulas.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação da tabela).
     */
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            // autor do comentário (aluno ou professor)
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade'); 
            // aula relacionada
            $table->text('conteudo'); // texto do comentário
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (remove a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
