# 👑 Admin Avançado - Documentação de Implementação

**Versão:** 2.1.0  
**Data:** 07/02/2026  
**Status:** 🚧 Em Implementação

---

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Funcionalidades Admin](#funcionalidades-admin)
3. [Models & Migrations](#models--migrations)
4. [Controllers & Rotas](#controllers--rotas)
5. [Views Tríade](#views-tríade-html-css-js)
6. [Componentes Reutilizáveis](#componentes-reutilizáveis)
7. [Segurança & Validação](#segurança--validação)
8. [Performance & Caching](#performance--caching)
9. [SEO & Tags Semânticas](#seo--tags-semânticas)

---

## 🎯 Visão Geral

Sistema Admin Enterprise para plataforma LMS com funcionalidades completas:

- 👥 **Gestão de Usuários**: CRUD, permissões, histórico
- 📚 **Gestão de Cursos**: Criar, editar, excluir, agendamento
- 💰 **Financeiro**: Pagamentos, boletos, relatórios
- 📊 **Relatórios**: Gráficos, estatísticas, exportação
- 📧 **Campanhas**: Marketing, email, notificações
- 🎓 **Matrículas**: Gestionar inscrições em cursos
- 🔐 **Controle de Acesso**: Papéis e permissões granulares

### Requisitos Técnicos

- ✅ **Escalabilidade**: 10.000+ usuários simultâneos
- ✅ **Performance**: <200ms resposta (p95)
- ✅ **Segurança**: Rate limiting, criptografia, auditoria
- ✅ **Responsividade**: Mobile-first, todos dispositivos
- ✅ **SEO**: Tags semânticas, Open Graph, estrutura
- ✅ **Clean Code**: DRY, reutilização, heranças
- ✅ **Documentação**: Comentários + README

---

## 🎨 Funcionalidades Admin

### 1. Gestão de Usuários

#### Listagem com Filtros
```
Dashboard Admin → Usuários
├── Filtro por Papel (Aluno / Professor / Admin)
├── Busca por Nome/Email
├── Ordenação (Nome, Email, Criado em, Status)
├── Paginação (25, 50, 100 por página)
└── Bulk Actions (Ativar/Desativar, Mudar Papel)
```

**Exibição Separada**:
- **Seção Alunos**: Todos usuários com role='aluno'
  - Mostra: Nome, Email, Data inscrição, Status
  - Ações: Editar, Deletar, Redefine senha, Ver matrículas
  
- **Seção Professores**: Todos usuários com role='professor'
  - Mostra: Nome, Email, Cursos lecionando, Avaliação
  - Ações: Editar, Deletar, Redefine senha, Ver cursos

#### CRUD Completo

**CREATE** (Novo Usuário):
```form
├── Nome* (required, max:255)
├── Email* (required, unique, email)
├── Papel* (select: Aluno, Professor, Admin)
├── Permissões específicas (checkboxes)
├── Gerar Senha ou Enviar convite
└── [Criar] [Cancelar]
```

**READ** (Visualizar):
```card
├── Avatar + Nome
├── Email + Status
├── Papel + Permissões
├── Data criação / Último login
├── Atividades recentes
└── [Editar] [Deletar] [Mais ações]
```

**UPDATE** (Editar):
```form
├── Informações básicas (nome, email)
├── Papel e permissões
├── Status (Ativo/Inativo)
├── Reset de senha
└── [Salvar] [Cancelar]
```

**DELETE** (Excluir):
```modal
├── Confirmação com 2FA
├── Opção: Deletar ou Desativar
├── Backup automático de dados
└── [Excluir] [Cancelar]
```

### 2. Gestão de Cursos

#### Listagem de Cursos
```
Dashboard Admin → Cursos
├── Grid ou Tabela de cursos
├── Busca por nome/descrição
├── Filtro por Status (Ativo/Inativo)
├── Filtro por Professor responsável
└── Ordenação por: Nome, Data criação, Alunos inscritos
```

#### CRUD Completo

**CREATE** (Novo Curso):
```form
├── Informações Básicas
│   ├── Título* (required, max:255)
│   ├── Descrição* (required, textarea)
│   ├── Categoria* (select)
│   └── Imagem thumbnail
│
├── Configurações
│   ├── Professor responsável* (select)
│   ├── Preço* (decimal)
│   ├── Duração em horas* (number)
│   └── Limite de alunos (number, nullable)
│
├── Agendamento
│   ├── Data início* (date)
│   ├── Data fim* (date)
│   └── Horário aulas* (time)
│
└── [Criar] [Cancelar]
```

**READ** (Visualizar Curso):
```card
├── Capa + Título
├── Professor responsável
├── Status, Vagas, Inscritos
├── Descrição completa
├── Datas (início/fim)
├── Preço e receita total
├── Alunos inscritos (com paginação)
├── Aulas/Conteúdos
└── [Editar] [Deletar] [Duplicar] [Gerar relatório]
```

**UPDATE** (Editar Curso):
```form
├── Todas informações editáveis
├── Histórico de mudanças
├── Publicar/Despublicar
└── [Salvar] [Salvar e fechar]
```

**DELETE** (Excluir Curso):
```modal
├── Avisar sobre alunos inscritos
├── Opção: Deletar ou Arquivar
├── Exportar dados antes de deletar
└── [Excluir] [Cancelar]
```

### 3. Gestão de Matrículas

#### Visualizar Matrículas
```
Dashboard Admin → Matrículas
├── Tabela com:
│   ├── Aluno (nome + avatar)
│   ├── Curso
│   ├── Data inscrição
│   ├── Status (Ativo/Concluído/Cancelado)
│   ├── Progressão (%)
│   └── Ações
│
├── Filtros:
│   ├── Por Status
│   ├── Por Curso
│   ├── Por Período
│   └── Busca por aluno
│
└── Bulk: Cancelar, Completar, Gerar certificados
```

#### Matrícula Manual
```form
├── Selecionar Aluno* (select/busca)
├── Selecionar Curso* (select/busca)
├── Data início (pre-preenchida com data do curso)
├── Observações (textarea)
└── [Matrícula] [Cancelar]
```

### 4. Gestão de Pagamentos

#### Listar Pagamentos
```
Dashboard Admin → Pagamentos
├── Tabela:
│   ├── Aluno
│   ├── Curso/Matrícula
│   ├── Valor
│   ├── Data pagamento
│   ├── Método (Boleto/Cartão/PIX)
│   ├── Status (Pendente/Pago/Cancelado)
│   └── Ações
│
├── Filtros:
│   ├── Por Status
│   ├── Por Método de pagamento
│   ├── Por Data (range)
│   └── Por Aluno
│
└── Ações:
    ├── Ver comprovante
    ├── Registrar pagamento manual
    ├── Cancelar
    └── Gerar boleto
```

#### Gerar Boleto
```form
├── Selecionar Matrícula* (select)
├── Valor* (pre-preenchido de matricula.valor)
├── Data vencimento* (date, default 7 dias)
├── Descrição (textarea)
└── [Gerar Boleto] [Cancelar]
```

**Resultado**: PDF com boleto para impressão/envio

### 5. Relatórios & Gráficos

#### Dashboard de Relatórios
```
Dashboard Admin → Relatórios
├── Gráficos em Tempo Real:
│   ├── Receita (line chart, últimos 12 meses)
│   ├── Alunos por mês (bar chart, crescimento)
│   ├── Cursos popular (pie chart, top 5)
│   ├── Taxa de conclusão (gauge, por curso)
│   └── Pagamentos (status breakdown)
│
├── Tabelas:
│   ├── Vendas por período
│   ├── Alunos mais ativos
│   ├── Professores melhores avaliados
│   ├── Cursos com menor taxa conclusão
│   └── Últimas transações
│
├── Exportar (PDF, Excel, CSV)
└── Agendar envio automático (email)
```

#### Relatório Detalhado (por Curso/Período)
```
├── Informações gerais
├── Gráficos personalizados
├── Tabelas detalhadas
├── Insights automáticos
└── Download (PDF/Excel)
```

### 6. Campanhas de Marketing

#### Listar Campanhas
```
Dashboard Admin → Campanhas
├── Tabela:
│   ├── Nome da campanha
│   ├── Status (Draft/Agendada/Ativa/Concluída)
│   ├── Tipo (Email/SMS/Push)
│   ├── Audiência (N alunos)
│   ├── Taxa abertura (%)
│   ├── Data criação/envio
│   └── Ações (Editar/Duplicar/Deletar)
│
└── Novo (+)
```

#### Criar Campanha
```form
├── Informações
│   ├── Nome* (required)
│   ├── Tipo* (Email/SMS/Push)
│   ├── Assunto/Título*
│   └── Conteúdo (rich text editor)
│
├── Segmentação (Opcional)
│   ├── Público alvo (Alunos/Professores/Todos)
│   ├── Por Curso inscritos
│   ├── Por Status de pagamento
│   └── Por Data inscrição (range)
│
├── Agendamento
│   ├── Enviar agora ou agendar?
│   ├── Data/Hora de envio
│   └── Timezone
│
└── [Enviar] [Agendar] [Salvar rascunho] [Cancelar]
```

#### Analytics da Campanha
```
├── Taxa de entrega (%)
├── Taxa de abertura (%)
├── Taxa de clique (%)
├── Conversões
├── ROI
└── Gráfico ao longo do tempo
```

### 7. Gestão de Aulas/Conteúdos

#### Listar Aulas (por Curso)
```
Dashboard Admin → Cursos → [Curso] → Aulas
├── Lista de aulas com:
│   ├── Número e título
│   ├── Descrição preview
│   ├── Status (Publicada/Rascunho)
│   ├── Data publicação
│   └── Ações
│
└── Novo/Reordenar
```

#### Criar/Editar Aula
```form
├── Informações
│   ├── Título*
│   ├── Descrição*
│   ├── Conteúdo (HTML editor)
│   └── Status (Publicada/Rascunho)
│
├── Tipo de Conteúdo
│   ├── Texto
│   ├── Vídeo (embed)
│   ├── Quiz
│   ├── Tarefa
│   └── Recurso (arquivo)
│
└── [Salvar] [Publicar] [Cancelar]
```

### 8. Permissões & Papéis

#### Gerenciar Permissões por Papel
```
Dashboard Admin → Configurações → Papéis
├── Tabela de papéis (Admin, Professor, Aluno, Custom)
│
├── Para cada papel:
│   ├── Ver permissões atuais (checkboxes)
│   ├── Adicionar novas permissões
│   ├── Remover permissões
│   └── Aplicar para novo usuário
│
└── Log de mudanças
```

**Permissões Implementadas**:
```
Admin:
├── users.view (Visualizar usuários)
├── users.create (Criar usuários)
├── users.edit (Editar usuários)
├── users.delete (Deletar usuários)
├── courses.view
├── courses.create
├── courses.edit
├── courses.delete
├── payments.view
├── payments.manage
├── reports.view
├── campaigns.manage
└── settings.manage

Professor:
├── courses.view (Próprios cursos)
├── courses.create
├── courses.edit (Próprios cursos)
├── students.view (Seu curso)
├── materials.create
└── materials.edit (Próprios materiais)

Aluno:
├── courses.view (Inscritos)
├── materials.view (Disponíveis)
└── submissions.create (Tarefas)
```

### 9. Configurações do Sistema

#### Seção Configurações
```
Dashboard Admin → Configurações
├── Geral
│   ├── Nome da plataforma
│   ├── Logo e favicon
│   ├── Email de contato
│   └── Descrição/Tagline
│
├── Financeiro
│   ├── Moeda padrão
│   ├── Conta bancária
│   ├── Gateway de pagamento (Stripe/MercadoPago)
│   └── Comissão de curso
│
├── Email
│   ├── SMTP server
│   ├── Email remetente
│   ├── Templates padrão
│   └── Teste envio
│
├── Segurança
│   ├── Requer 2FA para admin?
│   ├── Timeout de sessão
│   ├── Rate limiting
│   └── Backup automático (dia/hora)
│
└── [Salvar]
```

---

## 💾 Models & Migrations

### Models Necessários

```php
// app/Models/User.php
- id, name, email, password, role, status, avatar, phone, address, created_at

// app/Models/Course.php
- id, title, description, category, teacher_id, price, duration, max_students,
  start_date, end_date, status, thumbnail, created_at, updated_at

// app/Models/Enrollment.php
- id, user_id, course_id, enrolled_at, completed_at, progress, status, certificate_id

// app/Models/Payment.php
- id, user_id, enrollment_id, amount, method, status, reference, receipt, created_at

// app/Models/Invoice.php (Boleto)
- id, payment_id, number, due_date, url, status, created_at

// app/Models/Campaign.php
- id, title, type, content, audience_count, status, scheduled_at, sent_at

// app/Models/Lesson.php
- id, course_id, title, content, type, order, published_at

// app/Models/Permission.php
- id, name, description

// app/Models/Role.php
- id, name, permissions (many-to-many)

// app/Models/AuditLog.php
- id, user_id, action, model, model_id, changes, ip_address, user_agent, created_at
```

### Migrations a Criar

```php
// 2026_02_07_*_create_courses_table.php
$table->id();
$table->string('title');
$table->text('description');
$table->string('category');
$table->foreignId('teacher_id')->constrained('users');
$table->decimal('price', 10, 2);
$table->integer('duration'); // em horas
$table->integer('max_students')->nullable();
$table->date('start_date');
$table->date('end_date');
$table->enum('status', ['draft', 'active', 'inactive', 'archived']);
$table->string('thumbnail')->nullable();
$table->timestamps();

// 2026_02_07_*_create_enrollments_table.php
$table->id();
$table->foreignId('user_id')->constrained();
$table->foreignId('course_id')->constrained();
$table->timestamp('enrolled_at');
$table->timestamp('completed_at')->nullable();
$table->decimal('progress', 5, 2)->default(0);
$table->enum('status', ['active', 'completed', 'cancelled']);
$table->foreignId('certificate_id')->nullable();
$table->timestamps();

// 2026_02_07_*_create_payments_table.php
$table->id();
$table->foreignId('user_id')->constrained();
$table->foreignId('enrollment_id')->nullable()->constrained();
$table->decimal('amount', 10, 2);
$table->enum('method', ['boleto', 'card', 'pix']);
$table->enum('status', ['pending', 'paid', 'cancelled']);
$table->string('reference')->nullable();
$table->text('receipt')->nullable();
$table->timestamps();

// 2026_02_07_*_create_invoices_table.php
$table->id();
$table->foreignId('payment_id')->constrained();
$table->string('number')->unique();
$table->date('due_date');
$table->text('url')->nullable();
$table->enum('status', ['pending', 'paid', 'cancelled']);
$table->timestamps();

// 2026_02_07_*_create_campaigns_table.php
$table->id();
$table->foreignId('created_by')->constrained('users');
$table->string('title');
$table->enum('type', ['email', 'sms', 'push']);
$table->text('content');
$table->integer('audience_count')->default(0);
$table->decimal('open_rate', 5, 2)->default(0);
$table->enum('status', ['draft', 'scheduled', 'active', 'completed']);
$table->timestamp('scheduled_at')->nullable();
$table->timestamp('sent_at')->nullable();
$table->timestamps();

// 2026_02_07_*_create_audit_logs_table.php
$table->id();
$table->foreignId('user_id')->nullable()->constrained();
$table->string('action');
$table->string('model');
$table->unsignedBigInteger('model_id');
$table->json('changes')->nullable();
$table->string('ip_address')->nullable();
$table->text('user_agent')->nullable();
$table->timestamps();

// 2026_02_07_*_add_fields_to_users_table.php (ALTER)
$table->enum('role', ['admin', 'professor', 'aluno'])->default('aluno');
$table->enum('status', ['active', 'inactive'])->default('active');
$table->string('avatar')->nullable();
$table->string('phone')->nullable();
$table->text('address')->nullable();
```

---

## 🎮 Controllers & Rotas

### AdminController

```php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard principal do admin
     * 
     * Exibe:
     * - Gráficos de receita (últimos 12 meses)
     * - Alunos novos (últimos 7 dias)
     * - Cursos ativos
     * - Pagamentos pendentes
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Cache de 1 hora para dados pesados
        $revenue = Cache::remember('admin.revenue', 3600, function () {
            return Payment::selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->where('status', 'paid')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get();
        });
        
        // Outras métricas...
        return view('pages.admin_dashboard.index', [
            'revenue' => $revenue,
            // ...
        ]);
    }
}
```

### Rotas Admin

```php
// routes/web.php

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', 'Admin\AdminController@dashboard')->name('dashboard');
    
    // Usuários CRUD
    Route::resource('usuarios', 'Admin\UserController');
    Route::post('usuarios/{user}/change-password', 'Admin\UserController@changePassword')->name('usuarios.changePassword');
    Route::post('usuarios/{user}/toggle-status', 'Admin\UserController@toggleStatus')->name('usuarios.toggleStatus');
    Route::get('usuarios/{user}/activity', 'Admin\UserController@activity')->name('usuarios.activity');
    
    // Cursos CRUD
    Route::resource('cursos', 'Admin\CourseController');
    Route::post('cursos/{course}/publish', 'Admin\CourseController@publish')->name('cursos.publish');
    Route::get('cursos/{course}/students', 'Admin\CourseController@students')->name('cursos.students');
    
    // Matrículas
    Route::resource('matriculas', 'Admin\EnrollmentController', ['only' => ['index', 'create', 'store', 'show', 'destroy']]);
    Route::get('matriculas/{enrollment}/progress', 'Admin\EnrollmentController@progress')->name('matriculas.progress');
    
    // Pagamentos
    Route::resource('pagamentos', 'Admin\PaymentController', ['only' => ['index', 'show']]);
    Route::post('pagamentos/{payment}/confirm', 'Admin\PaymentController@confirm')->name('pagamentos.confirm');
    Route::get('pagamentos/{payment}/invoice', 'Admin\PaymentController@invoice')->name('pagamentos.invoice');
    Route::post('pagamentos/{enrollment}/generate-boleto', 'Admin\PaymentController@generateBoleto')->name('pagamentos.generateBoleto');
    
    // Relatórios
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/', 'Admin\ReportController@index')->name('index');
        Route::get('/cursos', 'Admin\ReportController@courses')->name('courses');
        Route::get('/alunos', 'Admin\ReportController@students')->name('students');
        Route::get('/receita', 'Admin\ReportController@revenue')->name('revenue');
        Route::post('/export', 'Admin\ReportController@export')->name('export');
    });
    
    // Campanhas Marketing
    Route::resource('campanhas', 'Admin\CampaignController');
    Route::post('campanhas/{campaign}/send', 'Admin\CampaignController@send')->name('campanhas.send');
    Route::get('campanhas/{campaign}/analytics', 'Admin\CampaignController@analytics')->name('campanhas.analytics');
    
    // Configurações
    Route::prefix('configuracoes')->name('configuracoes.')->group(function () {
        Route::get('/', 'Admin\SettingsController@index')->name('index');
        Route::post('/', 'Admin\SettingsController@update')->name('update');
        Route::get('/papeis', 'Admin\SettingsController@roles')->name('roles');
        Route::put('/papeis/{role}', 'Admin\SettingsController@updateRole')->name('updateRole');
    });
});
```

---

## 🎨 Views Tríade (HTML/CSS/JS)

### Estrutura de Diretórios

```
resources/views/pages/
├── admin_dashboard/
│   ├── index.blade.php
│   ├── style.css
│   └── script.js
│
├── admin_usuarios/
│   ├── index.blade.php (Lista)
│   ├── create.blade.php (Novo)
│   ├── edit.blade.php (Editar)
│   ├── show.blade.php (Detalhe)
│   ├── style.css
│   └── script.js
│
├── admin_cursos/
│   ├── index.blade.php (Lista)
│   ├── create.blade.php (Novo)
│   ├── edit.blade.php (Editar)
│   ├── show.blade.php (Detalhe + Alunos)
│   ├── style.css
│   └── script.js
│
├── admin_pagamentos/
│   ├── index.blade.php (Lista filtrada)
│   ├── show.blade.php (Detalhe)
│   ├── boleto.blade.php (PDF boleto)
│   ├── style.css
│   └── script.js
│
├── admin_relatorios/
│   ├── index.blade.php (Dashboard gráficos)
│   ├── cursos.blade.php
│   ├── alunos.blade.php
│   ├── style.css
│   └── script.js
│
└── admin_campanhas/
    ├── index.blade.php (Lista)
    ├── create.blade.php (Nova)
    ├── edit.blade.php (Editar)
    ├── analytics.blade.php (Analytics)
    ├── style.css
    └── script.js
```

### Exemplo: index.blade.php (Usuários)

```php
@extends('layouts.admin')

@section('title', 'Gerenciar Usuários')

@push('styles')
<link rel="stylesheet" href="{{ asset('pages/admin_usuarios/style.css') }}">
@endpush

<section class="admin-section" aria-label="Gestão de Usuários">
    <header class="section-header">
        <h1>Usuários da Plataforma</h1>
        <p>Gerencie usuários, permissões e acessos</p>
        <a href="{{ route('admin.usuarios.create') }}" 
           hx-get="{{ route('admin.usuarios.create') }}"
           hx-target="#main-content"
           hx-push-url="true"
           class="btn btn-primary">
            + Novo Usuário
        </a>
    </header>

    <!-- Filtros & Busca -->
    <section class="filters-section" aria-label="Filtros">
        <form hx-get="{{ route('admin.usuarios.index') }}" 
              hx-target="#users-list"
              hx-trigger="change"
              class="filter-form">
            
            <input type="text" 
                   name="search" 
                   placeholder="Buscar por nome ou email..."
                   hx-trigger="keyup changed delay:500ms"
                   class="input">
            
            <select name="role" class="select">
                <option value="">Todos os papéis</option>
                <option value="aluno">Aluno</option>
                <option value="professor">Professor</option>
                <option value="admin">Admin</option>
            </select>
            
            <select name="status" class="select">
                <option value="">Todos os status</option>
                <option value="active">Ativo</option>
                <option value="inactive">Inativo</option>
            </select>
            
            <button type="submit" class="btn btn-secondary">Filtrar</button>
        </form>
    </section>

    <!-- Tabela de Usuários com Separação -->
    <div id="users-list">
        <!-- Seção Alunos -->
        <section aria-label="Alunos inscritos">
            <h2>Alunos <span class="badge">{{ $students->count() }}</span></h2>
            
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Inscritos em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $user)
                        <tr data-user-id="{{ $user->id }}">
                            <td>
                                <span class="avatar" 
                                      style="background: linear-gradient(135deg, #003d82, #0051b8);">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                                {{ $user->name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="status-badge status-{{ $user->status }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="#" 
                                   hx-get="{{ route('admin.usuarios.show', $user) }}"
                                   hx-target="#main-content"
                                   hx-push-url="true">
                                    {{ $user->enrollments_count }} cursos
                                </a>
                            </td>
                            <td class="actions">
                                <a href="{{ route('admin.usuarios.edit', $user) }}"
                                   hx-get="{{ route('admin.usuarios.edit', $user) }}"
                                   hx-target="#main-content"
                                   hx-push-url="true"
                                   class="btn btn-sm btn-secondary">
                                    Editar
                                </a>
                                <button hx-delete="{{ route('admin.usuarios.destroy', $user) }}"
                                        hx-confirm="Tem certeza que deseja deletar este usuário?"
                                        class="btn btn-sm btn-danger">
                                    Deletar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum aluno encontrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <!-- Seção Professores -->
        <section aria-label="Professores">
            <h2>Professores <span class="badge">{{ $teachers->count() }}</span></h2>
            
            <table class="users-table">
                <!-- Estrutura similar aos alunos -->
            </table>
        </section>
    </div>
</section>

@push('scripts')
<script src="{{ asset('pages/admin_usuarios/script.js') }}" defer></script>
@endpush
```

---

## 🧩 Componentes Reutilizáveis

### Blade Components (Laravel 8+)

```php
// resources/views/components/
├── admin/
│   ├── table.blade.php (Tabela genérica)
│   ├── form.blade.php (Form genérico)
│   ├── modal.blade.php (Modal genérico)
│   ├── filter.blade.php (Filtros)
│   ├── badge.blade.php (Status badges)
│   ├── chart.blade.php (Gráficos)
│   ├── pagination.blade.php (Paginação)
│   └── action-menu.blade.php (Menu ações)
│
└── shared/
    ├── navbar.blade.php (Navbar global)
    ├── sidebar.blade.php (Sidebar global)
    ├── alert.blade.php (Alertas)
    ├── loader.blade.php (Loading)
    └── toast.blade.php (Notificações)
```

### Exemplo: Table Component

```php
<!-- resources/views/components/admin/table.blade.php -->

@props([
    'headers' => [],
    'rows' => [],
    'actions' => [],
])

<table class="data-table" role="table">
    <thead>
        <tr role="row">
            @foreach ($headers as $header)
                <th role="columnheader" 
                    {{ isset($header['sortable']) ? 'data-sortable="true"' : '' }}>
                    {{ $header['label'] }}
                </th>
            @endforeach
            @if (count($actions) > 0)
                <th role="columnheader">Ações</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse ($rows as $row)
            <tr role="row" data-id="{{ $row->id }}">
                @foreach ($headers as $header)
                    <td role="cell">
                        {{ $row->{$header['field']} }}
                    </td>
                @endforeach
                
                @if (count($actions) > 0)
                    <td role="cell" class="actions">
                        @foreach ($actions as $action)
                            <a href="{{ $action['url'] }}"
                               class="btn btn-sm btn-{{ $action['type'] ?? 'secondary' }}">
                                {{ $action['label'] }}
                            </a>
                        @endforeach
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($headers) + 1 }}" class="text-center">
                    Nenhum registro encontrado
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
```

### Uso do Componente

```php
<x-admin.table
    :headers="[
        ['label' => 'Nome', 'field' => 'name'],
        ['label' => 'Email', 'field' => 'email'],
        ['label' => 'Papel', 'field' => 'role'],
    ]"
    :rows="$users"
    :actions="[
        ['label' => 'Editar', 'url' => route('admin.usuarios.edit', $user), 'type' => 'secondary'],
        ['label' => 'Deletar', 'url' => '#', 'type' => 'danger'],
    ]"
/>
```

### Herança de Estilos

**Base Admin**: `resources/css/admin.css`
```css
:root {
    --admin-primary: #3d0909;  /* Vermelho admin */
    --admin-secondary: #8b0000;
    --spacing-base: 1rem;
    /* Global vars */
}

.admin-section { /* Base para todas seções */ }
.section-header { /* Cabeçalho padrão */ }
.data-table { /* Tabela padrão */ }
.filters-section { /* Filtros padrão */ }
```

**Página Específica**: `resources/views/pages/admin_usuarios/style.css`
```css
@import '../../../css/admin.css';

/* Estilos específicos da página de usuários */
.users-table {
    /* Herda .data-table */
    background: var(--bg-white);
}

.status-badge {
    /* Específico para badges de status */
}
```

---

## 🔐 Segurança & Validação

### Middlewares

```php
// app/Http/Middleware/AdminMiddleware.php

public function handle($request, Closure $next)
{
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403, 'Acesso negado');
    }
    
    // Log de auditoria
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'admin_access',
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);
    
    return $next($request);
}
```

### Form Validation

```php
// app/Http/Requests/Admin/StoreUserRequest.php

public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $this->user?->id,
        'role' => 'required|in:admin,professor,aluno',
        'password' => 'nullable|min:8|confirmed',
        'permissions' => 'array|exists:permissions,id',
        'status' => 'required|in:active,inactive',
    ];
}

public function authorize()
{
    return auth()->user()->role === 'admin';
}
```

### Rate Limiting by Feature

```php
// app/Providers/RouteServiceProvider.php

RateLimiter::for('admin.create', function (Request $request) {
    return Limit::perDay(100)->by($request->user()->id);
});

RateLimiter::for('admin.delete', function (Request $request) {
    return Limit::perDay(50)->by($request->user()->id);
});
```

### Criptografia de Dados Sensíveis

```php
// Usar Encryption Laravel para dados sensíveis
use Illuminate\Support\Facades\Crypt;

// Salvar
$encrypted = Crypt::encryptString($bankAccount);
$model->bank_account = $encrypted;

// Recuperar
$decrypted = Crypt::decryptString($model->bank_account);
```

---

## ⚡ Performance & Caching

### Query Optimization com Eager Loading

```php
// ✅ Bom - 1 query
$users = User::with(['enrollments.course', 'roles'])->get();

// ❌ Ruim - N+1 queries
$users = User::all();
foreach ($users as $user) {
    $user->enrollments;  // 1 query por user
}
```

### Cache Strategy

```php
// Cache queries pesadas
$courses = Cache::remember('courses.all', 3600, function () {
    return Course::active()
        ->withCount('enrollments')
        ->get();
});

// Cache views parciais
<section class="dashboard-stats">
    {{ Cache::remember('admin.dashboard.stats', 300, function () {
        return view('partials.dashboard-stats', [
            'stats' => $this->getStats()
        ])->render();
    }) }}
</section>

// Invalidar cache ao criar/atualizar
Route::post('/courses', function () {
    // ...
    Cache::forget('courses.all');
    return redirect();
});
```

### Database Indexing

```php
// Adicionar índices em migrações
$table->index('email');
$table->index('role');
$table->index('created_at');
$table->index(['course_id', 'user_id']);  // Índice composto
$table->index(['status', 'created_at']);
```

### Queue for Heavy Operations

```php
// Gerar relatório em background
use App\Jobs\GenerateReport;

GenerateReport::dispatch($course, $period)
    ->onQueue('reports');

// Worker processa: php artisan queue:work reports
```

---

## 🔍 SEO & Tags Semânticas

### Meta Tags

```php
<!-- layouts/admin.blade.php -->

<meta name="description" content="{{ $description ?? 'Admin LMS - Gerencie sua plataforma de cursos' }}">
<meta name="og:title" content="{{ $title ?? 'Admin' }}">
<meta name="og:description" content="{{ $description ?? 'Painel administrativo' }}">
<meta name="og:type" content="website">

<!-- Estruturado JSON-LD -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "LMS Admin",
    "applicationCategory": "EducationalApplication"
}
</script>
```

### Semantic HTML Tags

```html
<!-- Usar tags semânticas ao invés de divs -->

<main id="main-content" role="main">
    <section aria-label="Gestão de Usuários">
        <header>
            <h1>Usuários</h1>
        </header>
        
        <nav aria-label="Filtros">
            <!-- Filtros -->
        </nav>
        
        <table role="table">
            <thead>
                <tr role="row">
                    <th role="columnheader" scope="col">Nome</th>
                </tr>
            </thead>
            <tbody>
                <tr role="row">
                    <td role="cell">João</td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<footer role="contentinfo">
    <!-- Footer semântico -->
</footer>
```

### Responsive Meta Tags

```html
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#3d0909">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
```

---

## 📱 Responsividade

### Mobile-First Grid

```css
/* Mobile (< 768px) */
.admin-grid {
    display: grid;
    grid-template-columns: 1fr;  /* 1 coluna */
    gap: 1rem;
}

/* Tablet (≥ 768px) */
@media (min-width: 768px) {
    .admin-grid {
        grid-template-columns: repeat(2, 1fr);  /* 2 colunas */
    }
}

/* Desktop (≥ 1024px) */
@media (min-width: 1024px) {
    .admin-grid {
        grid-template-columns: repeat(3, 1fr);  /* 3 colunas */
    }
}

/* Large (≥ 1440px) */
@media (min-width: 1440px) {
    .admin-grid {
        grid-template-columns: repeat(4, 1fr);  /* 4 colunas */
    }
}
```

### Tabelas Responsivas

```html
<!-- Desktop: Tabela normal -->
<!-- Mobile: Cartão com CSS Grid -->

<table class="responsive-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <tr data-label="Nome">João</tr>
    </tbody>
</table>
```

```css
@media (max-width: 768px) {
    .responsive-table {
        display: grid;
    }
    
    .responsive-table tr {
        display: grid;
        grid-template-columns: 120px 1fr;
    }
    
    .responsive-table td::before {
        content: attr(data-label);
        font-weight: bold;
    }
}
```

---

## 📚 Documentação de Componentes

### Storybook ou Guia de Componentes

Criar `APP_COMPONENTS.md` documentando cada componente:

```markdown
## Button Component

### Uso
```php
<button class="btn btn-primary">Texto</button>
```

### Variações
- `.btn-primary` - Primário
- `.btn-secondary` - Secundário
- `.btn-danger` - Perigo

### Acessibilidade
- Usar `type="button"` ou `type="submit"`
- Adicionar `aria-label` se necessário
```

---

## 🚀 Implementação Step-by-Step

### Fase 1: Database & Models
- [ ] Criar migrations (courses, enrollments, payments, etc)
- [ ] Criar models com relacionamentos
- [ ] Seeders para dados de teste

### Fase 2: Controllers & Rotas  
- [ ] Controllers CRUD para cada recurso
- [ ] Validação robusta
- [ ] Auditoria de ações

### Fase 3: Views & Frontend
- [ ] Views tríade para cada seção
- [ ] Componentes reutilizáveis
- [ ] HTMX para navegação

### Fase 4: Relatórios & Gráficos
- [ ] Integrar Chart.js ou charts similares
- [ ] Gerar PDFs com Laravel DomPDF
- [ ] Export para Excel

### Fase 5: Segurança & Performance
- [ ] Rate limiting
- [ ] Caching (Redis)
- [ ] Indexação de DB
- [ ] Tests (Feature + Unit)

---

## 🎯 Checklist de Qualidade

- [ ] Responsivo (mobile, tablet, desktop, TV)
- [ ] Acessível (WCAG 2.1 AA)
- [ ] SEO amigável (meta tags, structured data)
- [ ] Seguro (CSRF, XSS, rate limiting)
- [ ] Performático (<200ms p95)
- [ ] Clean code (PSR-12, DRY)
- [ ] Documentado (README + comentários)
- [ ] Testado (testes de feature)
- [ ] Sem redundância (componentes reutilizáveis)
- [ ] Escalável (cache, queue, indexação)

---

**Última atualização:** 07/02/2026  
**Versão:** 2.1.0 (Em implementação)  
**Próxima:** Implementar todos Controllers e Views

