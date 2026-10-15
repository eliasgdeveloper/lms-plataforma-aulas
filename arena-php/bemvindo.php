<?php
require_once __DIR__ . '/auth/bootstrap.php';
require_once __DIR__ . '/desafio/progress_store.php';
plm_start_session();
plm_require_login_or_lms_sso('bemvindo.php');

$user = plm_current_user();
$username = isset($user['username']) ? $user['username'] : '';
$name = isset($user['name']) && $user['name'] !== '' ? $user['name'] : $username;
$role = isset($user['role']) ? $user['role'] : '';

$progresso = desafio_progress_boot();
$pontos = (int) $progresso['pontos'];
$concluidas = count($progresso['concluidas']);
$extraDone = !empty($progresso['extra_done']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>Bem-vindo · Elias Gomes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 28px 18px;
            font-family: "Manrope", sans-serif;
            color: #f5f7fb;
            background:
                radial-gradient(ellipse at 18% 8%, rgba(212, 180, 90, 0.16), transparent 42%),
                linear-gradient(160deg, #06101f, #0c1b33 55%, #071426);
        }
        main {
            width: min(640px, 100%);
            padding: 36px 32px 28px;
            border-radius: 24px;
            background: rgba(8, 18, 36, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
        }
        p.eyebrow {
            letter-spacing: 0.22em;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 700;
            color: #d4b45a;
            margin-bottom: 14px;
        }
        h1 {
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(36px, 6vw, 52px);
            font-weight: 600;
            line-height: 1.05;
            margin-bottom: 8px;
        }
        .who {
            color: rgba(245, 247, 251, 0.72);
            font-size: 14px;
            margin-bottom: 22px;
        }
        .text {
            color: rgba(245, 247, 251, 0.86);
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 18px;
        }
        .score {
            margin-bottom: 22px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid rgba(212, 180, 90, 0.28);
            background: rgba(212, 180, 90, 0.08);
            color: #f0e2b0;
            font-size: 13px;
            font-weight: 700;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        a {
            display: inline-block;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.04em;
            padding: 12px 18px;
            border-radius: 14px;
            background: linear-gradient(90deg, #0150f9, #3d7bff);
        }
        a.ghost {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.16);
        }
    </style>
</head>
<body>
    <main>
        <p class="eyebrow">Área do aluno</p>
        <h1>Bem-vindo, <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="who">Usuário: <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?><?php echo $role !== '' ? ' · ' . htmlspecialchars($role, ENT_QUOTES, 'UTF-8') : ''; ?></p>
        <p class="text">
            <?php
            $primeiro = explode(' ', trim($name))[0];
            if (strcasecmp($primeiro, 'Leandro') === 0) {
                echo 'Leandro, a Arena PHP está pronta pra você: missões, pontos, sons e surpresas a cada acerto. Bora mostrar do que o foreach é capaz?';
            } else {
                echo 'Hoje o foco é PHP básico, com missão gamificada: pontos, avatares e surpresas a cada etapa correta.';
            }
            ?>
        </p>
        <div class="score">Arena PHP · <?php echo (int) $pontos; ?> pts · <?php echo (int) $concluidas; ?>/8 missões<?php echo $extraDone ? ' · <i class="fa-solid fa-star" aria-hidden="true"></i> Extra' : ''; ?> · progresso salvo</div>
        <div class="actions">
            <a href="desafio.php">Entrar no desafio PHP (Arena 1)</a>
            <a href="desafio_sprint2.php" style="margin-left:10px">Arena Sprint 2 · War Room</a>
            <a class="ghost" href="auth/logout.php">Sair</a>
        </div>
    </main>
</body>
</html>
