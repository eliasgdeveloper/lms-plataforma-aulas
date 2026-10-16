<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class InitialCourseSeeder extends Seeder
{
    public function run(): void
    {
        if (Curso::query()->exists()) {
            return;
        }

        $this->call(PhpMissionCourseSeeder::class);
    }
}
