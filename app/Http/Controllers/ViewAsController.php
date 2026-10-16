<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Support\ViewAs;
use Illuminate\Http\Request;

class ViewAsController extends Controller
{
    public function edit()
    {
        abort_unless(ViewAs::canSwitch(), 403, 'Acesso negado');

        if (! ViewAs::isPreview()) {
            $back = url()->previous();
            if ($this->safeReturn($back)) {
                session(['view_as_return' => $back]);
            }
        }

        return view('pages.view_as.edit', [
            'roles' => ViewAs::roles(),
            'realRole' => ViewAs::realRole(),
            'effectiveRole' => ViewAs::effectiveRole(),
        ]);
    }

    public function update(Request $request)
    {
        abort_unless(ViewAs::canSwitch(), 403, 'Acesso negado');

        $data = $request->validate([
            'role' => 'required|in:admin,professor,aluno,self',
            'curso_id' => 'nullable|integer|exists:cursos,id',
        ]);

        if ($data['role'] === 'self' || $data['role'] === ViewAs::realRole()) {
            $back = $request->session()->pull('view_as_return');
            $request->session()->forget(ViewAs::SESSION_KEY);
            if (! is_string($back) || ! str_starts_with($back, url('/'))) {
                $back = ViewAs::homeFor(ViewAs::realRole());
            }

            return redirect()->to($back);
        }

        $request->session()->put(ViewAs::SESSION_KEY, $data['role']);

        if ($data['role'] === 'aluno') {
            $request->user()->enrollInAvailableCourses();

            if (! empty($data['curso_id'])) {
                $curso = Curso::query()->findOrFail($data['curso_id']);

                return redirect()->route('aluno.cursos.show', $curso);
            }

            // Lista de cursos com acesso de prévia (sem pedir matrícula).
            return redirect()->route('aluno.cursos');
        }

        return redirect()->to(ViewAs::homeFor($data['role']));
    }

    private function safeReturn(?string $url): bool
    {
        if (! is_string($url) || ! str_starts_with($url, url('/'))) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '';
        if (str_starts_with($path, '/login') || str_starts_with($path, '/papel') || str_starts_with($path, '/logout')) {
            return false;
        }

        $real = ViewAs::realRole();
        if ($real === 'professor' && ! str_starts_with($path, '/professor')) {
            return false;
        }
        if ($real === 'admin' && ! str_starts_with($path, '/admin')) {
            return false;
        }

        return true;
    }
}
