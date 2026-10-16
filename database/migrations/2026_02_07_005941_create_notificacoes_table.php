<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de notificações
 *
 * Este arquivo cria:
 * - Tabela `notificacoes` → armazena avisos e alertas enviados aos usuários.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação da tabela).
     */
    public function up(): void
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            // usuário que recebe a notificação
            $table->string('titulo'); // título curto da notificação
            $table->text('mensagem'); // texto completo da notificação
            $table->enum('tipo', ['sistema', 'curso', 'aula', 'mensagem'])->default('sistema'); 
            // tipo de notificação
            $table->boolean('lida')->default(false); // status de leitura
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (remove a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};

