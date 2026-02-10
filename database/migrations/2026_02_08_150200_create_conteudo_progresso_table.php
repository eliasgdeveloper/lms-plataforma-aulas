<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conteudo_progresso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conteudo_id')->constrained('conteudos')->onDelete('cascade');
            $table->unsignedTinyInteger('progresso')->default(0);
            $table->boolean('concluido')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'conteudo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conteudo_progresso');
    }
};
