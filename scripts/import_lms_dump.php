<?php
/**
 * Importa lms-data-dump.json no MySQL do servidor.
 * Garante colunas essenciais e preserva IDs (Elias=12, Leandro=11).
 */
declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/plain; charset=utf-8');

$dumpFile = __DIR__ . '/lms-data-dump.json';
if (! is_file($dumpFile)) {
    fwrite(STDERR, "Falta lms-data-dump.json\n");
    exit(1);
}

$dump = json_decode(file_get_contents($dumpFile), true);
if (! is_array($dump)) {
    fwrite(STDERR, "JSON inválido\n");
    exit(1);
}

echo "DB=", DB::connection()->getDatabaseName(), PHP_EOL;

// --- schema mínimo ---
if (! Schema::hasColumn('cursos', 'status')) {
    Schema::table('cursos', function (Blueprint $table) {
        $table->string('status')->default('ativo')->nullable();
    });
    echo "Add cursos.status\n";
}
if (! Schema::hasColumn('cursos', 'agendado_em')) {
    Schema::table('cursos', function (Blueprint $table) {
        $table->timestamp('agendado_em')->nullable();
    });
}
foreach (['duracao_horas_min', 'duracao_horas_max'] as $col) {
    if (! Schema::hasColumn('cursos', $col)) {
        Schema::table('cursos', function (Blueprint $table) use ($col) {
            $table->unsignedSmallInteger($col)->nullable();
        });
    }
}
foreach (['nivel_trilha', 'capa_path', 'matriz_path', 'checklist_path'] as $col) {
    if (! Schema::hasColumn('cursos', $col)) {
        Schema::table('cursos', function (Blueprint $table) use ($col) {
            $table->string($col)->nullable();
        });
    }
}
if (! Schema::hasColumn('aulas', 'ordem')) {
    Schema::table('aulas', function (Blueprint $table) {
        $table->integer('ordem')->default(0);
    });
}
if (! Schema::hasColumn('aulas', 'is_hidden')) {
    Schema::table('aulas', function (Blueprint $table) {
        $table->boolean('is_hidden')->default(false);
    });
}
foreach (['arquivo', 'ordem'] as $col) {
    if (! Schema::hasColumn('conteudos', $col)) {
        Schema::table('conteudos', function (Blueprint $table) use ($col) {
            if ($col === 'ordem') {
                $table->integer('ordem')->default(0);
            } else {
                $table->string('arquivo')->nullable();
            }
        });
    }
}
if (! Schema::hasColumn('conteudos', 'is_hidden')) {
    Schema::table('conteudos', function (Blueprint $table) {
        $table->boolean('is_hidden')->default(false);
    });
}
foreach (['allow_download', 'allow_download_student', 'allow_download_professor'] as $col) {
    if (! Schema::hasColumn('conteudos', $col)) {
        Schema::table('conteudos', function (Blueprint $table) use ($col) {
            $table->boolean($col)->default(false);
        });
    }
}
if (! Schema::hasColumn('matriculas', 'turma_id')) {
    Schema::table('matriculas', function (Blueprint $table) {
        $table->unsignedBigInteger('turma_id')->nullable();
    });
}
if (! Schema::hasTable('conteudo_progresso')) {
    Schema::create('conteudo_progresso', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('conteudo_id');
        $table->unsignedTinyInteger('progresso')->default(0);
        $table->boolean('concluido')->default(false);
        $table->timestamps();
        $table->unique(['user_id', 'conteudo_id']);
    });
    echo "Created conteudo_progresso\n";
}

// MySQL: ampliar enum/tipo de conteudos
try {
    DB::statement("ALTER TABLE conteudos MODIFY tipo VARCHAR(40) NOT NULL");
    echo "conteudos.tipo -> VARCHAR\n";
} catch (Throwable $e) {
    echo "tipo warn: ", $e->getMessage(), PHP_EOL;
}
try {
    DB::statement("ALTER TABLE conteudos MODIFY url LONGTEXT NULL");
    DB::statement("ALTER TABLE conteudos MODIFY descricao LONGTEXT NULL");
    echo "conteudos.url/descricao -> LONGTEXT\n";
} catch (Throwable $e) {
    echo "url warn: ", $e->getMessage(), PHP_EOL;
}

DB::statement('SET FOREIGN_KEY_CHECKS=0');

$order = ['conteudo_progresso', 'matriculas', 'conteudos', 'aulas', 'cursos', 'users'];
foreach ($order as $table) {
    if (Schema::hasTable($table)) {
        DB::table($table)->delete();
        echo "cleared $table\n";
    }
}

$insertOrder = ['users', 'cursos', 'aulas', 'conteudos', 'matriculas', 'conteudo_progresso'];

foreach ($insertOrder as $table) {
    $pack = $dump['tables'][$table] ?? null;
    if (! $pack) {
        echo "skip missing dump $table\n";
        continue;
    }
    $existingCols = Schema::getColumnListing($table);
    $n = 0;
    foreach ($pack['rows'] as $row) {
        $data = [];
        foreach ($row as $k => $v) {
            if (in_array($k, $existingCols, true)) {
                $data[$k] = $v;
            }
        }
        if ($table === 'users' && isset($data['password'])) {
            // já vem bcrypt do SQLite — não re-hashear
        }
        DB::table($table)->insert($data);
        $n++;
    }
    echo "imported $table: $n\n";
}

DB::statement('SET FOREIGN_KEY_CHECKS=1');

// sanity
echo "\nCOUNTS:\n";
foreach ($insertOrder as $table) {
    echo $table, '=', DB::table($table)->count(), PHP_EOL;
}
$elias = DB::table('users')->where('email', 'elias@codar.local')->first();
$leo = DB::table('users')->where('email', 'leandro@codar.local')->first();
echo 'Elias id=', $elias->id ?? '?', ' role=', $elias->role ?? '?', PHP_EOL;
echo 'Leandro id=', $leo->id ?? '?', ' role=', $leo->role ?? '?', PHP_EOL;
$curso3 = DB::table('cursos')->where('id', 3)->first();
echo 'Curso3=', $curso3->titulo ?? '?', ' professor_id=', $curso3->professor_id ?? '?', PHP_EOL;
echo 'Aulas curso3=', DB::table('aulas')->where('curso_id', 3)->count(), PHP_EOL;
echo 'Matricula Leandro curso3=', DB::table('matriculas')->where('aluno_id', $leo->id ?? 0)->where('curso_id', 3)->count(), PHP_EOL;
echo "OK\n";
