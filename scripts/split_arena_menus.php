<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

DB::beginTransaction();
try {
    $aula1 = DB::table('aulas')->where('id', 38)->first();
    if (! $aula1) {
        throw new RuntimeException('aula 38 missing');
    }

    $existing = DB::table('aulas')
        ->where('curso_id', $aula1->curso_id)
        ->where('titulo', 'like', '%05 · [Basico] Arena 2%')
        ->first();
    if ($existing) {
        echo "already_split aula2_id={$existing->id}\n";
        DB::rollBack();
        exit(0);
    }

    DB::table('aulas')
        ->where('curso_id', $aula1->curso_id)
        ->where('ordem', '>=', 6)
        ->increment('ordem');

    $updateAula1 = [
        'titulo' => '05 · [Basico] Arena 1 · Arrays na pratica',
        'ordem' => 5,
        'updated_at' => now(),
    ];
    if (Schema::hasColumn('aulas', 'descricao')) {
        $updateAula1['descricao'] = 'Arena 1: arrays, foreach, count e texto. Leia o guia, entre na Arena e consolide com o quiz.';
    }
    DB::table('aulas')->where('id', 38)->update($updateAula1);

    $insert = [
        'curso_id' => $aula1->curso_id,
        'titulo' => '05 · [Basico] Arena 2 · Caixa Litoral',
        'ordem' => 6,
        'created_at' => now(),
        'updated_at' => now(),
    ];
    if (Schema::hasColumn('aulas', 'descricao')) {
        $insert['descricao'] = 'Arena 2: count, soma, media, reduce e texto. Outra empresa, mesmos fundamentos.';
    }
    if (Schema::hasColumn('aulas', 'is_hidden')) {
        $insert['is_hidden'] = 0;
    }
    $aula2Id = DB::table('aulas')->insertGetId($insert);

    $n = DB::table('conteudos')->whereIn('id', [173, 174, 175, 176, 177, 178])->update([
        'aula_id' => $aula2Id,
        'updated_at' => now(),
    ]);

    foreach ([123 => 1, 124 => 2, 125 => 3, 126 => 4, 127 => 5, 128 => 6] as $id => $ord) {
        DB::table('conteudos')->where('id', $id)->update(['ordem' => $ord]);
    }
    foreach ([173 => 1, 174 => 2, 175 => 3, 176 => 4, 177 => 5, 178 => 6] as $id => $ord) {
        DB::table('conteudos')->where('id', $id)->update(['ordem' => $ord, 'aula_id' => $aula2Id]);
    }

    DB::table('conteudos')->whereIn('id', [128, 178])->update(['is_hidden' => 1]);

    DB::commit();
    echo "ok aula2_id=$aula2Id moved=$n\n";
    foreach (DB::table('aulas')->where('curso_id', $aula1->curso_id)->whereBetween('ordem', [5, 8])->orderBy('ordem')->get(['id', 'ordem', 'titulo']) as $r) {
        echo "{$r->ordem}|{$r->id}|{$r->titulo}\n";
    }
} catch (Throwable $e) {
    DB::rollBack();
    fwrite(STDERR, $e->getMessage()."\n");
    exit(1);
}
