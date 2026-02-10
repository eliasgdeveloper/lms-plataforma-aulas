<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Criação da tabela de biblioteca
 *
 * Este arquivo cria:
 * - Tabela `biblioteca` → armazena recursos acadêmicos extras.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biblioteca', function (Blueprint $table) {
            $table->id(); // chave primária
            $table->string('titulo'); // título do recurso
            $table->string('autor')->nullable(); // autor ou responsável
            $table->enum('tipo', ['livro', 'artigo', 'apostila', 'material_digital']); 
            // tipo de recurso
            $table->text('descricao')->nullable(); // descrição detalhada
            $table->string('arquivo')->nullable(); // caminho do arquivo armazenado
            $table->string('url')->nullable(); // link externo
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->onDelete('cascade'); 
            // curso relacionado (opcional)
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biblioteca');
    }
};

