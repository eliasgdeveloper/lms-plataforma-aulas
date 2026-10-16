<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocalizationTest extends TestCase
{
    public function test_platform_defaults_to_brazilian_portuguese(): void
    {
        $this->assertSame('pt_BR', config('app.locale'));
        $this->assertSame('pt_BR', config('app.fallback_locale'));

        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="pt-BR">', false);

        $this->get('/login')
            ->assertOk()
            ->assertSee('Acesse sua conta');
    }

    public function test_framework_validation_and_authentication_messages_are_translated(): void
    {
        $this->assertSame(
            'O campo e-mail é obrigatório.',
            __('validation.required', ['attribute' => __('validation.attributes.email')])
        );
        $this->assertSame('Usuário ou senha incorretos.', __('auth.failed'));
        $this->assertSame('Entrar', __('Log in'));
    }
}
