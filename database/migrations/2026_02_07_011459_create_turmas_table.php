<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de turmas
 *
 * Este arquivo cria:
 * - Tabela `turmas` → grupos de alunos vinculados a cursos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); 
            // curso relacionado
            $table->foreignId('professor_id')->constrained('users')->onDelete('cascade'); 
            // professor responsável
            $table->string('nome'); // nome da turma
            $table->text('descricao')->nullable(); // descrição opcional
            $table->date('data_inicio'); // data de início
            $table->date('data_fim')->nullable(); // data de término
            $table->string('horario')->nullable(); // dias/horários
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};

