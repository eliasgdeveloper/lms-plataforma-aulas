<section
    class="php-typing"
    data-php-typing
    data-target="{{ $typingExercise['code'] }}"
    data-progress-url="{{ route('conteudos.progress', $conteudo) }}"
    data-csrf="{{ csrf_token() }}"
>
    <div data-step="typing">
        <div class="php-typing__target" data-target-area tabindex="0" role="group" aria-label="Área interativa do exercício. Clique e digite o trecho exibido.">
            <pre aria-label="Texto para digitar"><code data-target-display></code></pre>
        </div>
        <div class="php-typing__progress" role="progressbar" aria-label="Progresso da digitação" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
            <span data-progress-bar></span>
        </div>
        <div class="php-typing__meta">
            <p data-typing-feedback aria-live="polite">Clique na área de texto e comece a digitar — o cronômetro inicia na primeira tecla.</p>
            <div class="php-typing__stats">
                <span>Tempo: <strong data-timer>00:00</strong></span>
                <span>Precisão: <strong data-accuracy>100%</strong></span>
                <span>Velocidade: <strong data-speed>0 PPM</strong></span>
                <span>Meta: <strong><span data-typed-count>0</span>/<span data-target-count>0</span> caracteres</strong></span>
            </div>
        </div>

        <textarea id="php-typing-input" class="php-typing__input-proxy" data-typing-input rows="1" spellcheck="false" autocapitalize="off" autocomplete="off" autocorrect="off" tabindex="-1" aria-label="Entrada do treino de digitação"></textarea>
        <p class="php-typing__hint">Clique no trecho acima e digite diretamente nele. Cada ↵ marca uma quebra de linha: pressione Enter. O cronômetro começa na primeira tecla.</p>

        <div class="php-typing__hands-panel">
            <div class="php-typing__hands-heading">
                <div>
                    <h3>Dedo da próxima tecla</h3>
                    <p data-finger-hint>A indicação aparece quando você começar.</p>
                </div>
                <span class="php-typing__legend"><i></i> dedo recomendado</span>
            </div>
            <div class="php-typing__hands">
                <div class="php-typing__hand-group">
                    <span>MÃO ESQUERDA</span>
                    <div class="php-typing__hand" data-hand="left" aria-label="Dedos da mão esquerda">
                        <span data-finger="left-pinky">Mínimo</span>
                        <span data-finger="left-ring">Anelar</span>
                        <span data-finger="left-middle">Médio</span>
                        <span data-finger="left-index">Indicador</span>
                        <span data-finger="left-thumb">Polegar</span>
                    </div>
                </div>
                <div class="php-typing__hand-group">
                    <span>MÃO DIREITA</span>
                    <div class="php-typing__hand" data-hand="right" aria-label="Dedos da mão direita">
                        <span data-finger="right-thumb">Polegar</span>
                        <span data-finger="right-index">Indicador</span>
                        <span data-finger="right-middle">Médio</span>
                        <span data-finger="right-ring">Anelar</span>
                        <span data-finger="right-pinky">Mínimo</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="php-typing__keyboard-heading">
            <label for="php-keyboard-layout">Layout do teclado</label>
            <select id="php-keyboard-layout" data-keyboard-layout>
                <option value="auto">Detectar do sistema (se disponível)</option>
                <option value="abnt2">Português (Brasil) · ABNT2</option>
                <option value="abnt">Português (Brasil) · ABNT</option>
                <option value="pt">Português (Portugal) · QWERTY</option>
                <option value="us">Inglês (EUA) · QWERTY</option>
                <option value="us-intl">Inglês (EUA) · Internacional</option>
                <option value="uk">Inglês (Reino Unido) · QWERTY</option>
                <option value="dvorak">Inglês (EUA) · Dvorak</option>
                <option value="colemak">Inglês · Colemak</option>
                <option value="fr">Francês · AZERTY</option>
                <option value="be">Belga · AZERTY</option>
                <option value="de">Alemão · QWERTZ</option>
                <option value="ch-de">Suíço (alemão) · QWERTZ</option>
                <option value="ch-fr">Suíço (francês) · QWERTZ</option>
                <option value="es">Espanhol · QWERTY</option>
                <option value="it">Italiano · QWERTY</option>
                <option value="tr">Turco · Q</option>
                <option value="ru">Russo · ЙЦУКЕН</option>
                <option value="jp">Japonês · JIS</option>
            </select>
        </div>
        <div class="php-typing__keyboard" data-virtual-keyboard aria-label="Teclado virtual"></div>

        <div class="php-typing__actions">
            <button type="button" data-next-step disabled>Ir para o exercício</button>
        </div>
    </div>

    <div data-step="recall" hidden>
        <div class="php-typing__recall">
            <h3>Exercício: escreva o exemplo sem consultar o modelo</h3>
            <p>Use a memória para reconstruir o código desta aula. Se precisar, volte para rever o trecho.</p>
        </div>
        <label class="php-typing__input-label" for="php-recall-input">Reconstrua o trecho PHP</label>
        <textarea id="php-recall-input" data-recall-input rows="8" spellcheck="false" autocapitalize="off" autocomplete="off" placeholder="Escreva o código que acabou de praticar..."></textarea>
        <div class="php-typing__actions">
            <button type="button" data-check-recall>Conferir exercício</button>
            <button type="button" data-back-step>Rever o trecho</button>
        </div>
        <p class="php-typing__feedback" data-recall-feedback aria-live="polite"></p>
    </div>

    <div data-step="complete" hidden role="status">
        <div class="php-typing__complete">
            <h3>Treino e exercício concluídos!</h3>
            <p>Seu progresso foi salvo. Agora volte aos conteúdos desta aula para abrir os exercícios, desafios e materiais disponíveis.</p>
            <a href="{{ route('conteudos.index', $aula) }}">Continuar para os exercícios da aula</a>
        </div>
    </div>
</section>
