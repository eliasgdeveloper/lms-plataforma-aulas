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
            $table->boolean('allow_download')->default(true)->after('ordem');
        });

        DB::table('conteudos')
            ->where('tipo', 'video')
            ->update(['allow_download' => false]);
    }

    public function down(): void
    {
        Schema::table('conteudos', function (Blueprint $table) {
            $table->dropColumn('allow_download');
        });
    }
};
