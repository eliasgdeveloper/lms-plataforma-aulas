<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->string('status')->default('ativo')->after('categoria');
            $table->timestamp('agendado_em')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn(['status', 'agendado_em']);
        });
    }
};
