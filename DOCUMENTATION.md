# Documentação do Projeto — LMS (Resumo das alterações e guia rápido)

Data: 2026-02-06
Autor das alterações: Elias Gomes

---

## Objetivo
Este documento resume e documenta as mudanças recentes feitas no projeto LMS para corrigir problemas de autenticação, rotas e redirecionamento por `role` (admin/professor/aluno). Além disso, contém instruções de execução e testes básicos.

## Principais alterações aplicadas
- Registramos views Fortify para permitir que os contratos `*ViewResponse` sejam resolvidos (login, register, reset password, verify email, confirm password).
- Substituímos a resposta de login padrão do Fortify por uma implementação customizada (`App\Http\Responses\LoginResponse`) que redireciona por `role`.
- Adicionamos rate limiters (`login` e `two-factor`) usados pelo Fortify para mitigar ataques de força bruta.
- Criadas rotas nomeadas protegidas por middleware `auth` e `role` para `admin`, `professor`, `aluno` e rotas de perfil (`profile.edit`, `profile.update`, `profile.destroy`).
- Adicionados comentários explicativos nos arquivos modificados para facilitar manutenção futura.

## Arquivos modificados / criados (principais)
- `app/Providers/FortifyServiceProvider.php` — registro das views Fortify; binding de `LoginResponse`; rate limiters.
- `app/Http/Responses/LoginResponse.php` — implementação customizada que redireciona por `role`.
- `routes/web.php` — rotas públicas e protegidas para admin/professor/aluno e perfil.
- `app/Http/Middleware/RoleMiddleware.php` — middleware que valida o papel do usuário.
- `app/Http/Controllers/ProfileController.php` — comentários e explicações de fluxo de edição/atualização/exclusão.
- `resources/views/layouts/navigation.blade.php` — comentários na área de navegação para `profile` e logout.
- `DOCUMENTATION.md` — este arquivo.

## Como funciona o fluxo de autenticação e redirecionamento
1. O usuário envia credenciais para `POST /login` (Fortify).
2. Após autenticação, o Fortify chama a implementação do contrato `LoginResponse`.
3. `App\Http\Responses\LoginResponse` calcula a rota de destino com base em `user->role` e faz `redirect()` para a rota nomeada correspondente (`admin.dashboard`, `professor.dashboard`, `aluno.dashboard` ou `dashboard`).
4. O middleware `RoleMiddleware` protege os grupos de rotas por role (ex.: `->middleware('role:admin')`).

## Rate limiters
Definidos em `FortifyServiceProvider`:
- `login`: 5 tentativas por minuto por combinação `email+IP`.
- `two-factor`: 5 tentativas por minuto, indexado por `login.id` salvo em sessão.

## Rotas importantes
- `/` → `welcome`
- `/login` → formulário de login (Fortify view)
- `/dashboard` → dashboard genérico (rota `dashboard`)
- `/admin` → dashboard admin (rota `admin.dashboard`) + subrotas:
  - `/admin/usuarios` → `admin.usuarios`
  - `/admin/cursos` → `admin.cursos`
  - `/admin/configuracoes` → `admin.configuracoes`
- `/professor` → dashboard professor (rota `professor.dashboard`) + subrotas: `professor.aulas`, `professor.materiais`, `professor.alunos`
- `/aluno` → dashboard aluno (rota `aluno.dashboard`) + subrotas: `aluno.aulas`, `aluno.materiais`, `aluno.notas`
- `/profile` → edição de perfil (`profile.edit`), `profile.update`, `profile.destroy`

## Como testar localmente
1. Limpe caches e reinicie o servidor:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan serve
```

2. Acesse em browser:
- `http://localhost:8000/login` — teste autenticação
- Após login, confirme redirecionamento para a rota esperada conforme `role` do usuário.

## Sugestões / próximos passos
- Criar seeders para popular usuários `admin`, `professor`, `aluno` para testes automatizados.
- Adicionar testes Feature para validar redirecionamentos pós-login.
- Extrair controladores das closures nas rotas para classes dedicadas para melhor organização.
- Adicionar políticas/autorizações finas (gates/policies) para recursos sensíveis.

## Git
As alterações foram commitadas localmente.
Commit atual:
```
91eef36 - Fix: resolve login/routing issues - add Fortify views, rate limiters, and role-based dashboards
```

---

Se quiser, posso:
- Comentar mais arquivos além dos modificados (ex.: views, components) — diga quais diretórios priorizar.
- Gerar seeders/test users automaticamente.
- Criar um CHANGELOG.md separado com um passo-a-passo cronológico.

Fim do documento.

## Estrutura completa do projeto (visão geral)

Raiz do projeto (principais pastas):

- `app/` — código de aplicação: controllers, models, providers, middleware.
  - `Http/Controllers` — controladores HTTP (ex.: `ProfileController`).
  - `Models` — modelos Eloquent (ex.: `User`).
  - `Providers` — service providers (ex.: `FortifyServiceProvider`, `AppServiceProvider`).
  - `Middleware` — middlewares customizados (ex.: `RoleMiddleware`).
- `routes/` — definição de rotas. Arquivos principais: `web.php`, `auth.php`.
- `resources/views/` — views Blade. Layouts, páginas por role (`admin`, `professor`, `aluno`), e `auth`.
- `database/` — migrations, seeders e factories.
- `config/` — arquivos de configuração (Fortify, auth, app, database, etc.).
- `public/` — arquivos públicos, entrypoint `index.php`, assets compilados.

## Como o projeto organiza autenticação e roles

- O Laravel Fortify fornece as rotas e handlers de autenticação. Nós:
  - Registramos as views Fortify para resolver os contratos de view.
  - Substituímos `LoginResponse` com `App\Http\Responses\LoginResponse` para
    redirecionar por `role` após login.
  - Usamos um middleware `role` simples (`App\Http\Middleware\RoleMiddleware`) para
    restringir acesso às rotas com `->middleware('role:professor')`.

## Comandos úteis

- Instalar dependências PHP:

```bash
composer install
```

- Instalar dependências JS/CSS (quando aplicável):

```bash
npm install
npm run build   # ou npm run dev durante desenvolvimento
```

- Rodar migrations e seeders (atenção: pode apagar dados em produção):

```bash
php artisan migrate
php artisan db:seed
```

## Nota sobre ambiente local

- O projeto pode usar SQLite em `database/database.sqlite` para testes locais; ver `config/database.php`.
- Verifique `.env` para `APP_URL`, `DB_CONNECTION` e `APP_ENV` antes de rodar.

## Próximas ações recomendadas (se quiser que eu execute)

- Gerar seeders com usuários de teste (`admin`, `professor`, `aluno`) e um comando `setup_users.php` já presente — posso transformá-lo em seeder.
- Adicionar testes automatizados (Feature tests) para garantir que os redirecionamentos pós-login funcionem para cada role.
- Extrair as closures das rotas para controllers dedicados para melhorar testabilidade e organização.

---

Se quiser eu já faço o commit das mudanças de documentação e comentários; me confirme que eu devo rodar:

```bash
git add .
git commit -m "docs: add inline comments and expand DOCUMENTATION.md"
```

Ou se preferir, eu apenas gero os comandos e você executa localmente.
