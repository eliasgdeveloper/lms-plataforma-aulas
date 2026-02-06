<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Renderiza a página de edição de perfil. A view `profile.edit`
        // espera um array com o usuário atual para preencher o formulário.
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Valida e atualiza os dados do usuário com o FormRequest `ProfileUpdateRequest`.
        $request->user()->fill($request->validated());

        // Se o e-mail foi alterado, removemos a marca de verificação.
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Redireciona de volta para a edição com um 'status' flash para mostrar sucesso.
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Valida a senha atual do usuário antes de permitir exclusão da conta.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Desloga e remove o registro do usuário
        Auth::logout();

        $user->delete();

        // Invalida sessão atual e regenera token para segurança
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
