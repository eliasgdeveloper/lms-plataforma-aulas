# Estrutura de Páginas - Padrão Tríade

## 📁 Organização

Cada página visível no navegador agora segue o **padrão tríade** com 3 arquivos na mesma pasta:

```
resources/views/pages/{page_name}/
├── index.blade.php  ← HTML/Template (Blade)
├── style.css        ← CSS da página
└── script.js        ← JavaScript da página
```

### Exemplo: Aluno Dashboard

```
resources/views/pages/aluno_dashboard/
├── index.blade.php
├── style.css
└── script.js
```

## 🎯 Vantagens

✅ **Edição fácil no VS Code**: Todos os 3 arquivos da página ficam juntos
✅ **Organização clara**: Uma pasta = Uma página
✅ **Laravel nativo**: Usa `@extends`, `@push`, `asset()` normalmente
✅ **Hot reload**: Funciona com Vite/npm run dev
✅ **Fácil manutenção**: Não precisa navegar entre resources/ e public/

## 📂 Estrutura Completa

```
resources/views/pages/
├── welcome/
│   ├── index.blade.php
│   ├── style.css
│   └── script.js
├── dashboard/
│   ├── index.blade.php
│   ├── style.css
│   └── script.js
├── profile/
│   ├── index.blade.php
│   ├── style.css
│   └── script.js
├── aluno_dashboard/
│   ├── index.blade.php
│   ├── style.css
│   └── script.js
├── aluno_aulas/
├── aluno_materiais/
├── aluno_notas/
├── aluno_conteudos/
├── professor_dashboard/
├── professor_aulas/
├── professor_materiais/
├── professor_alunos/
├── admin_dashboard/
├── admin_usuarios/
├── admin_cursos/
└── admin_configuracoes/
```

## 🔧 Como Usar

### 1. Criar Nova Página

Use o comando Artisan:

```bash
php artisan make:page nome_da_pagina
```

Isso cria automaticamente:
- `resources/views/pages/nome_da_pagina/index.blade.php`
- `resources/views/pages/nome_da_pagina/style.css`
- `resources/views/pages/nome_da_pagina/script.js`
- `public/pages/nome_da_pagina/style.css` (cópia para asset())
- `public/pages/nome_da_pagina/script.js` (cópia para asset())

### 2. Adicionar Rota

Em `routes/web.php`:

```php
Route::get('/minha-pagina', function () {
    return view('pages.nome_da_pagina.index');
})->name('minha.pagina');
```

### 3. Estrutura do Blade

```blade
@extends('layouts.page')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/nome_da_pagina/style.css') }}">
@endpush

<div class="page-container">
    <h1>Título</h1>
    <p>Conteúdo</p>
</div>

@push('scripts')
<script src="{{ asset('pages/nome_da_pagina/script.js') }}" defer></script>
@endpush
@endsection
```

## 🎨 Layouts Disponíveis

- `layouts.page` - Layout base (sem sidebar)
- `layouts.aluno` - Layout com sidebar azul + extends page
- `layouts.professor` - Layout com sidebar indigo + extends page
- `layouts.admin` - Layout com sidebar vermelho + extends page

## 📝 CSS Pattern

Todos os CSS seguem este padrão com variáveis CSS:

```css
:root {
    --primary: #003d82;
    --secondary: #0051b8;
    --text-dark: #1b1b18;
    --text-light: #6b7280;
    --bg-light: #f3f4f6;
    --bg-white: #ffffff;
    --border: #e5e7eb;
}

@media (prefers-color-scheme: dark) {
    :root {
        --text-dark: #f5f5f1;
        --text-light: #d1d5db;
        --bg-light: #1f2937;
        --bg-white: #111827;
        --border: #374151;
    }
}

.page-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}
```

## 🚀 Deploy / Build

### Desenvolvimento

1. Edite os arquivos em `resources/views/pages/{page_name}/`
2. Copie mudanças CSS/JS para `public/pages/{page_name}/`:

```bash
# Exemplo: copiar CSS editado
cp resources/views/pages/aluno_dashboard/style.css public/pages/aluno_dashboard/
```

### Produção

Antes do deploy:

```bash
# Copiar todos CSS/JS editados para public/
php artisan view:cache
php artisan config:cache
```

## 📌 Importante

- ⚠️ O Laravel serve assets de `public/`, então `asset()` sempre aponta para `public/pages/`
- ✅ Edite em `resources/views/pages/{page}/` e depois copie para `public/pages/{page}/`
- ✅ O comando `make:page` já faz isso automaticamente

## 📍 Páginas Atuais

### Aluno (5 páginas)
- `/aluno` → Dashboard
- `/aluno/aulas` → Minhas Aulas
- `/aluno/materiais` → Materiais de Estudo
- `/aluno/notas` → Notas e Desempenho
- `/aluno/aulas/{id}/conteudos` → Conteúdos da Aula

### Professor (4 páginas)
- `/professor` → Dashboard
- `/professor/aulas` → Criar Aula
- `/professor/materiais` → Postar Material
- `/professor/alunos` → Acompanhar Alunos

### Admin (4 páginas)
- `/admin` → Dashboard
- `/admin/usuarios` → Gerenciar Usuários
- `/admin/cursos` → Gerenciar Cursos
- `/admin/configuracoes` → Configurações

### Geral (3 páginas)
- `/` → Welcome
- `/dashboard` → Dashboard Genérico
- `/profile` → Perfil do Usuário

---

**Total: 16 páginas + 3 layouts = 19 tríades completas**
