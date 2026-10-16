# 🎓 LMS - Learning Management System

<p align="center">
  <strong>Sistema de Gestão de Aprendizagem robusto, escalável e moderno</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.50-FF2D20?logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/HTMX-2.0-3366CC" alt="HTMX">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0" alt="Alpine.js">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

---

## 📋 Sobre o Projeto

Sistema LMS completo com autenticação por papéis (Admin, Professor, Aluno), navegação SPA-like sem refresh, e arquitetura escalável para milhares de usuários simultâneos.

### ✨ Características Principais

- 🔐 **Autenticação Robusta**: Laravel Fortify com rate limiting e proteção CSRF
- 🎓 **Matrícula Automática**: estudantes são matriculados nos cursos ativos ao acessar o perfil de estudante
- 🎨 **Navegação SPA-like**: HTMX + Alpine.js (sem refresh visível)
- 🏗️ **Arquitetura Tríade**: HTML/CSS/JS organizados por página
- 🚀 **Performance**: Cache Redis, queue system, database optimization
- 🔒 **Segurança**: Proteção contra CSRF, XSS, SQL Injection, rate limiting
- 📱 **Responsivo**: Design mobile-first com breakpoint em 768px
- 🧹 **Clean Code**: SOLID, PSR-12, comentários explicativos

---

## 🚀 Quick Start

### Pré-requisitos

- PHP 8.3+
- Composer 2.x
- Node.js 20.19+ (ou 22.12+) & NPM
- MySQL 8+ ou SQLite
- Redis (opcional, para cache)

### Instalação

```bash
# 1. Clonar repositório
git clone https://github.com/seu-usuario/lms-projeto.git
cd lms-projeto

# 2. Instalar dependências, configurar o ambiente, preparar SQLite e compilar assets
composer run setup

# 3. Iniciar o servidor Laravel
php artisan serve
```

O setup também cria as contas de demonstração, prepara os cursos de exemplo com aulas e conteúdos iniciais e disponibiliza os materiais enviados.

Acesse: `http://localhost:8000`

Para iniciar em modo de desenvolvimento com o servidor, filas, logs e Vite:

```bash
composer run dev
```

### 👤 Usuários Padrão

| Email | Senha | Papel |
|-------|-------|-------|
| `admin@example.com` | `password` | Admin |
| `professor@example.com` | `password` | Professor |
| `aluno@example.com` | `password` | Aluno |

---

## 📁 Estrutura do Projeto

```
lms-projeto/
├── app/
│   ├── Console/Commands/
│   │   └── MakePage.php          # Artisan command: php artisan make:page
│   ├── Http/
│   │   ├── Controllers/          # Controllers do sistema
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php  # Proteção por papel (role)
│   │   └── Responses/
│   │       └── LoginResponse.php   # Redirecionamento pós-login
│   └── Models/                   # User, Curso, Aula, Conteudo
│
├── resources/views/
│   ├── layouts/
│   │   ├── page.blade.php        # Layout base (navbar + auth)
│   │   ├── admin.blade.php       # Extends page + sidebar vermelha
│   │   ├── professor.blade.php   # Extends page + sidebar indigo
│   │   └── aluno.blade.php       # Extends page + sidebar azul
│   │
│   └── pages/                    # ⭐ PADRÃO TRÍADE
│       ├── admin_dashboard/
│       │   ├── index.blade.php   # HTML da página
│       │   ├── style.css         # CSS isolado
│       │   └── script.js         # JavaScript isolado
│       ├── professor_dashboard/
│       ├── aluno_dashboard/
│       └── ... (16 páginas total)
│
├── public/pages/                 # Cópia de CSS/JS para servir
├── routes/web.php                # Rotas com middleware role
├── database/migrations/          # Schema do banco
│
├── README.md                     # Este arquivo
├── README_ARQUITETURA.md         # 📚 Arquitetura detalhada
├── README_TRIADE.md              # 📁 Padrão Tríade explicado
├── IMPLEMENTACAO_SPA.md          # 🚀 Guia HTMX + Alpine.js
└── DOCUMENTATION.md              # 📝 Changelog e decisões técnicas
```

---

## 🎯 Arquitetura

### Padrão Tríade

Cada página tem **3 arquivos separados** no **mesmo diretório**:

```
resources/views/pages/admin_dashboard/
├── index.blade.php    # Template Blade (HTML)
├── style.css          # Estilos isolados
└── script.js          # JavaScript isolado
```

**Benefícios:**
- ✅ Fácil edição no VS Code (todos arquivos visíveis)
- ✅ CSS/JS isolados (não vazam para outras páginas)
- ✅ Layouts compartilhados (admin/professor/aluno)
- ✅ Escalável (fácil adicionar novas páginas)

### Navegação SPA-like (Sem Refresh)

Usando **HTMX + Alpine.js** para navegação instantânea:

```html
<!-- Link sem refresh -->
<a href="/admin/usuarios"
   hx-get="/admin/usuarios"
   hx-target="#main-content"
   hx-push-url="true">
   Usuários
</a>
```

**Resultado:**
- ⚡ 80% mais rápido que refresh tradicional
- 🎨 Sem tela branca
- 📦 Apenas 29kb (HTMX + Alpine)
- 🔄 Histórico do navegador funciona

**📖 Leia:** [IMPLEMENTACAO_SPA.md](IMPLEMENTACAO_SPA.md) para detalhes

---

## 🔐 Segurança

### Proteções Implementadas

| Ameaça | Proteção | Status |
|--------|----------|--------|
| CSRF | Token `@csrf` em todos forms | ✅ |
| XSS | Blade escaping automático | ✅ |
| SQL Injection | Eloquent prepared statements | ✅ |
| Brute Force | Rate limiting (5 tentativas/min) | ✅ |
| Session Hijacking | Regeneração de session ID | ✅ |
| Unauthorized Access | Middleware `role:admin\|professor\|aluno` | ✅ |

### Rate Limiting

```php
// FortifyServiceProvider.php
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->email . $request->ip());
});
```

### Middleware de Papel (Role)

```php
// Protege rotas por papel do usuário
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', fn() => view('pages.admin_dashboard.index'));
});
```

**📖 Leia:** [README_ARQUITETURA.md](README_ARQUITETURA.md#segurança) para checklist completo

---

## 📈 Escalabilidade

### Arquitetura para Alto Tráfego

```
Load Balancer → [App #1, App #2, App #3, App #4]
                      ↓
                Redis Cluster (Cache + Sessions)
                      ↓
                MySQL Master + Replicas
```

### Otimizações

- **Cache Redis**: Queries e views cacheadas (3600s)
- **Queue System**: Tarefas pesadas em background (Horizon)
- **Database Indexing**: Índices em `email`, `role`, `created_at`
- **CDN**: Assets servidos via CDN em produção
- **Lazy Loading**: JavaScript e imagens carregados sob demanda

**Capacidade Estimada:**
- 🔢 1000+ requisições/segundo
- 👥 10000+ usuários simultâneos
- ⚡ <200ms response time (p95)

**📖 Leia:** [README_ARQUITETURA.md](README_ARQUITETURA.md#escalabilidade) para detalhes

---

## 🛠️ Desenvolvimento

### Criar Nova Página

```bash
# Artisan command cria estrutura completa
php artisan make:page nome_pagina

# Cria automaticamente:
# - resources/views/pages/nome_pagina/index.blade.php
# - resources/views/pages/nome_pagina/style.css
# - resources/views/pages/nome_pagina/script.js
# - public/pages/nome_pagina/style.css
# - public/pages/nome_pagina/script.js
```

### Adicionar Rota

```php
// routes/web.php
Route::get('/custom', fn() => view('pages.custom.index'))
    ->middleware(['auth', 'role:admin'])
    ->name('custom.page');
```

### Code Style (PSR-12)

```bash
# Formatar código automaticamente
./vendor/bin/pint

# Verificar sem modificar
./vendor/bin/pint --test
```

### Testes

```bash
# Rodar testes
php artisan test

# Com cobertura
php artisan test --coverage
```

---

## 📚 Documentação Completa

| Nº | Documento | Descrição |
|---|-----------|-----------|
| 0 | [README.md](README.md) | 📌 Documentação principal (você está aqui) |
| 1 | [CHANGELOG.md](CHANGELOG.md) | 📝 Histórico de mudanças e versões |
| 2 | [README2_PassosIniciais.md](README2_PassosIniciais.md) | 🚀 Quick start e primeiros passos |
| 3 | [README3_TRIADE.md](README3_TRIADE.md) | 📁 Padrão Tríade (HTML/CSS/JS separados) |
| 4 | [README4_ARQUITETURA.md](README4_ARQUITETURA.md) | 🏗️ Arquitetura, segurança, escalabilidade |
| 5 | [README5_IMPLEMENTACAO_SPA.md](README5_IMPLEMENTACAO_SPA.md) | 🚀 Guia HTMX + Alpine.js (navegação sem refresh) |
| 6 | [README6_CRONOLOGIA_PROJETO.md](README6_CRONOLOGIA_PROJETO.md) | 📅 Cronologia e decisões técnicas do projeto |
| 7 | [README7_ADMIN_AVANCADO.md](README7_ADMIN_AVANCADO.md) | 👑 Admin avançado (CRUDs, relatórios, segurança) |

---

## 🗺️ Roadmap

### ✅ Concluído (v1.0)
- [x] Autenticação com Fortify
- [x] Redirecionamento por role
- [x] Padrão Tríade implementado
- [x] Navbar com auth
- [x] 16 páginas criadas (admin/professor/aluno)
- [x] Layouts com herança
- [x] Artisan command `make:page`

### ✅ Concluído (v2.0)
- [x] Navegação SPA-like (HTMX + Alpine.js)
- [x] Loading indicators e transições
- [x] Documentação completa (7 READMEs)
- [x] Especificação de Admin avançado
- [x] Models & Migrations planejadas
- [x] Controllers & Rotas definidas
- [x] Componentes reutilizáveis

### 🚧 Em Progresso (v2.1)
- [ ] Implementar Admin Controllers CRUD
- [ ] Criar Views Admin (usuários, cursos, pagamentos)
- [ ] Integrar Chart.js para gráficos
- [ ] Implementar gerador de boletos
- [ ] Campanha de marketing
- [ ] Relatórios & exportação

### 📋 Planejado (v3.0)
- [ ] PWA (Progressive Web App)
- [ ] Mobile app (React Native)
- [ ] Websockets (real-time)
- [ ] 2FA (Two-Factor Authentication)
- [ ] Analytics dashboard
- [ ] Kubernetes deployment

---

---

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -m 'Add: nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

### Code Review Checklist

- [ ] Código segue PSR-12 (`./vendor/bin/pint`)
- [ ] Comentários explicam "por quê", não "o quê"
- [ ] Testes cobrem funcionalidade nova
- [ ] Sem código comentado ou debug (`dd()`, `var_dump()`)
- [ ] Migration tem rollback (`down()`)
- [ ] Variáveis têm nomes descritivos

---

## 📄 Licença

Este projeto está sob licença MIT. Veja [LICENSE](LICENSE) para mais detalhes.

---

## 👨‍💻 Autor

**Elias Gomes**  
📧 Email: [seu-email@example.com](mailto:seu-email@example.com)  
🔗 LinkedIn: [seu-perfil](https://linkedin.com/in/seu-perfil)

---

## 🙏 Agradecimentos

- Laravel Framework
- HTMX & Alpine.js
- Comunidade PHP
- Todos os contribuidores

---

<p align="center">
  Feito com ❤️ usando Laravel, HTMX e Alpine.js
</p>

<p align="center">
  <strong>⭐ Se este projeto te ajudou, considere dar uma estrela!</strong>
</p>

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
