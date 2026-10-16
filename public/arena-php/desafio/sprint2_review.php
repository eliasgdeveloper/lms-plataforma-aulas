<?php
/**
 * Juiz da Arena 2 (Caixa Litoral).
 * Acertos e erros por etapa. Não devolve a saída esperada.
 */

function desafio_extra_saida_parece_codigo($saida)
{
    $s = (string) $saida;
    if (stripos($s, '<?php') !== false || stripos($s, '<?=') !== false) {
        return true;
    }
    $temEcho = (bool) preg_match('/\becho\b/i', $s);
    $temLaco = (bool) preg_match('/\b(foreach|while|array_reduce|array_sum)\b/i', $s);
    $temVar = (bool) preg_match('/\$[a-zA-Z_][a-zA-Z0-9_]*/', $s);
    if ($temEcho && $temVar) {
        return true;
    }
    if ($temLaco && $temVar) {
        return true;
    }
    return false;
}

function desafio_extra_linhas($texto)
{
    $texto = str_replace(array("\r\n", "\r"), "\n", (string) $texto);
    $raw = explode("\n", $texto);
    $out = array();
    foreach ($raw as $linha) {
        $out[] = trim($linha);
    }
    while (!empty($out) && $out[count($out) - 1] === '') {
        array_pop($out);
    }
    return $out;
}

function desafio_sprint2_codigo_tem($codigo, $pedaco)
{
    return stripos((string) $codigo, $pedaco) !== false;
}

function desafio_sprint2_falhas_extra()
{
    return array(
        'Etapa 1: a primeira linha não é o cabeçalho de abertura do caixa Litoral.',
        'Etapa 2: a linha da Copacabana precisa de número com parêntese, título em maiúsculas, inicial do código, status e valor.',
        'Etapa 2: a linha da Ipanema não fecha com o contrato (número, título, inicial, status e valor).',
        'Etapa 2: a linha do Leblon não fecha com o contrato.',
        'Etapa 2: a linha de Botafogo não fecha com o contrato.',
        'Etapa 4: a linha TOTAL não fecha com a soma das quatro vendas (array_sum).',
        'Etapa 5: a linha MEDIA não fecha com a soma dividida pela quantidade de lojas.',
        'Etapa 6: a linha REDUCE_MIL não fecha com a soma só da faixa de mil ou mais.',
        'Etapa 7: a linha OBS não fecha com a quantidade de campos e o maior texto.',
        'Etapa 8: a linha da inicial não é a primeira letra da palavra fechamento.',
        'Etapa 9: a última linha não é o fechamento do caixa Litoral.',
    );
}

/**
 * @return array{acertos: string[], erros: string[], message: string}
 */
function desafio_extra_review($codigo, $saida, $nome = 'Aluno')
{
    $codigo = (string) $codigo;
    $saida = (string) $saida;
    $acertos = array();
    $erros = array();
    $saidaECodigo = desafio_extra_saida_parece_codigo($saida);
    $saidaVazia = trim($saida) === '';
    $soCodigo = $saidaECodigo || $saidaVazia;

    if ($saidaECodigo) {
        $erros[] = $nome . ', o campo de saída parece código PHP. Cole só o texto impresso pelo echo.';
        if (trim($codigo) === '' && trim($saida) !== '') {
            $codigo = $saida;
        }
    }

    $checagens = array(
        array('foreach', 'Etapa 2: falta percorrer as lojas com foreach (ou o laço pedido no contrato).'),
        array('strtoupper', 'Etapa 2: falta colocar o nome da loja em maiúsculas com strtoupper — é título de vitrine.'),
        array('array_sum', 'Etapa 4: falta array_sum para o TOTAL. No PHP não existe a função sum().'),
        array('count', 'Etapa 5 e 7: falta count para a quantidade de lojas ou de campos de texto.'),
        array('array_reduce', 'Etapa 6: falta array_reduce para a faixa de mil ou mais.'),
        array('strlen', 'Etapa 7: falta strlen para medir o campo de observação.'),
        array('strval', 'Etapa 5: falta strval para mostrar número como texto. No PHP não existe toString().'),
        array('if', 'Etapa 3: falta if para classificar estrela, ok ou atencao.'),
    );

    if ($soCodigo) {
        foreach ($checagens as $item) {
            if (desafio_sprint2_codigo_tem($codigo, $item[0])) {
                $acertos[] = 'Encontrei ' . $item[0] . ' no código.';
            } else {
                $erros[] = $item[1];
            }
        }
        $temLetra = (bool) preg_match('/\[\s*0\s*\]|mb_substr|substr\s*\(/i', $codigo);
        if ($temLetra) {
            $acertos[] = 'Encontrei leitura da primeira letra (posição 0 ou substr).';
        } else {
            $erros[] = 'Etapa 2 e 8: falta ler a primeira letra de um texto (posição 0). No PHP não existe charAt().';
        }
        if (stripos($codigo, 'elseif') === false && stripos($codigo, 'else') === false) {
            $erros[] = 'Etapa 3: o status precisa de mais de um caminho (elseif ou else), não só um if.';
        } else {
            $acertos[] = 'Encontrei um segundo caminho de condicional para o status.';
        }

        return array(
            'acertos' => $soCodigo ? array() : $acertos,
            'erros' => $erros,
            'message' => $nome . ', o código do fechamento ainda precisa dos ajustes abaixo. Sem a resposta pronta.',
        );
    }

    $extra = require __DIR__ . '/extra_sprint2.php';
    $esperadas = desafio_extra_linhas($extra['saida_esperada']);
    $enviadas = desafio_extra_linhas($saida);
    $falhas = desafio_sprint2_falhas_extra();

    $hifen = false;
    foreach ($enviadas as $linha) {
        if (preg_match('/^\d+\s*[-–]/', $linha)) {
            $hifen = true;
            break;
        }
    }
    if ($hifen) {
        $erros[] = 'Etapa 2: a lista está com hífen. O contrato pede número seguido de parêntese.';
    }

    $n = max(count($esperadas), count($enviadas));
    for ($i = 0; $i < $n; $i++) {
        $esp = array_key_exists($i, $esperadas) ? $esperadas[$i] : null;
        $env = array_key_exists($i, $enviadas) ? $enviadas[$i] : null;
        if ($esp === null) {
            $erros[] = 'Há linha a mais depois da última etapa do contrato.';
            continue;
        }
        if ($env === null || $env === '') {
            $erros[] = isset($falhas[$i]) ? $falhas[$i] : ('Etapa ' . ($i + 1) . ' está vazia.');
            continue;
        }
        if ($env === $esp) {
            $acertos[] = 'Etapa da linha ' . ($i + 1) . ' conferida.';
        } else {
            $erros[] = isset($falhas[$i]) ? $falhas[$i] : ('A linha ' . ($i + 1) . ' não fecha com o contrato.');
        }
    }

    foreach ($checagens as $item) {
        if (!desafio_sprint2_codigo_tem($codigo, $item[0])) {
            $erros[] = $item[1];
        }
    }

    $message = empty($erros)
        ? $nome . ', o contrato fechou.'
        : $nome . ', o fechamento do caixa ainda não fecha com o contrato. Veja a etapa de cada ajuste — sem a resposta pronta.';

    return array(
        'acertos' => $acertos,
        'erros' => $erros,
        'message' => $message,
    );
}

/**
 * @return array{acertos: string[], erros: string[], message: string}
 */
function desafio_sprint2_missao_review($etapa, $codigo, $saida, $nome = 'Aluno')
{
    $esperadas = desafio_extra_linhas(isset($etapa['saida_esperada']) ? $etapa['saida_esperada'] : '');
    $enviadas = desafio_extra_linhas($saida);
    $falhas = isset($etapa['falhas']) && is_array($etapa['falhas']) ? $etapa['falhas'] : array();
    $acertos = array();
    $erros = array();

    $n = max(count($esperadas), count($enviadas));
    for ($i = 0; $i < $n; $i++) {
        $esp = array_key_exists($i, $esperadas) ? $esperadas[$i] : null;
        $env = array_key_exists($i, $enviadas) ? $enviadas[$i] : null;
        if ($esp === null) {
            $erros[] = 'Há linha a mais depois da última etapa desta missão.';
            continue;
        }
        if ($env === null || $env === '') {
            $erros[] = isset($falhas[$i]) ? $falhas[$i] : ('Etapa ' . ($i + 1) . ' está vazia.');
            continue;
        }
        if ($env === $esp) {
            $acertos[] = 'Etapa ' . ($i + 1) . ' conferida.';
        } else {
            $erros[] = isset($falhas[$i]) ? $falhas[$i] : ('Etapa ' . ($i + 1) . ' não fecha com o contrato.');
        }
    }

    return array(
        'acertos' => $acertos,
        'erros' => $erros,
        'message' => $nome . ', a saída não fecha com o contrato desta missão. As etapas marcam o que já entrou e o que falta — sem a resposta pronta.',
    );
}
