<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de presenças
 *
 * Este arquivo cria:
 * - Tabela `presencas` → registra a frequência dos alunos nas aulas.
 */
return new class extends Migration
{
    /**
     * Executa as migrations (criação da tabela).
     */
    public function up(): void
    {
        Schema::create('presencas', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade'); 
            // aluno presente ou ausente
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade'); 
            // aula relacionada
            $table->enum('status', ['presente', 'ausente', 'atrasado'])->default('presente'); 
            // situação da presença
            $table->text('observacao')->nullable(); // comentários adicionais do professor
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (remove a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('presencas');
    }
};
