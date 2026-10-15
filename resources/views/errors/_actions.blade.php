@php
    use App\Support\ViewAs;

    $home = url('/');
    $label = 'Ir para o início';

    if (auth()->check()) {
        try {
            $role = ViewAs::effectiveRole() ?: (auth()->user()->role ?? null);
            $home = ViewAs::homeFor($role);
            $label = 'Ir para minha área';
        } catch (Throwable $e) {
            $home = url('/dashboard');
            $label = 'Ir para o painel';
        }
    } else {
        $home = route('login');
        $label = 'Fazer login';
    }
@endphp

<a class="error-btn error-btn--primary" href="{{ $home }}">{{ $label }}</a>
<a class="error-btn error-btn--ghost" href="javascript:history.back()">Voltar</a>
@if (!empty($extra ?? null))
    {!! $extra !!}
@endif
