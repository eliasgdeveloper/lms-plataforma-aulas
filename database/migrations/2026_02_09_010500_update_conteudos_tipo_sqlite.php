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

        if (Schema::hasTable('conteudo_progresso')) {
            Schema::rename('conteudo_progresso', 'conteudo_progresso_old');
            DB::statement('DROP INDEX IF EXISTS conteudo_progresso_user_id_conteudo_id_unique');

            Schema::create('conteudo_progresso', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('conteudo_id')->constrained('conteudos')->onDelete('cascade');
                $table->unsignedTinyInteger('progresso')->default(0);
                $table->boolean('concluido')->default(false);
                $table->timestamps();

                $table->unique(['user_id', 'conteudo_id']);
            });

            DB::statement(
                'INSERT INTO conteudo_progresso (id, user_id, conteudo_id, progresso, concluido, created_at, updated_at) '
                . 'SELECT id, user_id, conteudo_id, progresso, concluido, created_at, updated_at FROM conteudo_progresso_old'
            );

            Schema::drop('conteudo_progresso_old');
        }

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

        if (Schema::hasTable('conteudo_progresso')) {
            Schema::rename('conteudo_progresso', 'conteudo_progresso_old');
            DB::statement('DROP INDEX IF EXISTS conteudo_progresso_user_id_conteudo_id_unique');

            Schema::create('conteudo_progresso', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('conteudo_id')->constrained('conteudos')->onDelete('cascade');
                $table->unsignedTinyInteger('progresso')->default(0);
                $table->boolean('concluido')->default(false);
                $table->timestamps();

                $table->unique(['user_id', 'conteudo_id']);
            });

            DB::statement(
                'INSERT INTO conteudo_progresso (id, user_id, conteudo_id, progresso, concluido, created_at, updated_at) '
                . 'SELECT id, user_id, conteudo_id, progresso, concluido, created_at, updated_at FROM conteudo_progresso_old'
            );

            Schema::drop('conteudo_progresso_old');
        }

        Schema::drop('conteudos_old');

        DB::statement('PRAGMA foreign_keys=ON');
    }
};
