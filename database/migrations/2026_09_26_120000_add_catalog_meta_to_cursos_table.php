<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->unsignedSmallInteger('duracao_horas_min')->nullable()->after('categoria');
            $table->unsignedSmallInteger('duracao_horas_max')->nullable()->after('duracao_horas_min');
            $table->string('nivel_trilha', 40)->nullable()->after('duracao_horas_max');
            $table->string('capa_path')->nullable()->after('nivel_trilha');
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn([
                'duracao_horas_min',
                'duracao_horas_max',
                'nivel_trilha',
                'capa_path',
            ]);
        });
    }
};
