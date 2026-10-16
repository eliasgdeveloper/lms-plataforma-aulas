<?php
/**
 * Diagnóstico do Desafio Extra (código + saída).
 * Devolve acertos[] e erros[] — sem colar a saída esperada
 * nem o valor literal do status pedido no item 4.
 */

function desafio_extra_saida_parece_codigo($saida)
{
    $s = (string) $saida;
    if (stripos($s, '<?php') !== false || stripos($s, '<?=') !== false) {
        return true;
    }
    $temEcho = (bool) preg_match('/\becho\b/i', $s);
    $temForeach = (bool) preg_match('/\bforeach\b/i', $s);
    $temMap = (bool) preg_match('/\barray_map\b/i', $s);
    $temVar = (bool) preg_match('/\$[a-zA-Z_][a-zA-Z0-9_]*/', $s);
    if (($temEcho || $temForeach || $temMap) && $temVar) {
        return true;
    }
    if ($temEcho && ($temForeach || $temMap)) {
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
    return $out;
}

function desafio_extra_tem_frase_diretor($texto)
{
    $t = mb_strtolower((string) $texto, 'UTF-8');
    $sinais = array(
        'já pode ir ao ar',
        'ja pode ir ao ar',
        'pedido do diretor',
        'site institucional delta já pode',
        'site institucional delta ja pode',
    );
    foreach ($sinais as $s) {
        if (strpos($t, $s) !== false) {
            return true;
        }
    }
    return false;
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
    // Sem saída impressa (ou colaram código no campo errado): julga só o script.
    $soCodigo = $saidaECodigo || $saidaVazia;

    if ($saidaECodigo) {
        $erros[] = "{$nome}, o campo de saída parece código PHP (echo, foreach, array_map ou <?php). "
            . "Cole só o texto impresso no navegador/terminal — o juiz compara a saída, não o script.";
        if (trim($codigo) === '' && trim($saida) !== '') {
            $codigo = $saida;
        }
    }

    $linhas = desafio_extra_linhas($saida);
    $naoVazias = array();
    if (!$soCodigo) {
        foreach ($linhas as $l) {
            if ($l !== '') {
                $naoVazias[] = $l;
            }
        }
    }

    /* ---- item 1: cabeçalho ---- */
    if (!$soCodigo) {
        $temCabecalho = false;
        foreach ($naoVazias as $l) {
            if (stripos($l, 'RELATORIO DE PROJETOS TI') !== false || stripos($l, 'RELATÓRIO DE PROJETOS TI') !== false) {
                $temCabecalho = true;
                break;
            }
        }
        if ($temCabecalho) {
            $acertos[] = "Cabeçalho do relatório aparece na saída (item 1 do enunciado).";
        } else {
            $erros[] = "Falta o cabeçalho pedido no item 1 do enunciado (=== RELATORIO…). Confira maiúsculas e o texto exato.";
        }
    } elseif (preg_match('/===\s*RELAT[OÓ]RIO DE PROJETOS TI\s*===/iu', $codigo)) {
        $acertos[] = "No código, o cabeçalho do item 1 está sendo impresso — valide gerando a saída real.";
    }

    /* ---- item 2: lista ---- */
    $paresOk = 0;
    $paresComTraco = 0;
    $paresComSeta = 0;
    $paresHifenNumeracao = 0;
    foreach ($naoVazias as $l) {
        if (preg_match('/^\d+\s*-\s+/u', $l)) {
            $paresHifenNumeracao++;
            if (strpos($l, '→') === false) {
                $paresComTraco++;
            }
            continue;
        }
        if (!preg_match('/^\d+\)\s+/u', $l)) {
            continue;
        }
        $paresOk++;
        if (strpos($l, '→') !== false || strpos($l, '->') !== false) {
            $paresComSeta++;
        } elseif (preg_match('/\s+-\s+/u', $l)) {
            $paresComTraco++;
        }
    }

    $codigoComHifenLista = (bool) preg_match(
        '/\(\s*\$\w+\s*\+\s*1\s*\)\s*\.\s*[\'"]\s*-\s*[\'"]/u',
        $codigo
    ) || (bool) preg_match('/\.\s*[\'"]\s*-\s*\{\$/u', $codigo)
        || (bool) preg_match('/\.\s*[\'"]\s*-\s*[\'"]\s*\.\s*\$/u', $codigo)
        || (bool) preg_match('/"\s*-\s*\{\$/u', $codigo);

    $codigoTemSeta = (strpos($codigo, '→') !== false);
    $msgListaHifen = "Na listagem (item 2), o número e o separador entre projeto e status não batem com o enunciado. "
        . "Você montou algo no estilo \"N - projeto status\". Relê o exemplo do item 2: fechamento após o número e seta entre nome e status.";

    if ($paresOk >= 10 && $paresComSeta >= 8) {
        $acertos[] = "Lista dos 10 pares com contador está no caminho certo (item 2).";
    } elseif ($paresHifenNumeracao >= 1 || $paresComTraco >= 1 || $codigoComHifenLista) {
        $erros[] = $msgListaHifen;
    } elseif ($paresOk > 0 && $paresOk < 10) {
        $erros[] = "A lista numerada está incompleta (item 2): confira se saíram os 10 pares, na ordem, cruzando índices.";
    } elseif ($paresOk === 0 && !$soCodigo) {
        $erros[] = "Não achei a lista no formato do item 2. Revise o foreach/contador e o exemplo do enunciado.";
    } elseif ($paresOk > 0) {
        $erros[] = "A lista (item 2) ainda não fecha com o formato do enunciado. Confira contador, seta e cruzamento de índices.";
    } elseif ($soCodigo && $codigoTemSeta && (bool) preg_match('/\(\s*\$\w+\s*\+\s*1\s*\)\s*\.\s*[\'"]\)/u', $codigo)) {
        $acertos[] = "No código a listagem parece seguir o formato do item 2 (contador + seta) — confirme na saída impressa.";
    }

    if (!$codigoTemSeta && ($codigoComHifenLista || $paresHifenNumeracao > 0) && !in_array($msgListaHifen, $erros, true)) {
        $erros[] = $msgListaHifen;
    }

    if (preg_match('/\bforeach\b/i', $codigo)) {
        $acertos[] = "No código tem foreach — bom sinal para montar a lista (item 2).";
    }

    /* ---- item 3: TOTAL ---- */
    if (!$soCodigo) {
        $temTotal = false;
        foreach ($naoVazias as $l) {
            if (preg_match('/^TOTAL:\s*10\s*$/i', $l)) {
                $temTotal = true;
                break;
            }
        }
        if ($temTotal) {
            $acertos[] = "TOTAL com count aparece como no item 3.";
        } else {
            $erros[] = "Falta (ou está diferente) a linha TOTAL pedida no item 3 — use count e o rótulo TOTAL:.";
        }
    } elseif (preg_match('/\bcount\s*\(/i', $codigo) && preg_match('/TOTAL:/i', $codigo)) {
        $acertos[] = "No código há count + TOTAL (item 3) — valide gerando a saída impressa.";
    }

    /* ---- itens 4 e 5 ---- */
    $codigoTemBackup = (stripos($codigo, 'statusBackup') !== false)
        || (bool) preg_match('/\$\w+\s*=\s*\$status\b/', $codigo);
    $codigoAltera3 = (bool) preg_match('/\$status\s*\[\s*3\s*\]\s*=/', $codigo);

    if ($codigoTemBackup || stripos($codigo, 'backup') !== false) {
        $acertos[] = "Há indício de cópia/backup do array no código (item 4).";
    } else {
        $erros[] = "No código, falta a cópia do \$status antes de alterar o índice (item 4 do enunciado).";
    }

    if (desafio_extra_tem_frase_diretor($codigo) || (!$soCodigo && desafio_extra_tem_frase_diretor($saida))) {
        $erros[] = "Cuidado no item 4: a frase do diretor é só o contexto. "
            . "Atualize \$status[3] para o valor de status indicado no enunciado — não cole a frase do pedido como texto do status.";
    } elseif ($codigoAltera3) {
        $acertos[] = "Você altera a posição [3] no código (item 4) — confira se o valor é o do enunciado, não a frase do diretor.";
    } else {
        $erros[] = "Não vi a atualização de \$status[3] no código (item 4). Só essa posição deve mudar, após a cópia.";
    }

    if (!$soCodigo) {
        $temBackupLinha = false;
        $temAtualLinha = false;
        foreach ($naoVazias as $l) {
            if (stripos($l, 'BACKUP STATUS [3]') !== false) {
                $temBackupLinha = true;
            }
            if (stripos($l, 'STATUS ATUAL [3]') !== false) {
                $temAtualLinha = true;
            }
        }
        if ($temBackupLinha && $temAtualLinha) {
            $acertos[] = "As duas linhas de BACKUP / STATUS ATUAL [3] aparecem (item 5). Confira se os valores batem com o enunciado.";
        } else {
            $erros[] = "Faltam as linhas BACKUP STATUS [3] e/ou STATUS ATUAL [3] na ordem do item 5.";
        }
    } elseif (stripos($codigo, 'BACKUP STATUS [3]') !== false && stripos($codigo, 'STATUS ATUAL [3]') !== false) {
        $acertos[] = "No código você imprime BACKUP e STATUS ATUAL [3] (item 5).";
    }

    /* ---- itens 6 e 7 ---- */
    $temBannersHdr = false;
    $temSlugsHdr = false;
    foreach ($naoVazias as $l) {
        if (preg_match('/^BANNERS:\s*$/i', $l)) {
            $temBannersHdr = true;
        }
        if (preg_match('/^SLUGS:\s*$/i', $l)) {
            $temSlugsHdr = true;
        }
    }

    $linhasSetaDupla = array();
    foreach ($naoVazias as $l) {
        if (preg_match('/^>>\s*(.*)$/u', $l, $m)) {
            $linhasSetaDupla[] = trim($m[1]);
        }
    }
    $setasComConteudo = 0;
    $setasVaziasOuTitulo = 0;
    foreach ($linhasSetaDupla as $resto) {
        if ($resto === '' || preg_match('/^(banners|slugs|nome em mai|nome em min|mai[uú]sculas|min[uú]sculas)/iu', $resto)) {
            $setasVaziasOuTitulo++;
        } else {
            $setasComConteudo++;
        }
    }

    $temMap = (bool) preg_match('/\barray_map\b/i', $codigo);
    $temUpper = (bool) preg_match('/\bstrtoupper\b/i', $codigo);
    $temLower = (bool) preg_match('/\bstrtolower\b/i', $codigo);

    if ($temMap && $temUpper && $temLower) {
        $acertos[] = "array_map com strtoupper e strtolower aparece no código — caminho certo para os itens 6 e 7.";
    } elseif ($temMap && ($temUpper || $temLower)) {
        $acertos[] = "array_map está no código com uma das transformações; complete maiúsculas e minúsculas (itens 6 e 7).";
    } elseif ($temMap) {
        $acertos[] = "array_map está no código; complete com strtoupper e strtolower conforme itens 6 e 7.";
    } else {
        $erros[] = "No código, não achei array_map (itens 6 e 7). O enunciado pede array_map + strtoupper e array_map + strtolower.";
    }

    $codigoTemBanners = (bool) preg_match('/BANNERS\s*:/i', $codigo);
    $codigoTemSlugs = (bool) preg_match('/SLUGS\s*:/i', $codigo);
    $codigoSetaComoTitulo = (bool) preg_match('/echo\s+[\'"][^\'"]*>>\s*(NOME EM|nome em)/iu', $codigo)
        || (bool) preg_match('/[\'"]\s*>>\s*NOME EM MAI/iu', $codigo)
        || (bool) preg_match('/[\'"]\s*>>\s*nome em min/iu', $codigo);

    if ($soCodigo) {
        if (!$codigoTemBanners) {
            $erros[] = "Falta o cabeçalho BANNERS: no echo (item 6). Ele deve vir sozinho numa linha, antes dos nomes.";
        } else {
            $acertos[] = "No código há BANNERS: (item 6).";
        }
        if (!$codigoTemSlugs) {
            $erros[] = "Falta o cabeçalho SLUGS: no echo (item 7).";
        } else {
            $acertos[] = "No código há SLUGS: (item 7).";
        }
        if ($codigoSetaComoTitulo) {
            $erros[] = "O >> está virando título de seção (\">> NOME EM…\"). Nos itens 6 e 7, >> deve preceder cada nome transformado, um por linha.";
        }
    } else {
        if (!$temBannersHdr) {
            $erros[] = "Falta o cabeçalho BANNERS: (item 6). Ele deve vir sozinho numa linha, antes dos nomes em maiúsculas.";
        } else {
            $acertos[] = "Cabeçalho BANNERS: encontrado (item 6).";
        }
        if (!$temSlugsHdr) {
            $erros[] = "Falta o cabeçalho SLUGS: (item 7). Mesma ideia: linha própria, depois os nomes em minúsculas.";
        } else {
            $acertos[] = "Cabeçalho SLUGS: encontrado (item 7).";
        }
        if ($setasVaziasOuTitulo > 0 && $setasComConteudo < 8) {
            $erros[] = "O >> está sendo usado quase como título de seção, mas o enunciado (itens 6 e 7) pede "
                . ">> seguido do nome transformado — um projeto por linha, na ordem original.";
        } elseif ($setasComConteudo > 0 && $setasComConteudo < 10) {
            $erros[] = "Há linhas com >>, porém ainda não fecham os 10 nomes de BANNERS/SLUGS (itens 6 e 7).";
        } elseif ($setasComConteudo >= 10 && $temBannersHdr && $temSlugsHdr) {
            $acertos[] = "Há várias linhas >> com nomes — bom para banners/slugs; confira maiúsculas vs minúsculas e a ordem.";
        } elseif ($setasComConteudo === 0 && ($temMap || $temBannersHdr || $temSlugsHdr)) {
            $erros[] = "Depois de BANNERS:/SLUGS:, cada nome deve sair com o prefixo >> (itens 6 e 7).";
        }
    }

    if (empty($acertos) && empty($erros)) {
        $erros[] = "A saída ainda não fecha com o pedido do diretor. Revise os itens 1 a 7 do enunciado, linha a linha.";
    }

    $nErr = count($erros);
    $nOk = count($acertos);
    if ($nErr === 0) {
        $message = "Quase no ponto, {$nome}! Tem acertos claros, mas ainda falta alinhar algum detalhe com o enunciado.";
    } elseif ($nOk > 0) {
        $message = "Boa tentativa, {$nome}! Vi {$nOk} ponto(s) no caminho e {$nErr} ajuste(s). Olha o relatório abaixo — sem entregar a resposta pronta.";
    } else {
        $message = "Calma, {$nome}! O relatório Extra ainda não fecha. Use os apontamentos e os itens do enunciado como checklist.";
    }

    return array(
        'acertos' => $acertos,
        'erros' => $erros,
        'message' => $message,
    );
}
