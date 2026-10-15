<?php
/**
 * v2: cards 10c + kind, hide card visual, quizzes sem pergunta duplicada,
 * provas 30q, flags anti-cola.
 */
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

function q_mcq(string $q, array $options, int $answer): array
{
    return ['q' => $q, 'type' => 'mcq', 'options' => $options, 'answer' => $answer];
}
function q_input(string $q, string $answerText): array
{
    return ['q' => $q, 'type' => 'input', 'answer_text' => $answerText];
}
function q_match(string $q, array $pairs): array
{
    return ['q' => $q, 'type' => 'match', 'pairs' => $pairs];
}
function flip(string $front, string $back, string $kind): array
{
    return ['front' => $front, 'back' => $back, 'kind' => $kind];
}
function quizPack(string $titulo, array $questions, string $success): array
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
function provaPack(string $titulo, array $questions, string $intro, string $success): array
{
    return [
        'titulo' => $titulo,
        'pass_score' => 70,
        'max_attempts' => 1,
        'time_limit_sec' => 1800,
        'mode' => 'prova',
        'leave_warning' => true,
        'anti_cheat' => true,
        'camera_sim' => true,
        'intro' => $intro,
        'success_message' => $success,
        'questions' => $questions,
    ];
}

$MEMO = 'Também serve como memorização: tente acertar na sua memória ou pensamento o que representa este comando em PHP antes de virar. Ao virar, veja se você acertou ou errou, e mentalize onde precisa melhorar.';

$decks = [
    // Módulo 1
    220 => ['Cards · Ambiente PHP', [
        flip('<?php', 'Abre um bloco de código PHP no arquivo.', 'Tag / delimitador'),
        flip('echo', 'Envia texto para a saída (HTML ou CLI).', 'Construto de linguagem'),
        flip('.php', 'Extensão padrão do script PHP.', 'Convenção de arquivo'),
        flip('php -S', 'Sobe servidor embutido para testes locais.', 'Comando CLI'),
        flip(';', 'Termina uma instrução (statement).', 'Sintaxe'),
        flip('//', 'Comentário de uma linha.', 'Sintaxe de comentário'),
        flip('print', 'Também imprime; echo é mais comum.', 'Construto de linguagem'),
        flip('phpinfo()', 'Mostra configuração do ambiente PHP.', 'Função built-in'),
        flip('<?=', 'Short echo tag (quando habilitada).', 'Tag / delimitador'),
        flip('Laragon/XAMPP', 'Ambiente local com Apache + PHP + MySQL.', 'Ferramenta de ambiente'),
    ]],
    // Módulo 2
    221 => ['Cards · Variáveis e tipos', [
        flip('$nome', 'Variável: sempre começa com $.', 'Variável'),
        flip('===', 'Igualdade estrita (valor e tipo).', 'Operador'),
        flip('bool', 'true ou false — base de decisões.', 'Tipo de dado'),
        flip('.=', 'Concatena e atribui em string.', 'Operador de atribuição'),
        flip('int', 'Número inteiro.', 'Tipo de dado'),
        flip('float', 'Número com casas decimais.', 'Tipo de dado'),
        flip('null', 'Ausência de valor.', 'Tipo de dado'),
        flip('??', 'Null coalescing: usa fallback se null.', 'Operador'),
        flip('(string)', 'Cast explícito para string.', 'Cast / conversão'),
        flip('const', 'Define constante (imutável no escopo).', 'Palavra-chave'),
    ]],
    // Módulo 3
    222 => ['Cards · Condicionais e loops', [
        flip('if', 'Executa bloco se a condição for verdadeira.', 'Estrutura de controle'),
        flip('foreach', 'Percorre cada item de um array.', 'Estrutura de loop'),
        flip('break', 'Interrompe o loop atual.', 'Palavra-chave de controle'),
        flip('continue', 'Pula para a próxima iteração.', 'Palavra-chave de controle'),
        flip('while', 'Repete enquanto a condição for true.', 'Estrutura de loop'),
        flip('for', 'Loop com contador inicial/condição/passo.', 'Estrutura de loop'),
        flip('elseif', 'Nova condição após um if falso.', 'Estrutura de controle'),
        flip('switch', 'Escolhe ramo por valor comparado.', 'Estrutura de controle'),
        flip('match', 'Expressão PHP 8+ mais estrita que switch.', 'Expressão / construto'),
        flip('?:', 'Operador ternário (condição ? a : b).', 'Operador'),
    ]],
    // Módulo 4 — arrays/strings (base teórica)
    223 => ['Cards · Arrays e strings', [
        flip('[]', 'Sintaxe curta de array.', 'Sintaxe / literal'),
        flip('count', 'Quantidade de elementos do array.', 'Função built-in'),
        flip('implode', 'Junta array em string com separador.', 'Função built-in'),
        flip('explode', 'Parte string em array por delimitador.', 'Função built-in'),
        flip('isset', 'Verifica se variável/chave existe e não é null.', 'Construto de linguagem'),
        flip('array_key_exists', 'Verifica se a chave existe (mesmo se null).', 'Função built-in'),
        flip('substr', 'Extrai pedaço de uma string.', 'Função built-in'),
        flip('strlen', 'Comprimento da string (bytes).', 'Função built-in'),
        flip('in_array', 'Testa se um valor está no array.', 'Função built-in'),
        flip('array_push', 'Insere elemento(s) no final do array.', 'Função built-in'),
    ]],
    // Arena 1 — prática/missão (SEM repetir o núcleo do módulo 4)
    224 => ['Cards flip · Arena 1', [
        flip('foreach ($a as $v)', 'Varre valores; forma idiomática na missão.', 'Estrutura de loop'),
        flip('strtoupper', 'Converte texto para MAIÚSCULAS.', 'Função built-in'),
        flip('PHP_EOL', 'Quebra de linha portável (Windows/Linux).', 'Constante predefinida'),
        flip('Contrato', 'Saída deve bater exatamente com o juiz da Arena.', 'Conceito da Arena'),
        flip('array_map', 'Aplica uma função a cada elemento e devolve novo array.', 'Função built-in'),
        flip('unset', 'Remove variável ou chave do array.', 'Construto de linguagem'),
        flip('&$ref', 'Referência: altera o original, não uma cópia.', 'Operador'),
        flip('print_r', 'Depura estrutura de array de forma legível.', 'Função built-in'),
        flip('array_values', 'Reindexa array numérico após remoções.', 'Função built-in'),
        flip('trim', 'Remove espaços nas pontas da string.', 'Função built-in'),
    ]],
    // Arena 2 — caixa / agregações
    225 => ['Cards flip · Arena 2', [
        flip('array_sum', 'Soma valores numéricos do array (totais do caixa).', 'Função built-in'),
        flip('strlen', 'Valida tamanho de observação/texto no relatório.', 'Função built-in'),
        flip('strval', 'Converte valor para string.', 'Função built-in'),
        flip('array_reduce', 'Acumula um resultado percorrendo o array com callback.', 'Função built-in'),
        flip('média', 'array_sum / count (com count > 0).', 'Padrão / fórmula'),
        flip('str_replace', 'Substitui trechos de texto.', 'Função built-in'),
        flip('number_format', 'Formata número para exibição (ex.: moeda).', 'Função built-in'),
        flip('is_numeric', 'Verifica se o valor é numérico antes de somar.', 'Função built-in'),
        flip('array_filter', 'Filtra elementos com uma condição.', 'Função built-in'),
        flip('sprintf', 'Monta string formatada para o relatório.', 'Função built-in'),
    ]],
];

// —— Quizzes (perguntas únicas entre si; comandos podem repetir) ——
$quizMod1 = quizPack('Quiz · Ambiente e primeiro script', [
    q_mcq('Qual extensão o servidor costuma associar ao interpretador PHP?', ['.html', '.php', '.css', '.md'], 1),
    q_mcq('A forma recomendada de abrir um bloco PHP é:', ['<%', '<?php', '<script php>', '{{php}}'], 1),
    q_mcq('O que echo "Codar"; faz?', ['Cria classe', 'Envia texto à saída', 'Abre socket', 'Compila C'], 1),
    q_mcq('Em JS usamos console.log. Em PHP, o análogo comum é:', ['System.out', 'echo/print', 'fmt.Println', 'puts!'], 1),
    q_input('Digite a tag de abertura padrão (exatamente):', '<?php'),
    q_mcq('Ambiente local típico para iniciante inclui:', ['Só Excel', 'Servidor web + PHP', 'Só GPU', 'Somente DNS'], 1),
    q_match('Associe ferramenta ao papel no setup.', [['PHP', 'Linguagem no servidor'], ['echo', 'Saída de texto'], ['Laragon', 'Stack local'], ['navegador', 'Consome HTTP']]),
    q_mcq('Comentário de uma linha válido:', ['# ou //', '<!-- --> apenas', '** **', 'REM'], 0),
    q_mcq('Por que usamos ; no fim de muitas instruções?', ['Enfeita', 'Termina o statement', 'Inicia função', 'Importa pacote'], 1),
    q_mcq('index.php em uma pasta pública costuma ser:', ['Ignorado', 'Documento padrão do diretório', 'Binário EFI', 'Certificado SSL'], 1),
], 'Ambiente consolidado. Bom quiz!');

$quizMod2 = quizPack('Quiz · Variáveis, tipos e operadores', [
    q_mcq('Toda variável PHP deve começar com:', ['#', '$', '@', '&'], 1),
    q_mcq('Qual comparação é estrita (tipo + valor)?', ['==', '===', '=', '<>'], 1),
    q_mcq('Tipo booleano correto:', ['yes/no', 'true/false', 'on/off', '1/"1" apenas'], 1),
    q_input('Operador de concatenação de strings (1 caractere):', '.'),
    q_mcq('$a = "10"; $b = 2; $a * $b tipicamente resulta:', ['"102"', '20', 'erro sempre', 'null'], 1),
    q_mcq('null coalescing ?? serve para:', ['Loop infinito', 'Fallback quando o valor é null', 'Abrir arquivo', 'Hash de senha'], 1),
    q_match('Associe tipo → exemplo.', [['int', '42'], ['float', '3.14'], ['string', '"ola"'], ['bool', 'false']]),
    q_mcq('Cast (int)"3.9" resulta tipicamente:', ['3.9', '3', 'erro', '"3.9"'], 1),
    q_mcq('Melhor nome de variável:', ['$1total', '$totalPedidos', '$Total-Pedidos', 'total pedidos'], 1),
    q_mcq('Aspas simples interpolam $var?', ['Sim sempre', 'Não', 'Só no PHP 8', 'Só no Windows'], 1),
], 'Tipos e operadores no sangue!');

$quizMod3 = quizPack('Quiz · Condicionais e loops', [
    q_mcq('Estrutura condicional principal:', ['when', 'if / elseif / else', 'caseonly', 'goto-only'], 1),
    q_mcq('for ($i=0; $i<3; $i++) executa o corpo quantas vezes?', ['2', '3', '1', 'infinito'], 1),
    q_mcq('while (false) { ... } executa:', ['1 vez', '0 vezes', '2 vezes', 'erro de parse'], 1),
    q_mcq('Análogo PHP de for...of do JS:', ['foreach ($arr as $x)', 'for-of ($arr)', 'each.in', 'loopof'], 0),
    q_input('Palavra que interrompe o loop atual:', 'break'),
    q_mcq('continue faz o quê?', ['Encerra o script', 'Vai à próxima iteração', 'Cria thread', 'Faz commit'], 1),
    q_match('Associe estrutura de controle ao papel.', [['if', 'Decisão'], ['for', 'Contador'], ['while', 'Condição no início'], ['foreach', 'Percorre array']]),
    q_mcq('match (PHP 8+) destaca-se por:', ['Ser CSS', 'Ser mais estrito e retornar valor', 'Substituir MySQL', 'Ser async'], 1),
    q_mcq('Operador ternário é:', ['Loop', 'Expressão condicional', 'Include', 'Namespace'], 1),
    q_mcq('Evitar loop infinito exige:', ['while(true) sempre', 'Progresso na condição', 'Só goto', 'Desligar PHP'], 1),
], 'Fluxo de controle aprovado!');

$quizMod4 = quizPack('Quiz · Arrays e strings', [
    q_mcq('Literal curto de array:', ['(1,2)', '[1, 2]', '{1,2}', '<1,2>'], 1),
    q_mcq('Exemplo de chave associativa:', ['$a[0]', '$a["nome"] = "Ana"', '$a->nome', '$a::nome'], 1),
    q_mcq('implode(",", [1,2]) retorna tipicamente:', ['"1,2"', '[1,2]', '3', 'erro'], 0),
    q_mcq('explode(",", "a,b") retorna:', ['"a,b"', '["a","b"]', '2', 'null'], 1),
    q_input('Função que junta elementos de array em string:', 'implode'),
    q_mcq('JS arr.join(",") ≈ PHP:', ['implode(",", $arr)', 'explode', 'concat_ws JS', 'merge'], 0),
    q_mcq('isset($a["x"]) verifica principalmente:', ['Se a chave existe e não é null', 'Permissão Linux', 'DNS', 'CORS'], 0),
    q_match('Associe operação de array/string.', [['[]', 'Array curto'], ['implode', 'Array → string'], ['explode', 'String → array'], ['count', 'Cardinalidade']]),
    q_mcq('substr("abcdef", 0, 3) ≈', ['"abc"', '"def"', '"abcdef"', 'erro'], 0),
    q_mcq('Arrays PHP podem misturar tipos de valores?', ['Não', 'Sim', 'Só PHP 4', 'Só com JS'], 1),
], 'Arrays e strings fechados com chave!');

// Arena quizzes — ângulo prático/missão (perguntas distintas dos quizzes 1–4)
$quizA1 = quizPack('Quiz · Arena PHP atividade 1', [
    q_mcq('Na Arena, o juiz valida principalmente:', ['Cor da IDE', 'A saída frente ao contrato da etapa', 'Velocidade de digitação', 'Nome do arquivo .bak'], 1),
    q_mcq('Melhor forma de listar serviços de um array indexado:', ['foreach ($servicos as $s) { echo $s, PHP_EOL; }', 'for-in ($servicos)', 'servicos.forEach sem adaptar', 'while(true) echo $servicos'], 0),
    q_mcq('strtoupper("php") produz:', ['Php', 'PHP', 'php', 'erro de tipo'], 1),
    q_mcq('Para alterar só uma cópia do valor (sem referência):', ['$x = &$arr[0];', '$x = $arr[0];', 'unset($arr);', 'array_pop($arr);'], 1),
    q_input('Função que conta elementos de um array:', 'count'),
    q_mcq('array_map("strtoupper", $titulos) serve para:', ['Ordenar decrescente', 'Transformar cada título', 'Abrir PDO', 'Gerar SSL'], 1),
    q_match('Associe missão → ferramenta.', [['varrer lista', 'foreach'], ['padronizar texto', 'strtoupper'], ['reindexar', 'array_values'], ['remover chave', 'unset']]),
    q_mcq('PHP_EOL é útil porque:', ['Coloriza o terminal', 'Normaliza quebra de linha entre SO', 'Cria thread', 'Valida CSRF'], 1),
    q_mcq('Índices numéricos em PHP costumam começar em:', ['1 obrigatório', '0 (com chaves string também possíveis)', 'Sempre -1', 'Somente letras'], 1),
    q_mcq('print_r($arr) é mais adequado para:', ['Produção limpa sem debug', 'Inspecionar estrutura durante o desenvolvimento', 'Assinar JWT', 'Compilar C++'], 1),
], 'Bom quiz! Arrays na prática da Arena.');

$quizA2 = quizPack('Quiz · Arena 2 Caixa Litoral', [
    q_mcq('array_sum([10, 20, 5]) retorna:', ['35', '3', '10', '205'], 0),
    q_mcq('Média segura de array numérico:', ['array_sum($a) / count($a) com count > 0', 'avg($a)', 'mean($a)', 'array_average($a)'], 0),
    q_mcq('strlen("caixa") retorna:', ['4', '5', '6', '0'], 1),
    q_mcq('strval(12.5) tipicamente produz:', ['"12"', '"12.5"', '12', 'erro'], 1),
    q_input('Função PHP que soma valores numéricos de um array:', 'array_sum'),
    q_mcq('Antes de dividir para média, proteja com:', ['array_key_exists(0, $a) só', 'count($a) > 0', 'isset($a) apenas', 'is_string($a)'], 1),
    q_match('Associe caixa → função.', [['totalizar vendas', 'array_sum'], ['qtd. lojas', 'count'], ['tamanho observação', 'strlen'], ['acumular custom', 'array_reduce']]),
    q_mcq('array_reduce é útil quando:', ['Só precisa do 1º elemento', 'Acumula resultado com callback', 'Ordena CSS', 'Converte XML→JSON'], 1),
    q_mcq('number_format ajuda no relatório para:', ['Abrir porta 80', 'Exibir valores monetários legíveis', 'Criar trait', 'Gerar UUID'], 1),
    q_mcq('Saída adequada de relatório de caixa no terminal:', ['Só var_dump sem contexto', 'echo com totais e textos claros', 'die() em cada linha', 'HTML sem escape em CLI'], 1),
], 'Caixa Litoral no domínio!');

$introProva = "Prova objetiva do módulo.\n1 tentativa · 30 minutos · mínimo 70%.\nNão saia da página. Anti-fraude ativo (cópia, cola e troca de aba são monitoradas).\nIsto protege o seu aprendizado — como em processo seletivo real.";

function prova30(string $titulo, array $qs, string $success, string $intro): array
{
    if (count($qs) !== 30) {
        throw new RuntimeException($titulo.' precisa de 30 questões, tem '.count($qs));
    }

    return provaPack($titulo, $qs, $intro, $success);
}

$prova1 = prova30('Prova · Módulo 1 — Ambiente e primeiro script', [
    q_mcq('Hello World mínimo correto em PHP:', ['<?php echo "Hello World";', 'print Hello World', 'console.log("Hello World")', 'System.out.println(...)'], 0),
    q_mcq('PHP no modelo web clássico executa principalmente:', ['No GPU do cliente', 'No servidor, gerando resposta', 'Só no DNS', 'No cabo de rede'], 1),
    q_input('Construção mais usada para imprimir texto:', 'echo'),
    q_mcq('Misturar HTML e PHP no mesmo arquivo é:', ['Proibido', 'Comum em intro (com cuidado)', 'Só com React', 'Só Python'], 1),
    q_mcq('Faltar ; tipicamente gera:', ['200 OK', 'Parse error', 'CSS inválido', 'DNS failure'], 1),
    q_mcq('JS console.log ≈ PHP:', ['class', 'echo/print', 'namespace', 'trait'], 1),
    q_mcq('Bom hábito inicial:', ['Commitar senhas', 'Testar no ambiente configurado', 'Desligar logs', 'eval em tudo'], 1),
    q_match('Associe peça do ambiente à função.', [['.php', 'Extensão'], ['Servidor web', 'Recebe request'], ['Interpretador', 'Executa código'], ['Cliente', 'Vê resultado']]),
    q_mcq('<?= vs <?php na prática:', ['Iguais sempre', '<?= é short echo (se habilitada)', '<?= define classe', '<?php só Windows'], 1),
    q_mcq('Objetivo do módulo 1:', ['ORM avançado', 'Ambiente + primeira saída', 'Kubernetes', 'WebAssembly'], 1),
    q_mcq('php -v serve para:', ['Apagar projeto', 'Ver versão do PHP', 'Compilar C++', 'Gerar SSL'], 1),
    q_mcq('DocumentRoot/htdocs/www é:', ['NoSQL', 'Pasta pública do servidor', 'Firewall', 'Composer'], 1),
    q_mcq('Navegador baixa .php em vez de executar: causa comum:', ['CSS', 'PHP não interpretado pelo servidor', 'GPU', 'DNSSEC'], 1),
    q_input('Extensão do script (com ponto):', '.php'),
    q_mcq('Comentário de bloco:', ['<!-- -->', '/* ... */', '** ... **', '{{-- --}}'], 1),
    q_mcq('Contrato de saída "OK\\n" testa:', ['Design', 'Atenção ao texto exato', 'Cor da IDE', 'Mouse'], 1),
    q_mcq('Laragon/XAMPP/Docker local existem para:', ['Substituir Git', 'Facilitar ambiente', 'Gerar NF-e', 'Bloquear portas'], 1),
    q_mcq('Superglobais futuras de request:', ['$_GET / $_POST', '$window', 'process.env', 'os.environ'], 0),
    q_mcq('Segurança inicial:', ['Expor .env', 'Não versionar segredos', 'Desativar HTTPS', 'Senha 123'], 1),
    q_mcq('Página não abre — primeiro passo:', ['Formatar HD', 'Checar serviço/logs', 'Novo monitor', 'Trocar linguagem'], 1),
    q_mcq('php -S 127.0.0.1:8000 é:', ['Firewall', 'Servidor embutido de desenvolvimento', 'Proxy Nginx prod', 'Redis'], 1),
    q_mcq('Arquivo deve ser salvo como:', ['DOCX', 'Texto puro / UTF-8', 'PDF escaneado', 'PPT'], 1),
    q_mcq('Short tags <? sem php:', ['Sempre portáveis', 'Podem estar desabilitadas', 'Obrigatórias', 'Só CLI'], 1),
    q_mcq('Erro “command not found: php” indica:', ['CSS quebrado', 'PHP não no PATH', 'GPU', 'DNS'], 1),
    q_mcq('Bom primeiro exercício:', ['Cluster K8s', 'Script que imprime mensagem e roda local', 'JIT assembly', 'Kafka'], 1),
    q_mcq('Separar lógica de apresentação cedo ajuda a:', ['Aumentar acoplamento', 'Manter código legível', 'Quebrar Git', 'Desligar HTTPS'], 1),
    q_mcq('Extensão .phtml às vezes usada para:', ['Imagens', 'Templates misturando PHP/HTML', 'Áudio', 'Fontes'], 1),
    q_mcq('die("erro") / exit:', ['Inicia loop', 'Encerra o script', 'Cria array', 'Abre PDO'], 1),
    q_mcq('include vs require (ideia):', ['Iguais e sem efeito', 'require falha mais estrito se arquivo ausente', 'include é SQL', 'require é CSS'], 1),
    q_mcq('Checklist do módulo 1 completo inclui:', ['Só ler PDF', 'Ambiente + script + ver saída no browser/CLI', 'Só instalar IDE', 'Só criar repo vazio'], 1),
], 'Prova do módulo 1 concluída. Sucesso!', $introProva);

$prova2 = prova30('Prova · Módulo 2 — Variáveis, tipos e operadores', [
    q_mcq('Declaração válida:', ['int x = 1;', '$x = 1;', 'var $x = 1;', 'let x = 1;'], 1),
    q_mcq('=== compara:', ['Só referência de objeto JS', 'Valor e tipo', 'Só memória', 'Só encoding'], 1),
    q_input('Operador de igualdade estrita (3 chars):', '==='),
    q_mcq('$a = 5; $a += 2; valor final:', ['2', '5', '7', '52'], 2),
    q_mcq('Aspas simples interpolam variáveis?', ['Não', 'Sim', 'Só Windows', 'Só PHP 8+'], 0),
    q_mcq('"Olá $nome" com $nome="Ana" (aspas duplas):', ['Olá $nome', 'Olá Ana', 'erro', 'null'], 1),
    q_mcq('Cast bool de "0":', ['true', 'false', 'null', 'erro'], 1),
    q_mcq('Nome idiomatico:', ['$1x', '$totalPedidos', '$Total-Pedidos', 'total pedidos'], 1),
    q_mcq('Objetivo do módulo 2:', ['PDO', 'Variáveis, tipos e operadores', 'CSS Grid', 'Docker'], 1),
    q_mcq('Quando tipos importam, prefira:', ['==', '===', '!= frouxo', '<>'], 1),
    q_mcq('$x ??= 10; atribui se:', ['$x já é 10', '$x null/indefinido (ideia do ??/=)', '$x string', 'Sempre'], 1),
    q_mcq('Tipo de 3.14:', ['int', 'float', 'bool', 'resource'], 1),
    q_mcq('Concatenar "a" e "b":', ['"a" + "b"', '"a" . "b"', '"a" && "b"', '"a" | "b"'], 1),
    q_mcq('== pode surpreender porque:', ['É estrito', 'Faz coerção de tipos', 'Não existe', 'Só compara strings'], 1),
    q_match('Associe operador → uso.', [['=', 'Atribuição'], ['==', 'Igualdade frouxa'], ['===', 'Igualdade estrita'], ['.', 'Concatenação']]),
    q_mcq('(int) true resulta:', ['0', '1', 'erro', 'null'], 1),
    q_mcq('Variável não definida (sem @) gera:', ['Warning/Notice conforme config', 'Sempre Exception checked', 'Nada', 'Reboot'], 0),
    q_mcq('is_string(10) retorna:', ['true', 'false', '10', 'erro'], 1),
    q_mcq('empty("0") em PHP clássico:', ['false', 'true', 'erro', 'null'], 1),
    q_mcq('Operador %:', ['Potência', 'Resto da divisão', 'Raiz', 'Concatena'], 1),
    q_mcq('++$i vs $i++ diferença clássica:', ['Nenhuma', 'Pré vs pós incremento no valor da expressão', 'Só CSS', 'Só SQL'], 1),
    q_mcq('Stringable "123abc" cast (int):', ['123', '0', 'erro fatal', 'null'], 0),
    q_mcq('const PI = 3.14; depois PI = 2:', ['OK', 'Erro (constante)', 'Vira variável', 'Reinicia Apache'], 1),
    q_mcq('gettype(false) retorna:', ['"bool"', '"boolean"', 'false', '0'], 1),
    q_mcq('Espaço em nome de variável:', ['Permitido', 'Inválido', 'Só com hífen', 'Só em aspas'], 1),
    q_mcq('$a = []; tipo:', ['string', 'array', 'object obrigatório', 'resource'], 1),
    q_mcq('Operador && :', ['OU lógico', 'E lógico', 'XOR de bits só', 'Concatena'], 1),
    q_mcq('Operador || :', ['E lógico', 'OU lógico', 'Atribuição', 'Cast'], 1),
    q_mcq('Negação lógica:', ['!', 'nott', '~~', 'neg'], 0),
    q_mcq('Boa prática em seletivo:', ['Misturar == sem critério', 'Ser explícito com tipos quando importa', 'Ignorar notices', 'Usar eval'], 1),
], 'Prova do módulo 2 finalizada. Sucesso!', $introProva);

$prova3 = prova30('Prova · Módulo 3 — Condicionais e loops', [
    q_mcq('if (0) { echo "a"; } else { echo "b"; } imprime:', ['a', 'b', 'ab', 'erro'], 1),
    q_mcq('for ($i=1; $i<=3; $i++) echo $i; saída:', ['123', '012', '321', '1 2 3'], 0),
    q_mcq('foreach ([1,2] as $v) echo $v; saída:', ['12', '21', '1,2', 'erro'], 0),
    q_input('Palavra que pula o restante do corpo do loop:', 'continue'),
    q_mcq('JS while ≈ PHP while:', ['Falsidade', 'Mesma ideia de repetir sob condição', 'Só JS', 'Só PHP 8'], 1),
    q_mcq('match vs switch (ideia):', ['match mais estrito e retorna valor', 'match não existe', 'match é CSS', 'match é async'], 0),
    q_match('Associe risco → mitigação.', [['while(true)', 'Condição de saída'], ['índice fora', 'isset/validação'], ['switch longo', 'map/lookup'], ['aninhamento', 'early return']]),
    q_mcq('else if idiomático:', ['elif', 'elseif ou else if', 'elsewhen', 'otherwise'], 1),
    q_mcq('do { } while ($x); garante:', ['0 execuções', 'Pelo menos 1 execução', 'Paralelismo', 'Transação'], 1),
    q_mcq('break 2; pode:', ['Quebrar dois níveis', 'Apagar arquivos', 'Criar array', 'Reiniciar Apache'], 0),
    q_mcq('Melhor para varrer associativo:', ['for infinito', 'foreach', 'goto', 'eval'], 1),
    q_mcq('if ($a = 1) risco:', ['Atribuição acidental', 'Nenhum', 'Sintaxe inválida', 'Criptografia'], 0),
    q_input('Laço baseado em condição no início:', 'while'),
    q_mcq('$i=0; while($i<2){ echo $i; $i++; } saída:', ['01', '12', '012', 'erro'], 0),
    q_mcq('FizzBuzz exige principalmente:', ['HTML puro', 'Condicionais + loop + operadores', 'GPU', 'XML'], 1),
    q_mcq('Operador E:', ['&& ou and', 'AND_ONLY', 'et', 'amp HTML'], 0),
    q_mcq('Operador OU:', ['|| ou or', 'ou', 'ORR', '//'], 0),
    q_mcq('Negação:', ['!', 'nott', '~~', 'neg'], 0),
    q_mcq('Objetivo do módulo 3:', ['PDO', 'Controlar fluxo com if/loops', 'K8s', 'Photoshop'], 1),
    q_mcq('Condições complexas legíveis:', ['Uma linha de 300 chars', 'Booleanos nomeados / early return', '10 ifs aninhados', 'Só goto'], 1),
    q_mcq('switch sem break (fall-through):', ['Sempre erro', 'Pode executar cases seguintes', 'Cria thread', 'Invalida PHP'], 1),
    q_mcq('foreach ($a as $k => $v) entrega:', ['Só valores', 'Chave e valor', 'Só tipos', 'Só referências obrigatórias'], 1),
    q_mcq('for (;;) sem saída é:', ['Ótimo padrão', 'Loop infinito (risco)', 'Sintaxe inválida', 'Async'], 1),
    q_mcq('Operador spaceship <=> :', ['Concatena', 'Compara e retorna -1/0/1', 'Abre arquivo', 'Cast bool'], 1),
    q_mcq('empty([]) é:', ['false', 'true', 'erro', '[]'], 1),
    q_mcq('Truthy em if ("0"):', ['true', 'false (string zero)', 'erro', 'null'], 1),
    q_mcq('Melhor que aninhar 5 ifs:', ['Mais ifs', 'Extrair funções / early return', 'eval', 'goto em cascata'], 1),
    q_mcq('continue 2; ideia:', ['Pula 2 níveis de loop', 'Apaga 2 arquivos', 'Cria 2 arrays', 'HTTP 2'], 0),
    q_mcq('default no switch:', ['Obrigatório', 'Ramo quando nada casa', 'Só PHP 4', 'Inicia PDO'], 1),
    q_mcq('Teste mental: $n=5; echo $n%2 ? "impar" : "par";', ['par', 'impar', 'erro', '5'], 1),
], 'Prova do módulo 3 ok. Sucesso!', $introProva);

$prova4 = prova30('Prova · Módulo 4 — Arrays e strings', [
    q_mcq('Array associativo de produto:', ['$p = ["id"=>1,"nome"=>"Teclado"]', '$p = (id=1)', '$p = new String', '$p = {id:1}'], 0),
    q_mcq('count(["a"=>1,"b"=>2]) =', ['1', '2', '0', '3'], 1),
    q_mcq('array_key_exists vs isset (clássico):', ['null na chave', 'CSS', 'SQL injection', 'UTF-32'], 0),
    q_input('Função que parte string em array por delimitador:', 'explode'),
    q_mcq('JS "a,b".split(",") ≈', ['explode(",", "a,b")', 'implode', 'split()', 'tokenize_js'], 0),
    q_mcq('str_replace("a","x","banana") contém:', ['bxnxnx', 'bxnana', 'banana', 'erro'], 0),
    q_match('Associe necessidade de texto/array à função.', [['juntar CSV', 'implode'], ['separar CSV', 'explode'], ['trocar texto', 'str_replace'], ['tamanho', 'strlen']]),
    q_mcq('in_array(2,[1,2,3], true) com "2" string:', ['Pode falhar (strict)', 'Sempre true', 'Erro de sintaxe', 'Cria índice'], 0),
    q_mcq('array_push($a, 9):', ['Remove 9', 'Insere no final', 'Ordena', 'Inverte'], 1),
    q_mcq('Lista ordenada de IDs:', ['array indexado', 'string gigante', 'float', 'resource'], 0),
    q_mcq('Interpolação "Olá {$user[\'nome\']}" exige:', ['Chaves/escapes adequados', 'JSON', 'XML', 'GPU'], 0),
    q_mcq('mb_strlen vs strlen:', ['Unicode/multibyte', 'SQL', 'HTTP/2', 'CORS'], 0),
    q_input('Função count de elementos:', 'count'),
    q_mcq('Remover e reindexar numérico:', ['unset + array_values', 'echo', 'include', 'header'], 0),
    q_mcq('Frequência de palavras combina:', ['explode + loops/contagem', 'só CSS', 'FTP', 'SMTP'], 0),
    q_mcq('$a[] = 3; faz:', ['Push no próximo índice', 'Declara classe', 'Abre socket', 'Nada'], 0),
    q_mcq('sort($a):', ['Ordena por referência (retorna bool)', 'Sempre cópia imutável', 'Só strings', 'Arquivos'], 0),
    q_mcq('Analogia correta:', ['implode ≈ JS join', 'explode ≈ JS concat', 'count ≈ JS class', 'echo ≈ JS import'], 0),
    q_mcq('Objetivo do módulo 4:', ['Auth JWT', 'Manipular arrays e strings', 'Terraform', 'Blender'], 1),
    q_mcq('Evitar notice em chave ausente:', ['@ em tudo', '?? / isset com intenção', 'Ignorar logs', 'Reiniciar PC'], 1),
    q_mcq('array_merge([1],[2]) :', ['[1,2]', '[1]', '3', 'erro'], 0),
    q_mcq('strpos("abc","b") === false?', ['Não (encontrou)', 'Sim', 'Erro', 'null'], 0),
    q_mcq('Chave "0" e 0 em arrays:', ['Sempre distintas', 'Podem colidir/coagir conforme uso', 'Impossíveis', 'Só CSV'], 1),
    q_mcq('list($a,$b) = [1,2];', ['Desempacota valores', 'Cria classe', 'Abre PDO', 'Hash'], 0),
    q_mcq('array_unique remove:', ['Duplicatas de valores', 'Todas as chaves', 'Arquivos', 'Cookies'], 0),
    q_mcq('strtolower("AbC"):', ['"abc"', '"ABC"', '"AbC"', 'erro'], 0),
    q_mcq('Operador [] em string $s[0]:', ['Primeiro caractere (byte)', 'Último', 'Array novo', 'Erro sempre'], 0),
    q_mcq('array_slice($a,0,2):', ['Subarray com 2 elementos', 'Inverte', 'Ordena', 'Soma'], 0),
    q_mcq('JSON encode de array PHP típico:', ['json_encode', 'xml_encode', 'yaml_dump', 'toJSON() JS'], 0),
    q_mcq('Boa prática de chaves:', ['Espaços e acentos sem cuidado', 'Nomes claros e consistentes', 'Só números aleatórios', 'Misturar idiomas ao acaso'], 1),
], 'Prova do módulo 4 concluída. Sucesso!', $introProva);

$prova5 = prova30('Prova · Módulo 5 — Arrays, texto e Arenas', [
    q_mcq('Listar serviços idiomático:', ['foreach ($servicos as $s) { echo $s, PHP_EOL; }', 'for-in ($servicos)', 'servicos.forEach sem adaptar', 'while(true) echo $servicos'], 0),
    q_mcq('Contar itens em $pedidos:', ['sizeof_array($pedidos)', 'count($pedidos)', '$pedidos->length', 'len($pedidos)'], 1),
    q_mcq('JS arr.length vs PHP count:', ['count é função; length é propriedade em JS', 'Iguais sintaxe', 'PHP usa .length', 'JS usa count()'], 0),
    q_mcq('Atualizar índice 2 do portfólio:', ['$p=["a"]; unset($p);', '$portfolio[2]="Novo projeto";', '$portfolio="Novo projeto";', 'array_shift($portfolio,2)'], 1),
    q_mcq('Totalizar vendas em $valores:', ['array_sum($valores)', 'sum($valores)', 'reduce.sum', 'array_total'], 0),
    q_mcq('strtoupper é:', ['Método de instância obrigatório', 'Função built-in que transforma texto', 'Propriedade', 'Namespace'], 1),
    q_mcq('foreach ($a as $k => $v) disponibiliza:', ['Só $v', 'Chave e valor', 'Só tipos', 'Só referências'], 1),
    q_mcq('Echo legível da quantidade:', ['echo count($a)." itens";', 'echo $a.count', 'print length($a)', 'echo sizeof_array'], 0),
    q_mcq('Cópia $copia = $original (arrays):', ['Semântica de cópia por valor (copy-on-write)', 'Sempre referência', 'Erro', 'Só string'], 0),
    q_mcq('array_map("strtoupper", $t) :', ['Transforma cada item', 'Ordena', 'Abre PDO', 'Gera SSL'], 0),
    q_mcq('Ler chave 99 com segurança:', ['$a[99] direto', 'isset($a[99]) ou ?? antes', 'Sempre existe', 'die()'], 1),
    q_mcq('Contrato da Arena significa:', ['CSS bonito', 'Saída alinhada ao juiz', 'Commit message', 'Cor do terminal'], 1),
    q_mcq('Média com proteção:', ['array_sum/count com count>0', 'avg()', 'mean()', '/0 ok'], 0),
    q_mcq('strlen vs mb_strlen no relatório:', ['Unicode pode exigir mb_*', 'Sempre iguais', 'strlen é SQL', 'mb é CSS'], 0),
    q_mcq('array_reduce no caixa serve para:', ['Só pegar primeiro', 'Acúmulo customizado', 'Criar HTML', 'DNS'], 1),
    q_mcq('strval no relatório:', ['Converte para string', 'Soma', 'Ordena', 'Abre arquivo'], 0),
    q_mcq('is_numeric antes de somar evita:', ['Somar lixo não numérico', 'CSS', 'Git', 'SSL'], 0),
    q_mcq('number_format ajuda a:', ['Exibir dinheiro legível', 'Criar trait', 'Hash bcrypt', 'Abrir porta'], 0),
    q_mcq('unset($a[$i]) + array_values:', ['Remove e reindexa', 'Cria classe', 'Inicia sessão', 'Envia e-mail'], 0),
    q_mcq('print_r vs echo de escalar:', ['print_r melhor para estruturas', 'echo sempre dump completo', 'Iguais', 'print_r é SQL'], 0),
    q_mcq('Referência &$x = &$arr[0]:', ['Altera o original', 'Nunca altera', 'Cria processo', 'É CSS'], 0),
    q_mcq('array_filter no caixa pode:', ['Tirar valores inválidos', 'Compilar C', 'Gerar XML obrigatório', 'Reiniciar PHP'], 0),
    q_mcq('sprintf no relatório:', ['Monta string formatada', 'Abre PDO', 'Só JS', 'Só CSS'], 0),
    q_mcq('PHP_EOL no juiz CLI:', ['Quebra de linha portável', 'Cor ANSI', 'Timeout', 'CORS'], 0),
    q_mcq('Falha comum na Arena:', ['Saída quase certa mas com espaço/quebra extra', 'Usar foreach', 'Usar count', 'Usar echo'], 0),
    q_mcq('array_slice para “top 3”:', ['Pega subconjunto', 'Soma', 'Conecta DB', 'Valida CSRF'], 0),
    q_mcq('json_encode de lista de lojas:', ['Serializa para JSON', 'Desenha gráfico', 'Compacta ZIP', 'Envia SMTP'], 0),
    q_mcq('Boa mensagem de erro ao usuário final:', ['var_dump gigante', 'Texto claro sem vazar internals', 'Stack completa', 'Senha do DB'], 1),
    q_mcq('Objetivo das Arenas no módulo 5:', ['Memorizar HTML sem PHP', 'Praticar arrays/texto com contratos', 'Substituir LMS', 'Só CSS'], 1),
    q_mcq('Antes da prova, o melhor treino é:', ['Pular missões', 'Completar Arenas + quizzes com atenção aos contratos', 'Só ler título', 'Desligar o cronômetro'], 1),
], 'Prova do módulo 5 concluída. Sucesso — ritmo de seletivo!', $introProva);

DB::beginTransaction();
try {
    foreach ($decks as $id => [$titulo, $cards]) {
        $payload = json_encode([
            'kind' => 'flip_deck',
            'titulo' => $titulo,
            'hint' => 'Clique no card para virar.',
            'memo' => $MEMO,
            'cards' => $cards,
        ], JSON_UNESCAPED_UNICODE);
        DB::table('conteudos')->where('id', $id)->update([
            'titulo' => $titulo,
            'url' => $payload,
            'descricao' => '10 cards · memorização · classificação do termo.',
            'updated_at' => now(),
        ]);
        echo "deck #$id updated (".count($cards).")\n";
    }

    // Esconde "Card visual" redundante (preserva ID/progresso)
    $hidden = DB::table('conteudos')
        ->where('titulo', 'like', 'Card visual%')
        ->whereIn('aula_id', [27, 28, 29, 30, 38, 59])
        ->update(['is_hidden' => 1, 'updated_at' => now()]);
    echo "card_visual_hidden=$hidden\n";

    $quizMap = [
        212 => $quizMod1,
        214 => $quizMod2,
        216 => $quizMod3,
        218 => $quizMod4,
        127 => $quizA1,
        177 => $quizA2,
    ];
    foreach ($quizMap as $id => $pack) {
        DB::table('conteudos')->where('id', $id)->update([
            'url' => json_encode($pack, JSON_UNESCAPED_UNICODE),
            'descricao' => '10 questões · até 3 tentativas · maior nota · mínimo 70%.',
            'updated_at' => now(),
        ]);
        echo "quiz #$id q=".count($pack['questions'])."\n";
    }

    $provaMap = [
        213 => $prova1,
        215 => $prova2,
        217 => $prova3,
        219 => $prova4,
        211 => $prova5,
    ];
    foreach ($provaMap as $id => $pack) {
        DB::table('conteudos')->where('id', $id)->update([
            'url' => json_encode($pack, JSON_UNESCAPED_UNICODE),
            'tipo' => 'prova',
            'descricao' => '30 questões · 30 minutos · 1 tentativa · anti-fraude · mínimo 70%.',
            'updated_at' => now(),
        ]);
        echo "prova #$id q=".count($pack['questions'])."\n";
    }

    $seen = [];
    $dups = 0;
    foreach ([$quizMap, $provaMap] as $map) {
        foreach ($map as $id => $pack) {
            foreach ($pack['questions'] as $q) {
                $extra = '';
                if (($q['type'] ?? '') === 'mcq') {
                    $extra = implode('|', array_slice($q['options'] ?? [], 0, 2));
                } elseif (($q['type'] ?? '') === 'match') {
                    $extra = implode('|', array_map(fn ($p) => $p[0], $q['pairs'] ?? []));
                } else {
                    $extra = (string) ($q['answer_text'] ?? '');
                }
                $key = mb_strtolower(preg_replace('/\s+/', ' ', trim(($q['type'] ?? '').'|'.$q['q'].'|'.$extra)));
                if (isset($seen[$key])) {
                    echo "DUP q between #{$seen[$key]} and #$id : {$q['q']}\n";
                    $dups++;
                } else {
                    $seen[$key] = $id;
                }
            }
        }
    }
    echo "dup_count=$dups\n";
    if ($dups > 0) {
        throw new RuntimeException('Perguntas duplicadas detectadas');
    }

    DB::commit();
    echo "OK upgrade_v2\n";
} catch (Throwable $e) {
    DB::rollBack();
    fwrite(STDERR, $e->getMessage()."\n");
    exit(1);
}
