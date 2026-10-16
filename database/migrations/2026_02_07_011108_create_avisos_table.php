<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de avisos
 *
 * Este arquivo cria:
 * - Tabela `avisos` → mural de comunicados dos cursos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avisos', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); 
            // curso relacionado
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            // autor do aviso (professor)
            $table->string('titulo'); // título do aviso
            $table->text('mensagem'); // texto completo
            $table->date('data_publicacao'); // data de publicação
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avisos');
    }
};
