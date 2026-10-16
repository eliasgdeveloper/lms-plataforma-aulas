function initializePhpTypingExercises(root) {
    const exercises = [];
    if (root instanceof Element && root.matches('[data-php-typing]')) {
        exercises.push(root);
    }
    if (typeof root.querySelectorAll === 'function') {
        exercises.push(...root.querySelectorAll('[data-php-typing]'));
    }

    exercises.forEach((exercise) => {
        if (exercise.dataset.initialized === 'true') {
            return;
        }

        const target = exercise.dataset.target.replace(/\r\n/g, '\n');
        const typingInput = exercise.querySelector('[data-typing-input]');
        const recallInput = exercise.querySelector('[data-recall-input]');
        const nextButton = exercise.querySelector('[data-next-step]');
        const checkButton = exercise.querySelector('[data-check-recall]');
        const typingStep = exercise.querySelector('[data-step="typing"]');
        const recallStep = exercise.querySelector('[data-step="recall"]');
        const completeStep = exercise.querySelector('[data-step="complete"]');
        const typingFeedback = exercise.querySelector('[data-typing-feedback]');
        const recallFeedback = exercise.querySelector('[data-recall-feedback]');
        const accuracyOutput = exercise.querySelector('[data-accuracy]');
        const speedOutput = exercise.querySelector('[data-speed]');
        const timerOutput = exercise.querySelector('[data-timer]');
        const typedCountOutput = exercise.querySelector('[data-typed-count]');
        const targetCountOutput = exercise.querySelector('[data-target-count]');
        const targetDisplay = exercise.querySelector('[data-target-display]');
        const progressBar = exercise.querySelector('[data-progress-bar]');
        const progressTrack = exercise.querySelector('[data-progress-bar]')?.parentElement;
        const fingerHint = exercise.querySelector('[data-finger-hint]');
        const virtualKeyboard = exercise.querySelector('[data-virtual-keyboard]');
        const keyboardLayout = exercise.querySelector('[data-keyboard-layout]');
        const targetArea = exercise.querySelector('[data-target-area]');
        const backButton = exercise.querySelector('[data-back-step]');
        if (
            !typingInput || !recallInput || !nextButton || !checkButton || !typingStep
            || !recallStep || !completeStep || !typingFeedback || !recallFeedback
            || !accuracyOutput || !speedOutput || !timerOutput || !typedCountOutput || !targetCountOutput
            || !targetDisplay || !progressBar || !progressTrack || !fingerHint || !virtualKeyboard
            || !keyboardLayout || !targetArea || !backButton
        ) {
            return;
        }

        exercise.dataset.initialized = 'true';

        const normalize = (value) => value.replace(/\r\n/g, '\n');
        const characters = Array.from(target);
        const fingerNames = {
            'left-pinky': 'mínimo da mão esquerda',
            'left-ring': 'anelar da mão esquerda',
            'left-middle': 'médio da mão esquerda',
            'left-index': 'indicador da mão esquerda',
            'left-thumb': 'polegar da mão esquerda',
            'right-pinky': 'mínimo da mão direita',
            'right-ring': 'anelar da mão direita',
            'right-middle': 'médio da mão direita',
            'right-index': 'indicador da mão direita',
            'right-thumb': 'polegar da mão direita',
        };
        const layouts = {
            abnt2: { rows: ['qwertyuiop', 'asdfghjklç', 'zxcvbnm'], numbers: '1234567890', punctuation: ['´[', '~]', '\\,.;/'] },
            abnt: { rows: ['qwertyuiop', 'asdfghjklç', 'zxcvbnm'], numbers: '1234567890', punctuation: ['´[', '~]', '\\,.;/'] },
            pt: { rows: ['qwertyuiop', 'asdfghjklç', 'zxcvbnm'], numbers: '1234567890', punctuation: ['´+', '~*', '<,.;-'] },
            us: { rows: ['qwertyuiop', 'asdfghjkl', 'zxcvbnm'], numbers: '1234567890', punctuation: ['-[', ';\'', '`\\,./'] },
            'us-intl': { rows: ['qwertyuiop', 'asdfghjkl', 'zxcvbnm'], numbers: '1234567890', punctuation: ['-[', ';\'', '`\\,./'] },
            uk: { rows: ['qwertyuiop', 'asdfghjkl', 'zxcvbnm'], numbers: '1234567890', punctuation: ['-=', ';\'', '#\\,./'] },
            dvorak: { rows: ["',.pyfgcrl", 'aoeuidhtns', ';qjkxbmwvz'], numbers: '1234567890', punctuation: ['[]', '=-', '/\\,.'] },
            colemak: { rows: ['qwfpgjluy;', 'arstdhneio', 'zxcvbkm,.'], numbers: '1234567890', punctuation: ['-[', ';\'', '`\\,./'] },
            fr: { rows: ['azertyuiop', 'qsdfghjklm', 'wxcvbn'], numbers: '1234567890', punctuation: ['^$', 'ù*', '<,;:!'] },
            be: { rows: ['azertyuiop', 'qsdfghjklm', 'wxcvbn'], numbers: '1234567890', punctuation: ['^$', 'ù*', '<,;:!'] },
            de: { rows: ['qwertzuiop', 'asdfghjklö', 'yxcvbnm'], numbers: '1234567890', punctuation: ['ü+', 'ä#', '<,.-'] },
            'ch-de': { rows: ['qwertzuiop', 'asdfghjklö', 'yxcvbnm'], numbers: '1234567890', punctuation: ['ü¨', 'ä$', '<,.-'] },
            'ch-fr': { rows: ['qwertzuiop', 'asdfghjklè', 'yxcvbnm'], numbers: '1234567890', punctuation: ['¨^', 'éà', '<,.-'] },
            es: { rows: ['qwertyuiop', 'asdfghjklñ', 'zxcvbnm'], numbers: '1234567890', punctuation: ['\'¡', '´+', '<,.-'] },
            it: { rows: ['qwertyuiop', 'asdfghjklò', 'zxcvbnm'], numbers: '1234567890', punctuation: ['ì+', 'èà', '<,.-'] },
            tr: { rows: ['fgğıodrnhp', 'uièaütmkly', 'jövczş'], numbers: '1234567890', punctuation: ['ü,', 'ş', ',-.'] },
            ru: { rows: ['йцукенгшщзх', 'фывапролджэ', 'ячсмитьбю'], numbers: '1234567890', punctuation: ['ъ', 'жэ', 'ё'] },
            jp: { rows: ['qwertyuiop', 'asdfghjkl', 'zxcvbnm'], numbers: '1234567890', punctuation: ['@[]', ':;', ']\\,./'] },
        };
        let detectedLayout = null;
        const fingerForPosition = (row, index) => {
            if (row === 4) return 'left-thumb';
            if (index === 0 || (row === 0 && index === 0)) return 'left-pinky';
            if (index === 1) return 'left-ring';
            if (index === 2) return 'left-middle';
            if (index >= 3 && index <= 5) return 'left-index';
            if (index >= 6 && index <= 8) return 'right-index';
            if (index === 9) return 'right-middle';
            if (index === 10) return 'right-ring';
            return 'right-pinky';
        };
        const keyboardRows = (layoutName) => {
            const layout = layouts[layoutName] || layouts.abnt2;
            const numberRows = layoutName === 'us' || layoutName === 'us-intl' || layoutName === 'uk'
                ? ['!@#$%^&*()', '!@#$%^&*()']
                : ['!@#$%¨&*()', '!@#$%¨&*()'];
            const keys = (chars, shifted, rowIndex) => Array.from(chars).map((char, index) => ({
                label: char,
                characters: [char, Array.from(shifted)[index] || char.toUpperCase()],
                finger: fingerForPosition(rowIndex, index),
            }));
            return [
                [
                    ...keys(layout.numbers, numberRows[0], 0),
                    ...keys('-=', '_+', 0),
                    { label: '⌫', characters: ['Backspace'], finger: 'right-pinky', special: 'backspace' },
                ],
                [
                    { label: 'Tab', characters: ['Tab'], finger: 'left-pinky', special: 'tab' },
                    ...keys(layout.rows[0], layout.rows[0].toUpperCase(), 1),
                    ...keys(layout.punctuation[0], layout.punctuation[0].toUpperCase(), 1),
                    { label: 'Del', characters: ['Delete'], finger: 'right-pinky', special: 'delete' },
                ],
                [
                    { label: 'Caps', characters: ['CapsLock'], finger: 'left-pinky', special: 'caps' },
                    ...keys(layout.rows[1], layout.rows[1].toUpperCase(), 2),
                    ...keys(layout.punctuation[1], layout.punctuation[1].toUpperCase(), 2),
                    { label: '↵ Enter', characters: ['Enter', '\n'], finger: 'right-pinky', special: 'enter' },
                ],
                [
                    { label: '⇧ Shift', characters: ['Shift'], finger: 'left-pinky', special: 'shift' },
                    ...keys(layout.punctuation[2], layout.punctuation[2].toUpperCase(), 3),
                    ...keys(layout.rows[2], layout.rows[2].toUpperCase(), 3),
                    { label: '⇧ Shift', characters: ['Shift'], finger: 'right-pinky', special: 'shift' },
                ],
                [
                    { label: 'Ctrl', characters: ['Control'], finger: 'left-pinky', special: 'ctrl' },
                    { label: 'Alt', characters: ['Alt'], finger: 'left-ring', special: 'alt' },
                    { label: keyboardLayout.value === 'us' ? 'Space' : 'Espaço', characters: [' '], finger: 'left-thumb', special: 'space' },
                    { label: 'AltGr', characters: ['AltGraph'], finger: 'right-thumb', special: 'altgr' },
                    { label: 'Ctrl', characters: ['Control'], finger: 'right-pinky', special: 'ctrl' },
                ],
            ];
        };
        const renderKeyboard = () => {
            const selectedLayout = keyboardLayout.value === 'auto' ? detectedLayout || 'abnt2' : keyboardLayout.value;
            const rows = keyboardRows(selectedLayout);
            virtualKeyboard.replaceChildren();
            rows.forEach((row) => {
                const rowElement = document.createElement('div');
                rowElement.className = 'php-typing__key-row';
                row.forEach((key) => {
                    const keyElement = document.createElement('span');
                    keyElement.className = 'php-typing__key';
                    keyElement.dataset.characters = JSON.stringify(key.characters);
                    keyElement.dataset.finger = key.finger || '';
                    keyElement.textContent = key.label;
                    if (key.special) {
                        keyElement.classList.add(`php-typing__key--${key.special}`);
                    }
                    rowElement.appendChild(keyElement);
                });
                virtualKeyboard.appendChild(rowElement);
            });
        };
        const detectSystemLayout = async () => {
            if (!navigator.keyboard || typeof navigator.keyboard.getLayoutMap !== 'function') {
                return;
            }

            try {
                const layoutMap = await navigator.keyboard.getLayoutMap();
                const physicalRows = [
                    ['KeyQ', 'KeyW', 'KeyE', 'KeyR', 'KeyT', 'KeyY', 'KeyU', 'KeyI', 'KeyO', 'KeyP'],
                    ['KeyA', 'KeyS', 'KeyD', 'KeyF', 'KeyG', 'KeyH', 'KeyJ', 'KeyK', 'KeyL'],
                    ['KeyZ', 'KeyX', 'KeyC', 'KeyV', 'KeyB', 'KeyN', 'KeyM'],
                ];
                const actualRows = physicalRows.map((row) => row.map((code) => layoutMap.get(code) || '').join('').toLocaleLowerCase());
                detectedLayout = Object.keys(layouts).find((name) => layouts[name].rows.every((row, index) => row === actualRows[index])) || null;
                if (keyboardLayout.value === 'auto') {
                    renderKeyboard();
                    renderFingerHint(characters[Array.from(typingInput.value).length]);
                    const detectedName = detectedLayout
                        ? keyboardLayout.querySelector(`option[value="${detectedLayout}"]`)?.textContent
                        : null;
                    typingFeedback.textContent = detectedLayout
                        ? `Layout do sistema detectado: ${detectedName || detectedLayout}.`
                        : 'Não foi possível identificar o layout do sistema; mostrando ABNT2.';
                }
            } catch (error) {
                typingFeedback.textContent = 'O navegador não permitiu detectar o layout; selecione o teclado manualmente.';
            }
        };
        const renderTarget = (typed, errorIndex = null) => {
            const typedCharacters = Array.from(typed);
            targetDisplay.replaceChildren();
            characters.forEach((character, index) => {
                const span = document.createElement('span');
                const stateClass = index === errorIndex
                    ? 'is-incorrect'
                    : index < typedCharacters.length
                    ? typedCharacters[index] === character ? 'is-correct' : 'is-incorrect'
                    : index === typedCharacters.length ? 'is-current' : '';

                if (character === '\n') {
                    const enterHint = document.createElement('span');
                    enterHint.className = `php-typing__enter-hint ${stateClass}`.trim();
                    enterHint.textContent = '↵';
                    enterHint.title = 'Pressione Enter para quebrar a linha';
                    enterHint.setAttribute('aria-label', 'Pressione Enter');
                    targetDisplay.appendChild(enterHint);
                    span.textContent = '\n';
                    span.className = 'php-typing__line-break';
                } else {
                    span.textContent = character;
                    span.className = stateClass;
                }
                targetDisplay.appendChild(span);
            });
        };
        const clearFingerHints = () => {
            exercise.querySelectorAll('[data-finger]').forEach((finger) => finger.classList.remove('is-recommended'));
        };
        const renderFingerHint = (character) => {
            clearFingerHints();
            exercise.querySelectorAll('.php-typing__key').forEach((key) => key.classList.remove('is-current-key'));
            if (!character) {
                fingerHint.textContent = 'Trecho concluído.';
                return;
            }
            const matchingKey = Array.from(exercise.querySelectorAll('.php-typing__key')).find((element) => {
                const options = JSON.parse(element.dataset.characters || '[]');
                return options.includes(character) || options.some((option) => option.toLocaleLowerCase() === character.toLocaleLowerCase());
            });
            const finger = matchingKey?.dataset.finger;
            matchingKey?.classList.add('is-current-key');
            if (finger) {
                exercise.querySelector(`[data-finger="${finger}"]`)?.classList.add('is-recommended');
                fingerHint.textContent = `Use ${fingerNames[finger]} para "${character === '\n' ? 'Enter' : character === ' ' ? 'Espaço' : character}".`;
            } else {
                fingerHint.textContent = `Próximo caractere: "${character}".`;
            }
        };
        const formatTime = (seconds) => `${String(Math.floor(seconds / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`;
        let timerInterval = null;
        let startedAt = null;
        let mistakes = 0;
        let errorIndex = null;
        let audioContext = null;

        const playMistakeSound = () => {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (!AudioContextClass) {
                return;
            }

            try {
                audioContext ??= new AudioContextClass();
            } catch (error) {
                console.error('Não foi possível iniciar o áudio de aviso do treino.', error);
                return;
            }

            const playTone = () => {
                const now = audioContext.currentTime;
                const oscillator = audioContext.createOscillator();
                const gain = audioContext.createGain();
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(440, now);
                oscillator.frequency.exponentialRampToValueAtTime(220, now + 0.16);
                gain.gain.setValueAtTime(0.0001, now);
                gain.gain.exponentialRampToValueAtTime(0.4, now + 0.01);
                gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.18);
                oscillator.connect(gain);
                gain.connect(audioContext.destination);
                oscillator.start(now);
                oscillator.stop(now + 0.18);
            };

            if (audioContext.state === 'suspended') {
                audioContext.resume().then(playTone).catch((error) => {
                    console.error('Não foi possível reproduzir o áudio de aviso do treino.', error);
                });
                return;
            }

            playTone();
        };

        targetCountOutput.textContent = String(characters.length);
        renderKeyboard();
        renderTarget('');
        renderFingerHint(characters[0]);
        detectSystemLayout();
        keyboardLayout.addEventListener('change', () => {
            renderKeyboard();
            renderFingerHint(characters[Array.from(typingInput.value).length]);
        });
        targetArea.addEventListener('click', () => typingInput.focus());
        targetArea.addEventListener('focus', () => typingInput.focus());
        targetArea.addEventListener('keydown', () => typingInput.focus());
        typingInput.addEventListener('focus', () => {
            typingFeedback.textContent = 'Digite o trecho na ordem. O cronômetro começa na primeira tecla.';
        });

        [typingInput, recallInput].forEach((input) => {
            input.addEventListener('keydown', (event) => {
                if (event.key !== 'Tab') {
                    return;
                }

                event.preventDefault();
                input.setRangeText('    ', input.selectionStart, input.selectionEnd, 'end');
                input.dispatchEvent(new Event('input', { bubbles: true }));
            });
        });

        typingInput.addEventListener('input', () => {
            let typed = normalize(typingInput.value);
            const typedCharacters = Array.from(typed);
            const firstMistake = typedCharacters.findIndex((character, index) => character !== characters[index]);
            if (firstMistake !== -1) {
                playMistakeSound();
                mistakes += 1;
                errorIndex = firstMistake;
                typed = typedCharacters.slice(0, firstMistake).join('');
                typingInput.value = typed;
                typingInput.setSelectionRange(typed.length, typed.length);
            } else {
                errorIndex = null;
            }

            if ((typed.length || mistakes > 0) && timerInterval === null) {
                if (startedAt === null) {
                    startedAt = Date.now();
                }
                timerInterval = window.setInterval(() => {
                    const elapsed = Math.floor((Date.now() - startedAt) / 1000);
                    timerOutput.textContent = formatTime(elapsed);
                    const elapsedMinutes = elapsed / 60;
                    const current = normalize(typingInput.value);
                    const correct = Array.from(current).length;
                    speedOutput.textContent = `${elapsedMinutes > 0 ? Math.round((correct / 5) / elapsedMinutes) : 0} PPM`;
                }, 1000);
            }

            const correctCharacters = Array.from(typed).length;
            const accuracy = correctCharacters + mistakes === 0
                ? 100
                : Math.round((correctCharacters / (correctCharacters + mistakes)) * 100);
            const elapsedMinutes = startedAt === null ? 0 : (Date.now() - startedAt) / 60000;
            const wordsPerMinute = elapsedMinutes > 0 ? Math.round((correctCharacters / 5) / elapsedMinutes) : 0;
            accuracyOutput.textContent = `${accuracy}%`;
            speedOutput.textContent = `${wordsPerMinute} PPM`;
            typedCountOutput.textContent = String(Math.min(correctCharacters, characters.length));
            const progress = characters.length ? Math.min(100, Math.round((correctCharacters / characters.length) * 100)) : 0;
            progressBar.style.width = `${progress}%`;
            progressTrack.setAttribute('aria-valuenow', String(progress));
            renderTarget(typed, errorIndex);
            renderFingerHint(characters[correctCharacters]);

            const completed = typed === target;
            nextButton.disabled = !completed;
            if (completed && timerInterval !== null) {
                window.clearInterval(timerInterval);
                timerInterval = null;
            }
            typingFeedback.textContent = completed
                ? 'Muito bem! Você digitou o trecho corretamente. Agora tente reconstruí-lo sem consultar o modelo.'
                : errorIndex !== null
                    ? 'Tecla incorreta. Você continua nesta posição; tente a tecla destacada até acertar.'
                    : 'Boa! Continue até completar todo o código.';
        });

        nextButton.addEventListener('click', () => {
            typingStep.hidden = true;
            recallStep.hidden = false;
            recallInput.focus();
        });

        backButton.addEventListener('click', () => {
            recallStep.hidden = true;
            typingStep.hidden = false;
            typingInput.focus();
        });

        checkButton.addEventListener('click', async () => {
            checkButton.disabled = true;
            recallFeedback.textContent = 'Conferindo o exercício e salvando seu progresso...';
            recallFeedback.className = 'mt-3 text-sm text-gray-600';

            if (normalize(recallInput.value) !== target) {
                recallFeedback.textContent = 'O código ainda tem diferenças. Confira a explicação, reveja o exemplo e tente novamente.';
                recallFeedback.className = 'mt-3 text-sm font-semibold text-amber-800';
                checkButton.disabled = false;
                return;
            }

            try {
                const response = await fetch(exercise.dataset.progressUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': exercise.dataset.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ progress: 100 }),
                });

                if (!response.ok) {
                    throw new Error(`Não foi possível salvar o progresso (HTTP ${response.status}).`);
                }

                const result = await response.json();
                if (!result.completed && !result.preview) {
                    throw new Error('O servidor não confirmou a conclusão do exercício.');
                }

                recallStep.hidden = true;
                completeStep.hidden = false;
            } catch (error) {
                recallFeedback.textContent = `${error.message} Verifique sua conexão e tente salvar novamente.`;
                recallFeedback.className = 'mt-3 text-sm font-semibold text-red-700';
                checkButton.disabled = false;
            }
        });
    });
}

initializePhpTypingExercises(document);

new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
            if (node.nodeType === Node.ELEMENT_NODE) {
                initializePhpTypingExercises(node);
            }
        });
    });
}).observe(document.documentElement, { childList: true, subtree: true });
