<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        DB::statement('PRAGMA foreign_keys=OFF');

        Schema::rename('conteudos', 'conteudos_old');

        Schema::create('conteudos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            $table->string('tipo');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('url')->nullable();
            $table->string('arquivo')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });

        DB::statement(
            'INSERT INTO conteudos (id, aula_id, tipo, titulo, descricao, url, arquivo, is_hidden, ordem, created_at, updated_at) '
            . 'SELECT id, aula_id, tipo, titulo, descricao, url, arquivo, is_hidden, ordem, created_at, updated_at FROM conteudos_old'
        );

        Schema::drop('conteudos_old');

        DB::statement('PRAGMA foreign_keys=ON');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        DB::statement('PRAGMA foreign_keys=OFF');

        Schema::rename('conteudos', 'conteudos_old');

        Schema::create('conteudos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            $table->enum('tipo', ['video', 'pdf', 'link', 'texto']);
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('url')->nullable();
            $table->string('arquivo')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });

        DB::statement(
            'INSERT INTO conteudos (id, aula_id, tipo, titulo, descricao, url, arquivo, is_hidden, ordem, created_at, updated_at) '
            . "SELECT id, aula_id, tipo, titulo, descricao, url, arquivo, is_hidden, ordem, created_at, updated_at FROM conteudos_old WHERE tipo IN ('video','pdf','link','texto')"
        );

        Schema::drop('conteudos_old');

        DB::statement('PRAGMA foreign_keys=ON');
    }
};
