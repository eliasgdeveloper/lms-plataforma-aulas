<?php

namespace App\Http\Controllers;

use App\Models\Conteudo;
use App\Models\ConteudoProgresso;
use App\Models\Enrollment;
use App\Support\DesafioCaixaJudge;
use App\Support\ViewAs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DesafioProvaController extends Controller
{
    public function judge(Request $request, Conteudo $conteudo): JsonResponse
    {
        $this->authorizeAluno($conteudo);

        $data = $request->validate([
            'codigo' => 'required|string|max:200000',
            'saida' => 'nullable|string|max:20000',
        ]);

        $user = auth()->user();
        $primeiro = explode(' ', trim((string) $user->name))[0] ?: 'Aluno';
        $result = DesafioCaixaJudge::grade($data['codigo'], (string) ($data['saida'] ?? ''), $primeiro);

        if ($result['ok'] && ! ViewAs::isPreview()) {
            $this->bumpProgress($user->id, $conteudo->id, 50);
        }

        $result['progress'] = $this->currentProgress($user->id, $conteudo->id);
        $result['preview'] = ViewAs::isPreview();
        $result['unlock_quiz'] = $result['ok'] || $result['progress'] >= 50;

        return response()->json($result);
    }

    public function completeQuiz(Request $request, Conteudo $conteudo): JsonResponse
    {
        $this->authorizeAluno($conteudo);

        $data = $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        $score = (int) $data['score'];
        $user = auth()->user();
        $passed = $score >= 70;
        $progress = $passed ? 100 : max(50, $score);

        if (! ViewAs::isPreview()) {
            $this->bumpProgress($user->id, $conteudo->id, $progress);
            if ($passed) {
                $this->awardBadge($user, 'caixa-litoral');
            }
        }

        return response()->json([
            'ok' => $passed,
            'score' => $score,
            'progress' => $this->currentProgress($user->id, $conteudo->id),
            'badge' => $passed ? '/badges/caixa-litoral.svg' : null,
            'preview' => ViewAs::isPreview(),
        ]);
    }

    private function authorizeAluno(Conteudo $conteudo): void
    {
        $user = auth()->user();
        if (! $user || ($user->role !== 'aluno' && ! ViewAs::isPreview())) {
            abort(403);
        }

        $cursoId = $conteudo->aula?->curso_id;
        if (! $cursoId) {
            abort(404);
        }

        if (ViewAs::isPreview()) {
            return;
        }

        $isEnrolled = Enrollment::query()
            ->where('aluno_id', $user->id)
            ->where('curso_id', $cursoId)
            ->exists();

        if (! $isEnrolled) {
            abort(403);
        }
    }

    private function bumpProgress(int $userId, int $conteudoId, int $incoming): void
    {
        $existing = ConteudoProgresso::query()
            ->where('user_id', $userId)
            ->where('conteudo_id', $conteudoId)
            ->first();

        $progress = max($incoming, (int) ($existing->progresso ?? 0));
        ConteudoProgresso::updateOrCreate(
            ['user_id' => $userId, 'conteudo_id' => $conteudoId],
            [
                'progresso' => $progress,
                'concluido' => $progress >= 90 || (bool) ($existing->concluido ?? false),
            ]
        );
    }

    private function currentProgress(int $userId, int $conteudoId): int
    {
        return (int) (ConteudoProgresso::query()
            ->where('user_id', $userId)
            ->where('conteudo_id', $conteudoId)
            ->value('progresso') ?? 0);
    }

    private function awardBadge($user, string $slug): void
    {
        if (! Schema::hasColumn('users', 'lms_badge')) {
            return;
        }
        $path = '/badges/'.$slug.'.svg';
        if ((string) $user->lms_badge !== $path) {
            $user->forceFill(['lms_badge' => $path])->save();
        }
    }
}
