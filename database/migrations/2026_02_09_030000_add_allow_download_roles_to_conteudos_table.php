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
            $table->boolean('allow_download_student')->default(true)->after('allow_download');
            $table->boolean('allow_download_professor')->default(true)->after('allow_download_student');
        });

        if (Schema::hasColumn('conteudos', 'allow_download')) {
            DB::table('conteudos')->update([
                'allow_download_student' => DB::raw('allow_download'),
                'allow_download_professor' => true,
            ]);
        }

        DB::table('conteudos')
            ->where('tipo', 'video')
            ->update(['allow_download_student' => false]);
    }

    public function down(): void
    {
        Schema::table('conteudos', function (Blueprint $table) {
            $table->dropColumn(['allow_download_student', 'allow_download_professor']);
        });
    }
};
