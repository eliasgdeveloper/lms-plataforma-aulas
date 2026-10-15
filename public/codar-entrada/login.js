(function () {
    var canvas = document.getElementById("network");
    var toggle = document.querySelector(".toggle-pass");
    var form = document.querySelector(".login-form");
    var password = document.getElementById("password") || document.getElementById("senha");
    var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (toggle && password) {
        toggle.addEventListener("click", function () {
            var hidden = password.type === "password";
            password.type = hidden ? "text" : "password";
            toggle.setAttribute("aria-label", hidden ? "Ocultar senha" : "Mostrar senha");
            toggle.innerHTML = hidden
                ? '<i class="fa-solid fa-eye-slash"></i>'
                : '<i class="fa-solid fa-eye"></i>';
        });
    }

    if (form) {
        form.addEventListener("submit", function () {
            var button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.textContent = "Entrando...";
            }
        });
    }

    // Mantém a marca no primeiro viewport, sem atrapalhar âncoras (#em-breve).
    if ("scrollRestoration" in history) {
        history.scrollRestoration = "manual";
    }
    if (!window.location.hash) {
        window.scrollTo(0, 0);
    }

    var reveals = document.querySelectorAll(".reveal");
    if (reveals.length) {
        if (reduced || !("IntersectionObserver" in window)) {
            reveals.forEach(function (el) {
                el.classList.add("is-in");
            });
        } else {
            var io = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("is-in");
                            io.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.14, rootMargin: "0px 0px -40px 0px" }
            );
            reveals.forEach(function (el) {
                io.observe(el);
            });
        }
    }

    if (!canvas || reduced || !canvas.getContext) {
        return;
    }

    var ctx = canvas.getContext("2d");
    var nodes = [];
    var width = 0;
    var height = 0;
    var dpr = Math.min(window.devicePixelRatio || 1, 2);

    function resize() {
        width = canvas.clientWidth || window.innerWidth;
        height = canvas.clientHeight || window.innerHeight;
        canvas.width = Math.floor(width * dpr);
        canvas.height = Math.floor(height * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function createNodes() {
        var count = width < 700 ? 28 : 54;
        nodes = [];
        for (var i = 0; i < count; i++) {
            nodes.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.35,
                vy: (Math.random() - 0.5) * 0.35,
                r: Math.random() * 1.6 + 0.6
            });
        }
    }

    function tick() {
        ctx.clearRect(0, 0, width, height);
        for (var i = 0; i < nodes.length; i++) {
            var node = nodes[i];
            node.x += node.vx;
            node.y += node.vy;
            if (node.x < 0 || node.x > width) node.vx *= -1;
            if (node.y < 0 || node.y > height) node.vy *= -1;

            ctx.beginPath();
            ctx.fillStyle = "rgba(255,255,255,0.38)";
            ctx.arc(node.x, node.y, node.r, 0, Math.PI * 2);
            ctx.fill();

            for (var j = i + 1; j < nodes.length; j++) {
                var other = nodes[j];
                var dx = node.x - other.x;
                var dy = node.y - other.y;
                var dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 130) {
                    ctx.beginPath();
                    ctx.strokeStyle = "rgba(76,141,255," + (0.18 - dist / 900) + ")";
                    ctx.lineWidth = 1;
                    ctx.moveTo(node.x, node.y);
                    ctx.lineTo(other.x, other.y);
                    ctx.stroke();
                }
            }
        }
        requestAnimationFrame(tick);
    }

    resize();
    createNodes();
    window.addEventListener("resize", function () {
        resize();
        createNodes();
    });
    tick();
})();
