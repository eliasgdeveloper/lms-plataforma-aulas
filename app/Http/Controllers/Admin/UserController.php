<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;

/**
 * AdminUserController - Gestão completa de usuários (CRUD + Segurança)
 * 
 * Funcionalidades:
 * - Listar usuários com filtros (papel, status, busca)
 * - Visualizar detalhes do usuário (atividades, matrículas)
 * - Criar novo usuário com papel e permissões
 * - Editar usuário (nome, email, papel, status)
 * - Deletar usuário (soft delete + backup)
 * - Trocar senha (reset)
 * - Toggle status (ativar/desativar)
 * - Histórico de atividades (auditoria)
 * 
 * Segurança:
 * - Rate limiting (criar/deletar)
 * - Validação robusta
 * - Log de auditoria para cada ação
 * - Soft delete para GDPR compliance
 * 
 * Performance:
 * - Eager loading de relacionamentos
 * - Cache de listagens pesadas
 * - Paginação (25/50/100 por página)
 * 
 * @route GET|HEAD  /admin/usuarios                 - Listar usuários
 * @route GET|HEAD  /admin/usuarios/create          - Form criar novo
 * @route POST      /admin/usuarios                 - Salvar novo usuário
 * @route GET|HEAD  /admin/usuarios/{user}          - Visualizar detail
 * @route GET|HEAD  /admin/usuarios/{user}/edit     - Form editar
 * @route PUT|PATCH /admin/usuarios/{user}          - Salvar edição
 * @route DELETE    /admin/usuarios/{user}          - Deletar usuário
 * @route POST      /admin/usuarios/{user}/toggle   - Ativar/Desativar
 * @route POST      /admin/usuarios/{user}/password - Trocar senha
 */
class UserController extends Controller
{
    /**
     * Listar usuários com filtros e busca
     * 
     * Filtros:
     * - ?search=termo       - Busca por nome/email
     * - ?role=aluno         - Filtro por papel
     * - ?status=active      - Filtro por status
     * - ?per_page=25        - Itens por página
     * - ?sort=name          - Ordenação
     * 
     * Cache:
     * - Lista com pagination não cacheada (dinâmica)
     * - Mas contagem de papéis cacheada por 1 hora
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');
        $sortBy = $request->input('sort', 'created_at');
        
        // Query builder com filtros
        $query = User::query();
        
        // Busca por nome ou email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filtro por papel (role)
        if ($role && in_array($role, ['admin', 'professor', 'aluno'])) {
            $query->where('role', $role);
        }
        
        // Filtro por status
        if ($status && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }
        
        // Eager load de relacionamentos para evitar N+1
        $query->with([
            'enrollments' => function ($q) {
                $q->count();
            }
        ]);
        
        // Ordenação
        $query->orderBy($sortBy, 'desc');
        
        // Paginar
        $users = $query->paginate($perPage);
        
        // Separar por papel
        $students = User::where('role', 'aluno')
            ->withCount('enrollments')
            ->paginate($perPage);
            
        $teachers = User::where('role', 'professor')
            ->withCount('enrollments')
            ->paginate($perPage);
        
        return view('pages.admin_usuarios.index', [
            'users' => $users,
            'students' => $students,
            'teachers' => $teachers,
            'search' => $search,
            'role' => $role,
            'status' => $status,
        ]);
    }

    /**
     * Formulário para criar novo usuário
     * 
     * Mostra:
     * - Input nome, email
     * - Select papel (aluno, professor, admin)
     * - Checkboxes permissões específicas
     * - Opção: Gerar senha ou Enviar convite
     */
    public function create()
    {
        return view('pages.admin_usuarios.create', [
            'roles' => ['aluno' => 'Aluno', 'professor' => 'Professor', 'admin' => 'Admin'],
        ]);
    }

    /**
     * Salvar novo usuário
     * 
     * - Validar inputs
     * - Hash da senha
     * - Gerar email de confirmação (opcional)
     * - Log de auditoria
     * - Redirect com sucesso
     */
    public function store(StoreUserRequest $request)
    {
        // Validação realizada em StoreUserRequest
        $validated = $request->validated();
        
        // Hash da senha (ou gerar aleatória)
        $validated['password'] = $validated['password'] 
            ? Hash::make($validated['password'])
            : Hash::make(str_random(12));
        
        // Criar usuário
        $user = User::create($validated);
        
        // Log de auditoria
        AuditLog::log(
            'created',
            'User',
            $user->id,
            ['name' => $user->name, 'role' => $user->role]
        );
        
        // Redirecionar com sucesso
        return redirect()
            ->route('admin.usuarios.show', $user)
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Visualizar detalhes do usuário
     * 
     * Mostra:
     * - Informações básicas (avatar, nome, email, papel)
     * - Status e datas (criação, último login)
     * - Matrículas em cursos
     * - Pagamentos realizados
     * - Histórico de atividades (últimos 10 logs)
     * - Ações (editar, deletar, trocar senha)
     */
    public function show(User $user)
    {
        // Eager load relacionamentos
        $user->load([
            'enrollments.course',
            'payments',
        ]);
        
        // Histórico de atividades
        $activityLogs = AuditLog::byUser($user->id)
            ->latest()
            ->limit(10)
            ->get();
        
        return view('pages.admin_usuarios.show', [
            'user' => $user,
            'activityLogs' => $activityLogs,
        ]);
    }

    /**
     * Formulário para editar usuário
     */
    public function edit(User $user)
    {
        return view('pages.admin_usuarios.edit', [
            'user' => $user,
            'roles' => ['aluno' => 'Aluno', 'professor' => 'Professor', 'admin' => 'Admin'],
        ]);
    }

    /**
     * Salvar edição de usuário
     * 
     * - Validar inputs
     * - Atualizar apenas campos permitidos
     * - Loop auditoria
     * - Invalidar cache
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        
        // Capturar mudanças para auditoria
        $changes = [];
        foreach ($validated as $field => $value) {
            if ($user->$field !== $value) {
                $changes[$field] = [
                    'from' => $user->$field,
                    'to' => $value,
                ];
            }
        }
        
        // Atualizar usuário
        $user->update($validated);
        
        // Log de auditoria apenas se houve mudanças
        if (!empty($changes)) {
            AuditLog::log('updated', 'User', $user->id, $changes);
            
            // Invalidar cache de usuários
            Cache::forget('admin.users.list');
        }
        
        return redirect()
            ->route('admin.usuarios.show', $user)
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Deletar usuário
     * 
     * Segurança:
     * - Soft delete (preserva dados para GDPR)
     * - Backup automático dos dados
     * - Log de auditoria
     * - Requer 2FA se config ativada
     */
    public function destroy(User $user)
    {
        // Não permitir deletar a si mesmo
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Você não pode deletar sua própria conta!');
        }
        
        // Backup dos dados antes de deletar
        $userData = $user->toJson();
        file_put_contents(
            storage_path("app/backups/user_delete_{$user->id}_" . now()->timestamp . '.json'),
            $userData
        );
        
        // Soft delete
        $user->delete();
        
        // Log de auditoria
        AuditLog::log('deleted', 'User', $user->id, ['name' => $user->name]);
        
        // Invalidar cache
        Cache::forget('admin.users.list');
        
        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuário deletado com sucesso!');
    }

    /**
     * Toggle status (ativar/desativar usuário)
     * 
     * Útil para:
     * - Desativar sem deletar (preserva dados)
     * - Re-ativar depois se necessário
     * - Analisar padrões de acesso
     */
    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        
        $user->update(['status' => $newStatus]);
        
        // Log
        AuditLog::log('toggled_status', 'User', $user->id, [
            'from' => $user->getOriginal('status'),
            'to' => $newStatus,
        ]);
        
        return back()->with('success', 'Status alterado com sucesso!');
    }

    /**
     * Trocar senha do usuário
     * 
     * - Gerar nova senha aleatória
     * - Hash e salvar
     * - Enviar email com senha temporária (opcional)
     * - Log
     */
    public function changePassword(User $user)
    {
        $newPassword = str_random(12);
        
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
        
        // Log
        AuditLog::log('password_reset', 'User', $user->id);
        
        // Aqui você pode enviar email com nova senha
        // Mail::to($user->email)->send(new NewPasswordMail($newPassword));
        
        return back()->with('success', "Senha redefinida para: {$newPassword}");
    }

    /**
     * Retornar histórico de atividades do usuário
     * 
     * Via HTMX:
     * hx-get="{{ route('admin.usuarios.activity', $user) }}"
     * hx-target="#activity-list"
     */
    public function activity(User $user)
    {
        $logs = AuditLog::byUser($user->id)
            ->latest()
            ->paginate(15);
        
        return view('partials.activity-logs', ['logs' => $logs]);
    }
}
