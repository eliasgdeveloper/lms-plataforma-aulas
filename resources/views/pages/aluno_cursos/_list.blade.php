<section class="courses-page" aria-label="Cursos para alunos">
    <header class="courses-hero">
        <h1>Cursos disponiveis</h1>
        <p>Escolha seu curso, veja a previa gratis, analise a grade e inicie sua jornada no LMS.</p>
    </header>

    <div class="courses-grid">
        @forelse ($courses as $course)
            <x-course-card :course="$course" />
        @empty
            <p role="status">Ainda não há cursos ativos disponíveis para matrícula.</p>
        @endforelse
    </div>
</section>