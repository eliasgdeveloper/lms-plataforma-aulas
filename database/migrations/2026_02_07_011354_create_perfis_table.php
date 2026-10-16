<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de perfis
 *
 * Este arquivo cria:
 * - Tabela `perfis` → informações adicionais dos usuários (alunos/professores).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfis', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            // usuário dono do perfil
            $table->text('bio')->nullable(); // biografia
            $table->string('foto')->nullable(); // foto de perfil
            $table->string('especializacao')->nullable(); // área de especialização
            $table->string('rede_social')->nullable(); // link para redes sociais
            $table->string('telefone')->nullable(); // contato telefônico
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfis');
    }
};

