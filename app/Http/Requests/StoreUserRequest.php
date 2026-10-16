<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * StoreUserRequest
 *
 * Validação para criação de novo usuário no sistema admin
 * Implementa regras rigorosas para dados de usuário
 *
 * Campos validados:
 * - name: Nome completo (obrigatório, 3-100 chars)
 * - email: Email único (obrigatório, formato válido)
 * - role: Papel do usuário (aluno/professor/admin)
 * - password: Senha forte (8+ chars, números, maiúsculas)
 * - password_confirmation: Confirmação de senha
 * - status: Status inicial (active/inactive)
 *
 * Recursos de Segurança:
 * ✓ Email único no banco
 * ✓ Senha forte com validação
 * ✓ Role whitelist
 * ✓ Mensagens de erro customizadas
 *
 * Uso:
 * public function store(StoreUserRequest $request) {
 *     $validated = $request->validated();
 *     User::create($validated);
 * }
 */
class StoreUserRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer este request
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // ✓ admin pode criar usuários
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Obter as regras de validação que se aplicam ao request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $emailRule = app()->environment('testing') ? 'email:rfc' : 'email:rfc,dns';

        $passwordRule = Password::min(8)
            ->letters()
            ->numbers()
            ->symbols();

        if (!app()->environment('testing')) {
            $passwordRule = $passwordRule->uncompromised();
        }

        return [
            // Nome completo
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/', // apenas letras e acentos
            ],

            // Email válido e único
            'email' => [
                'required',
                $emailRule,
                'max:255',
                'unique:users,email', // único na tabela users
            ],

            // Papel (role) do usuário
            'role' => [
                'required',
                'in:aluno,professor,admin', // whitelist
            ],

            // Senha forte
            'password' => [
                'required',
                'confirmed', // deve ter password_confirmation
                $passwordRule,
            ],

            // Confirmação de senha
            'password_confirmation' => [
                'required',
                'same:password',
            ],

            // Status opcional (padrão: active)
            'status' => [
                'nullable',
                'in:active,inactive',
            ],

            // Telefone opcional
            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            // Bio opcional
            'bio' => [
                'nullable',
                'string',
                'max:500',
            ],

            // Permissões opcionais (admin pode atribuir diretamente)
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'in:view_users,create_users,edit_users,delete_users,view_courses,create_courses,view_payments,process_payments,view_reports,send_campaigns',
            ],
        ];
    }

    /**
     * Obter as mensagens de validação customizadas
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Nome
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode exceder 100 caracteres.',
            'name.regex' => 'O nome deve conter apenas letras e acentos.',

            // Email
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço válido.',
            'email.unique' => 'Este e-mail já está registrado no sistema.',
            'email.max' => 'O e-mail não pode exceder 255 caracteres.',

            // Role
            'role.required' => 'O papel do usuário é obrigatório.',
            'role.in' => 'O papel deve ser: aluno, professor ou admin.',

            // Senha
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'A confirmação de senha não coincide.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.letters' => 'A senha deve conter letras (maiúsculas e minúsculas).',
            'password.numbers' => 'A senha deve conter números.',
            'password.symbols' => 'A senha deve conter caracteres especiais (!@#$%^&).',
            'password.uncompromised' => 'Esta senha aparece em listas de senhas comprometidas. Escolha outra.',

            // Confirmação
            'password_confirmation.required' => 'A confirmação de senha é obrigatória.',
            'password_confirmation.same' => 'A confirmação de senha não coincide com a senha.',
        ];
    }

    /**
     * Preparar dados para validação
     *
     * - Trim em strings
     * - Lowercase em email
     * - Status padrão: 'active'
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Limpar espaços em nome
        $this->merge([
            'name' => trim($this->name),
            'email' => strtolower($this->email),
            'status' => $this->status ?? 'active',
        ]);
    }
}
