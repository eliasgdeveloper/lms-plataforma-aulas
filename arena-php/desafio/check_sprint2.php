<?php
define('DESAFIO_BUCKET', 'sprint2');
require_once dirname(__DIR__) . '/auth/bootstrap.php';
require_once __DIR__ . '/progress_store.php';
plm_start_session();

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

function desafio_json($payload, $code = 200)
{
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function desafio_primeiro_nome()
{
    $user = plm_current_user();
    $name = isset($user['name']) && $user['name'] !== '' ? $user['name'] : (isset($user['username']) ? $user['username'] : 'Aluno');
    $partes = preg_split('/\s+/', trim($name));
    return $partes[0];
}

function desafio_state_init()
{
    return desafio_progress_boot();
}

function desafio_normaliza($texto)
{
    $texto = str_replace(array("\r\n", "\r"), "\n", $texto);
    $texto = trim($texto);
    $linhas = preg_split("/\n/", $texto);
    $limpas = array();
    foreach ($linhas as $linha) {
        $limpas[] = trim($linha);
    }
    return implode("\n", $limpas);
}

function desafio_fala_acerto($nome, $etapaId, $pontos)
{
    $falas = array(
        1 => "Isso aí, {$nome}! Array na veia e +{$pontos} pontos no bolso.",
        2 => "Contou certinho, {$nome}! Você e o count() já são dupla.",
        3 => "foreach dançando com o contador… {$nome} manja do ritmo!",
        4 => "Índice alterado com estilo, {$nome}. Backup intacto, missão no ponto.",
        5 => "BANNER ativado! {$nome} gritou em MAIÚSCULAS e o PHP obedeceu.",
        6 => "array_map no modo ninja, {$nome}. Minúsculas afiadas.",
        7 => "Aluno encontrou curso! {$nome} cruzou os arrays como um detetive PHP.",
        8 => "Boss derrotado, {$nome}! Catálogo montado e pontuação bombando.",
    );
    return isset($falas[$etapaId]) ? $falas[$etapaId] : "Missão concluída, {$nome}! +{$pontos} pontos";
}

function desafio_fala_surpresa($nome, $etapaId, $textoBase, $completo)
{
    $extras = array(
        1 => "{$nome} desbloqueou a Raposa Coder. Ela já tá pedindo café e mais arrays.",
        2 => "Badge Contador Oficial carimbada no crachá do {$nome}.",
        3 => "Robô PHP reportando: '{$nome} passou no tour foreach. Bip bip!'",
        4 => "{$nome} agora é Mestre dos Índices. Posição [2] treme na base.",
        5 => "Mago do Código! {$nome} lançou strtoupper e o banner brilhou.",
        6 => "Map Hero liberado. {$nome} transformou serviços em minúsculas lendárias.",
        7 => "Ninja dos Loops: {$nome} aninhou foreach sem deixar rastro.",
        8 => "Troféu Campeão PHP Básico. {$nome} zerou a arena. Aplausos merecidos!",
    );

    if ($completo) {
        return "As 8 missões estão completas, {$nome}! "
            . "Olha só: o Desafio Extra acabou de destrancar. "
            . "É o relatório que o diretor pediria numa segunda-feira de verdade.";
    }

    return isset($extras[$etapaId]) ? $extras[$etapaId] : ($textoBase . " · Vai que vai, {$nome}!");
}

function desafio_fala_erro($nome, $tipo, $extra = '')
{
    if ($tipo === 'tokens') {
        return "Calma, {$nome}! No código ainda falta: {$extra}. Respira e tenta de novo 😄";
    }
    if ($tipo === 'saida_vazia') {
        return "{$nome}, cola também a saída do echo. Sem resultado, o juiz PHP fica confuso.";
    }
    if ($tipo === 'saida') {
        return "Quase, {$nome}! A saída ainda não bate. Confere espaços, acentos e a ordem das linhas.";
    }
    if ($tipo === 'bloqueado') {
        return "{$nome}, o Desafio Extra ainda está lacrado. Conclua as 8 missões para abrir.";
    }
    return "Ops, {$nome}. Algo deu errado — tenta outra vez.";
}

function desafio_detecta_repeticao($codigo)
{
    $echoCount = preg_match_all('/\becho\b/i', $codigo);
    $foreachCount = preg_match_all('/\bforeach\b/i', $codigo);
    $mapCount = preg_match_all('/\barray_map\b/i', $codigo);
    $forCount = preg_match_all('/\bfor\s*\(/i', $codigo);
    $whileCount = preg_match_all('/\bwhile\s*\(/i', $codigo);
    $loops = $foreachCount + $forCount + $whileCount;

    if ($echoCount >= 10 && $loops < 1) {
        return true;
    }
    if ($echoCount >= 12 && $mapCount < 1) {
        return true;
    }
    if ($loops < 1 && $mapCount < 1 && strlen($codigo) > 900) {
        return true;
    }
    return false;
}

/**
 * Heurística: código parece ser do Desafio Extra (não de missão 1..8).
 * true se tem "RELATORIO DE PROJETOS TI" OU ($projetos + $status + (BANNERS|SLUGS|statusBackup)).
 */
function desafio_parece_codigo_extra($codigo)
{
    $c = (string) $codigo;
    if (stripos($c, 'CAIXA LITORAL') !== false) {
        return true;
    }
    return stripos($c, '$lojas') !== false
        && stripos($c, '$vendas') !== false
        && (stripos($c, 'array_sum') !== false || stripos($c, 'array_reduce') !== false);
}

function desafio_is_professor()
{
    $user = plm_current_user();
    if (!$user || !is_array($user)) {
        return false;
    }
    $username = isset($user['username']) ? (string) $user['username'] : '';
    $role = isset($user['role']) ? (string) $user['role'] : '';
    if (strcasecmp($username, 'Elias') === 0) {
        return true;
    }
    if ($role !== '' && stripos($role, 'Professor') !== false) {
        return true;
    }
    return false;
}

require_once __DIR__ . '/sprint2_review.php';

if (!plm_is_logged_in()) {
    desafio_json(array('ok' => false, 'message' => 'Faça login para jogar.'), 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    desafio_json(array('ok' => false, 'message' => 'Método inválido.'), 405);
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

$csrf = isset($data['csrf']) ? $data['csrf'] : '';
if (!plm_csrf_ok($csrf)) {
    desafio_json(array('ok' => false, 'message' => 'Sessão expirada. Recarregue a página.'), 403);
}

$nome = desafio_primeiro_nome();
$etapas = require __DIR__ . '/etapas_sprint2.php';
$extra = require __DIR__ . '/extra_sprint2.php';
$etapaRaw = isset($data['etapa']) ? $data['etapa'] : 0;
$codigo = isset($data['codigo']) ? (string) $data['codigo'] : '';
$saida = isset($data['saida']) ? (string) $data['saida'] : '';

desafio_state_init();
$state = &desafio_progress_state_ref();

$todasMissaoes = true;
foreach ($etapas as $item) {
    if (!in_array((int) $item['id'], $state['concluidas'], true)) {
        $todasMissaoes = false;
        break;
    }
}

$extraLiberado = $todasMissaoes || desafio_is_professor();

/* -------------------- DESAFIO EXTRA -------------------- */
if ((string) $etapaRaw === 'extra') {
    if (!$extraLiberado) {
        desafio_json(array(
            'ok' => false,
            'message' => desafio_fala_erro($nome, 'bloqueado'),
            'sound' => 'funny',
            'pontos' => (int) $state['pontos'],
            'extra_unlocked' => false,
            'extra_done' => !empty($state['extra_done']),
        ));
    }

    $esperada = desafio_normaliza($extra['saida_esperada']);
    $enviada = desafio_normaliza($saida);

    if ($enviada === '') {
        $reviewVazio = desafio_extra_review($codigo, $saida, $nome);
        $errosCodigo = isset($reviewVazio['erros']) ? $reviewVazio['erros'] : array();
        if (empty($errosCodigo)) {
            desafio_json(array(
                'ok' => false,
                'codigo_ok' => true,
                'message' => "{$nome}, o código do Extra parece certo. Falta só colar, no campo de baixo, a saída que o PHP imprime (o texto do echo). Sem essa saída o juiz não aprova.",
                'acertos' => array(),
                'erros' => array(),
                'sound' => 'funny',
                'pontos' => (int) $state['pontos'],
                'extra_unlocked' => true,
                'extra_done' => !empty($state['extra_done']),
            ));
        }
        $errosMarcados = array();
        foreach ($errosCodigo as $item) {
            $errosMarcados[] = (strpos($item, '✗') === 0) ? $item : ('✗ ' . $item);
        }
        desafio_json(array(
            'ok' => false,
            'codigo_ok' => false,
            'message' => "{$nome}, cola também a saída do echo. E o código ainda precisa destes ajustes (sem te entregar a resposta):",
            'acertos' => array(),
            'erros' => $errosMarcados,
            'sound' => 'funny',
            'pontos' => (int) $state['pontos'],
            'extra_unlocked' => true,
            'extra_done' => !empty($state['extra_done']),
        ));
    }

    if ($enviada !== $esperada) {
        $review = desafio_extra_review($codigo, $saida, $nome);
        desafio_json(array(
            'ok' => false,
            'message' => $review['message'],
            'acertos' => $review['acertos'],
            'erros' => $review['erros'],
            'sound' => 'funny',
            'dica' => $extra['dica'],
            'pontos' => (int) $state['pontos'],
            'extra_unlocked' => true,
            'extra_done' => !empty($state['extra_done']),
        ));
    }

    $faltandoExtra = array();
    if (!empty($extra['tokens']) && is_array($extra['tokens'])) {
        foreach ($extra['tokens'] as $token) {
            if (stripos($codigo, $token) === false) {
                $faltandoExtra[] = '✗ O contrato pede ' . $token . ' no código, e ele não apareceu.';
            }
        }
    }
    if (!empty($faltandoExtra)) {
        desafio_json(array(
            'ok' => false,
            'message' => "{$nome}, a saída até conversa com o contrato, mas o código ainda não usa o que a etapa pede.",
            'acertos' => array(),
            'erros' => $faltandoExtra,
            'sound' => 'funny',
            'pontos' => (int) $state['pontos'],
            'extra_unlocked' => true,
            'extra_done' => !empty($state['extra_done']),
        ));
    }

    $repetitivo = desafio_detecta_repeticao($codigo);
    $jaFez = !empty($state['extra_done']);
    $ganhou = 0;

    if (!$jaFez) {
        $state['extra_done'] = true;
        $state['pontos'] += (int) $extra['pontos'];
        $state['surpresas'][] = $extra['surpresa'];
        $state['avatar'] = 'star';
        $ganhou = (int) $extra['pontos'];
    }

    $alerta = '';
    if ($repetitivo) {
        $alerta = " Observação do professor: a saída está correta, {$nome}, então eu aceito. "
            . "Porém o encadeamento recomendado (foreach / laços + array_map) quase não apareceu — "
            . "deu para sentir repetição demais. Em empresa isso complica a manutenção. "
            . "Na próxima, tente percorrer as listas em vez de repetir echo na mão.";
    }

    $msg = $jaFez
        ? "{$nome}, você já conquistou o Desafio Extra. Pode treinar de novo à vontade — pontos do Extra não duplicam."
        : "Fechamento aprovado! {$nome} mandou bem no Extra do Caixa Litoral. +{$ganhou} pontos." . $alerta;

    desafio_progress_save_from_session();

    desafio_json(array(
        'ok' => true,
        'already' => $jaFez,
        'extra' => true,
        'warning' => $repetitivo,
        'message' => $msg,
        'pontos' => (int) $state['pontos'],
        'ganhou' => $ganhou,
        'concluidas' => $state['concluidas'],
        'surpresa' => $extra['surpresa'],
        'surpresa_texto' => $repetitivo
            ? "Estrela de Ouro do Relatório para {$nome}! Aprovado com ressalva de estilo: use mais foreach/array_map da próxima vez."
            : "Estrela de Ouro do Relatório! {$nome} entregou o Extra com cara de profissional de TI. Isso ainda não é certificado — é um marco de confiança.",
        'surpresas' => $state['surpresas'],
        'avatar' => $state['avatar'],
        'completo' => false,
        'extra_unlocked' => true,
        'extra_done' => true,
        'sound' => 'extra',
        'modal_title' => "Desafio Extra conquistado, {$nome}!",
        'aluno' => $nome,
        'saved' => true,
    ));
}

/* -------------------- MISSÕES 1..8 -------------------- */
if (desafio_parece_codigo_extra($codigo) && (string) $etapaRaw !== 'extra') {
    desafio_json(array(
        'ok' => false,
        'parece_extra' => true,
        'message' => "{$nome}, esse código é do Desafio Extra (Relatório do Diretor). "
            . "Abra o Extra pela estrela ⭐ ao lado das missões e valide por lá — "
            . "aqui o juiz está no modo Missão e vai reclamar de tokens errados.",
        'sound' => 'funny',
        'pontos' => (int) $state['pontos'],
        'extra_unlocked' => $extraLiberado,
        'extra_done' => !empty($state['extra_done']),
    ));
}

$etapaId = (int) $etapaRaw;
$etapa = null;
foreach ($etapas as $item) {
    if ((int) $item['id'] === $etapaId) {
        $etapa = $item;
        break;
    }
}

if ($etapa === null) {
    desafio_json(array('ok' => false, 'message' => 'Etapa não encontrada.'), 404);
}

if (in_array($etapaId, $state['concluidas'], true)) {
    desafio_json(array(
        'ok' => true,
        'already' => true,
        'message' => "{$nome}, essa você já zerou! Pode revisar o código à vontade e seguir em frente.",
        'sound' => 'funny',
        'pontos' => (int) $state['pontos'],
        'concluidas' => $state['concluidas'],
        'surpresas' => $state['surpresas'],
        'avatar' => $state['avatar'],
        'extra_unlocked' => $extraLiberado,
        'extra_done' => !empty($state['extra_done']),
    ));
}

$esperada = desafio_normaliza($etapa['saida_esperada']);
$enviada = desafio_normaliza($saida);

if ($enviada === '') {
    $faltandoVazio = array();
    foreach ($etapa['tokens'] as $token) {
        if (stripos($codigo, $token) === false) {
            $faltandoVazio[] = $token;
        }
    }
    if (empty($faltandoVazio)) {
        desafio_json(array(
            'ok' => false,
            'codigo_ok' => true,
            'saida_vazia' => true,
            'message' => "{$nome}, o código desta missão parece certo. Falta só colar, no campo de baixo, a saída que o PHP imprime. Sem essa saída o juiz não aprova.",
            'acertos' => array(),
            'erros' => array(),
            'sound' => 'funny',
            'pontos' => (int) $state['pontos'],
            'extra_unlocked' => $extraLiberado,
            'extra_done' => !empty($state['extra_done']),
        ));
    }
    $errosTok = array();
    foreach ($faltandoVazio as $token) {
        $errosTok[] = '✗ No código ainda falta: ' . $token . '.';
    }
    desafio_json(array(
        'ok' => false,
        'codigo_ok' => false,
        'saida_vazia' => true,
        'message' => "{$nome}, cola também a saída do echo. E o código ainda precisa destes ajustes:",
        'acertos' => array(),
        'erros' => $errosTok,
        'sound' => 'funny',
        'pontos' => (int) $state['pontos'],
        'extra_unlocked' => $extraLiberado,
        'extra_done' => !empty($state['extra_done']),
    ));
}

$faltando = array();
foreach ($etapa['tokens'] as $token) {
    if (stripos($codigo, $token) === false) {
        $faltando[] = $token;
    }
}

if (!empty($faltando)) {
    desafio_json(array(
        'ok' => false,
        'message' => desafio_fala_erro($nome, 'tokens', implode(', ', $faltando)),
        'sound' => 'funny',
        'pontos' => (int) $state['pontos'],
        'extra_unlocked' => $extraLiberado,
        'extra_done' => !empty($state['extra_done']),
    ));
}

if ($enviada !== $esperada) {
    $reviewMissao = desafio_sprint2_missao_review($etapa, $codigo, $saida, $nome);
    desafio_json(array(
        'ok' => false,
        'message' => $reviewMissao['message'],
        'acertos' => $reviewMissao['acertos'],
        'erros' => $reviewMissao['erros'],
        'sound' => 'funny',
        'dica' => $etapa['dica'],
        'pontos' => (int) $state['pontos'],
        'extra_unlocked' => $extraLiberado,
        'extra_done' => !empty($state['extra_done']),
    ));
}

$state['concluidas'][] = $etapaId;
$state['pontos'] += (int) $etapa['pontos'];
$state['surpresas'][] = $etapa['surpresa'];

$avatares = array(
    'avatar_fox' => 'fox',
    'avatar_robot' => 'robot',
    'avatar_wizard' => 'wizard',
    'avatar_ninja' => 'ninja',
    'trophy' => 'champion',
);
if (isset($avatares[$etapa['surpresa']])) {
    $state['avatar'] = $avatares[$etapa['surpresa']];
}

$restantes = 0;
foreach ($etapas as $item) {
    if (!in_array((int) $item['id'], $state['concluidas'], true)) {
        $restantes++;
    }
}

$completo = $restantes === 0;
$surpresaTexto = desafio_fala_surpresa($nome, $etapaId, $etapa['surpresa_texto'], $completo);

desafio_progress_save_from_session();

desafio_json(array(
    'ok' => true,
    'already' => false,
    'message' => desafio_fala_acerto($nome, $etapaId, (int) $etapa['pontos']),
    'pontos' => (int) $state['pontos'],
    'ganhou' => (int) $etapa['pontos'],
    'concluidas' => $state['concluidas'],
    'surpresa' => $etapa['surpresa'],
    'surpresa_texto' => $surpresaTexto,
    'surpresas' => $state['surpresas'],
    'avatar' => $state['avatar'],
    'completo' => $completo,
    'extra_unlocked' => ($completo || desafio_is_professor()),
    'extra_done' => !empty($state['extra_done']),
    'sound' => $completo ? 'finale' : 'cheer',
    'modal_title' => $completo
        ? "{$nome} zerou as 8 missões!"
        : "Surpresa pra você, {$nome}!",
    'aluno' => $nome,
    'saved' => true,
));
