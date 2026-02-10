<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AuditLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * UserController - GestÃ£o de UsuÃ¡rios (Admin)
 *
 * Controlador responsÃ¡vel por toda a gestÃ£o CRUD de usuÃ¡rios no sistema.
 *
 * Implementa:
 * - Listagem com busca avanÃ§ada e filtros
 * - CriaÃ§Ã£o de novos usuÃ¡rios
 * - EdiÃ§Ã£o de dados do usuÃ¡rio
 * - ExclusÃ£o (soft delete com GDPR)
 * - Toggle de status (ativo/inativo)
 * - AlteraÃ§Ã£o de senha
 * - HistÃ³rico de atividades (Audit Log)
 * - Busca por AJAX (autocomplete)
 * - Export para CSV
 *
 * SeguranÃ§a:
 * - Rate limiting em operaÃ§Ãµes sensÃ­veis
 * - ValidaÃ§Ã£o robusta de inputs
 * - ProteÃ§Ã£o contra SQL injection (Eloquent)
 * - Log de auditoria para cada aÃ§Ã£o
 * - Soft delete para GDPR compliance
 * - TransaÃ§Ãµes de banco para garantir integridade
 *
 * Performance:
 * - Eager loading de relacionamentos
 * - PaginaÃ§Ã£o otimizada
 * - Busca com Ã­ndices de banco
 * - <200ms tempo de resposta alvo
 *
 * @author Elias Gomes
 * @version 2.1.0
 * @category Admin
 */
class UserController extends Controller
{
    /**
     * NÃºmero padrÃ£o de itens por pÃ¡gina
     * @var int
     */
    private const DEFAULT_PER_PAGE = 15;

    /**
     * Colunas ordenÃ¡veis
     * @var array
     */
    private const SORTABLE_COLUMNS = ['id', 'name', 'email', 'role', 'status', 'created_at', 'updated_at'];

    /**
     * PapÃ©is disponÃ­veis
     * @var array
     */
    public const AVAILABLE_ROLES = ['admin', 'professor', 'aluno'];

    /**
     * Status disponÃ­veis
     * @var array
     */
    public const AVAILABLE_STATUS = ['active', 'inactive'];

    /**
     * Construtor
     * Applica middleware de autorizaÃ§Ã£o para todas as aÃ§Ãµes
     */
    public function __construct()
    {
        $this->middleware('role:admin');
        $this->middleware('throttle:60,1')->only(['index', 'search']);
    }

    /**
     * Listagem de usuÃ¡rios com busca e filtros avanÃ§ados
     *
     * Features:
     * - Busca por nome, email ou telefone
     * - Filtro por papel (role)
     * - Filtro por status
     * - OrdenaÃ§Ã£o dinÃ¢mica
     * - PaginaÃ§Ã£o
     * - Eager loading de relacionamentos
     *
     * @param  Request $request RequisiÃ§Ã£o HTTP com parÃ¢metros de busca
     * @return \Illuminate\View\View
     *
     * @example
     * GET /admin/usuarios?search=john&role=professor&status=active&sort=name&page=1
     */
    public function index(Request $request)
    {
        // Validar parÃ¢metros de entrada
        $request->validate([
            'search' => 'nullable|string|max:100',
            'role' => 'nullable|in:' . implode(',', self::AVAILABLE_ROLES),
            'status' => 'nullable|in:' . implode(',', self::AVAILABLE_STATUS),
            'sort' => 'nullable|in:' . implode(',', self::SORTABLE_COLUMNS),
            'order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:100',
        ]);

        // Inicializar query com soft delete (apenas nÃ£o deletados)
        $query = User::query();

        // ===== BUSCA AVANÃ‡ADA =====
        if ($search = $request->input('search')) {
            // Busca em mÃºltiplos campos
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });

            // Registrar busca no audit log
            AuditLog::log(
                auth()->user(),
                'search',
                'user',
                null,
                ['search_term' => $search]
            );
        }

        // ===== FILTROS =====
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // ===== ORDENAÃ‡ÃƒO =====
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');

        // ValidaÃ§Ã£o contra SQL injection
        if (!in_array($sort, self::SORTABLE_COLUMNS)) {
            $sort = 'created_at';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $query->orderBy($sort, $order);

        // ===== PAGINAÃ‡ÃƒO =====
        $perPage = $request->input('per_page', self::DEFAULT_PER_PAGE);
        $users = $query->paginate($perPage)
                       ->appends($request->query()); // Manter parÃ¢metros na paginaÃ§Ã£o

        // Se for requisição HTMX, retornar apenas a tabela (partial)
        if ($request->header('HX-Request')) {
            return view('pages.admin_usuarios.table', [
                'users' => $users,
            ]);
        }

        // Retornar view com dados completa (página inteira)
        return view('pages.admin_usuarios.index', [
            'users' => $users,
            'search' => $search ?? '',
            'role' => $request->input('role'),
            'status' => $request->input('status'),
            'sort' => $sort,
            'order' => $order,
            'roles' => self::AVAILABLE_ROLES,
            'statuses' => self::AVAILABLE_STATUS,
        ]);
    }

    /**
     * FormulÃ¡rio para criar novo usuÃ¡rio
     *
     * Mostra:
     * - Input nome, email
     * - Select papel (aluno, professor, admin)
     * - Checkboxes permissÃµes especÃ­ficas
     * - OpÃ§Ã£o: Gerar senha ou Enviar convite
     */
    public function create()
    {
        return view('pages.admin_usuarios.create', [
            'roles' => ['aluno' => 'Aluno', 'professor' => 'Professor', 'admin' => 'Admin'],
        ]);
    }

    /**
     * Armazenar novo usuÃ¡rio no banco de dados
     *
     * ValidaÃ§Ã£o via StoreUserRequest:
     * - name: required, string, max 255
     * - email: required, email, unique:users
     * - role: required, in:admin,professor,aluno
     * - status: required, in:active,inactive
     * - password: required, min:8, confirmed
     *
     * @param  StoreUserRequest $request RequisiÃ§Ã£o validada
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            // Usar transaÃ§Ã£o para garantir integridade de dados
            DB::beginTransaction();

            // Preparar dados
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            // Criar usuÃ¡rio
            $user = User::create($data);

            // Registrar no audit log
            AuditLog::log(
                auth()->user(),
                'create',
                'user',
                $user,
                ['created_user_id' => $user->id]
            );

            DB::commit();

            return redirect()
                ->route('admin.usuarios.show', $user)
                ->with('success', "✅ Usuario '{$user->name}' criado com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erro ao criar usuÃ¡rio:', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Erro ao criar usuario. Tente novamente.');
        }
    }

    /**
     * Visualizar detalhes do usuÃ¡rio
     *
     * Mostra:
     * - InformaÃ§Ãµes bÃ¡sicas (avatar, nome, email, papel)
     * - Status e datas (criaÃ§Ã£o, Ãºltimo login)
     * - MatrÃ­culas em cursos
     * - Pagamentos realizados
     * - HistÃ³rico de atividades (Ãºltimos 10 logs)
     * - AÃ§Ãµes (editar, deletar, trocar senha)
     */
    public function show(User $usuario)
    {
        // ═══ DEBUG LOG ═══
        \Log::info('UserController::show() DEBUG', [
            'user_id' => $usuario->id,
            'user_name' => $usuario->name,
            'user_attributes' => $usuario->getAttributes(),
            'timestamps_enabled' => $usuario->timestamps,
            'created_at_value' => $usuario->created_at,
            'created_at_type' => gettype($usuario->created_at),
        ]);
        // ═════════════════
        
        // Eager load relacionamentos (temporariamente desabilitado)
        // $usuario->load([
        //     'enrollments.course',
        //     'payments',
        // ]);
        
        // HistÃ³rico de atividades
        $activityLogs = AuditLog::byUser($usuario->id)
            ->latest()
            ->limit(10)
            ->get();
        
        return view('pages.admin_usuarios.show', [
            'user' => $usuario,
            'activityLogs' => $activityLogs,
        ]);
    }

    /**
     * Trazer formulÃ¡rio para editar usuÃ¡rio
     *
     * @param  User $usuario UsuÃ¡rio a ser editado
     * @return \Illuminate\View\View
     */
    public function edit(User $usuario)
    {
        return view('pages.admin_usuarios.edit', [
            'user' => $usuario,
            'roles' => self::AVAILABLE_ROLES,
            'statuses' => self::AVAILABLE_STATUS,
        ]);
    }

    /**
     * Atualizar dados do usuÃ¡rio
     *
     * @param  UpdateUserRequest $request RequisiÃ§Ã£o validada
     * @param  User $usuario UsuÃ¡rio a ser atualizado
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $usuario)
    {
        try {
            DB::beginTransaction();

            // Preparar dados (excludir campos vazios)
            $data = array_filter($request->validated(), fn($value) => $value !== null && $value !== '');

            // Se password foi fornecida, fazer hash
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Armazenar valores antigos para audit log
            $oldValues = $user->only(array_keys($data));

            // Atualizar usuÃ¡rio
            $user->update($data);

            // Registrar mudanÃ§as no audit log
            AuditLog::log(
                auth()->user(),
                'update',
                'user',
                $user,
                [
                    'old_values' => $oldValues,
                    'new_values' => $user->only(array_keys($data)),
                ]
            );

            DB::commit();

            return redirect()
                ->route('admin.usuarios.show', $usuario)
                ->with('success', "✅ Usuário '{$usuario->name}' atualizado com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erro ao atualizar usuÃ¡rio:', [
                'error' => $e->getMessage(),
                'user_id' => $usuario->id,
                'admin_id' => auth()->id(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'âŒ Erro ao atualizar usuÃ¡rio. Tente novamente.');
        }
    }

    /**
     * Deletar (soft delete) usuÃ¡rio
     *
     * Implementa GDPR-compliant soft delete
     *
     * @param  User $usuario UsuÃ¡rio a ser deletado
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $usuario)
    {
        try {
            // NÃ£o permitir deletar a si mesmo
            if (auth()->id() === $usuario->id) {
                return back()->with('error', 'âŒ VocÃª nÃ£o pode deletar sua prÃ³pria conta!');
            }

            DB::beginTransaction();

            // Registrar deleÃ§Ã£o (antes de deletar)
            AuditLog::log(
                auth()->user(),
                'delete',
                'user',
                $usuario,
                ['deleted_by' => auth()->id()]
            );

            // Soft delete
            $usuario->delete();

            DB::commit();

            return redirect()
                ->route('admin.usuarios.index')
                ->with('success', "✅ UsuÃ¡rio '{$usuario->name}' deletado com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erro ao deletar usuÃ¡rio:', [
                'error' => $e->getMessage(),
                'user_id' => $usuario->id,
                'admin_id' => auth()->id(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'âŒ Erro ao deletar usuÃ¡rio. Tente novamente.');
        }
    }

    /**
     * Alternar status do usuÃ¡rio (ativo/inativo)
     *
     * @param  User $usuario UsuÃ¡rio a ser alterado
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(User $usuario)
    {
        try {
            DB::beginTransaction();

            $oldStatus = $usuario->status;
            $newStatus = $usuario->status === 'active' ? 'inactive' : 'active';

            $usuario->update(['status' => $newStatus]);

            // Registrar mudanÃ§a de status
            AuditLog::log(
                auth()->user(),
                'toggle_status',
                'user',
                $usuario,
                [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]
            );

            DB::commit();

            $statusLabel = $newStatus === 'active' ? 'ativado' : 'desativado';

            return redirect()
                ->back()
                ->with('success', "âœ… UsuÃ¡rio '{$user->name}' {$statusLabel} com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erro ao alternar status:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return redirect()
                ->back()
                ->with('error', 'âŒ Erro ao alternar status. Tente novamente.');
        }
    }

    /**
     * Alterar senha do usuÃ¡rio (por admin)
     *
     * @param  Request $request RequisiÃ§Ã£o com nova senha
     * @param  User $usuario UsuÃ¡rio cuja senha serÃ¡ alterada
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request, User $usuario)
    {
        try {
            // Validar nova senha
            $validated = $request->validate([
                'new_password' => 'required|string|min:8|regex:/[A-Z]/|regex:/\d/',
            ], [
                'new_password.required' => 'Nova senha Ã© obrigatÃ³ria',
                'new_password.min' => 'Senha deve ter mÃ­nimo 8 caracteres',
                'new_password.regex' => 'Senha deve conter maiÃºscula e nÃºmero',
            ]);

            DB::beginTransaction();

            $usuario->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            // Registrar mudanÃ§a de senha
            AuditLog::log(
                auth()->user(),
                'change_password',
                'user',
                $usuario,
                ['changed_by' => auth()->id()]
            );

            DB::commit();

            return redirect()
                ->back()
                ->with('success', "✅ Senha do usuÃ¡rio '{$usuario->name}' alterada com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erro ao alterar senha:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'âŒ Erro ao alterar senha. Tente novamente.');
        }
    }

    /**
     * Busca avanÃ§ada com autocomplete (para AJAX)
     *
     * Retorna JSON com usuÃ¡rios que correspondem Ã  busca
     *
     * @param  Request $request RequisiÃ§Ã£o com 'q' (query)
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Validar entrada
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        // Buscar usuÃ¡rios
        $users = User::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(10)
            ->select('id', 'name', 'email', 'role', 'status')
            ->get();

        // Formatar resposta
        return response()->json([
            'success' => true,
            'data' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                ];
            }),
        ]);
    }

    /**
     * Exportar lista de usuÃ¡rios para CSV
     *
     * @param  Request $request ParÃ¢metros de filtro
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        // Aplicar mesmos filtros da listagem
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        // Registrar export
        AuditLog::log(auth()->user(), 'export', 'user');

        // Gerar CSV
        $filename = "usuarios_export_" . now()->format('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nome', 'Email', 'Papel', 'Status', 'Telefone', 'Criado em']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->status,
                    $user->phone ?? '-',
                    $user->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

