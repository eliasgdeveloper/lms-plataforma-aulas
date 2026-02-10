<x-app-layout>
    <div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-extrabold text-blue-600 mb-4">Área do Aluno</h1>
        <p class="text-gray-600 mb-8">Aqui você acessa suas aulas, materiais e notas.</p>

        <nav>
            <ul class="grid grid-cols-3 gap-6">
                <li>
                    <a href="{{ route('aluno.aulas') }}"
                       class="block text-center px-6 py-4 bg-gray-100 text-blue-600 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                       Minhas Aulas
                    </a>
                </li>
                <li>
                    <a href="{{ route('aluno.materiais') }}"
                       class="block text-center px-6 py-4 bg-gray-100 text-green-600 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                       Materiais
                    </a>
                </li>
                <li>
                    <a href="{{ route('aluno.notas') }}"
                       class="block text-center px-6 py-4 bg-gray-100 text-purple-600 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                       Notas
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</x-app-layout>



