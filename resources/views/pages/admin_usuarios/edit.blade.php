@extends('layouts.page')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- ===== BREADCRUMB ===== -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('admin.usuarios.index') }}" class="text-blue-600 hover:underline">👥 Usuários</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.usuarios.show', $user) }}" class="text-blue-600 hover:underline">{{ $user->name }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-semibold">✏️ Editar</span>
    </div>

    <!-- ===== HEADER ===== -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Usuário</h1>
        <p class="text-gray-600 mt-2">
            Atualize as informações de <strong>{{ $user->name }}</strong>
        </p>
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

        <!-- Alert Sucesso (se de volta de validação com sucesso anterior) -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-start gap-2">
                    <span class="text-2xl">✅</span>
                    <div>
                        <h3 class="font-semibold text-green-800">{{ session('success') }}</h3>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.usuarios.update', $user) }}" method="POST" x-data="editForm()">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- ===== AVATAR ===== -->
                <div class="md:col-span-2 flex items-center gap-4 pb-6 border-b">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                         alt="{{ $user->name }}"
                         class="w-20 h-20 rounded-full border-2 border-gray-300">
                    <div>
                        <p class="text-sm text-gray-600">Avatar do usuário</p>
                        <button type="button" 
                                @click="alert('Upload de avatar virá em breve')"
                                class="mt-2 px-3 py-1 bg-blue-100 text-blue-600 rounded text-sm font-semibold hover:bg-blue-200 transition">
                            📷 Alterar Avatar
                        </button>
                    </div>
                </div>

                <!-- ===== NOME ===== -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        👤 Nome Completo
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           required
                           @input="user.name = $event.target.value; markChanged('name')"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ===== EMAIL ===== -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        📧 Email
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           @input="user.email = $event.target.value; markChanged('email')"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Email único no sistema</p>
                </div>

                <!-- ===== ROLE (PAPEL) ===== -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        📋 Papel
                    </label>
                    <select id="role"
                            name="role"
                            required
                            @change="user.role = $event.target.value; markChanged('role')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                        <option value="aluno" @selected(old('role', $user->role) === 'aluno')>👤 Aluno</option>
                        <option value="professor" @selected(old('role', $user->role) === 'professor')>👨‍🏫 Professor</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>👑 Admin</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ===== STATUS ===== -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        ⚡ Status
                    </label>
                    <select id="status"
                            name="status"
                            required
                            @change="user.status = $event.target.value; markChanged('status')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="active" @selected(old('status', $user->status) === 'active')>🟢 Ativo</option>
                        <option value="inactive" @selected(old('status', $user->status) === 'inactive')>🔴 Inativo</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="Ex: (11) 98765-4321"
                                   @input="user.phone = $event.target.value; markChanged('phone')"
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
                                      @input="user.bio = $event.target.value; markChanged('bio')"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                    </div>
                </details>

                <!-- ===== SEÇÃO SENHA (Colapsível) ===== -->
                <details class="md:col-span-2">
                    <summary class="cursor-pointer font-semibold text-gray-700 mb-4">🔐 Alterar Senha</summary>
                    
                    <div class="space-y-4 mt-4 p-4 bg-gray-50 rounded-md">
                        <p class="text-sm text-gray-600">Deixe em branco para manter a senha atual</p>

                        <!-- Nova Senha -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                🔐 Nova Senha
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   placeholder="Deixe em branco para manter atual"
                                   @input="user.password = $event.target.value; markChanged('password')"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Senha -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                🔐 Confirmar Nova Senha
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Repita a nova senha"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </details>

                <!-- ===== INFO METADATA ===== -->
                <div class="md:col-span-2 p-4 bg-gray-50 rounded-md text-sm text-gray-600 space-y-1">
                    <p><strong>📅 Criado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>✏️ Última atualização:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    @if($user->deleted_at)
                        <p><strong>🗑️ Deletado em:</strong> {{ $user->deleted_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>

            </div>

            <!-- ===== ACTIONS ===== -->
            <div class="mt-8 flex gap-4 justify-between">
                <a href="{{ route('admin.usuarios.show', $user) }}" 
                   class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition">
                    ❌ Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    💾 Salvar Alterações
                </button>
            </div>
        </form>
    </div>

</div>

<!-- ===== Alpine.js Data & Methods ===== -->
<script>
function editForm() {
    return {
        user: {
            name: '',
            email: '',
            role: '',
            status: '',
            password: '',
            phone: '',
            bio: ''
        },
        changed: {},

        markChanged(field) {
            this.changed[field] = true;
        },

        get hasChanges() {
            return Object.keys(this.changed).length > 0;
        }
    };
}
</script>

@vite([
    'resources/views/pages/admin_usuarios/style.css',
    'resources/views/pages/admin_usuarios/script.js'
])
@endsection
