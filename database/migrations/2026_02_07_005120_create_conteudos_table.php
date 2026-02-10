<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de conteúdos
 *
 * Este arquivo cria:
 * - Tabela `conteudos` → armazena materiais e recursos vinculados às aulas.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação da tabela).
     */
    public function up(): void
    {
        Schema::create('conteudos', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade'); 
            // aula relacionada
            $table->enum('tipo', ['video', 'pdf', 'link', 'texto']); 
            // tipo de conteúdo
            $table->string('titulo'); // título do conteúdo
            $table->text('descricao')->nullable(); // descrição opcional
            $table->string('url')->nullable(); // link ou caminho do material
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (remove a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('conteudos');
    }
};

