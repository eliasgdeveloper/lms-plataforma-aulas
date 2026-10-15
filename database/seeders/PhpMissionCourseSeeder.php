<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\AppliesCourseBlueprint;
use Illuminate\Database\Seeder;

/**
 * Popular o curso PHP (id 3) a partir do blueprint reutilizável.
 *
 * php artisan db:seed --class=PhpMissionCourseSeeder
 */
class PhpMissionCourseSeeder extends Seeder
{
    use AppliesCourseBlueprint;

    public function run(): void
    {
        $blueprint = require database_path('blueprints/php_mission.php');
        $curso = $this->applyCourseBlueprint($blueprint);

        $this->command?->info("Curso #{$curso->id} '{$curso->titulo}' pronto com {$curso->aulas->count()} módulos.");
    }
}
