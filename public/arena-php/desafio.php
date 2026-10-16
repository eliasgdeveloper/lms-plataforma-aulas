<?php
require_once __DIR__ . '/auth/bootstrap.php';
require_once __DIR__ . '/desafio/progress_store.php';
plm_start_session();
plm_require_login_or_lms_sso('desafio.php');

$user = plm_current_user();
$name = isset($user['name']) && $user['name'] !== '' ? $user['name'] : $user['username'];
$csrf = plm_csrf_token();
$etapas = require __DIR__ . '/desafio/etapas.php';
$extra = require __DIR__ . '/desafio/extra.php';

$progresso = desafio_progress_boot();

$publicEtapas = array();
foreach ($etapas as $etapa) {
    $publicEtapas[] = array(
        'id' => (int) $etapa['id'],
        'titulo' => $etapa['titulo'],
        'tema' => $etapa['tema'],
        'pontos' => (int) $etapa['pontos'],
        'enunciado' => $etapa['enunciado'],
        'dica' => $etapa['dica'],
        'starter' => $etapa['starter'],
        'surpresa' => $etapa['surpresa'],
    );
}

$extraUnlocked = true;
foreach ($etapas as $etapa) {
    if (!in_array((int) $etapa['id'], $progresso['concluidas'], true)) {
        $extraUnlocked = false;
        break;
    }
}

$isProfessor = false;
$uname = isset($user['username']) ? (string) $user['username'] : '';
$urole = isset($user['role']) ? (string) $user['role'] : '';
if (strcasecmp($uname, 'Elias') === 0 || ($urole !== '' && stripos($urole, 'Professor') !== false)) {
    $isProfessor = true;
}
if ($isProfessor) {
    $extraUnlocked = true;
}

$payload = array(
    'csrf' => $csrf,
    'aluno' => $name,
    'checkUrl' => plm_url('desafio/check.php'),
    'progressUrl' => plm_url('desafio/progresso.php'),
    'etapas' => $publicEtapas,
    'extra' => array(
        'id' => 'extra',
        'titulo' => $extra['titulo'],
        'tema' => $extra['tema'],
        'pontos' => (int) $extra['pontos'],
        'enunciado' => $extra['enunciado'],
        'dica' => $extra['dica'],
        'starter' => $extra['starter'],
        'surpresa' => $extra['surpresa'],
    ),
    'progresso' => array(
        'pontos' => (int) $progresso['pontos'],
        'concluidas' => $progresso['concluidas'],
        'surpresas' => $progresso['surpresas'],
        'avatar' => $progresso['avatar'],
        'extra_done' => !empty($progresso['extra_done']),
        'extra_unlocked' => $extraUnlocked,
    ),
);
$isEmbed = isset($_GET['embed']) && (string) $_GET['embed'] === '1';
$backToCourse = isset($_GET['back']) ? (string) $_GET['back'] : '/aluno/cursos';
if ($backToCourse === '' || $backToCourse[0] !== '/' || str_starts_with($backToCourse, '//') || str_contains($backToCourse, '://')) {
    $backToCourse = '/aluno/cursos';
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="<?php echo $isEmbed ? 'is-embed' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>Arena 1 · Curso de Codar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS_Estilo/Desafio/desafio.css?v=20261002a">
</head>
<body class="<?php echo $isEmbed ? 'is-embed' : ''; ?>">
    <header class="topbar">
        <div class="brand">
            <div class="avatar" id="avatar" aria-hidden="true"><i class="fa-solid fa-seedling"></i></div>
            <div>
                <h1>Arena 1 · <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p>8 missões + Desafio Extra · feito pra você, <?php echo htmlspecialchars(explode(' ', trim($name))[0], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
        <div class="stats">
            <span class="pill gold" id="pontos">0 pts</span>
            <span class="pill" id="progresso">0/8 missões</span>
            <span class="pill saved" id="save-pill" title="Progresso gravado no servidor">salvo</span>
            <?php if (!$isEmbed): ?>
            <span class="pill"><a href="<?php echo htmlspecialchars($backToCourse, ENT_QUOTES, 'UTF-8'); ?>">Voltar ao curso</a></span>
            <span class="pill"><a href="<?php echo htmlspecialchars($backToCourse, ENT_QUOTES, 'UTF-8'); ?>#arena">Módulo da Arena</a></span>
            <span class="pill"><a href="/aluno/cursos">Meus cursos</a></span>
            <?php endif; ?>
            <span class="pill"><a href="bemvindo.php">Painel Arena</a></span>
            <span class="pill"><a href="auth/logout.php">Sair</a></span>
        </div>
    </header>

    <div class="layout">
        <aside class="side">
            <h2>Missões</h2>
            <ul class="mission-list" id="mission-list"></ul>

            <div class="extra-gate" id="extra-gate">
                <p class="extra-label">Desafio Extra</p>
                <button type="button" class="extra-card locked" id="btn-extra" disabled>
                    <span class="extra-ico" id="extra-ico"><i class="fa-solid fa-lock" aria-hidden="true"></i></span>
                    <strong id="extra-title">Relatório do Diretor</strong>
                    <span class="meta" id="extra-meta">Bloqueado · termine as 8 missões</span>
                </button>
            </div>

            <div style="margin-top:14px">
                <button class="ghost" id="btn-reset-game" type="button" style="width:100%">Zerar progresso</button>
            </div>
        </aside>

        <section class="main">
            <p class="eyebrow" id="mission-eyebrow">Desafio ao vivo</p>
            <h2 id="mission-title">Missão</h2>
            <p class="tema" id="mission-tema"></p>
            <p class="coach" id="mission-coach"></p>
            <div class="enunciado" id="mission-enunciado"></div>
            <p class="dica" id="mission-dica"></p>

            <label for="codigo">Cole ou escreva seu código PHP</label>
            <textarea id="codigo" spellcheck="false"></textarea>

            <label for="saida">Cole a saída que o código imprime (rode no XAMPP, Hostinger ou onlinephp.io)</label>
            <textarea id="saida" spellcheck="false" placeholder="Exatamente o texto que apareceria no echo..."></textarea>

            <div class="actions">
                <button class="primary" id="btn-check" type="button">Validar missão</button>
                <button class="ghost" id="btn-hint" type="button">Ver dica</button>
                <button class="ghost" id="btn-reset-fields" type="button">Restaurar modelo</button>
            </div>
            <div class="feedback" id="feedback"></div>

            <div class="rewards" id="rewards">
                <div class="reward-card" data-reward="avatar_fox"><div class="ico"><i class="fa-solid fa-user-astronaut" aria-hidden="true"></i></div><div class="t">Raposa Coder</div></div>
                <div class="reward-card" data-reward="badge_contador"><div class="ico"><i class="fa-solid fa-hashtag" aria-hidden="true"></i></div><div class="t">Contador Oficial</div></div>
                <div class="reward-card" data-reward="avatar_robot"><div class="ico"><i class="fa-solid fa-robot" aria-hidden="true"></i></div><div class="t">Robô PHP</div></div>
                <div class="reward-card" data-reward="badge_indice"><div class="ico"><i class="fa-solid fa-crosshairs" aria-hidden="true"></i></div><div class="t">Mestre dos Índices</div></div>
                <div class="reward-card" data-reward="avatar_wizard"><div class="ico"><i class="fa-solid fa-hat-wizard" aria-hidden="true"></i></div><div class="t">Mago do Código</div></div>
                <div class="reward-card" data-reward="badge_map"><div class="ico"><i class="fa-solid fa-map" aria-hidden="true"></i></div><div class="t">Array Map Hero</div></div>
                <div class="reward-card" data-reward="avatar_ninja"><div class="ico"><i class="fa-solid fa-user-ninja" aria-hidden="true"></i></div><div class="t">Ninja dos Loops</div></div>
                <div class="reward-card" data-reward="trophy"><div class="ico"><i class="fa-solid fa-trophy" aria-hidden="true"></i></div><div class="t">Campeão PHP</div></div>
                <div class="reward-card reward-extra" data-reward="estrela_extra"><div class="ico"><i class="fa-solid fa-star" aria-hidden="true"></i></div><div class="t">Estrela Extra</div></div>
            </div>
        </section>
    </div>

    <div class="overlay" id="overlay">
        <div class="modal" id="modal-box">
            <div class="big" id="modal-icon"><i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i></div>
            <h3 id="modal-title">Surpresa!</h3>
            <p id="modal-text"></p>
            <button class="primary" id="btn-close-modal" type="button">Continuar</button>
        </div>
    </div>
    <canvas id="confetti"></canvas>

    <script>
        window.DESAFIO_DATA = <?php echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    </script>
    <script src="Javascript/Desafio/desafio.js?v=20260930arena2"></script>
</body>
</html>
