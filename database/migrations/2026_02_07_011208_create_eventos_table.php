<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de eventos
 *
 * Este arquivo cria:
 * - Tabela `eventos` → agenda escolar e eventos institucionais.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->string('titulo'); // título do evento
            $table->text('descricao')->nullable(); // descrição detalhada
            $table->dateTime('data_inicio'); // início do evento
            $table->dateTime('data_fim')->nullable(); // término do evento
            $table->string('local')->nullable(); // local do evento
            $table->enum('tipo', ['institucional', 'curso', 'aula', 'feriado'])->default('institucional'); 
            // tipo de evento
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
