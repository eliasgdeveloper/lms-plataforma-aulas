# Estrutura do Projeto LMS

Este documento descreve a estrutura principal do repositorio e aponta os pontos de entrada.

## Arvore principal (nivel alto)

```
lms-projeto/
├── app/
│   ├── Console/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Responses/
│   ├── Listeners/
│   ├── Models/
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── build/
│   └── pages/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
├── storage/
├── tests/
├── vendor/
├── docs/
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── tailwind.config.js
└── vite.config.js
```

## Pontos de entrada principais

- HTTP: [public/index.php](public/index.php)
- Rotas web: [routes/web.php](routes/web.php)
- Rotas auth: [routes/auth.php](routes/auth.php)
- Providers: [app/Providers](app/Providers)
- Configuracao de banco: [config/database.php](config/database.php)

## Pastas mais importantes

- [app/Http/Controllers](app/Http/Controllers) - Controllers de HTTP
- [app/Http/Requests](app/Http/Requests) - Form Requests e validacoes
- [app/Models](app/Models) - Eloquent Models
- [resources/views](resources/views) - Views Blade
- [database/migrations](database/migrations) - Migrations
- [database/seeders](database/seeders) - Seeders

## Documentacao

- Indice principal: [docs/README.md](docs/README.md)

## Atualizacoes 2026-02-08 (Cursos)

- Controller: [app/Http/Controllers/CursoController.php](app/Http/Controllers/CursoController.php)
- Component: [resources/views/components/course-card.blade.php](resources/views/components/course-card.blade.php)
- Views:
	- [resources/views/pages/aluno_cursos/index.blade.php](resources/views/pages/aluno_cursos/index.blade.php)
	- [resources/views/pages/professor_cursos/index.blade.php](resources/views/pages/professor_cursos/index.blade.php)
	- [resources/views/pages/admin_cursos/index.blade.php](resources/views/pages/admin_cursos/index.blade.php)
	- [resources/views/pages/admin_cursos/create.blade.php](resources/views/pages/admin_cursos/create.blade.php)
	- [resources/views/pages/professor_cursos/create.blade.php](resources/views/pages/professor_cursos/create.blade.php)
	- [resources/views/pages/cursos/_form.blade.php](resources/views/pages/cursos/_form.blade.php)
	- [resources/views/pages/admin_cursos/show.blade.php](resources/views/pages/admin_cursos/show.blade.php)
	- [resources/views/pages/professor_cursos/show.blade.php](resources/views/pages/professor_cursos/show.blade.php)
	- [resources/views/pages/cursos/_settings.blade.php](resources/views/pages/cursos/_settings.blade.php)
- CSS:
	- [public/pages/cursos/style.css](public/pages/cursos/style.css)
	- [public/pages/cursos/create.css](public/pages/cursos/create.css)
	- [public/pages/cursos/settings.css](public/pages/cursos/settings.css)
