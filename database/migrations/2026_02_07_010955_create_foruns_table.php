<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de fóruns
 *
 * Este arquivo cria:
 * - Tabela `foruns` → tópicos de discussão vinculados a cursos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foruns', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); 
            // curso relacionado
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            // autor do tópico
            $table->string('titulo'); // título da discussão
            $table->text('descricao')->nullable(); // descrição inicial
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foruns');
    }
};

