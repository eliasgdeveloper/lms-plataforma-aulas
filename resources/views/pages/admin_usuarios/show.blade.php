@extends('layouts.page')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- ===== BREADCRUMB ===== -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('admin.usuarios.index') }}" class="text-blue-600 hover:underline">👥 Usuários</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-semibold">👤 {{ $user->name }}</span>
    </div>

    <!-- ===== HEADER COM AVATAR ===== -->
    <div class="mb-8 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-lg p-8">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128' }}" 
                 alt="{{ $user->name }}"
                 class="w-24 h-24 rounded-full border-4 border-white">
            <div class="flex-1">
                <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                <p class="text-blue-100 mt-1">{{ $user->email }}</p>
                <div class="flex flex-wrap gap-2 mt-4">
                    <!-- Role Badge -->
                    @php
                        $roleBadges = [
                            'admin' => ['bg-red-100', 'text-red-800', '👑 Admin'],
                            'professor' => ['bg-blue-100', 'text-blue-800', '👨‍🏫 Professor'],
                            'aluno' => ['bg-green-100', 'text-green-800', '👤 Aluno'],
                        ];
                        $badge = $roleBadges[$user->role] ?? ['bg-gray-100', 'text-gray-800', $user->role];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $badge[0] }} {{ $badge[1] }}">
                        {{ $badge[2] }}
                    </span>

                    <!-- Status Badge -->
                    @if($user->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            🟢 Ativo
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                            🔴 Inativo
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ===== COLUNA ESQUERDA: DETALHES ===== -->
        <div class="lg:col-span-2 space-y-6">

            <!-- ===== INFORMAÇÕES PESSOAIS ===== -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">👤 Informações Pessoais</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Nome</p>
                        <p class="text-lg text-gray-900 mt-1">{{ $user->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Email</p>
                        <p class="text-lg text-gray-900 mt-1">
                            <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:underline">{{ $user->email }}</a>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Papel</p>
                        <p class="text-lg text-gray-900 mt-1">
                            @php
                                $roles = ['admin' => 'Administrador', 'professor' => 'Professor', 'aluno' => 'Aluno'];
                            @endphp
                            {{ $roles[$user->role] ?? $user->role }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Status</p>
                        <p class="text-lg text-gray-900 mt-1">
                            {{ $user->status === 'active' ? '🟢 Ativo' : '🔴 Inativo' }}
                        </p>
                    </div>

                    @if($user->phone)
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Telefone</p>
                            <p class="text-lg text-gray-900 mt-1">
                                <a href="tel:{{ $user->phone }}" class="text-blue-600 hover:underline">{{ $user->phone }}</a>
                            </p>
                        </div>
                    @endif
                </div>

                @if($user->bio)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600 font-semibold">Bio</p>
                        <p class="text-gray-700 mt-1">{{ $user->bio }}</p>
                    </div>
                @endif
            </div>

            <!-- ===== INFORMAÇÕES DO SISTEMA ===== -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">⚙️ Informações do Sistema</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-600 font-semibold">ID do Usuário</p>
                        <p class="text-gray-900 font-mono">{{ $user->id }}</p>
                    </div>

                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-600 font-semibold">Criado em</p>
                        <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-600 font-semibold">Última atualização</p>
                        <p class="text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($user->deleted_at)
                        <div class="flex justify-between items-center text-red-600">
                            <p class="text-sm font-semibold">Deletado em</p>
                            <p>{{ $user->deleted_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-600 font-semibold">Email verificado</p>
                        <p class="text-gray-900">
                            {{ $user->email_verified_at ? '✅ ' . $user->email_verified_at->format('d/m/Y') : '❌ Não verificado' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- ===== ESTATÍSTICAS ===== -->
            @if($user->role === 'aluno' || $user->role === 'professor')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">📊 Estatísticas</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">0</p>
                            <p class="text-sm text-gray-600 mt-1">Matrículas</p>
                        </div>

                        <div class="text-center">
                            <p class="text-3xl font-bold text-green-600">0</p>
                            <p class="text-sm text-gray-600 mt-1">Cursos</p>
                        </div>

                        <div class="text-center">
                            <p class="text-3xl font-bold text-yellow-600">0</p>
                            <p class="text-sm text-gray-600 mt-1">Pagamentos</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- ===== COLUNA DIREITA: AÇÕES E AUDIT LOG ===== -->
        <div class="space-y-6">

            <!-- ===== AÇÕES RÁPIDAS ===== -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">⚡ Ações</h2>
                
                <div class="space-y-2">
                    <!-- Editar -->
                    <a href="{{ route('admin.usuarios.edit', $user) }}"
                       class="block w-full px-4 py-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-md text-sm font-semibold text-center transition">
                        ✏️ Editar Usuário
                    </a>

                    <!-- Alternar Status -->
                    <button type="button"
                            @click="toggleStatus = true"
                            class="w-full px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md text-sm font-semibold transition">
                        {{ $user->status === 'active' ? '🔴 Desativar' : '🟢 Ativar' }}
                    </button>

                    <!-- Alterar Senha -->
                    <button type="button"
                            @click="changePwdModal = true"
                            class="w-full px-4 py-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-md text-sm font-semibold transition">
                        🔐 Alterar Senha
                    </button>

                    <!-- Deletar -->
                    <button type="button"
                            @click="deleteModal = true"
                            class="w-full px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-md text-sm font-semibold transition">
                        🗑️ Deletar Usuário
                    </button>
                </div>
            </div>

            <!-- ===== AUDIT LOG (Últimas Ações) ===== -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">📋 Histórico de Ações</h2>
                
                <div class="space-y-3">
                    <!-- Último acesso -->
                    <div class="p-3 bg-gray-50 rounded-md">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-sm text-gray-900">👁️ Visualizado</p>
                                <p class="text-xs text-gray-600 mt-1">Há 2 minutos</p>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">Info</span>
                        </div>
                    </div>

                    <!-- Última edição -->
                    <div class="p-3 bg-gray-50 rounded-md">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-sm text-gray-900">✏️ Editado</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">Update</span>
                        </div>
                    </div>

                    <!-- Criação -->
                    <div class="p-3 bg-gray-50 rounded-md">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-sm text-gray-900">✨ Criado</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Create</span>
                        </div>
                    </div>
                </div>

                <a href="#" class="block mt-4 text-center text-blue-600 hover:underline text-sm font-semibold">
                    Ver histórico completo →
                </a>
            </div>

        </div>

    </div>

</div>

<!-- ===== MODALS ALPINE.JS ===== -->
<div x-data="{ deleteModal: false, toggleStatus: false, changePwdModal: false }">

    <!-- Modal Deletar Usuário -->
    <div x-show="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm">
            <h3 class="text-lg font-semibold text-red-600 mb-4">⚠️ Confirmar Exclusão</h3>
            <p class="text-gray-600 mb-2">Tem certeza que deseja deletar o usuário <strong>{{ $user->name }}</strong>?</p>
            <p class="text-sm text-gray-500 mb-6">Os dados serão preservados (soft delete GDPR-compliant).</p>
            <div class="flex gap-4">
                <button type="button"
                        @click="deleteModal = false"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 transition">
                        Deletar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Toggle Status -->
    <div x-show="toggleStatus" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm">
            <h3 class="text-lg font-semibold text-blue-600 mb-4">⚡ Alterar Status</h3>
            <p class="text-gray-600 mb-6">
                Deseja {{ $user->status === 'active' ? 'desativar' : 'ativar' }} o usuário <strong>{{ $user->name }}</strong>?
            </p>
            <div class="flex gap-4">
                <button type="button"
                        @click="toggleStatus = false"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <form action="{{ route('admin.usuarios.toggleStatus', $user) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                        Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Change Password -->
    <div x-show="changePwdModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm">
            <h3 class="text-lg font-semibold text-purple-600 mb-4">🔐 Alterar Senha</h3>
            <form action="{{ route('admin.usuarios.changePassword', $user) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">Nova Senha</label>
                    <input type="password"
                           id="new_password"
                           name="new_password"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="flex gap-4">
                    <button type="button"
                            @click="changePwdModal = false"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-purple-600 text-white font-semibold rounded-md hover:bg-purple-700 transition">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@vite([
    'resources/views/pages/admin_usuarios/style.css',
    'resources/views/pages/admin_usuarios/script.js'
])
@endsection
