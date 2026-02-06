<x-app-layout>
    <div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-lg">
        <!-- Título -->
        <h1 class="text-3xl font-extrabold text-red-600 mb-4">Área do Admin</h1>
        <p class="text-gray-600 mb-8">Aqui você pode gerenciar usuários, cursos e configurações da plataforma.</p>

        <!-- Menu em grid -->
        <nav>
            <ul class="grid grid-cols-3 gap-6">
                <li>
                    <a href="{{ route('admin.usuarios') }}"
                       class="block text-center px-6 py-4 bg-gray-100 text-red-600 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                       Gerenciar Usuários
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.cursos') }}"
                       class="block text-center px-6 py-4 bg-gray-100 text-yellow-600 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                       Gerenciar Cursos
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.configuracoes') }}"
                       class="block text-center px-6 py-4 bg-gray-100 text-gray-800 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                       Configurações
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</x-app-layout>



