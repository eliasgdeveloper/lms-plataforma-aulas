<?php
/**
 * Enriquece o curso PHP (módulo 5 quizzes/prova + módulos 1–4).
 * Preserva IDs de conteúdo existentes (progresso do Leandro).
 */
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function q_mcq(string $q, array $options, int $answer): array
{
    return ['q' => $q, 'type' => 'mcq', 'options' => $options, 'answer' => $answer];
}

function q_input(string $q, string $answerText, ?string $compareNote = null): array
{
    $row = ['q' => $q, 'type' => 'input', 'answer_text' => $answerText];
    if ($compareNote) {
        $row['note'] = $compareNote;
    }

    return $row;
}

function q_match(string $q, array $pairs): array
{
    return ['q' => $q, 'type' => 'match', 'pairs' => $pairs];
}

function upsertConteudo(int $aulaId, string $titulo, string $tipo, string $url, string $descricao, int $ordem): int
{
    $existing = DB::table('conteudos')
        ->where('aula_id', $aulaId)
        ->where('titulo', $titulo)
        ->first();

    $payload = [
        'aula_id' => $aulaId,
        'titulo' => $titulo,
        'descricao' => $descricao,
        'tipo' => $tipo,
        'url' => $url,
        'ordem' => $ordem,
        'is_hidden' => 0,
        'updated_at' => now(),
    ];

    if ($existing) {
        DB::table('conteudos')->where('id', $existing->id)->update($payload);

        return (int) $existing->id;
    }

    $payload['created_at'] = now();
    if (Schema::hasColumn('conteudos', 'allow_download_student')) {
        $payload['allow_download_student'] = 0;
    }

    return (int) DB::table('conteudos')->insertGetId($payload);
}

$quizArena1 = [
    'titulo' => 'Quiz · Arena PHP atividade 1',
    'pass_score' => 70,
    'max_attempts' => 3,
    'mode' => 'quiz',
    'success_message' => 'Bom quiz! Você consolidou arrays e texto em PHP.',
    'questions' => [
        q_mcq('Em PHP, qual declaração cria um array indexado com três strings?', [
            '$a = array("a", "b", "c");',
            '$a = {"a", "b", "c"};',
            '$a = ["a" => "b" => "c"];',
            '$a = list("a", "b", "c");',
        ], 0),
        q_mcq('A função count($arr) retorna:', [
            'A soma numérica dos elementos',
            'A quantidade de elementos do array',
            'O último índice do array',
            'O tamanho em bytes da variável',
        ], 1),
        q_mcq('Qual laço percorre cada valor de um array indexado em PHP?', [
            'for-in',
            'foreach ($arr as $valor)',
            'each ($arr)',
            'loop ($arr)',
        ], 1),
        q_mcq('strtoupper("php") produz:', [
            'Php',
            'PHP',
            'php',
            'erro de tipo',
        ], 1),
        q_mcq('Para copiar um elemento pelo índice e alterar só a cópia, o padrão correto é:', [
            '$x = &$arr[0]; $x = "novo";',
            '$x = $arr[0]; $x = "novo";',
            'unset($arr[0]);',
            'array_pop($arr);',
        ], 1),
        q_input('Digite o nome da função PHP que conta elementos de um array (somente o nome, sem parênteses).', 'count'),
        q_mcq('Em JavaScript, Array.length é uma propriedade. Em PHP, o equivalente idiomático é a função:', [
            'size()',
            'count()',
            'length()',
            'sizeof_array()',
        ], 1),
        q_match('Associe o recurso PHP ao efeito esperado.', [
            ['foreach', 'Percorre itens de um array'],
            ['strtoupper', 'Converte texto para maiúsculas'],
            ['echo', 'Envia saída para o cliente'],
            ['count', 'Informa quantos itens existem'],
        ]),
        q_mcq('Qual afirmação sobre índices de array em PHP é verdadeira?', [
            'Todo array PHP começa obrigatoriamente no índice 1',
            'Índices numéricos costumam começar em 0, mas chaves associativas são permitidas',
            'PHP não permite misturar chaves string e numéricas',
            'echo $arr imprime todos os elementos formatados',
        ], 1),
        q_mcq('Ao validar uma missão da Arena, o juiz compara principalmente:', [
            'O nome do arquivo fonte',
            'A saída produzida frente a contratos da etapa',
            'A velocidade de digitação',
            'O número de linhas do código',
        ], 1),
    ],
];

$quizArena2 = [
    'titulo' => 'Quiz · Arena 2 Caixa Litoral',
    'pass_score' => 70,
    'max_attempts' => 3,
    'mode' => 'quiz',
    'success_message' => 'Sucesso no quiz! Caixa Litoral no domínio.',
    'questions' => [
        q_mcq('array_sum([10, 20, 5]) retorna:', ['35', '3', '10', '205'], 0),
        q_mcq('Para obter a média aritmética de um array numérico em PHP, o padrão seguro é:', [
            'array_sum($a) / count($a) (com count > 0)',
            'avg($a)',
            'mean($a)',
            'array_average($a)',
        ], 0),
        q_mcq('strlen("caixa") retorna:', ['4', '5', '6', '0'], 1),
        q_mcq('strval(12.5) produz tipicamente:', ['"12"', '"12.5"', '12', 'erro'], 1),
        q_input('Digite a função PHP que soma todos os valores numéricos de um array.', 'array_sum'),
        q_mcq('Em JavaScript, texto.length conta caracteres. Em PHP, para string byte-oriented clássica usa-se:', [
            'count($str)',
            'strlen($str)',
            'size($str)',
            'mb_count($str) obrigatoriamente',
        ], 1),
        q_mcq('Um if que protege divisão por zero ao calcular média deve verificar:', [
            'array_key_exists(0, $a)',
            'count($a) > 0',
            'isset($a) somente',
            'is_string($a)',
        ], 1),
        q_match('Associe operação da Arena 2 ao objetivo de negócio.', [
            ['array_sum', 'Totalizar vendas do sábado'],
            ['count', 'Quantas lojas entraram no relatório'],
            ['strlen', 'Validar tamanho de observação'],
            ['strtoupper', 'Padronizar títulos da vitrine'],
        ]),
        q_mcq('array_reduce é útil quando:', [
            'Você só precisa do primeiro elemento',
            'Precisa acumular um resultado percorrendo o array com uma função',
            'Quer ordenar strings alfabeticamente',
            'Deseja converter JSON em XML',
        ], 1),
        q_mcq('Qual saída é mais adequada para um relatório de caixa no terminal?', [
            'Somente var_dump sem contexto',
            'Textos clear com echo e valores calculados',
            'print_r dentro de HTML sem escape',
            'die() em toda linha',
        ], 1),
    ],
];

$provaMod5 = [
    'titulo' => 'Prova · Módulo 5 — Arrays, texto e Arenas',
    'pass_score' => 70,
    'max_attempts' => 1,
    'time_limit_sec' => 1800,
    'mode' => 'prova',
    'leave_warning' => true,
    'success_message' => 'Prova concluída. Sucesso — você treinou como em processo seletivo.',
    'intro' => "Leia com atenção. Não saia desta página/janela até finalizar: a saída pode encerrar a tentativa.\nTempo máximo: 30 minutos. Uma única tentativa. Sem gabarito durante a prova.\nNota mínima: 70%.",
    'questions' => [
        q_mcq('Em um processo seletivo PHP, pedem para listar serviços de um array. A abordagem idiomática é:', [
            'foreach ($servicos as $s) { echo $s, PHP_EOL; }',
            'for-in ($servicos)',
            'servicos.forEach sem adaptação',
            'while(true) echo $servicos',
        ], 0),
        q_mcq('Qual expressão PHP conta quantos itens há em $pedidos?', [
            'sizeof_array($pedidos)',
            'count($pedidos)',
            '$pedidos->length',
            'len($pedidos)',
        ], 1),
        q_mcq('Diferença importante: em JavaScript, arr.length é propriedade; em PHP count($arr) é:', [
            'Também uma propriedade mágica',
            'Uma função que recebe o array',
            'Um método obrigatório de classe Array',
            'Um operador unário',
        ], 1),
        q_input('Informe a função PHP que converte "codar" em "CODAR".', 'strtoupper'),
        q_mcq('Você precisa atualizar apenas o índice 2 de um portfólio sem perder os demais. O correto é:', [
            '$p = ["a"]; unset($p);',
            '$portfolio[2] = "Novo projeto";',
            '$portfolio = "Novo projeto";',
            'array_shift($portfolio, 2)',
        ], 1),
        q_mcq('Para totalizar valores de vendas em $valores, use:', [
            'array_sum($valores)',
            'sum($valores)',
            'reduce.sum($valores)',
            'array_total($valores)',
        ], 0),
        q_mcq('Antes de calcular média com array_sum/count, o risco clássico é:', [
            'Overflow de string',
            'Divisão por zero se o array estiver vazio',
            'Perder o índice associativo automaticamente',
            'Converter int em float sem aviso',
        ], 1),
        q_match('Associe o comando ao papel no relatório da padaria.', [
            ['count', 'Quantidade de lojas/registros'],
            ['array_sum', 'Soma das vendas'],
            ['strlen', 'Tamanho de um campo texto'],
            ['strval', 'Normalizar número como texto'],
        ]),
        q_mcq('strval(0) em PHP resulta em:', [
            'string vazia',
            '"0"',
            'null',
            'false',
        ], 1),
        q_mcq('Um contrato de juiz de missão costuma falhar quando:', [
            'A saída difere do formato esperado, mesmo com lógica “parecida”',
            'O aluno usa foreach em vez de for',
            'Há comentários no código',
            'O arquivo tem mais de 10 linhas',
        ], 0),
        q_mcq('Em JS, "texto".toUpperCase() é método de string. Em PHP, strtoupper($texto) é:', [
            'Método de objeto String nativo',
            'Função procedural que recebe a string',
            'Propriedade',
            'Operador',
        ], 1),
        q_input('Digite a função que retorna o comprimento de uma string em PHP (versão clássica de bytes).', 'strlen'),
        q_mcq('foreach ($a as $k => $v) disponibiliza:', [
            'Somente valores',
            'Chave e valor a cada iteração',
            'Somente chaves',
            'Referência obrigatória de objeto',
        ], 1),
        q_mcq('Qual opção imprime “3 itens” corretamente?', [
            'echo count($a) . " itens";',
            'echo $a.count . " itens";',
            'print length($a) + " itens";',
            'echo sizeof_array($a);',
        ], 0),
        q_mcq('Ao copiar $copia = $original (arrays em PHP), a semântica padrão é:', [
            'Sempre referência compartilhada profunda',
            'Atribuição por valor (cópia) do array',
            'Impossível copiar arrays',
            'Converte automaticamente para objeto',
        ], 1),
        q_mcq('array_map("strtoupper", $titulos) serve para:', [
            'Ordenar títulos',
            'Aplicar strtoupper a cada elemento, gerando novo array',
            'Somar títulos',
            'Remover duplicatas',
        ], 1),
        q_mcq('Em um seletivo, pedem evitar notice de índice indefinido. O padrão seguro é:', [
            'Acessar $a[99] diretamente',
            'Usar isset($a[99]) ou null coalescing antes de usar',
            'Suppressor @ em todo o arquivo',
            'Trocar para JavaScript',
        ], 1),
        q_mcq('Qual analogia está correta?', [
            'PHP count ≈ JS Array.length (ambos medem cardinalidade da coleção)',
            'PHP echo ≈ JS const',
            'PHP foreach ≈ JS class',
            'PHP strtoupper ≈ JS parseInt',
        ], 0),
        q_input('Função PHP que soma elementos numéricos de um array:', 'array_sum'),
        q_mcq('Objetivo pedagógico das Arenas neste módulo é:', [
            'Memorizar HTML sem PHP',
            'Praticar arrays/texto com contratos e progresso gamificado',
            'Substituir o LMS',
            'Aprender apenas CSS',
        ], 1),
    ],
];

function quizModule(string $titulo, array $questions, string $success): array
{
    return [
        'titulo' => $titulo,
        'pass_score' => 70,
        'max_attempts' => 3,
        'mode' => 'quiz',
        'success_message' => $success,
        'questions' => $questions,
    ];
}

function provaModule(string $titulo, array $questions, string $intro, string $success): array
{
    return [
        'titulo' => $titulo,
        'pass_score' => 70,
        'max_attempts' => 1,
        'time_limit_sec' => 1800,
        'mode' => 'prova',
        'leave_warning' => true,
        'intro' => $intro,
        'success_message' => $success,
        'questions' => $questions,
    ];
}

$mod1Quiz = quizModule('Quiz · Ambiente e primeiro script', [
    q_mcq('A extensão padrão de um script PHP é:', ['.html', '.php', '.js', '.py'], 1),
    q_mcq('Qual abertura inicia código PHP?', ['<%', '<?php', '<#php', '{{'], 1),
    q_mcq('echo "Olá"; faz o quê?', ['Declara variável', 'Envia texto para a saída', 'Cria função', 'Abre arquivo'], 1),
    q_mcq('Em JS usa-se console.log. Em PHP, o análogo comum em CLI/web é:', ['print_log', 'echo ou print', 'System.out', 'fmt.Println'], 1),
    q_input('Digite a tag de abertura curta recomendada nas docs modernas (quatro caracteres iniciais com ?).', '<?php'),
    q_mcq('Um ambiente local típico para PHP iniciante inclui:', ['Somente Excel', 'Servidor (Apache/Nginx) + interpretador PHP', 'Apenas CSS', 'GPU obrigatória'], 1),
    q_match('Associe ferramenta ao papel.', [['PHP', 'Linguagem interpretada no servidor'], ['echo', 'Saída de texto'], ['XAMPP/Laragon', 'Ambiente local'], ['navegador', 'Consome resposta HTTP']]),
    q_mcq('Comentário de uma linha em PHP:', ['# ou //', '<!-- --> somente', '/* obrigatório', '-- sql'], 0),
    q_mcq('Por que `;` aparece no fim de instruções?', ['É opcional sempre', 'Termina a instrução (statement)', 'Inicia bloco', 'Importa módulo'], 1),
    q_mcq('Arquivo index.php em uma pasta do servidor costuma ser:', ['Ignorado', 'Documento padrão ao acessar o diretório', 'Apenas CSS', 'Binário'], 1),
], 'Bom quiz! Ambiente e echo no ritmo.');

$mod1Prova = provaModule(
    'Prova · Módulo 1 — Ambiente e primeiro script',
    [
        q_mcq('Em processo seletivo, pedem “Hello World” em PHP. A resposta mínima correta é:', [
            '<?php echo "Hello World";',
            'print Hello World',
            'console.log("Hello World")',
            'System.out.println("Hello World")',
        ], 0),
        q_mcq('PHP no modelo web clássico executa principalmente:', ['No GPU do cliente', 'No servidor, gerando resposta', 'Só no DNS', 'No cabo de rede'], 1),
        q_input('Construção mais usada para imprimir texto na saída:', 'echo'),
        q_mcq('Misturar HTML e PHP no mesmo arquivo é:', ['Proibido', 'Comum em scripts introdutórios (com cuidado)', 'Só possível com React', 'Exclusivo do Python'], 1),
        q_mcq('Erro de sintaxe sem `;` tipicamente gera:', ['200 OK', 'Parse error', 'CSS inválido', 'DNS failure'], 1),
        q_mcq('Analogia: JS console.log ≈ PHP:', ['class', 'echo/print', 'namespace', 'trait'], 1),
        q_mcq('Um bom hábito inicial é:', ['Commitar senhas', 'Testar o script no ambiente configurado', 'Desligar logs para sempre', 'Usar eval em tudo'], 1),
        q_match('Associe.', [['.php', 'Extensão do script'], ['Servidor web', 'Recebe a requisição'], ['Interpretador PHP', 'Executa o código'], ['Cliente', 'Vê o resultado']]),
        q_mcq('Diferença prática entre <?php e <?= :', ['Nenhuma', '<?= é short echo (quando habilitada)', '<?= define classe', '<?php só roda em Windows'], 1),
        q_mcq('Objetivo do módulo 1:', ['ORM avançado', 'Configurar ambiente e produzir primeira saída', 'Kubernetes', 'WebAssembly'], 1),
        q_mcq('php -v no terminal serve para:', ['Apagar o projeto', 'Verificar a versão do PHP instalada', 'Compilar C++', 'Gerar certificado SSL'], 1),
        q_mcq('DocumentRoot / htdocs / www é conceito de:', ['Banco NoSQL', 'Pasta pública do servidor web', 'Firewall', 'Composer'], 1),
        q_mcq('Se o navegador baixa o .php em vez de executar, o problema mais comum é:', ['CSS', 'PHP não está sendo interpretado pelo servidor', 'GPU', 'DNSSEC'], 1),
        q_input('Extensão de arquivo do script PHP (com ponto):', '.php'),
        q_mcq('Comentário de bloco em PHP usa:', ['<!-- -->', '/* ... */', '** ... **', '{{-- --}}'], 1),
        q_mcq('Em um seletivo, pedem saída exatamente "OK\\n". Isso testa:', ['Design', 'Atenção a contratos de saída', 'Cor da IDE', 'Velocidade do mouse'], 1),
        q_mcq('Laragon/XAMPP/Docker local existem para:', ['Substituir Git', 'Facilitar ambiente de desenvolvimento', 'Gerar NF-e', 'Bloquear portas'], 1),
        q_mcq('Variável super global típica de requisição web (conceito futuro):', ['$_GET / $_POST', '$window', 'process.env', 'os.environ'], 0),
        q_mcq('Boa prática de segurança inicial:', ['Expor .env publicamente', 'Não versionar segredos', 'Desativar HTTPS para sempre', 'Usar senha 123'], 1),
        q_mcq('Primeiro passo ao “não abre a página”:', ['Formatar HD', 'Checar serviço do servidor e logs/erro', 'Comprar novo monitor', 'Trocar de linguagem'], 1),
    ],
    "Prova objetiva do módulo 1.\nNão saia da página até concluir. Tempo: 30 minutos. 1 tentativa. Nota mínima: 70%.",
    'Prova do módulo 1 concluída. Sucesso!'
);
// prova should have 20 questions - mod1Quiz has 10, I merged adding 10 more = 20. Good.

$mod2Quiz = quizModule('Quiz · Variáveis, tipos e operadores', [
    q_mcq('Variáveis em PHP começam com:', ['#', '$', '@', '&'], 1),
    q_mcq('Tipo de 42 é tipicamente:', ['string', 'int', 'array', 'object'], 1),
    q_mcq('Operador de concatenação de strings em PHP:', ['+', '.', '&', '||'], 1),
    q_mcq('Em JS, + pode somar ou concatenar. Em PHP, concatenação usa:', ['. (ponto)', '+ somente', '++', '=>'], 0),
    q_input('Operador aritmético de módulo (resto) em PHP:', '%'),
    q_mcq('$a = "10"; $b = 2; $a * $b em PHP tipicamente:', ['"102"', '20', 'erro fatal sempre', 'null'], 1),
    q_mcq('=== compara:', ['Só valor', 'Valor e tipo', 'Só referência', 'Hash'], 1),
    q_match('Associe.', [['$', 'Prefixo de variável'], ['.', 'Concatenação'], ['+', 'Soma numérica'], ['===', 'Igualdade estrita']]),
    q_mcq('null coalescing ?? serve para:', ['Loop infinito', 'Fallback quando null', 'Importar classe', 'Criar thread'], 1),
    q_mcq('Booleano falso em PHP NÃO é tipicamente:', ['false', '0', '"0"', '"false" como string não-vazia (é true)'], 3),
], 'Quiz ok! Tipos e operadores afinados.');

$mod2Prova = provaModule(
    'Prova · Módulo 2 — Variáveis, tipos e operadores',
    [
        q_mcq('Qual declaração é válida?', ['int x = 1;', '$x = 1;', 'var $x = 1;', 'let x = 1;'], 1),
        q_mcq('gettype(3.14) retorna:', ['float', 'double', 'real', 'number'], 1),
        q_mcq('"3" + 2 em PHP (com coerção) resulta em:', ['"32"', '5', 'erro', 'null'], 1),
        q_mcq('"3" . 2 resulta em:', ['5', '"32"', '6', 'erro'], 1),
        q_input('Símbolo de concatenação em PHP:', '.'),
        q_mcq('Analogia correta JS vs PHP:', ['JS === ≈ PHP ==', 'JS === ≈ PHP === (ambos estritos)', 'JS + string ≈ PHP + string', 'JS let ≈ PHP def'], 1),
        q_mcq('$x ??= 10; atribui 10 se:', ['$x já é 10', '$x é null ou não definido (comportamento do ??/=)', '$x é string', 'Sempre'], 1),
        q_match('Associe operadores.', [['*', 'Multiplicação'], ['%', 'Resto'], ['.', 'Concatenação'], ['===', 'Idêntico']]),
        q_mcq('(int) "15a" resulta em:', ['0', '15', 'erro', 'null'], 1),
        q_mcq('is_numeric("15.2") retorna:', ['false', 'true', 'null', '15.2'], 1),
        q_mcq('Precedência: 2 + 3 * 4 =', ['20', '14', '24', '9'], 1),
        q_mcq('Variável não definida ao ler (sem @) gera:', ['Warning/Notice conforme versão/config', 'Sempre Exception checked', 'Nada', 'Reboot'], 0),
        q_input('Operador de igualdade estrita (3 caracteres):', '==='),
        q_mcq('$a = 5; $a += 2; valor final:', ['2', '5', '7', '52'], 2),
        q_mcq('Strings com aspas simples NÃO interpolam variáveis. Correto?', ['Não', 'Sim', 'Só no Windows', 'Só no PHP 8+'], 1),
        q_mcq('"Olá $nome" com $nome="Ana" (aspas duplas) produz:', ['Olá $nome', 'Olá Ana', 'erro', 'null'], 1),
        q_mcq('Cast para bool de "0":', ['true', 'false', 'null', 'erro'], 1),
        q_mcq('Melhor prática de nomes:', ['$1x', '$totalPedidos', '$Total-Pedidos', 'total pedidos'], 1),
        q_mcq('Objetivo do módulo 2:', ['PDO', 'Dominar variáveis, tipos e operadores', 'CSS Grid', 'Docker'], 1),
        q_mcq('Em seletivo, pedem evitar == frouxo quando tipos importam. Prefira:', ['==', '===', '!=', '<>'], 1),
    ],
    "Prova do módulo 2. Não saia da página. 30 minutos. 1 tentativa. Mínimo 70%.",
    'Prova do módulo 2 finalizada. Sucesso!'
);

$mod3Quiz = quizModule('Quiz · Condicionais e loops', [
    q_mcq('Estrutura condicional principal em PHP:', ['when', 'if / elseif / else', 'caseonly', 'switch-only'], 1),
    q_mcq('for ($i=0; $i<3; $i++) executa o corpo:', ['2 vezes', '3 vezes', '1 vez', 'infinito'], 1),
    q_mcq('while (false) { ... } executa:', ['1 vez', '0 vezes', '2 vezes', 'erro'], 1),
    q_mcq('Em JS: for (const x of arr). Em PHP o mais próximo é:', ['foreach ($arr as $x)', 'for-of ($arr)', 'each.in', 'loopof'], 0),
    q_input('Palavra-chave que interrompe o loop atual:', 'break'),
    q_mcq('continue em um loop:', ['Encerra o script', 'Pula para a próxima iteração', 'Cria thread', 'Faz commit'], 1),
    q_match('Associe.', [['if', 'Condição'], ['for', 'Contador definido'], ['while', 'Repete enquanto verdadeiro'], ['foreach', 'Percorre array']]),
    q_mcq('switch compara com:', ['Somente objetos', 'case valores (igualdade frouxa/estrita conforme versão/uso)', 'CSS', 'SQL'], 1),
    q_mcq('Operador ternário: $a ? $b : $c', ['Loop', 'Expressão condicional', 'Include', 'Namespace'], 1),
    q_mcq('Evitar loop infinito implica:', ['Sempre while(true)', 'Garantir progresso da condição', 'Usar só goto', 'Desligar PHP'], 1),
], 'Quiz concluído! Condicionais no sangue.');

$mod3Prova = provaModule(
    'Prova · Módulo 3 — Condicionais e loops',
    [
        q_mcq('if (0) { echo "a"; } else { echo "b"; } imprime:', ['a', 'b', 'ab', 'erro'], 1),
        q_mcq('for ($i=1; $i<=3; $i++) echo $i; saída:', ['123', '012', '321', '1 2 3'], 0),
        q_mcq('foreach ([1,2] as $v) echo $v; saída:', ['12', '21', '1,2', 'erro'], 0),
        q_input('Palavra que pula o restante do corpo e vai à próxima iteração:', 'continue'),
        q_mcq('Analogia JS while ≈ PHP while:', ['Falsidade total', 'Mesma ideia de repetir sob condição', 'Só existe em JS', 'Só em PHP 8'], 1),
        q_mcq('match (PHP 8+) difere de switch principalmente por:', ['Ser mais estrito e retornar valor', 'Não existir', 'Ser CSS', 'Ser async'], 0),
        q_match('Associe risco → mitigação.', [['while(true)', 'Condição de saída clara'], ['índice fora', 'isset/validação'], ['switch longo', 'map/lookup'], ['aninhamento excessivo', 'early return']]),
        q_mcq('else if escrito em PHP idiomático:', ['elif', 'elseif ou else if', 'elsewhen', 'otherwise'], 1),
        q_mcq('do { } while ($x); garante:', ['0 execuções', 'Pelo menos 1 execução do bloco', 'Paralelismo', 'Transação DB'], 1),
        q_mcq('break 2; (quando suportado no contexto) pode:', ['Quebrar dois níveis de estruturas', 'Apagar arquivos', 'Criar array', 'Reiniciar Apache'], 0),
        q_mcq('Melhor estrutura para varrer array associativo:', ['for infinito', 'foreach', 'goto', 'eval'], 1),
        q_mcq('if ($a = 1) vs if ($a == 1): risco do primeiro é:', ['Atribuição acidental na condição', 'Nenhum', 'Só sintaxe inválida', 'Criptografia'], 0),
        q_input('Palavra-chave do laço baseado em condição no início:', 'while'),
        q_mcq('Saída de: $i=0; while($i<2){ echo $i; $i++; }', ['01', '12', '012', 'erro'], 0),
        q_mcq('Em seletivo, pedem FizzBuzz. Exige principalmente:', ['HTML puro', 'Condicionais + loop + operadores', 'GPU', 'XML'], 1),
        q_mcq('Operador lógico E em PHP:', ['&& ou and', 'AND_ONLY', 'et', '&amp;&amp; HTML'], 0),
        q_mcq('Operador OU:', ['|| ou or', 'ou', 'ORR', '//'], 0),
        q_mcq('Negação lógica:', ['!', 'nott', '~~', 'neg'], 0),
        q_mcq('Objetivo do módulo 3:', ['PDO', 'Controlar fluxo com if/loops', 'Kubernetes', 'Photoshop'], 1),
        q_mcq('Código legível em condições complexas recomenda:', ['Uma linha de 300 chars', 'Extrair booleanos nomeados / early return', 'Aninhar 10 ifs', 'Usar só goto'], 1),
    ],
    "Prova módulo 3. Permaneça na página. 30 min. 1 tentativa. Mínimo 70%.",
    'Prova do módulo 3 ok. Sucesso!'
);

$mod4Quiz = quizModule('Quiz · Arrays e strings', [
    q_mcq('Literal de array curto em PHP:', ['(1,2)', '[1, 2]', '{1,2}', '<1,2>'], 1),
    q_mcq('Chave associativa exemplo:', ['$a[0]', '$a["nome"] = "Ana"', '$a->nome', '$a::nome'], 1),
    q_mcq('implode(",", [1,2]) retorna:', ['"1,2"', '[1,2]', '3', 'erro'], 0),
    q_mcq('explode(",", "a,b") retorna:', ['"a,b"', '["a","b"]', '2', 'null'], 1),
    q_input('Função que junta elementos de array em string:', 'implode'),
    q_mcq('JS arr.join(",") ≈ PHP:', ['implode(",", $arr)', 'explode', 'concat_ws JS only', 'merge'], 0),
    q_mcq('isset($a["x"]) verifica:', ['Se a chave existe e não é null', 'Só tipo', 'Permissão Linux', 'DNS'], 0),
    q_match('Associe.', [['[]', 'Array curto'], ['implode', 'Array → string'], ['explode', 'String → array'], ['count', 'Cardinalidade']]),
    q_mcq('substr("abcdef", 0, 3) ≈', ['"abc"', '"def"', '"abcdef"', 'erro'], 0),
    q_mcq('Arrays em PHP podem misturar tipos de valores?', ['Não', 'Sim', 'Só no PHP 4', 'Só com JS'], 1),
], 'Quiz de arrays/strings fechado com chave de ouro!');

$mod4Prova = provaModule(
    'Prova · Módulo 4 — Arrays e strings',
    [
        q_mcq('Criar array associativo de produto:', ['$p = ["id"=>1,"nome"=>"Teclado"]', '$p = (id=1)', '$p = new String', '$p = {id:1}'], 0),
        q_mcq('count(["a"=>1,"b"=>2]) =', ['1', '2', '0', '3'], 1),
        q_mcq('array_key_exists("a", $x) vs isset: diferença clássica envolve:', ['null na chave', 'CSS', 'SQL injection', 'UTF-32'], 0),
        q_input('Função que parte string em array por delimitador:', 'explode'),
        q_mcq('JS "a,b".split(",") ≈ PHP:', ['explode(",", "a,b")', 'implode', 'split()', 'tokenize_js'], 0),
        q_mcq('str_replace("a", "x", "banana") contém:', ['bxnxnx', 'bxnana', 'banana', 'erro'], 0),
        q_match('Associe problema → função.', [['juntar CSV', 'implode'], ['separar CSV', 'explode'], ['trocar texto', 'str_replace'], ['tamanho string', 'strlen']]),
        q_mcq('in_array(2, [1,2,3], true) com strict:', ['Pode falhar com "2" string', 'Sempre true para "2"', 'Erro de sintaxe', 'Cria índice'], 0),
        q_mcq('array_push($a, 9) :', ['Remove 9', 'Insere 9 no final', 'Ordena', 'Inverte'], 1),
        q_mcq('Melhor estrutura para lista ordenada de IDs:', ['array indexado', 'somente string gigante', 'float', 'resource'], 0),
        q_mcq('Interpolação "Olá {$user[\'nome\']}" exige:', ['Chaves/escapes adequados na string', 'JSON', 'XML', 'GPU'], 0),
        q_mcq('mb_strlen vs strlen: tema central é:', ['Unicode/multibyte', 'SQL', 'HTTP/2', 'CORS'], 0),
        q_input('Função count de elementos:', 'count'),
        q_mcq('Remover elemento e reindexar numérico: típico uso de:', ['unset + array_values', 'echo', 'include', 'header'], 0),
        q_mcq('Em seletivo, pedem frequências de palavras: combina:', ['explode + loops/contagem', 'só CSS', 'FTP', 'SMTP'], 0),
        q_mcq('$a[] = 3; faz:', ['Push no próximo índice', 'Declara classe', 'Abre socket', 'Nada'], 0),
        q_mcq('sort($a) ordena:', ['Por referência o array (retorna bool)', 'Sempre nova cópia imutável', 'Só strings', 'Arquivos'], 0),
        q_mcq('Analogia correta:', ['PHP implode ≈ JS join', 'PHP explode ≈ JS concat', 'PHP count ≈ JS class', 'PHP echo ≈ JS import'], 0),
        q_mcq('Objetivo do módulo 4:', ['Auth JWT', 'Manipular arrays e strings com segurança', 'Terraform', 'Blender'], 1),
        q_mcq('Evitar notice ao ler chave ausente:', ['@$a["x"] em tudo sem critério', 'Null coalescing / isset com intenção clara', 'Ignorar logs', 'Reiniciar PC'], 1),
    ],
    "Prova módulo 4. Não abandone a janela. 30 minutos. 1 tentativa. Mínimo 70%.",
    'Prova do módulo 4 concluída. Sucesso!'
);

DB::beginTransaction();
try {
    // Atualiza quizzes Arena
    DB::table('conteudos')->where('id', 127)->update([
        'url' => json_encode($quizArena1, JSON_UNESCAPED_UNICODE),
        'descricao' => '10 questões · até 3 tentativas · vale a maior nota · mínimo 70%.',
        'updated_at' => now(),
    ]);
    DB::table('conteudos')->where('id', 177)->update([
        'url' => json_encode($quizArena2, JSON_UNESCAPED_UNICODE),
        'descricao' => '10 questões · até 3 tentativas · vale a maior nota · mínimo 70%.',
        'updated_at' => now(),
    ]);

    // Prova final no módulo Arena 2 (mesma trilha 05), após o quiz
    $provaId = upsertConteudo(
        59,
        'Prova · Módulo 5 — Arrays e Arenas',
        'prova',
        json_encode($provaMod5, JSON_UNESCAPED_UNICODE),
        '20 questões · 30 minutos · 1 tentativa · mínimo 70%. Não saia da página.',
        7
    );

    $map = [
        27 => [$mod1Quiz, $mod1Prova, 'Ambiente e primeiro script'],
        28 => [$mod2Quiz, $mod2Prova, 'Variáveis, tipos e operadores'],
        29 => [$mod3Quiz, $mod3Prova, 'Condicionais e loops'],
        30 => [$mod4Quiz, $mod4Prova, 'Arrays e strings'],
    ];

    foreach ($map as $aulaId => [$quiz, $prova, $tema]) {
        // ordem: manter existentes 1-4, quiz=5, prova=6
        $qid = upsertConteudo(
            $aulaId,
            'Quiz · '.$tema,
            'quiz',
            json_encode($quiz, JSON_UNESCAPED_UNICODE),
            '10 questões · até 3 tentativas · maior nota · mínimo 70%.',
            5
        );
        $pid = upsertConteudo(
            $aulaId,
            'Prova · '.$tema,
            'prova',
            json_encode($prova, JSON_UNESCAPED_UNICODE),
            'Prova do módulo · 30 minutos · 1 tentativa · mínimo 70%.',
            6
        );
        echo "aula $aulaId quiz=$qid prova=$pid\n";
    }

    echo "prova_mod5=$provaId\n";
    echo "quiz127_questions=".count($quizArena1['questions'])."\n";
    echo "quiz177_questions=".count($quizArena2['questions'])."\n";
    echo "prova5_questions=".count($provaMod5['questions'])."\n";

    // Textos ricos (php.net / freeCodeCamp handbook) nos módulos 1–4
    $tips = [
        79 => "Missão 1 — Ambiente e primeiro script\n\n"
            ."PHP roda no servidor e devolve HTML/texto ao navegador (manual php.net — Your first PHP-enabled page).\n"
            ."Checklist:\n"
            ."[ ] Confirme php -v no terminal\n"
            ."[ ] Crie hello.php com <?php echo \"Hello World\";\n"
            ."[ ] Abra via servidor local (Laragon/XAMPP/php -S)\n"
            ."[ ] Compare com console.log do JS: no PHP o eco vai para a resposta HTTP\n\n"
            ."Referência: https://www.php.net/manual/pt_BR/tutorial.firstpage.php",
        83 => "Missão 2 — Variáveis, tipos e operadores\n\n"
            ."Variáveis em PHP começam com \$ (freeCodeCamp PHP Handbook / manual language.types).\n"
            ."Foque em: int, float, string, bool, null; == vs ===; += e concatenação.\n"
            ."Checklist:\n"
            ."[ ] Declare \$nome e interpole em aspas duplas\n"
            ."[ ] Teste (string)\"0\" em contexto booleano\n"
            ."[ ] Prefira === quando o tipo importa (processo seletivo)\n\n"
            ."Referência: https://www.php.net/manual/pt_BR/language.types.intro.php",
        87 => "Missão 3 — Condicionais e loops\n\n"
            ."if / elseif / else controlam o fluxo; for, while e foreach repetem (php.net control-structures).\n"
            ."Analogia JS: for...of ≈ foreach (\$arr as \$x).\n"
            ."Checklist:\n"
            ."[ ] Escreva um if com early return mental\n"
            ."[ ] Faça um for 1..N e um foreach em array\n"
            ."[ ] Use break e continue com intenção clara\n\n"
            ."Referência: https://www.php.net/manual/pt_BR/language.control-structures.php",
        91 => "Missão 4 — Arrays e strings\n\n"
            ."Arrays curtos [1,2] e associativos [\"nome\"=>\"Ana\"]; implode/explode; count; substr (handbook + manual).\n"
            ."Checklist:\n"
            ."[ ] Monte um array de produtos e percorra com foreach\n"
            ."[ ] Junte com implode e separe com explode\n"
            ."[ ] Proteja chave ausente com isset / ??\n\n"
            ."Referência: https://www.php.net/manual/pt_BR/language.types.array.php",
        123 => "Dicas · Arena 1 — Arrays na prática\n\n"
            ."Treine foreach, count e manipulação de texto como no manual de arrays.\n"
            ."Na Arena, o juiz compara a saída com o contrato da etapa — foque no echo certo.\n"
            ."Depois da Arena: Quiz (10q, 3 tentativas) e siga para Arena 2 no mesmo módulo.",
        173 => "Dicas · Arena 2 — Caixa Litoral\n\n"
            ."Missão de negócio: count, array_sum, média, strlen/strval e reduce.\n"
            ."Pense em relatório de caixa: totais, quantidade de lojas e textos padronizados.\n"
            ."Feche com o Quiz da Arena 2 e a Prova do módulo 5 (20q / 30 min / 1 tentativa).",
    ];
    foreach ($tips as $cid => $body) {
        DB::table('conteudos')->where('id', $cid)->update([
            'url' => $body,
            'descricao' => 'Resumo enriquecido do módulo (material de referência oficial).',
            'updated_at' => now(),
        ]);
        echo "tip_$cid updated\n";
    }

    $flipMods = [
        27 => ['Cards · Ambiente PHP', [
            ['front' => '<?php', 'back' => 'Abre um bloco de código PHP no arquivo.'],
            ['front' => 'echo', 'back' => 'Envia texto para a saída (HTML/CLI).'],
            ['front' => '.php', 'back' => 'Extensão padrão do script PHP.'],
            ['front' => 'Servidor local', 'back' => 'Laragon, XAMPP ou php -S para testar.'],
        ]],
        28 => ['Cards · Variáveis e tipos', [
            ['front' => '$nome', 'back' => 'Variável PHP: sempre começa com $.'],
            ['front' => '===', 'back' => 'Comparação estrita (valor e tipo).'],
            ['front' => 'bool', 'back' => 'true ou false — decisões no if.'],
            ['front' => '.=', 'back' => 'Concatena e atribui em strings.'],
        ]],
        29 => ['Cards · Condicionais e loops', [
            ['front' => 'if', 'back' => 'Executa bloco se a condição for verdadeira.'],
            ['front' => 'foreach', 'back' => 'Percorre cada item de um array.'],
            ['front' => 'break', 'back' => 'Interrompe o loop atual.'],
            ['front' => 'continue', 'back' => 'Pula para a próxima iteração.'],
        ]],
        30 => ['Cards · Arrays e strings', [
            ['front' => '[]', 'back' => 'Sintaxe curta de array em PHP.'],
            ['front' => 'count', 'back' => 'Quantidade de elementos do array.'],
            ['front' => 'implode', 'back' => 'Array → string com separador.'],
            ['front' => 'explode', 'back' => 'String → array por delimitador.'],
        ]],
        38 => ['Cards flip · Arena 1', [
            ['front' => 'foreach', 'back' => 'Varre arrays da missão 1.'],
            ['front' => 'count', 'back' => 'Conta itens do inventário/array.'],
            ['front' => 'strtoupper', 'back' => 'Padroniza texto em maiúsculas.'],
            ['front' => 'Contrato', 'back' => 'Saída deve bater com o juiz da Arena.'],
        ]],
        59 => ['Cards flip · Arena 2', [
            ['front' => 'array_sum', 'back' => 'Soma vendas do caixa.'],
            ['front' => 'strlen', 'back' => 'Tamanho da observação/texto.'],
            ['front' => 'strval', 'back' => 'Converte valor para string.'],
            ['front' => 'array_reduce', 'back' => 'Acumula resultado percorrendo o array.'],
        ]],
    ];

    foreach ($flipMods as $aulaId => [$titulo, $cards]) {
        $payload = json_encode([
            'kind' => 'flip_deck',
            'titulo' => $titulo,
            'hint' => 'Clique no card para virar.',
            'cards' => $cards,
        ], JSON_UNESCAPED_UNICODE);
        $fid = upsertConteudo(
            $aulaId,
            $titulo,
            'texto',
            $payload,
            'Cards com flip — revise conceitos antes do quiz.',
            3
        );
        echo "flip aula $aulaId id=$fid\n";
    }

    DB::commit();
    echo "OK enrich\n";
} catch (Throwable $e) {
    DB::rollBack();
    fwrite(STDERR, $e->getMessage()."\n");
    exit(1);
}
