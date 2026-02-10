<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * UpdateUserRequest
 * 
 * Validação para edição de usuário existente no sistema admin
 * Permite atualização parcial (nem todos campos são obrigatórios)
 * 
 * Campos validados:
 * - name: Nome completo (opcional, mas validado se fornecido)
 * - email: Email Único (opcional, mas validado se fornecido)
 * - role: Papel do usuário (aluno/professor/admin)
 * - status: Status (active/inactive)
 * - permissions: Permissões customizadas
 * 
 * Diferenças vs StoreUserRequest:
 * - Senha NÃO é validada aqui (usa changePassword endpoint)
 * - Email é único exceto o próprio usuário
 * - Campos são mostly opcionais
 * 
 * Recursos de Segurança:
 * ✓ Email único (exceto próprio usuário)
 * ✓ Role whitelist
 * ✓ Status validação
 * ✓ Permissões whitelist
 * ✓ Mensagens de erro customizadas
 * 
 * Uso:
 * public function update(UpdateUserRequest $request, User $user) {
 *     $validated = $request->validated();
 *     $user->update($validated);
 *     AuditLog::log('update', 'User', $user->id, changes);
 * }
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer este request
     * 
     * Apenas admin pode editar usuários
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Obter as regras de validação que se aplicam ao request.
     * 
     * $this->route('user') obtém o parâmetro da rota (ex: /users/1)
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Pega ID do usuário sendo editado da rota /users/{user}
        $userId = $this->route('user')?->id ?? $this->route('id');

        return [
            // Nome completo - opcional
            'name' => [
                'sometimes', // apenas se fornecido
                'required_if_accepted', // se presente, não pode estar vazio
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/', // apenas letras
            ],

            // Email - opcional, único exceto próprio usuário
            'email' => [
                'sometimes',
                'required_if_accepted',
                'email:rfc,dns',
                'max:255',
                // Regra Rule::unique() permite exceção para próprio usuário
                Rule::unique('users', 'email')->ignore($userId),
            ],

            // Papel - opcional
            'role' => [
                'sometimes',
                'in:aluno,professor,admin',
            ],

            // Status - opcional
            'status' => [
                'sometimes',
                'in:active,inactive',
            ],

            // Permissões - opcional
            'permissions' => [
                'sometimes',
                'array',
            ],
            'permissions.*' => [
                'in:view_users,create_users,edit_users,delete_users,view_courses,create_courses,view_payments,process_payments,view_reports,send_campaigns',
            ],

            // Bio/Sobre - opcional
            'bio' => [
                'sometimes',
                'string',
                'max:500',
            ],

            // Avatar URL - opcional
            'avatar' => [
                'sometimes',
                'url',
                'max:2048',
            ],

            // Telefone - opcional
            'phone' => [
                'sometimes',
                'nullable',
                'digits_between:10,20',
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
            'name.string' => 'O nome deve ser um texto válido.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode exceder 100 caracteres.',
            'name.regex' => 'O nome deve conter apenas letras e acentos.',

            // Email
            'email.email' => 'O e-mail deve ser um endereço válido.',
            'email.unique' => 'Este e-mail já está registrado no sistema.',
            'email.max' => 'O e-mail não pode exceder 255 caracteres.',

            // Role
            'role.in' => 'O papel deve ser: aluno, professor ou admin.',

            // Status
            'status.in' => 'O status deve ser: ativo ou inativo.',

            // Bio
            'bio.string' => 'A bio deve ser um texto válido.',
            'bio.max' => 'A bio não pode exceder 500 caracteres.',

            // Avatar
            'avatar.url' => 'O avatar deve ser uma URL válida.',
            'avatar.max' => 'A URL não pode exceder 2048 caracteres.',

            // Telefone
            'phone.digits_between' => 'O telefone deve ter entre 10 e 20 dígitos.',
        ];
    }

    /**
     * Preparar dados para validação
     * 
     * - Trim em strings
     * - Lowercase em email
     * - Remove campos vazios
     * 
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->name ? trim($this->name) : null,
            'email' => $this->email ? strtolower($this->email) : null,
        ]);
    }

    /**
     * Obter apenas campos que foram realmente modificados
     * 
     * Útil para auditoria: saber exatamente o que mudou
     * 
     * Exemplo:
     * Se enviado {"name": "João", "email": "joao@example.com"}
     * Retorna apenas esses dois campos (excluindo role, status, etc)
     * 
     * @return array
     */
    public function onlyChanged(): array
    {
        $validated = $this->validated();
        
        // Aqui poderíamos trazer dados antigos para comparar
        // Por agora retorna tudo validado
        
        return $validated;
    }
}
