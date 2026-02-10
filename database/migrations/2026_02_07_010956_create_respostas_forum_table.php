<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de respostas do fórum
 *
 * Este arquivo cria:
 * - Tabela `respostas_forum` → mensagens dentro dos tópicos de discussão.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respostas_forum', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('forum_id')->constrained('foruns')->onDelete('cascade'); 
            // tópico relacionado
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            // autor da resposta
            $table->text('conteudo'); // mensagem da resposta
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respostas_forum');
    }
};

