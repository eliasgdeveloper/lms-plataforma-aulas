<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conteudos', function (Blueprint $table) {
            $table->string('arquivo')->nullable()->after('url');
            $table->boolean('is_hidden')->default(false)->after('arquivo');
            $table->integer('ordem')->default(0)->after('is_hidden');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE conteudos MODIFY tipo ENUM('video','pdf','link','texto','arquivo','word','excel','quiz','prova','tarefa') NOT NULL"
            );
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE conteudos MODIFY tipo ENUM('video','pdf','link','texto') NOT NULL"
            );
        }

        Schema::table('conteudos', function (Blueprint $table) {
            $table->dropColumn(['arquivo', 'is_hidden', 'ordem']);
        });
    }
};
