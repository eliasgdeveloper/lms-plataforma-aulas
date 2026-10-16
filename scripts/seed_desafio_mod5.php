<?php
/**
 * Insere o Desafio/Prova do módulo 5 (aula 59) APÓS a prova.
 * Preserva IDs existentes (progresso do Leandro).
 */
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (! Schema::hasColumn('users', 'lms_badge')) {
    Schema::table('users', function ($table) {
        $table->string('lms_badge', 180)->nullable();
    });
    echo "added users.lms_badge\n";
}

$enunciado = <<<'TXT'
Você foi contratado por uma fintech para o motor de fechamento de caixa diário via CLI.

O sistema recebe linhas brutas de transações, faz limpeza, filtragem, cálculos e formata a saída exatamente como o contrato do juiz.

Regras:
1. Processe o array de entrada linha a linha.
2. Ignore linhas vazias ou mal formatadas.
3. Só vale status APROVADO e valor numérico (is_numeric).
4. Use array_reduce, usort, array_slice e texto com trim.
5. Proteja o ticket médio contra divisão por zero.
6. number_format com 2 casas, ponto decimal, sem milhar.
7. Quebras de linha com PHP_EOL.

Array de entrada (já use este no seu script):

$dadosBrutosEntrada = [
    "101; Loja Centro & Cia; 1500.75; APROVADO",
    "102; Matriz SP; -50.00; REPROVADO",
    "103; Filial \"Sul\"; 3250.00; APROVADO",
    "104; Quiosque Shopping; abc; APROVADO",
    "105; Loja Express; 820.50; APROVADO",
    "106; Outlet Premium; 1200.00; APROVADO",
    "107; Loja Teste Invalida; ; PENDENTE"
];
TXT;

$saida = <<<'OUT'
=== RELATORIO DE FECHAMENTO DE CAIXA ===
Total de Lojas Analisadas: 7
Transacoes Validas Processadas: 4
Faturamento Total: 6771.25
Ticket Medio: 1692.81

--- TOP 3 MAIORES TRANSACOES ---
1. Filial "Sul": 3250.00
2. Loja Centro & Cia: 1500.75
3. Outlet Premium: 1200.00
=========================================
OUT;

$payload = [
    'kind' => 'desafio_prova',
    'titulo' => 'Desafio/Prova · Fechamento de caixa',
    'pass_score' => 70,
    'max_attempts' => 3,
    'badge' => '/badges/caixa-litoral.svg',
    'badge_name' => 'Carinha Caixa Litoral',
    'enunciado' => $enunciado,
    'saida_esperada' => $saida,
    'quiz_intro' => 'Estas perguntas treinam a memória do código que você acabou de fazer. Sem pressa: cada acerto grava o padrão na cabeça — isso vale ouro na carreira PHP.',
    'soon_message' => 'O módulo 5 ainda continua. Em breve teremos mais desafios, quiz, testes, provas e conteúdos por aqui — esta matéria é muito importante 😊 Você está indo bem, e repetir código de propósito ajuda a memorizar para a carreira PHP.',
    'questions' => [
        [
            'q' => 'Arraste cada ferramenta até o papel dela neste fechamento de caixa.',
            'type' => 'drag_slots',
            'slots' => [
                ['id' => 's1', 'label' => 'Somar o faturamento', 'answer' => 'array_reduce'],
                ['id' => 's2', 'label' => 'Ordenar do maior para o menor', 'answer' => 'usort'],
                ['id' => 's3', 'label' => 'Ficar só com 3 linhas do ranking', 'answer' => 'array_slice'],
                ['id' => 's4', 'label' => 'Checar se o valor é número de verdade', 'answer' => 'is_numeric'],
            ],
            'chips' => ['array_reduce', 'usort', 'array_slice', 'is_numeric', 'strtoupper'],
        ],
        [
            'q' => 'Complete o ticket médio com proteção contra divisão por zero. Preencha o buraco.',
            'type' => 'codefill',
            'template' => '$ticketMedio = ($qtd > 0) ? ($total / ___) : 0.0;',
            'answer_text' => '$qtd',
            'accept' => ['$qtd', 'qtd'],
        ],
        [
            'q' => 'No number_format do contrato, qual caractere entra como separador decimal?',
            'type' => 'input',
            'answer_text' => '.',
        ],
        [
            'q' => 'Por que o relatório conta 7 lojas analisadas e só 4 transações válidas?',
            'type' => 'mcq',
            'options' => [
                'Porque o juiz ignora APROVADO',
                'Porque 7 é o total de linhas úteis e 4 passaram em APROVADO + is_numeric',
                'Porque Top 3 vira 4',
                'Porque explode remove três lojas sempre',
            ],
            'answer' => 1,
        ],
        [
            'q' => 'Monte a ordem do fluxo: do bruto até o Top 3. Arraste os cartões de cima para baixo.',
            'type' => 'order',
            'items' => [
                'foreach + trim em cada linha',
                'explode(\';\', $linha, 4) e validar campos',
                'filtrar APROVADO + is_numeric',
                'array_reduce no faturamento',
                'usort e array_slice para o Top 3',
            ],
            'answer_order' => [
                'foreach + trim em cada linha',
                'explode(\';\', $linha, 4) e validar campos',
                'filtrar APROVADO + is_numeric',
                'array_reduce no faturamento',
                'usort e array_slice para o Top 3',
            ],
        ],
        [
            'q' => 'Qual palavra-chave/constante garante a quebra de linha portável no echo final?',
            'type' => 'input',
            'answer_text' => 'PHP_EOL',
        ],
        [
            'q' => 'Complete o recorte da linha. Qual o delimitador e o limite de campos?',
            'type' => 'codefill',
            'template' => '$partes = explode(\'___\', $linha, ___);',
            'blanks' => [';', '4'],
        ],
        [
            'q' => 'Associe o trecho da linha ao destino certo (arraste).',
            'type' => 'drag_slots',
            'slots' => [
                ['id' => 'a', 'label' => 'trim($partes[1])', 'answer' => 'nome da loja'],
                ['id' => 'b', 'label' => 'trim($partes[2])', 'answer' => 'valor em texto'],
                ['id' => 'c', 'label' => 'trim($partes[3])', 'answer' => 'status'],
                ['id' => 'd', 'label' => 'count($partes) < 4', 'answer' => 'linha mal formatada'],
            ],
            'chips' => ['nome da loja', 'valor em texto', 'status', 'linha mal formatada', 'ticket médio'],
        ],
        [
            'q' => '6771.25 dividido por 4 dá o ticket médio do contrato. Qual valor deve aparecer?',
            'type' => 'mcq',
            'options' => ['1692.8', '1692.81', '1693', '6771.25'],
            'answer' => 1,
        ],
        [
            'q' => 'Complete o Top 3 depois da ordenação.',
            'type' => 'codefill',
            'template' => '$top3 = array_slice($transacoesValidas, 0, ___);',
            'answer_text' => '3',
        ],
    ],
];

$aulaId = 59;
$titulo = 'Desafio/Prova · Fechamento de caixa';
$existing = DB::table('conteudos')->where('aula_id', $aulaId)->where('titulo', $titulo)->first();

$row = [
    'aula_id' => $aulaId,
    'titulo' => $titulo,
    'descricao' => 'Cole o código e a saída · depois 10 perguntas de memorização · selo Caixa Litoral.',
    'tipo' => 'desafio',
    'url' => json_encode($payload, JSON_UNESCAPED_UNICODE),
    'ordem' => 8,
    'is_hidden' => 0,
    'updated_at' => now(),
];

if ($existing) {
    DB::table('conteudos')->where('id', $existing->id)->update($row);
    $id = (int) $existing->id;
    echo "updated desafio id=$id\n";
} else {
    $row['created_at'] = now();
    if (Schema::hasColumn('conteudos', 'allow_download_student')) {
        $row['allow_download_student'] = 0;
    }
    $id = (int) DB::table('conteudos')->insertGetId($row);
    echo "inserted desafio id=$id\n";
}

echo 'questions='.count($payload['questions'])."\n";
echo "OK desafio_mod5\n";
