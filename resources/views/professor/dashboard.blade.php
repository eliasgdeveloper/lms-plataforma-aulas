<x-app-layout>
    <div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-lg">
        <!-- Título -->
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-4">Área do Professor</h1>
        <p class="text-gray-600 mb-8">Aqui você pode criar aulas, postar materiais e acompanhar seus alunos.</p>

        <!-- Menu em grid -->
        <nav>
            <ul class="grid grid-cols-3 gap-6">
                <li>
                    <a href="{{ route('professor.aulas') }}"
                       class="block text-center px-6 py-4 bg-gray-100 text-indigo-600 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                       Criar Aula
                    </a>
                </li>
                <li>
                    <a href="{{ route('professor.materiais') }}"
                       class="block text-center px-6 py-4 bg-gray-100 text-teal-600 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                       Postar Material
                    </a>
                </li>
                <li>
                    <a href="{{ route('professor.alunos') }}"
                       class="block text-center px-6 py-4 bg-gray-100 text-pink-600 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                       Acompanhar Alunos
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</x-app-layout>


