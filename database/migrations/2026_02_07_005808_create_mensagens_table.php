<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de mensagens
 *
 * Este arquivo cria:
 * - Tabela `mensagens` → armazena mensagens privadas entre usuários.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação da tabela).
     */
    public function up(): void
    {
        Schema::create('mensagens', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('remetente_id')->constrained('users')->onDelete('cascade'); 
            // usuário que enviou a mensagem
            $table->foreignId('destinatario_id')->constrained('users')->onDelete('cascade'); 
            // usuário que recebeu a mensagem
            $table->text('conteudo'); // texto da mensagem
            $table->boolean('lida')->default(false); // status de leitura
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (remove a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('mensagens');
    }
};
