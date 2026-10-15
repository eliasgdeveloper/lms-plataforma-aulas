<?php
require_once __DIR__ . '/auth/bootstrap.php';
plm_start_session();

if (plm_is_logged_in()) {
    header('Location: ' . plm_url('bemvindo.php'));
    exit;
}

$error = plm_consume_flash();
$csrf = plm_csrf_token();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>Em breve · Elias Gomes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;1,600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="CSS_Estilo/Login/login.css">
</head>
<body>
    <div class="scene">
        <div class="slides" aria-hidden="true">
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
        </div>
        <div class="veil"></div>
        <div class="grain"></div>
        <div class="orbs" aria-hidden="true">
            <div class="orb orb-a"></div>
            <div class="orb orb-b"></div>
            <div class="orb orb-c"></div>
            <div class="glass-panel one"></div>
            <div class="glass-panel two"></div>
        </div>
        <canvas id="network"></canvas>

        <main class="shell">
            <section class="copy">
                <div class="brand-row">
                    <p class="eyebrow">Elias Gomes</p>
                    <span class="badge">Em breve</span>
                </div>

                <h1 class="title">
                    <span>Em breve, aqui</span>
                    <span>o projeto de Elias Gomes</span>
                    <span class="role">engenheiro de software <em>e professor</em></span>
                </h1>

                <p class="lead">
                    Uma plataforma para aprender, construir e publicar na área de tecnologia.
                </p>
                <ul class="offers">
                    <li>Cursos de programação e design</li>
                    <li>Desenvolvimento de software</li>
                    <li>Planos de hospedagem de sites e projetos</li>
                    <li>Informação relevante da área de tecnologia</li>
                </ul>
                <p class="credit">Elias Gomes · Engenheiro de Software e Professor</p>
            </section>

            <form class="card login-form" method="post" action="auth/login.php" autocomplete="on">
                <h2>Acesso reservado</h2>
                <p>Entre com o usuário e a senha da sua conta.</p>

                <div class="alert" <?php echo $error === '' ? 'hidden' : ''; ?>>
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>

                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">

                <label for="usuario">Usuário</label>
                <div class="field">
                    <i class="fa-solid fa-user"></i>
                    <input id="usuario" name="usuario" type="text" required autofocus placeholder="Seu nome de acesso">
                </div>

                <label for="senha">Senha</label>
                <div class="field">
                    <i class="fa-solid fa-lock"></i>
                    <input id="senha" name="senha" type="password" required placeholder="Digite sua senha">
                    <button class="toggle-pass" type="button" aria-label="Mostrar senha">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <button type="submit">Acessar o projeto</button>
                <p class="footnote">Sessão protegida. Visitantes sem credencial não visualizam o conteúdo interno.</p>
            </form>
        </main>
    </div>
    <script src="Javascript/Login/login.js"></script>
</body>
</html>
