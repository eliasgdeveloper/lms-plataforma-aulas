<form class="login-form" method="POST" action="{{ route('login') }}" autocomplete="on">
    @csrf
    <h2 id="auth-title">Acesse sua conta</h2>
    <p>Entre com o usuário ou o e-mail, e a senha da sua conta.</p>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    <label for="email">Usuário ou e-mail</label>
    <div class="field">
        <i class="fa-solid fa-user"></i>
        <input id="email" name="email" type="text" value="{{ old('email') }}" required autocomplete="username" placeholder="Elias ou seu@email">
    </div>
    @error('email')
        <div class="field-error">{{ $message }}</div>
    @enderror

    <label for="password">Senha</label>
    <div class="field">
        <i class="fa-solid fa-lock"></i>
        <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Digite sua senha">
        <button class="toggle-pass" type="button" aria-label="Mostrar senha">
            <i class="fa-solid fa-eye"></i>
        </button>
    </div>
    @error('password')
        <div class="field-error">{{ $message }}</div>
    @enderror

    <label class="remember" for="remember_me">
        <input id="remember_me" type="checkbox" name="remember">
        Lembrar de mim
    </label>

    <button type="submit">Acessar a plataforma</button>

    <div class="links-row">
        <a href="{{ url('/') }}">Página inicial</a>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Esqueci a senha</a>
        @endif
        @if (Route::has('register'))
            <a href="{{ route('register') }}">Criar conta</a>
        @endif
    </div>
    <p class="footnote">Sessão protegida. As contas e senhas de sempre continuam valendo.</p>
</form>
