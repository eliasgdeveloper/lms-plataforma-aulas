<section class="copy">
    <div class="hero-intro">
        <figure class="portrait portrait--hero">
            <img
                src="{{ asset('codar-entrada/img/elias-gomes.jpg') }}"
                width="168"
                height="168"
                alt="Elias Gomes — engenheiro de software e professor"
                loading="eager"
                decoding="async"
            >
        </figure>

        <div class="hero-intro__text">
            <div class="brand-row">
                <div class="brand-lockup">
                    <img class="brand-mark" src="{{ asset('codar-entrada/img/brand-mark.svg') }}" width="40" height="40" alt="LMS Codar">
                    <p class="eyebrow">Plataforma de Aulas</p>
                </div>
                <span class="badge">Curso de Codar</span>
            </div>
            <h1 class="title">
                <span>Aprenda, construa</span>
                <span>e publique</span>
                <span class="role">com Elias Gomes, <em>engenheiro de software e professor</em> de Design e programação</span>
            </h1>
            <p class="lead">
                Aqui o conhecimento vira produto: aulas, arenas, LMS e sistemas reais —
                da primeira linha de código ao deploy. Mais de 35 anos transformando ideia em resultado.
            </p>
        </div>
    </div>

    <div class="hero-actions">
        @auth
            <a class="cta-primary" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
                Ir para minha área
            </a>
        @else
            <a class="cta-primary" href="{{ route('login') }}">
                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                Entrar na plataforma
            </a>
        @endauth
        <a class="cta-whatsapp" href="https://wa.me/5532998369193" target="_blank" rel="noopener">
            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
            Falar no WhatsApp
        </a>
        <a class="cta-ghost" href="#em-breve">
            Ver o que vem por aí
            <i class="fa-solid fa-arrow-down" aria-hidden="true"></i>
        </a>
    </div>
    <ul class="offers">
        <li>Cursos de programação e Design</li>
        <li>Plataformas LMS e aulas online</li>
        <li>Software robusto, SaaS e automação</li>
        <li>Hospedagem, bots de IA e sistemas inteligentes</li>
    </ul>
    <p class="credit">Elias Gomes · Full Stack · Dados · UX/UI · Cyber Segurança · Embaixador Superprof</p>
</section>
