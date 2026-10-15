@if (\App\Support\ViewAs::isPreview())
    <div class="view-as-banner" role="status">
        <p>
            Você está vendo como <strong>{{ \App\Support\ViewAs::label(\App\Support\ViewAs::effectiveRole()) }}</strong>.
            Seu papel real continua <strong>{{ \App\Support\ViewAs::label(\App\Support\ViewAs::realRole()) }}</strong>.
            Esta prévia não grava progresso e não altera a conta do Leandro.
        </p>
        <div class="view-as-banner__actions">
            <a href="{{ route('view-as.edit') }}">Mudar papel</a>
            <form method="POST" action="{{ route('view-as.update') }}">
                @csrf
                <input type="hidden" name="role" value="self">
                <button type="submit">Voltar ao meu papel</button>
            </form>
        </div>
    </div>
    <style>
        .view-as-banner {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
            padding: 0.7rem 1.1rem;
            background: #fef3c7;
            color: #78350f;
            border-bottom: 1px solid #fcd34d;
            font-size: 0.92rem;
        }
        .view-as-banner p { margin: 0; }
        .view-as-banner__actions { display: flex; gap: 0.6rem; align-items: center; }
        .view-as-banner a,
        .view-as-banner button {
            border: 0;
            background: #042047;
            color: #fff;
            border-radius: 999px;
            padding: 0.35rem 0.8rem;
            font: inherit;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
@endif
