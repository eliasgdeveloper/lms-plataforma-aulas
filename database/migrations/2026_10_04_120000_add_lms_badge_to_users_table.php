<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'lms_badge')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('lms_badge', 180)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'lms_badge')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('lms_badge');
            });
        }
    }
};
