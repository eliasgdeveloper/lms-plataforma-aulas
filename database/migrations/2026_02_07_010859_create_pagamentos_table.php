<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de pagamentos
 *
 * Este arquivo cria:
 * - Tabela `pagamentos` → registra mensalidades e transações financeiras dos alunos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade'); 
            // aluno que realiza o pagamento
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); 
            // curso relacionado
            $table->decimal('valor', 10, 2); // valor do pagamento
            $table->date('data_pagamento'); // data do pagamento
            $table->enum('metodo', ['cartao', 'boleto', 'pix', 'transferencia']); 
            // método de pagamento
            $table->enum('status', ['pendente', 'pago', 'cancelado'])->default('pendente'); 
            // status da transação
            $table->string('referencia')->nullable(); // código de referência
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};

