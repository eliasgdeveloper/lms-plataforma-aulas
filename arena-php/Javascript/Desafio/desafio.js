(function () {
    var data = window.DESAFIO_DATA;
    if (!data) return;

    var aluno = data.aluno || "Aluno";
    var firstName = (aluno.split(/\s+/)[0] || "Aluno");

    var state = {
        etapa: data.etapas[0] ? data.etapas[0].id : 1,
        modoExtra: false,
        pontos: data.progresso.pontos || 0,
        concluidas: data.progresso.concluidas || [],
        surpresas: data.progresso.surpresas || [],
        avatar: data.progresso.avatar || "starter",
        extraDone: !!data.progresso.extra_done,
        extraUnlocked: !!data.progresso.extra_unlocked
    };

    var MODO_EXTRA_KEY = (data.checkUrl && String(data.checkUrl).indexOf("sprint2") !== -1)
        ? "desafio_modo_extra_sprint2"
        : "desafio_modo_extra";

    function persistModoExtra() {
        try {
            sessionStorage.setItem(MODO_EXTRA_KEY, state.modoExtra ? "1" : "0");
        } catch (e) {}
    }

    function readPersistedModoExtra() {
        try {
            return sessionStorage.getItem(MODO_EXTRA_KEY) === "1";
        } catch (e) {
            return false;
        }
    }

    function pareceCodigoExtra(codigo) {
        var c = String(codigo || "");
        if (data.checkUrl && String(data.checkUrl).indexOf("sprint2") !== -1) {
            if (/CAIXA LITORAL/i.test(c)) return true;
            return c.indexOf("$lojas") !== -1 && c.indexOf("$vendas") !== -1 && /array_sum|array_reduce/i.test(c);
        }
        if (/RELATORIO DE PROJETOS TI/i.test(c)) return true;
        var temProjetos = c.indexOf("$projetos") !== -1;
        var temStatus = c.indexOf("$status") !== -1;
        if (!temProjetos || !temStatus) return false;
        return /BANNERS/i.test(c)
            || /SLUGS/i.test(c)
            || /statusBackup/i.test(c)
            || c.indexOf("$statusBackup") !== -1;
    }

    function setModoExtra(on) {
        state.modoExtra = !!on;
        persistModoExtra();
        var btn = document.getElementById("btn-check");
        if (btn) btn.classList.toggle("extra-mode", state.modoExtra);
    }

    var avatars = {
        starter: "seedling",
        fox: "user-astronaut",
        robot: "robot",
        wizard: "hat-wizard",
        ninja: "user-ninja",
        champion: "trophy",
        star: "star"
    };

    var surpriseIcon = {
        avatar_fox: "user-astronaut",
        badge_contador: "hashtag",
        avatar_robot: "robot",
        badge_indice: "crosshairs",
        avatar_wizard: "hat-wizard",
        badge_map: "map",
        avatar_ninja: "user-ninja",
        trophy: "trophy",
        estrela_extra: "star"
    };
    var iconMarkup = function (icon) {
        return '<i class="fa-solid fa-' + icon + '" aria-hidden="true"></i>';
    };

    var missaoHooks = {
        1: firstName + ", hora de montar seu primeiro array de verdade.",
        2: "Conta comigo, " + firstName + " — o count() tá do seu lado.",
        3: firstName + " no tour foreach: um passo, um eco, um ponto.",
        4: "Atenção, " + firstName + ": altere o índice sem assustar o backup.",
        5: "Grite no banner, " + firstName + "! strtoupper não tem medo de volume.",
        6: "Modo técnico liberado. " + firstName + " + array_map = combinação perigosa.",
        7: "Detetive " + firstName + ": ligue aluno ao curso sem GPS.",
        8: "Boss final na frente, " + firstName + ". Respire… e code."
    };

    var els = {
        list: document.getElementById("mission-list"),
        eyebrow: document.getElementById("mission-eyebrow"),
        title: document.getElementById("mission-title"),
        tema: document.getElementById("mission-tema"),
        enunciado: document.getElementById("mission-enunciado"),
        dica: document.getElementById("mission-dica"),
        coach: document.getElementById("mission-coach"),
        codigo: document.getElementById("codigo"),
        saida: document.getElementById("saida"),
        feedback: document.getElementById("feedback"),
        pontos: document.getElementById("pontos"),
        progresso: document.getElementById("progresso"),
        savePill: document.getElementById("save-pill"),
        avatar: document.getElementById("avatar"),
        rewards: document.getElementById("rewards"),
        overlay: document.getElementById("overlay"),
        modalBox: document.getElementById("modal-box"),
        modalIcon: document.getElementById("modal-icon"),
        modalTitle: document.getElementById("modal-title"),
        modalText: document.getElementById("modal-text"),
        confetti: document.getElementById("confetti"),
        btnExtra: document.getElementById("btn-extra"),
        extraIco: document.getElementById("extra-ico"),
        extraTitle: document.getElementById("extra-title"),
        extraMeta: document.getElementById("extra-meta")
    };

    var audioCtx = null;
    function ensureAudio() {
        if (!audioCtx) {
            var Ctx = window.AudioContext || window.webkitAudioContext;
            if (!Ctx) return null;
            audioCtx = new Ctx();
        }
        if (audioCtx.state === "suspended") audioCtx.resume();
        return audioCtx;
    }

    function tone(freq, start, dur, type, gainValue) {
        var ctx = ensureAudio();
        if (!ctx) return;
        try {
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.type = type || "sine";
            osc.frequency.value = freq;
            var t0 = ctx.currentTime + start;
            gain.gain.setValueAtTime(0.001, t0);
            gain.gain.linearRampToValueAtTime(gainValue || 0.12, t0 + 0.02);
            gain.gain.linearRampToValueAtTime(0.001, t0 + dur);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(t0);
            osc.stop(t0 + dur + 0.03);
        } catch (e) {}
    }

    function noiseBurst(start, dur, volume) {
        var ctx = ensureAudio();
        if (!ctx) return;
        try {
            var frames = Math.floor(ctx.sampleRate * dur);
            var buffer = ctx.createBuffer(1, frames, ctx.sampleRate);
            var dataArr = buffer.getChannelData(0);
            for (var i = 0; i < frames; i++) {
                dataArr[i] = (Math.random() * 2 - 1) * Math.pow(1 - i / frames, 1.6);
            }
            var src = ctx.createBufferSource();
            var filter = ctx.createBiquadFilter();
            var gain = ctx.createGain();
            src.buffer = buffer;
            filter.type = "bandpass";
            filter.frequency.value = 1800;
            gain.gain.value = volume || 0.18;
            src.connect(filter);
            filter.connect(gain);
            gain.connect(ctx.destination);
            src.start(ctx.currentTime + start);
        } catch (e) {}
    }

    function playSound(kind) {
        ensureAudio();
        if (kind === "funny") {
            tone(420, 0, 0.12, "square", 0.08);
            tone(280, 0.12, 0.14, "square", 0.07);
            tone(180, 0.28, 0.22, "triangle", 0.09);
            return;
        }
        if (kind === "cheer") {
            tone(523, 0, 0.12, "triangle", 0.1);
            tone(659, 0.1, 0.12, "triangle", 0.1);
            tone(784, 0.2, 0.18, "triangle", 0.12);
            noiseBurst(0.05, 0.18, 0.12);
            noiseBurst(0.2, 0.22, 0.1);
            return;
        }
        if (kind === "applause") {
            for (var i = 0; i < 8; i++) noiseBurst(i * 0.08, 0.2, 0.1 + (i % 2) * 0.04);
            tone(880, 0.1, 0.15, "sine", 0.05);
            return;
        }
        if (kind === "finale") {
            tone(523, 0, 0.14, "triangle", 0.12);
            tone(659, 0.14, 0.14, "triangle", 0.12);
            tone(784, 0.28, 0.14, "triangle", 0.12);
            tone(1046, 0.42, 0.35, "triangle", 0.14);
            for (var j = 0; j < 12; j++) noiseBurst(0.2 + j * 0.07, 0.25, 0.12);
            return;
        }
        if (kind === "extra") {
            tone(392, 0, 0.12, "sawtooth", 0.06);
            tone(523, 0.12, 0.12, "triangle", 0.1);
            tone(659, 0.24, 0.12, "triangle", 0.11);
            tone(784, 0.36, 0.12, "triangle", 0.11);
            tone(1046, 0.5, 0.28, "triangle", 0.13);
            tone(1318, 0.72, 0.35, "sine", 0.1);
            for (var k = 0; k < 16; k++) noiseBurst(0.35 + k * 0.06, 0.28, 0.13);
            return;
        }
        playSound("cheer");
    }

    function done(id) {
        return state.concluidas.indexOf(id) !== -1 || state.concluidas.indexOf(String(id)) !== -1;
    }

    function syncExtraUnlock() {
        var all = true;
        for (var i = 0; i < data.etapas.length; i++) {
            if (!done(data.etapas[i].id)) {
                all = false;
                break;
            }
        }
        state.extraUnlocked = all || state.extraUnlocked;
    }

    function etapaAtual() {
        if (state.modoExtra) return data.extra;
        for (var i = 0; i < data.etapas.length; i++) {
            if (data.etapas[i].id === state.etapa) return data.etapas[i];
        }
        return data.etapas[0];
    }

    function renderExtraCard() {
        syncExtraUnlock();
        var btn = els.btnExtra;
        btn.classList.remove("locked", "unlocked", "active", "done");
        if (!state.extraUnlocked) {
            btn.classList.add("locked");
            btn.disabled = true;
            els.extraIco.innerHTML = iconMarkup("lock");
            els.extraTitle.textContent = "Relatório do Diretor";
            els.extraMeta.textContent = "Bloqueado · termine as 8 missões";
            return;
        }
        btn.disabled = false;
        btn.classList.add("unlocked");
        if (state.modoExtra) btn.classList.add("active");
        if (state.extraDone) btn.classList.add("done");
        els.extraIco.innerHTML = iconMarkup(state.extraDone ? "star" : "lock-open");
        els.extraTitle.textContent = data.extra.titulo.replace(/^Desafio Extra · /, "");
        els.extraMeta.textContent = state.extraDone
            ? "Conquistado · " + data.extra.pontos + " pts · pode treinar de novo"
            : "Liberado · " + data.extra.pontos + " pts · pedido do diretor";
    }

    function renderList() {
        els.list.innerHTML = "";
        data.etapas.forEach(function (etapa) {
            var li = document.createElement("li");
            var btn = document.createElement("button");
            btn.type = "button";
            btn.innerHTML =
                '<span class="n">' + etapa.id + "</span>" +
                "<strong>" + etapa.titulo.replace(/^Missão \d+ · /, "") + "</strong>" +
                '<span class="meta">' + etapa.tema + " · " + etapa.pontos + " pts</span>";
            if (!state.modoExtra && etapa.id === state.etapa) btn.classList.add("active");
            if (done(etapa.id)) btn.classList.add("done");
            btn.addEventListener("click", function () {
                setModoExtra(false);
                state.etapa = etapa.id;
                renderAll();
                playSound("funny");
            });
            li.appendChild(btn);
            els.list.appendChild(li);
        });
    }

    function renderMission() {
        var etapa = etapaAtual();
        els.eyebrow.textContent = state.modoExtra ? "Desafio Extra" : "Desafio ao vivo";
        els.title.textContent = etapa.titulo;
        els.tema.textContent = state.modoExtra
            ? "Tema: " + etapa.tema + " · Vale " + etapa.pontos + " pontos · Jogador: " + firstName
            : "Tema: " + etapa.tema + " · Vale " + etapa.pontos + " pontos · Jogador: " + firstName;
        els.enunciado.textContent = etapa.enunciado;
        els.dica.textContent = "Dica: " + etapa.dica;
        if (els.coach) {
            els.coach.textContent = state.modoExtra
                ? firstName + ", o diretor quer o relatório completo. Encadeie o que você já aprendeu — com cara de profissional."
                : (missaoHooks[etapa.id] || ("Bora, " + firstName + "!"));
        }
        var key = state.modoExtra ? "extra" : String(etapa.id);
        var prevKey = els.codigo.dataset.etapa || "";
        var preserve = els.codigo.dataset.preserve === "1";
        if (prevKey !== key) {
            if (!preserve) {
                els.codigo.value = etapa.starter;
                els.saida.value = "";
            }
            els.codigo.dataset.etapa = key;
            els.codigo.dataset.preserve = "";
            els.feedback.className = "feedback";
            els.feedback.textContent = "";
        }
        var btnCheck = document.getElementById("btn-check");
        if (btnCheck) {
            btnCheck.textContent = state.modoExtra ? "Validar Desafio Extra" : "Validar missão";
            btnCheck.classList.toggle("extra-mode", state.modoExtra);
        }
    }

    function markSaved(ok) {
        if (!els.savePill) return;
        els.savePill.textContent = ok ? "salvo" : "falha ao salvar";
        els.savePill.classList.toggle("saved", !!ok);
        els.savePill.classList.toggle("gold", !ok);
    }

    function renderStats() {
        els.pontos.textContent = state.pontos + " pts";
        var base = state.concluidas.length + "/" + data.etapas.length + " missões";
        els.progresso.innerHTML = state.extraDone
            ? base + ' · <i class="fa-solid fa-star" aria-hidden="true"></i> Extra'
            : base;
        els.avatar.innerHTML = iconMarkup(avatars[state.avatar] || avatars.starter);
    }

    function renderRewards() {
        var cards = els.rewards.querySelectorAll("[data-reward]");
        for (var i = 0; i < cards.length; i++) {
            var key = cards[i].getAttribute("data-reward");
            if (state.surpresas.indexOf(key) !== -1) cards[i].classList.add("on");
            else cards[i].classList.remove("on");
        }
    }

    function renderAll() {
        renderList();
        renderExtraCard();
        renderMission();
        renderStats();
        renderRewards();
    }

    function showFeedback(ok, message, warn, acertos, erros) {
        els.feedback.className = "feedback show " + (warn ? "warn" : ok ? "ok" : "bad");
        els.feedback.textContent = "";

        var msg = document.createElement("p");
        msg.className = "feedback-msg";
        var icon = document.createElement("i");
        icon.className = "fa-solid " + (warn ? "fa-circle-info" : ok ? "fa-circle-check" : "fa-circle-xmark");
        icon.setAttribute("aria-hidden", "true");
        var text = document.createElement("span");
        text.textContent = message || "";
        msg.append(icon, text);
        els.feedback.appendChild(msg);

        var hasAcertos = acertos && acertos.length;
        var hasErros = erros && erros.length;
        if (!hasAcertos && !hasErros) {
            return;
        }

        var report = document.createElement("div");
        report.className = "feedback-report";

        if (hasAcertos) {
            var ulOk = document.createElement("ul");
            ulOk.className = "acertou";
            for (var i = 0; i < acertos.length; i++) {
                var liOk = document.createElement("li");
                liOk.textContent = acertos[i];
                ulOk.appendChild(liOk);
            }
            report.appendChild(ulOk);
        }

        if (hasErros) {
            var ulBad = document.createElement("ul");
            ulBad.className = "errou";
            for (var j = 0; j < erros.length; j++) {
                var liBad = document.createElement("li");
                liBad.textContent = erros[j];
                ulBad.appendChild(liBad);
            }
            report.appendChild(ulBad);
        }

        els.feedback.appendChild(report);
    }

    function burstConfetti(mode) {
        var canvas = els.confetti;
        var ctx = canvas.getContext("2d");
        var w = (canvas.width = window.innerWidth);
        var h = (canvas.height = window.innerHeight);
        var pieces = [];
        var colors = mode === "extra"
            ? ["#d4b45a", "#f0e2b0", "#ffffff", "#0150f9", "#ffd76a", "#2ecc71"]
            : ["#0150f9", "#4c8dff", "#d4b45a", "#2ecc71", "#ffffff", "#ff7b72"];
        var total = mode === "extra" ? 220 : 160;
        for (var i = 0; i < total; i++) {
            pieces.push({
                x: Math.random() * w,
                y: Math.random() * h * -0.5,
                r: Math.random() * 7 + 3,
                c: colors[i % colors.length],
                vx: (Math.random() - 0.5) * 5,
                vy: Math.random() * 5 + 2,
                a: Math.random() * Math.PI
            });
        }
        var frames = 0;
        var max = mode === "extra" ? 130 : 100;
        function tick() {
            frames++;
            ctx.clearRect(0, 0, w, h);
            pieces.forEach(function (p) {
                p.x += p.vx;
                p.y += p.vy;
                p.a += 0.12;
                ctx.save();
                ctx.translate(p.x, p.y);
                ctx.rotate(p.a);
                ctx.fillStyle = p.c;
                ctx.fillRect(-p.r, -p.r / 2, p.r * 2, p.r);
                ctx.restore();
            });
            if (frames < max) requestAnimationFrame(tick);
            else ctx.clearRect(0, 0, w, h);
        }
        tick();
    }

    function showSurprise(payload) {
        var isExtra = !!payload.extra;
        try {
            if (els.modalBox) els.modalBox.classList.toggle("extra-mode", isExtra);
            els.modalIcon.innerHTML = iconMarkup(surpriseIcon[payload.surpresa] || (isExtra ? "star" : "wand-magic-sparkles"));
            els.modalTitle.textContent = payload.modal_title || (isExtra
                ? "Desafio Extra conquistado, " + firstName + "!"
                : "Surpresa pra você, " + firstName + "!");
            els.modalText.textContent = payload.surpresa_texto || "";
            els.overlay.classList.add("show");
            els.avatar.classList.add("pop");
            burstConfetti(isExtra ? "extra" : "normal");
            playSound(payload.sound || (isExtra ? "extra" : "cheer"));
            if (isExtra || payload.completo) {
                setTimeout(function () { playSound("applause"); }, 500);
            }
            setTimeout(function () {
                els.avatar.classList.remove("pop");
            }, 800);
        } catch (err) {
            els.overlay.classList.add("show");
            els.modalTitle.textContent = payload.modal_title || "Surpresa!";
            els.modalText.textContent = payload.surpresa_texto || payload.message || "";
        }
    }

    els.btnExtra.addEventListener("click", function () {
        if (!state.extraUnlocked) {
            showFeedback(false, firstName + ", o Extra ainda está lacrado. Feche as 8 missões primeiro.");
            playSound("funny");
            return;
        }
        setModoExtra(true);
        els.codigo.dataset.etapa = "";
        els.codigo.dataset.preserve = "";
        renderAll();
        playSound("cheer");
    });

    function runValidation(button) {
        var etapa = etapaAtual();
        button.disabled = true;
        button.textContent = state.modoExtra
            ? "Lendo o relatório do " + firstName + "..."
            : "Corrigindo o código do " + firstName + "...";
        ensureAudio();

        fetch(data.checkUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                csrf: data.csrf,
                etapa: state.modoExtra ? "extra" : etapa.id,
                codigo: els.codigo.value,
                saida: els.saida.value
            })
        })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (typeof res.extra_unlocked !== "undefined") state.extraUnlocked = !!res.extra_unlocked;
                if (typeof res.extra_done !== "undefined") state.extraDone = !!res.extra_done;

                if (!res.ok) {
                    var soAvisos = !!res.codigo_ok;
                    showFeedback(
                        false,
                        res.message,
                        soAvisos,
                        soAvisos ? [] : (res.acertos || []),
                        res.erros || []
                    );
                    playSound(res.sound || "funny");
                    renderExtraCard();
                    return;
                }

                state.pontos = res.pontos;
                state.concluidas = res.concluidas || state.concluidas;
                state.surpresas = res.surpresas || state.surpresas;
                state.avatar = res.avatar || state.avatar;
                if (res.completo) state.extraUnlocked = true;
                renderAll();

                if (res.warning) {
                    showFeedback(true, res.message, true);
                } else {
                    showFeedback(true, res.message + (res.saved ? " · Progresso salvo." : ""));
                }
                markSaved(!!res.saved || res.already);

                if (!res.already || res.extra) {
                    if (!res.already) showSurprise(res);
                    else if (res.extra) playSound(res.sound || "funny");
                } else {
                    playSound(res.sound || "funny");
                }
            })
            .catch(function (err) {
                var detail = err && err.message ? " (" + err.message + ")" : "";
                showFeedback(false, firstName + ", a correção falhou na rede. Tenta de novo!" + detail);
                playSound("funny");
            })
            .finally(function () {
                button.disabled = false;
                button.textContent = state.modoExtra ? "Validar Desafio Extra" : "Validar missão";
                button.classList.toggle("extra-mode", state.modoExtra);
            });
    }

    document.getElementById("btn-check").addEventListener("click", function () {
        var button = this;
        var codigo = els.codigo.value;

        if (!state.modoExtra && pareceCodigoExtra(codigo)) {
            if (!state.extraUnlocked) {
                showFeedback(
                    false,
                    firstName + ", esse código é do Desafio Extra. Termine as 8 missões primeiro para liberar a estrela."
                );
                playSound("funny");
                return;
            }
            // Auto-switch to Extra and continue validation in the same click
            els.codigo.dataset.preserve = "1";
            setModoExtra(true);
            renderAll();
            runValidation(button);
            return;
        }

        runValidation(button);
    });

    document.getElementById("btn-hint").addEventListener("click", function () {
        var etapa = etapaAtual();
        showFeedback(true, firstName + ", olha a dica: " + etapa.dica);
        playSound("funny");
    });

    document.getElementById("btn-reset-fields").addEventListener("click", function () {
        var etapa = etapaAtual();
        els.codigo.value = etapa.starter;
        els.saida.value = "";
        els.feedback.className = "feedback";
        playSound("funny");
    });

    document.getElementById("btn-close-modal").addEventListener("click", function () {
        els.overlay.classList.remove("show");
        els.modalBox.classList.remove("extra-mode");

        if (state.extraUnlocked && !state.modoExtra && state.concluidas.length >= data.etapas.length && !state.extraDone) {
            setModoExtra(true);
            els.codigo.dataset.etapa = "";
            els.codigo.dataset.preserve = "";
            renderAll();
            showFeedback(true, "Desafio Extra liberado, " + firstName + "!");
            playSound("cheer");
            return;
        }

        if (state.modoExtra) {
            renderAll();
            return;
        }

        var next = null;
        for (var i = 0; i < data.etapas.length; i++) {
            if (!done(data.etapas[i].id)) {
                next = data.etapas[i].id;
                break;
            }
        }
        if (next) {
            state.etapa = next;
            els.codigo.dataset.etapa = "";
            renderAll();
            playSound("cheer");
        } else {
            showFeedback(true, "Missões concluídas, " + firstName + "! Agora é a vez do Desafio Extra.");
            playSound("applause");
            renderExtraCard();
        }
    });

    document.getElementById("btn-reset-game").addEventListener("click", function () {
        if (!window.confirm(firstName + ", zerar pontos, missões e o Desafio Extra desta sessão?")) return;
        fetch(data.progressUrl + "?reset=1")
            .then(function (r) { return r.json(); })
            .then(function (res) {
                state.pontos = res.pontos || 0;
                state.concluidas = res.concluidas || [];
                state.surpresas = res.surpresas || [];
                state.avatar = res.avatar || "starter";
                state.extraDone = !!res.extra_done;
                state.extraUnlocked = !!res.extra_unlocked;
                setModoExtra(false);
                state.etapa = data.etapas[0].id;
                els.codigo.dataset.etapa = "";
                els.codigo.dataset.preserve = "";
                renderAll();
                showFeedback(true, "Progresso zerado, " + firstName + ". Nova chance, mesmo talento!");
                playSound("funny");
            });
    });

    // Boot: restaurar Extra se sessionStorage diz extra e extraUnlocked;
    // ou auto-abrir Extra se todas missões feitas / professor com Extra liberado.
    (function bootModoExtra() {
        syncExtraUnlock();
        var allDone = state.concluidas.length >= data.etapas.length;
        var sessionExtra = readPersistedModoExtra();
        // Extra liberado + (8 missões OK | sessão pediu Extra | professor com 0 missões)
        if (state.extraUnlocked && (allDone || sessionExtra || state.concluidas.length === 0)) {
            setModoExtra(true);
        } else {
            persistModoExtra();
        }
    })();

    renderAll();
    markSaved(true);
    if (els.coach && !state.modoExtra) {
        els.coach.textContent = "Bem-vindo à arena, " + firstName + "! Cada missão certa libera surpresa, som e pontos. O Extra só abre no final. Seu progresso fica salvo no servidor.";
    }
})();
