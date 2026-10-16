<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de entregas
 *
 * Este arquivo cria:
 * - Tabela `entregas` → armazena submissões de tarefas feitas pelos alunos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('tarefa_id')->constrained('tarefas')->onDelete('cascade'); 
            // tarefa relacionada
            $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade'); 
            // aluno que fez a entrega
            $table->text('conteudo')->nullable(); // texto ou link enviado
            $table->string('arquivo')->nullable(); // caminho do arquivo anexado
            $table->decimal('nota', 5, 2)->nullable(); // nota atribuída
            $table->text('feedback')->nullable(); // comentário do professor
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};

