<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de tarefas
 *
 * Este arquivo cria:
 * - Tabela `tarefas` → armazena atividades vinculadas às aulas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade'); 
            // aula relacionada
            $table->string('titulo'); // título da tarefa
            $table->text('descricao')->nullable(); // descrição detalhada
            $table->enum('tipo', ['exercicio', 'trabalho', 'quiz', 'projeto']); 
            // tipo de atividade
            $table->date('data_entrega')->nullable(); // prazo de entrega
            $table->integer('pontuacao_maxima')->default(10); // pontuação máxima
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
