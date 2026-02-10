@extends('layouts.admin')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    
    <!-- ===== HEADER ===== -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">👥 Gestão de Usuários</h1>
            <p class="text-gray-600 mt-2">Gerencie alunos, professores e administradores</p>
        </div>
        
        <!-- Botão Criar Novo Usuário -->
        <a href="{{ route('admin.usuarios.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Usuário
        </a>
    </div>

    <!-- ===== FILTROS ===== -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6" x-data="filterForm()">
        <form action="{{ route('admin.usuarios.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            
            <!-- Busca por Nome/Email -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">🔍 Buscar</label>
                  <input type="text"
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Nome ou email..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Filtro por Papel (Role) -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">📋 Papel</label>
                <select id="role" 
                    name="role" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos os papéis</option>
                    <option value="aluno" @selected(request('role') === 'aluno')>👤 Aluno</option>
                    <option value="professor" @selected(request('role') === 'professor')>👨‍🏫 Professor</option>
                    <option value="admin" @selected(request('role') === 'admin')>👑 Admin</option>
                </select>
            </div>

            <!-- Filtro por Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">⚡ Status</label>
                <select id="status" 
                    name="status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos os status</option>
                    <option value="active" @selected(request('status') === 'active')>🟢 Ativo</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>🔴 Inativo</option>
                </select>
            </div>

            <!-- Sortagem -->
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">🔀 Ordenar</label>
                <select id="sort" 
                    name="sort" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="created_at" @selected(request('sort') === 'created_at')>Mais Recentes</option>
                    <option value="name" @selected(request('sort') === 'name')>Nome (A-Z)</option>
                    <option value="email" @selected(request('sort') === 'email')>Email (A-Z)</option>
                </select>
            </div>

            <!-- Botão Buscar -->
            <div class="flex items-end">
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200 inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Buscar
                </button>
            </div>
        </form>

    </div>

    <!-- ===== TABELA DE USUÁRIOS ===== -->
    <div id="usuarios-table" class="bg-white rounded-lg shadow-md overflow-hidden">
        
        <!-- Desktop: Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nome</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Papel</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Criado em</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition" x-data="{ deleteModal: false }">
                            <!-- Nome + Avatar -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                                         alt="{{ $user->name }}"
                                         class="w-10 h-10 rounded-full">
                                    <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-6 py-4 text-gray-600 text-sm">{{ $user->email }}</td>

                            <!-- Papel (Role) com Badge -->
                            <td class="px-6 py-4">
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
                            </td>

                            <!-- Status com Badge -->
                            <td class="px-6 py-4">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        🟢 Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                        🔴 Inativo
                                    </span>
                                @endif
                            </td>

                            <!-- Data Criação -->
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </td>

                            <!-- Ações -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <!-- Ver Detalhes -->
                                    <a href="{{ route('admin.usuarios.show', $user) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md text-sm font-semibold transition"
                                       title="Ver detalhes">
                                        👁️
                                    </a>

                                    <!-- Editar -->
                                    <a href="{{ route('admin.usuarios.edit', $user) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-md text-sm font-semibold transition"
                                       title="Editar">
                                        ✏️
                                    </a>

                                    <!-- Deletar (com Modal Alpine) -->
                                    <button type="button"
                                            @click="deleteModal = true"
                                            class="inline-flex items-center gap-1 px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-md text-sm font-semibold transition"
                                            title="Deletar">
                                        🗑️
                                    </button>

                                    <!-- Modal de Confirmação Deletar -->
                                    <div x-show="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm">
                                            <h3 class="text-lg font-semibold text-red-600 mb-4">⚠️ Confirmar Exclusão</h3>
                                            <p class="text-gray-600 mb-6">Tem certeza que deseja deletar o usuário <strong>{{ $user->name }}</strong>?</p>
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
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <p class="text-lg">😕 Nenhum usuário encontrado</p>
                                <a href="{{ route('admin.usuarios.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                    Criar novo usuário
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile: Card View -->
        <div class="md:hidden divide-y">
            @forelse($users as $user)
                <div class="p-4 bg-white border-b hover:bg-gray-50 transition" x-data="{ deleteModal: false }">
                    <!-- Header Card -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                                 alt="{{ $user->name }}"
                                 class="w-8 h-8 rounded-full">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <!-- Status Badge -->
                        @if($user->status === 'active')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">🟢</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">🔴</span>
                        @endif
                    </div>

                    <!-- Role Badge -->
                    @php
                        $roleBadges = [
                            'admin' => ['bg-red-100', 'text-red-800', '👑 Admin'],
                            'professor' => ['bg-blue-100', 'text-blue-800', '👨‍🏫 Professor'],
                            'aluno' => ['bg-green-100', 'text-green-800', '👤 Aluno'],
                        ];
                        $badge = $roleBadges[$user->role] ?? ['bg-gray-100', 'text-gray-800', $user->role];
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $badge[0] }} {{ $badge[1] }} mb-3">
                        {{ $badge[2] }}
                    </span>

                    <!-- Data -->
                    <p class="text-xs text-gray-500 mb-3">Criado: {{ $user->created_at->format('d/m/Y') }}</p>

                    <!-- Ações -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.usuarios.show', $user) }}" 
                           class="flex-1 px-2 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-xs font-semibold text-center transition">
                            👁️ Ver
                        </a>
                        <a href="{{ route('admin.usuarios.edit', $user) }}" 
                           class="flex-1 px-2 py-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded text-xs font-semibold text-center transition">
                            ✏️ Editar
                        </a>
                        <button type="button"
                                @click="deleteModal = true"
                                class="flex-1 px-2 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded text-xs font-semibold text-center transition">
                            🗑️ Deletar
                        </button>
                    </div>

                    <!-- Modal Mobile -->
                    <div x-show="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm mx-4">
                            <h3 class="text-lg font-semibold text-red-600 mb-4">⚠️ Confirmar Exclusão</h3>
                            <p class="text-gray-600 mb-6">Deletar <strong>{{ $user->name }}</strong>?</p>
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
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <p>😕 Nenhum usuário encontrado</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ===== PAGINAÇÃO ===== -->
    @if($users->hasPages())
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    @endif

</div>

<!-- ===== Carregar CSS e JS ===== -->
<link rel="stylesheet" href="{{ asset('pages/admin_usuarios/style.css') }}">
<script src="{{ asset('pages/admin_usuarios/script.js') }}" defer></script>
@endsection
