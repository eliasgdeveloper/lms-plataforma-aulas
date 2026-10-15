@extends('layouts.page')

@section('title', 'Mudar papel')

@section('content')
<div style="max-width: 640px; margin: 2.5rem auto; padding: 0 1.25rem 3rem;">
    <h1 style="font-size: 1.8rem; color: #042047; margin-bottom: 0.6rem;">Mudar papel para...</h1>
    <p style="color: #475569; line-height: 1.5; margin-bottom: 0.4rem;">
        Escolha um papel para ver a plataforma como essa pessoa vê.
        Sua conta continua sendo <strong>{{ \App\Support\ViewAs::label($realRole) }}</strong>.
        Nada do que você marcar na prévia é gravado, e o progresso do Leandro não muda.
    </p>
    <p style="color: #64748b; font-size: 0.92rem; margin-bottom: 1.4rem;">
        Papel atual da tela: {{ \App\Support\ViewAs::label($effectiveRole) }}.
    </p>

    <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 0.65rem;">
        @foreach ($roles as $role => $label)
            <form method="POST" action="{{ route('view-as.update') }}">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">
                <button type="submit" style="min-width: 280px; text-align: left; padding: 0.7rem 1rem; border-radius: 8px; border: 1px solid #cbd5e1; background: {{ $effectiveRole === $role ? '#dbeafe' : '#f8fafc' }}; color: #0f172a; font-weight: 700; cursor: pointer;">
                    {{ $label }}
                    @if ($realRole === $role)
                        <span style="font-weight: 500; color: #64748b;"> · seu papel</span>
                    @endif
                </button>
            </form>
        @endforeach
    </div>

    <p style="margin-top: 1.4rem;">
        <a href="{{ url()->previous() }}" style="color: #1d4ed8;">Cancelar</a>
    </p>
</div>
@endsection
