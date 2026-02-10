<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de certificados
 *
 * Este arquivo cria:
 * - Tabela `certificados` → registra certificados emitidos para alunos que concluíram cursos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade'); 
            // aluno que recebe o certificado
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); 
            // curso concluído
            $table->string('codigo')->unique(); // código único do certificado
            $table->date('data_emissao'); // data de emissão
            $table->date('valido_ate')->nullable(); // validade opcional
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};
