<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de aulas
 *
 * Este arquivo cria:
 * - Tabela `aulas` → armazena informações sobre as aulas vinculadas a cursos.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação da tabela).
     */
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->string('titulo'); // título da aula
            $table->text('descricao')->nullable(); // descrição detalhada da aula
            $table->date('data')->nullable(); // data da aula (opcional)
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); 
            // chave estrangeira para cursos (cada aula pertence a um curso)
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (remove a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
