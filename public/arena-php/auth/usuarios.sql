-- Execute este script se for autenticar pelo MySQL.
-- Configure as credenciais do banco em auth/config.php e habilite use_database.

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    nome VARCHAR(120) NOT NULL,
    cargo VARCHAR(80) NOT NULL DEFAULT '',
    password_hash VARCHAR(255) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
