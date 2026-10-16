# Curso de Codar

Página de acesso da plataforma de aulas.

A entrada fica em `index.php`. Depois do login, a página de teste é `bemvindo.php`.

Por padrão, nenhum usuário local é incluído no repositório. Para configurar contas
locais, crie `auth/users.local.php` no servidor (esse arquivo é ignorado pelo Git)
e retorne uma lista de usuários com `username`, `name`, `role` e `password_hash`.
Gere os hashes com `password_hash('sua-senha', PASSWORD_DEFAULT)` e nunca versione
senhas ou hashes. Como alternativa, configure o acesso ao MySQL em `auth/config.php`
e defina `use_database` como `true`.
