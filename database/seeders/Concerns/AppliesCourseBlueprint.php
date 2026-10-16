<?php

namespace Database\Seeders\Concerns;

use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\Curso;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

/**
 * Aplica um Course Blueprint (array) ao banco — padrão reutilizável.
 */
trait AppliesCourseBlueprint
{
    protected function applyCourseBlueprint(array $blueprint): Curso
    {
        $curso = Curso::query()->find($blueprint['curso_id'] ?? null);

        if (!$curso) {
            $curso = Curso::query()->firstOrNew([
                'titulo' => $blueprint['titulo'],
            ]);
        }

        $professorId = $curso->professor_id
            ?? User::query()->where('role', 'admin')->value('id')
            ?? User::query()->where('role', 'professor')->value('id')
            ?? User::query()->value('id');

        $curso->fill([
            'titulo' => $blueprint['titulo'],
            'descricao' => $blueprint['descricao'],
            'categoria' => $blueprint['categoria'] ?? 'Programacao',
            'duracao_horas_min' => $blueprint['duracao_horas_min'] ?? null,
            'duracao_horas_max' => $blueprint['duracao_horas_max'] ?? null,
            'nivel_trilha' => $blueprint['nivel_trilha'] ?? 'trilha_completa',
            'status' => $blueprint['status'] ?? 'ativo',
            'professor_id' => $professorId,
        ]);

        if (!empty($blueprint['capa_filename'])) {
            $curso->capa_path = $this->ensureCourseCover(
                $blueprint['capa_filename'],
                $blueprint['titulo']
            );
        }

        $curso->save();

        $curso->matriz_path = $this->storeCourseMatrixPdf($curso->id, $blueprint);
        $curso->checklist_path = $this->storeCourseChecklistPdf($curso->id, $blueprint);
        $curso->save();

        $this->rebuildModules($curso, $blueprint['modulos'] ?? []);

        $emails = $blueprint['enroll_student_emails']
            ?? (isset($blueprint['enroll_student_email']) ? [$blueprint['enroll_student_email']] : []);
        foreach ($emails as $email) {
            $this->enrollStudentIfConfigured($curso, $email);
        }

        return $curso->fresh(['aulas.conteudos']);
    }

    protected function rebuildModules(Curso $curso, array $modulos): void
    {
        $aulaIds = Aula::query()->where('curso_id', $curso->id)->pluck('id');

        if ($aulaIds->isNotEmpty()) {
            Conteudo::query()->whereIn('aula_id', $aulaIds)->delete();
            Aula::query()->where('curso_id', $curso->id)->delete();
        }

        foreach ($modulos as $modulo) {
            $ordem = (int) ($modulo['ordem'] ?? 0);
            $nivel = $modulo['nivel'] ?? 'Geral';
            $tituloCurto = $modulo['titulo'] ?? 'Tópico';
            $titulo = sprintf('%02d · [%s] %s', $ordem, $nivel, $tituloCurto);

            $aula = Aula::query()->create([
                'curso_id' => $curso->id,
                'titulo' => $titulo,
                'descricao' => $modulo['descricao'] ?? null,
                'ordem' => $ordem,
                'is_hidden' => false,
            ]);

            $conteudoOrdem = 1;

            Conteudo::query()->create([
                'aula_id' => $aula->id,
                'titulo' => 'Resumo da missão',
                'descricao' => $modulo['descricao'] ?? null,
                'tipo' => 'texto',
                'url' => $modulo['resumo'] ?? $modulo['descricao'] ?? '',
                'ordem' => $conteudoOrdem++,
                'is_hidden' => false,
                'allow_download_student' => false,
                'allow_download_professor' => true,
            ]);

            $pdfPath = $this->storeModulePdf(
                $curso->id,
                $ordem,
                $modulo['pdf_title'] ?? $tituloCurto,
                $modulo['pdf_body'] ?? ($modulo['descricao'] ?? '')
            );

            Conteudo::query()->create([
                'aula_id' => $aula->id,
                'titulo' => $modulo['pdf_title'] ?? 'Guia PDF',
                'descricao' => $modulo['pdf_body'] ?? ($modulo['descricao'] ?? 'Material de apoio do módulo'),
                'tipo' => 'pdf',
                'arquivo' => $pdfPath,
                'ordem' => $conteudoOrdem++,
                'is_hidden' => false,
                'allow_download_student' => true,
                'allow_download_professor' => true,
            ]);

            $imagePath = $this->storeModuleImage(
                $curso->id,
                $ordem,
                $modulo['image_title'] ?? $tituloCurto,
                $nivel
            );

            Conteudo::query()->create([
                'aula_id' => $aula->id,
                'titulo' => $modulo['image_title'] ?? 'Card visual da missão',
                'descricao' => 'Imagem de estímulo da missão',
                'tipo' => 'arquivo',
                'arquivo' => $imagePath,
                'ordem' => $conteudoOrdem++,
                'is_hidden' => false,
                'allow_download_student' => true,
                'allow_download_professor' => true,
            ]);

            if (!empty($modulo['link_url'])) {
                Conteudo::query()->create([
                    'aula_id' => $aula->id,
                    'titulo' => $modulo['link_label'] ?? 'Leitura recomendada',
                    'descricao' => 'Referência / arena',
                    'tipo' => 'link',
                    'url' => $modulo['link_url'],
                    'ordem' => $conteudoOrdem++,
                    'is_hidden' => false,
                    'allow_download_student' => false,
                    'allow_download_professor' => true,
                ]);
            }

            if (!empty($modulo['quiz']) && is_array($modulo['quiz'])) {
                Conteudo::query()->create([
                    'aula_id' => $aula->id,
                    'titulo' => $modulo['quiz']['titulo'] ?? 'Quiz da missão',
                    'descricao' => 'Questionário gamificado · acerte para consolidar XP',
                    'tipo' => 'quiz',
                    'url' => json_encode($modulo['quiz'], JSON_UNESCAPED_UNICODE),
                    'ordem' => $conteudoOrdem++,
                    'is_hidden' => false,
                    'allow_download_student' => false,
                    'allow_download_professor' => true,
                ]);
            }

            if (!empty($modulo['extra']) && is_array($modulo['extra'])) {
                Conteudo::query()->create([
                    'aula_id' => $aula->id,
                    'titulo' => $modulo['extra']['titulo'] ?? 'Atividade',
                    'descricao' => $modulo['extra']['descricao'] ?? null,
                    'tipo' => $modulo['extra']['tipo'] ?? 'tarefa',
                    'url' => $modulo['extra']['url'] ?? null,
                    'ordem' => $conteudoOrdem++,
                    'is_hidden' => false,
                    'allow_download_student' => false,
                    'allow_download_professor' => true,
                ]);
            }
        }
    }

    protected function enrollStudentIfConfigured(Curso $curso, ?string $email): void
    {
        if (!$email) {
            return;
        }

        $aluno = User::query()->where('email', $email)->first();
        if (!$aluno) {
            return;
        }

        Enrollment::query()->firstOrCreate(
            [
                'aluno_id' => $aluno->id,
                'curso_id' => $curso->id,
            ],
            [
                'data_matricula' => now()->toDateString(),
                'status' => 'ativo',
            ]
        );
    }

    protected function ensureCourseCover(string $filename, string $title): string
    {
        $relative = 'cursos/capas/'.$filename;

        if (!Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->put($relative, $this->buildCoverSvg($title));
        }

        return $relative;
    }

    protected function storeModulePdf(int $cursoId, int $ordem, string $title, string $body): string
    {
        $relative = sprintf('conteudos/curso-%d/modulo-%02d.pdf', $cursoId, $ordem);
        Storage::disk('public')->put($relative, $this->buildSimplePdf($title, $body));

        return $relative;
    }

    protected function storeModuleImage(int $cursoId, int $ordem, string $title, string $nivel): string
    {
        $relative = sprintf('conteudos/curso-%d/modulo-%02d.svg', $cursoId, $ordem);
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeNivel = htmlspecialchars($nivel, ENT_QUOTES, 'UTF-8');
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="960" height="360" viewBox="0 0 960 360">
  <defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#0f172a"/><stop offset="100%" stop-color="#2563eb"/></linearGradient></defs>
  <rect width="960" height="360" rx="24" fill="url(#bg)"/>
  <text x="48" y="120" fill="#93c5fd" font-family="Segoe UI, Arial" font-size="28" font-weight="700">{$safeNivel}</text>
  <text x="48" y="190" fill="#ffffff" font-family="Segoe UI, Arial" font-size="40" font-weight="800">{$safeTitle}</text>
  <text x="48" y="250" fill="#bfdbfe" font-family="Segoe UI, Arial" font-size="22">Missao {$ordem} · continue, ganhe XP e avance a barra</text>
</svg>
SVG;
        Storage::disk('public')->put($relative, $svg);

        return $relative;
    }

    protected function storeCourseMatrixPdf(int $cursoId, array $blueprint): string
    {
        $lines = [
            $blueprint['matriz_title'] ?? 'Matriz curricular',
            'Curso: '.($blueprint['titulo'] ?? ''),
            'Carga: '.($blueprint['duracao_horas_min'] ?? '?').'-'.($blueprint['duracao_horas_max'] ?? '?').'h',
            '',
        ];
        foreach ($blueprint['modulos'] ?? [] as $mod) {
            $lines[] = sprintf(
                '%02d [%s] %s',
                (int) ($mod['ordem'] ?? 0),
                $mod['nivel'] ?? '',
                $mod['titulo'] ?? ''
            );
        }
        $relative = sprintf('cursos/matrizes/curso-%d-matriz.pdf', $cursoId ?: 3);
        Storage::disk('public')->put($relative, $this->buildSimplePdf('Matriz curricular', implode("\n", $lines)));

        return $relative;
    }

    protected function storeCourseChecklistPdf(int $cursoId, array $blueprint): string
    {
        $lines = [
            $blueprint['checklist_title'] ?? 'Checklist do curso',
            'Marque cada item ao concluir. Use o progresso do LMS como fonte da verdade.',
            '',
        ];
        foreach ($blueprint['modulos'] ?? [] as $mod) {
            $ordem = (int) ($mod['ordem'] ?? 0);
            $lines[] = sprintf('[ ] %02d %s — ler resumo', $ordem, $mod['titulo'] ?? '');
            $lines[] = sprintf('[ ] %02d %s — estudar PDF', $ordem, $mod['titulo'] ?? '');
            $lines[] = sprintf('[ ] %02d %s — abrir link/arena', $ordem, $mod['titulo'] ?? '');
            if (!empty($mod['quiz'])) {
                $lines[] = sprintf('[ ] %02d %s — quiz >= %s%%', $ordem, $mod['titulo'] ?? '', $mod['quiz']['pass_score'] ?? 70);
            }
            $lines[] = '';
        }
        $lines[] = '[ ] Capstone entregue';
        $lines[] = '[ ] Revisao final com o professor';
        $relative = sprintf('cursos/checklists/curso-%d-checklist.pdf', $cursoId ?: 3);
        Storage::disk('public')->put($relative, $this->buildSimplePdf('Checklist detalhado', implode("\n", $lines)));

        return $relative;
    }

    protected function buildCoverSvg(string $title): string
    {
        $safe = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630" viewBox="0 0 1200 630">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#0f172a"/>
      <stop offset="55%" stop-color="#1d4ed8"/>
      <stop offset="100%" stop-color="#38bdf8"/>
    </linearGradient>
  </defs>
  <rect width="1200" height="630" fill="url(#g)"/>
  <circle cx="980" cy="120" r="160" fill="#ffffff22"/>
  <circle cx="180" cy="520" r="120" fill="#ffffff18"/>
  <text x="80" y="250" fill="#e2e8f0" font-family="Segoe UI, Arial, sans-serif" font-size="42" font-weight="700">LMS · Missão</text>
  <text x="80" y="330" fill="#ffffff" font-family="Segoe UI, Arial, sans-serif" font-size="54" font-weight="800">{$safe}</text>
  <text x="80" y="400" fill="#bfdbfe" font-family="Segoe UI, Arial, sans-serif" font-size="28">Basico → Intermediario → Avancado</text>
</svg>
SVG;
    }

    /**
     * PDF mínimo de uma página (texto ASCII) — sem dependências.
     */
    protected function buildSimplePdf(string $title, string $body): string
    {
        $lines = array_merge(
            [$this->pdfSafe($title), ''],
            preg_split("/\r\n|\n|\r/", $body) ?: []
        );

        $content = "BT /F1 12 Tf 50 780 Td\n";
        $first = true;
        foreach ($lines as $line) {
            $safe = $this->pdfSafe($line);
            if ($first) {
                $content .= "({$safe}) Tj\n";
                $first = false;
            } else {
                $content .= "0 -16 Td ({$safe}) Tj\n";
            }
        }
        $content .= "ET";

        $objects = [];
        $objects[] = '1 0 obj<< /Type /Catalog /Pages 2 0 R >>endobj';
        $objects[] = '2 0 obj<< /Type /Pages /Kids [3 0 R] /Count 1 >>endobj';
        $objects[] = '3 0 obj<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources<< /Font<< /F1 5 0 R >> >> >>endobj';
        // Length = bytes entre "stream\n" e "endstream" (sem newline extra).
        $objects[] = '4 0 obj<< /Length '.strlen($content)." >>stream\n{$content}endstream endobj";
        $objects[] = '5 0 obj<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>endobj';

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object."\n";
        }

        $xrefPos = strlen($pdf);
        $pdf .= 'xref\n0 '.count($offsets)."\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i < count($offsets); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $pdf .= 'trailer<< /Size '.count($offsets).' /Root 1 0 R >>'."\n";
        $pdf .= "startxref\n{$xrefPos}\n%%EOF";

        return $pdf;
    }

    protected function pdfSafe(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
        $text = preg_replace('/[^\x20-\x7E]/', '?', $text) ?? $text;

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
