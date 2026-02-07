<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação das tabelas principais de autenticação
 *
 * Este arquivo cria:
 * - Tabela `users` → armazena os perfis do sistema (aluno, professor, admin).
 * - Tabela `password_reset_tokens` → gerencia tokens de redefinição de senha.
 * - Tabela `sessions` → controla sessões ativas dos usuários.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação das tabelas).
     */
    public function up(): void
    {
        // Tabela de usuários
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->string('name'); // nome do usuário
            $table->string('email')->unique(); // email único para login
            $table->timestamp('email_verified_at')->nullable(); // verificação de email
            $table->string('password'); // senha criptografada
            $table->enum('role', ['aluno', 'professor', 'admin'])->default('aluno'); 
            // papel do usuário no sistema (define permissões)
            $table->rememberToken(); // token para login persistente
            $table->timestamps(); // created_at e updated_at
        });

        // Tabela de tokens para reset de senha
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // email associado ao token
            $table->string('token'); // token de redefinição
            $table->timestamp('created_at')->nullable(); // data de criação
        });

        // Tabela de sessões
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // identificador único da sessão
            $table->foreignId('user_id')->nullable()->index(); // usuário logado
            $table->string('ip_address', 45)->nullable(); // IP do usuário
            $table->text('user_agent')->nullable(); // navegador/dispositivo
            $table->longText('payload'); // dados da sessão
            $table->integer('last_activity')->index(); // última atividade
        });
    }

    /**
     * Reverte as migrations (remove as tabelas).
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
