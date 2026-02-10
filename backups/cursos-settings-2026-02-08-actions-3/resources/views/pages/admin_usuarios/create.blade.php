@extends('layouts.admin')

@section('admin-content')
<div style="padding-left: 24px; padding-right: 24px;">
<div class="container mx-auto px-4 py-6">
    @if ($errors->any())
        <div x-data="{ showErrorModal: true }">
            <div x-show="showErrorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4">
                    <div class="px-6 py-5 border-b border-red-100 bg-red-50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">⚠️</span>
                                <div>
                                    <p class="text-sm text-red-700">Nao foi possivel cadastrar</p>
                                    <h3 class="text-lg font-semibold text-red-800">Usuario nao cadastrado, verifique todos os campos.</h3>
                                </div>
                            </div>
                            <button type="button" @click="showErrorModal = false" class="text-red-700 hover:text-red-900 text-xl">×</button>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-600">Os campos com erro ja estao destacados em vermelho e o checklist no final mostra o que falta.</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end">
                        <button type="button" @click="showErrorModal = false" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Entendi</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- ===== BREADCRUMB ===== -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('admin.usuarios.index') }}" class="text-blue-600 hover:underline">👥 Usuários</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-semibold">➕ Novo Usuário</span>
    </div>

    <!-- ===== HEADER ===== -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">➕ Criar Novo Usuário</h1>
        <p class="text-gray-600 mt-2">Preencha os dados abaixo para adicionar um novo usuário à plataforma</p>
    </div>

    <!-- ===== FORM ===== -->
    <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl">
        
        <!-- Alert Erros -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start gap-2">
                    <span class="text-2xl">❌</span>
                    <div>
                        <h3 class="font-semibold text-red-800">Erros ao salvar</h3>
                        <ul class="list-disc list-inside text-red-700 text-sm mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.usuarios.store') }}" method="POST" x-data="createForm()" x-init="initFromDom()">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- ===== NOME ===== -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        👤 Nome Completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Ex: João Silva"
                           required
                              x-ref="name"
                              x-model="user.name"
                              @input="user.name = $event.target.value"
                              @change="user.name = $event.target.value"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ===== EMAIL ===== -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        📧 Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Ex: joao@example.com"
                           required
                              x-ref="email"
                              x-model="user.email"
                              @input="user.email = $event.target.value"
                              @change="user.email = $event.target.value"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p :class="user.email && validateEmail(user.email) ? 'text-green-600' : 'text-gray-500'" class="text-xs mt-1">
                        <span x-show="user.email && validateEmail(user.email)">✅ Email válido</span>
                        <span x-show="user.email && !validateEmail(user.email)">❌ Email inválido</span>
                        <span x-show="!user.email">📧 Será usado para login no sistema</span>
                    </p>
                </div>

                <!-- ===== ROLE (PAPEL) ===== -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        📋 Papel <span class="text-red-500">*</span>
                    </label>
                    <select id="role"
                            name="role"
                            required
                            x-ref="role"
                            x-model="user.role"
                            @change="user.role = $event.target.value"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                        <option value="">-- Selecione um papel --</option>
                        <option value="aluno" @selected(old('role') === 'aluno')>👤 Aluno</option>
                        <option value="professor" @selected(old('role') === 'professor')>👨‍🏫 Professor</option>
                        <option value="admin" @selected(old('role') === 'admin')>👑 Admin</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ===== STATUS ===== -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        ⚡ Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status"
                            name="status"
                            required
                            x-ref="status"
                            x-model="user.status"
                            @change="user.status = $event.target.value"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="">-- Selecione um status --</option>
                        <option value="active" @selected(old('status') === 'active')>🟢 Ativo</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>🔴 Inativo</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ===== SENHA ===== -->
                <div class="md:col-span-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        🔐 Senha <span class="text-red-500">*</span>
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Mínimo 8 caracteres"
                           required
                              x-ref="password"
                              x-model="user.password"
                              @input="validatePassword($event.target.value)"
                              @change="validatePassword($event.target.value)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                           autocomplete="new-password">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-3 space-y-2">
                        <div class="flex items-center gap-2 text-sm">
                            <span :class="user.password.length >= 8 ? 'text-green-600' : 'text-gray-400'">✓ Mínimo 8 caracteres</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <span :class="/[A-Z]/.test(user.password) ? 'text-green-600' : 'text-gray-400'">✓ Letra maiúscula (A-Z)</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <span :class="/\d/.test(user.password) ? 'text-green-600' : 'text-gray-400'">✓ Número (0-9)</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <span :class="/[^A-Za-z0-9]/.test(user.password) ? 'text-green-600' : 'text-gray-400'">✓ Símbolo (!@#$)</span>
                        </div>
                    </div>
                </div>

                <!-- ===== CONFIRMAR SENHA ===== -->
                <div class="md:col-span-2">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        🔐 Confirmar Senha <span class="text-red-500">*</span>
                    </label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Repita a senha"
                           required
                              x-ref="password_confirmation"
                              x-model="user.password_confirmation"
                              @input="user.password_confirmation = $event.target.value"
                              @change="user.password_confirmation = $event.target.value"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           autocomplete="new-password">
                    <p :class="passwordsMatch ? 'text-green-600' : 'text-red-600'" class="text-sm mt-1">
                        <span x-show="!passwordsMatch">❌ As senhas não conferem</span>
                        <span x-show="passwordsMatch">✅ As senhas conferem</span>
                    </p>
                </div>

                <!-- ===== CAMPOS OPCIONAIS ===== -->
                <details class="md:col-span-2" open>
                    <summary class="cursor-pointer font-semibold text-gray-700 mb-4">📝 Campos Opcionais</summary>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Telefone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                📱 Telefone
                            </label>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="Ex: (11) 98765-4321"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Bio -->
                        <div class="md:col-span-2">
                            <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">
                                💬 Bio
                            </label>
                            <textarea id="bio"
                                      name="bio"
                                      rows="3"
                                      placeholder="Descrição breve do usuário"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio') }}</textarea>
                        </div>
                    </div>
                </details>

            </div>

            <!-- ===== VALIDATION STATUS ===== -->
            <div class="mt-6 mb-6 p-4 rounded-md" 
                 :class="formValid ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200'">
                <div class="text-sm font-semibold mb-2" 
                     :class="formValid ? 'text-green-800' : 'text-yellow-800'">
                    <span x-show="formValid">✅ Formulário completo e pronto para submeter!</span>
                    <span x-show="!formValid">⚠️ Preencha todos os campos corretamente:</span>
                </div>
                <ul class="text-xs space-y-1" :class="formValid ? 'text-green-700' : 'text-yellow-700'">
                    <li :class="user.name ? 'text-green-600' : 'text-gray-400'">✓ Nome completo</li>
                    <li :class="user.email && validateEmail(user.email) ? 'text-green-600' : 'text-gray-400'">✓ Email válido</li>
                    <li :class="user.role ? 'text-green-600' : 'text-gray-400'">✓ Papel (Role) selecionado</li>
                    <li :class="user.status ? 'text-green-600' : 'text-gray-400'">✓ Status selecionado</li>
                    <li :class="validatePasswordStrength(user.password) ? 'text-green-600' : 'text-gray-400'">✓ Senha válida (min 8, maiúscula, número, símbolo)</li>
                    <li :class="passwordsMatch ? 'text-green-600' : 'text-gray-400'">✓ Senhas conferem</li>
                </ul>
            </div>

            <!-- ===== ACTIONS ===== -->
            <div class="mt-8 flex gap-4 justify-end">
                <a href="{{ route('admin.usuarios.index') }}" 
                   class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition">
                    ❌ Cancelar
                </a>
                <button type="submit"
                        :disabled="!formValid"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed disabled:opacity-50">
                    ✅ Criar Usuário
                </button>
            </div>
        </form>
    </div>

</div>
</div>

<!-- ===== Alpine.js Data & Methods ===== -->
<script>
function createForm() {
    return {
        user: {
            name: @js(old('name', '')),
            email: @js(old('email', '')),
            role: @js(old('role', '')),
            status: @js(old('status', '')),
            password: @js(old('password', '')),
            password_confirmation: @js(old('password_confirmation', ''))
        },

        initFromDom() {
            setTimeout(() => {
                if (this.$refs.name && this.$refs.name.value) {
                    this.user.name = this.$refs.name.value;
                }
                if (this.$refs.email && this.$refs.email.value) {
                    this.user.email = this.$refs.email.value;
                }
                if (this.$refs.role && this.$refs.role.value) {
                    this.user.role = this.$refs.role.value;
                }
                if (this.$refs.status && this.$refs.status.value) {
                    this.user.status = this.$refs.status.value;
                }
                if (this.$refs.password && this.$refs.password.value) {
                    this.user.password = this.$refs.password.value;
                }
                if (this.$refs.password_confirmation && this.$refs.password_confirmation.value) {
                    this.user.password_confirmation = this.$refs.password_confirmation.value;
                }
            }, 0);
        },
        
        get passwordsMatch() {
            return this.user.password && 
                   this.user.password.length > 0 && 
                   this.user.password === this.user.password_confirmation &&
                   this.user.password_confirmation.length > 0;
        },

        get formValid() {
            return this.user.name && 
                   this.user.email && 
                   this.validateEmail(this.user.email) &&
                   this.user.role && 
                   this.user.status && 
                   this.validatePasswordStrength(this.user.password) &&
                   this.passwordsMatch;
        },

        validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        },

        validatePassword(password) {
            this.user.password = password;
        },

        validatePasswordStrength(password) {
            if (!password) return false;
            if (password.length < 8) return false;
            if (!/[A-Z]/.test(password)) return false;
            if (!/\d/.test(password)) return false;
            if (!/[^A-Za-z0-9]/.test(password)) return false;
            return true;
        }
    };
}
</script>

<link rel="stylesheet" href="{{ asset('pages/admin_usuarios/style.css') }}">
<script src="{{ asset('pages/admin_usuarios/script.js') }}" defer></script>
@endsection
