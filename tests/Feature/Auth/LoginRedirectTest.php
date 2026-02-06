<?php
namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_aluno_is_redirected_to_aluno_dashboard_after_login(): void
    {
        $user = User::factory()->create([
            'role' => 'aluno',
            'password' => bcrypt('password'),
        ]);

        // Não seguir redirects — vamos inspecionar a resposta de redirecionamento

        // Verifique qual implementação está sendo resolvida pelo container
        $this->assertInstanceOf(
            \App\Http\Responses\LoginResponse::class,
            app(\Laravel\Fortify\Contracts\LoginResponse::class)
        );

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // O LoginResponse adiciona um cabeçalho para depuração; verifique-o
        $this->assertEquals(route('aluno.dashboard'), $response->headers->get('X-Login-Target'));

        // Deve ser um redirect 302 para a rota do aluno
        $response->assertStatus(302);
        $this->assertEquals(route('aluno.dashboard'), $response->headers->get('Location'));

        // Opcional: siga manualmente e verifique o conteúdo final
        $final = $this->get(route('aluno.dashboard'));
        $final->assertSee('Área do Aluno');
    }
}