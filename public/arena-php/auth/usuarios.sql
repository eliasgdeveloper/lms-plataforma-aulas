-- Rode este script no phpMyAdmin da Hostinger SOMENTE se quiser
-- autenticar pelo MySQL. Depois, em auth/config.php, mude:
--   'use_database' => true
-- e preencha host, name, user e pass do banco.

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    nome VARCHAR(120) NOT NULL,
    cargo VARCHAR(80) NOT NULL DEFAULT '',
    password_hash VARCHAR(255) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (username, nome, cargo, password_hash, ativo) VALUES
('Elias', 'Elias Gomes', 'Engenheiro de Software e Professor', '$2y$12$J5g47JB7dcgUBSvgaMSjqu87HsRPtHZTVuoea9ImrbwDCV4HkwyxK', 1),
('Leandro', 'Leandro', 'Aluno PHP', '$2y$12$KFeSsbpQJ86Doeg5oNcRU.Y/fyypjR6OJmEj6lQdsdPAh7mnNBUmy', 1),
('Convidado', 'Convidado', 'Visitante', '$2y$12$2IGPJQhouJR1ZvVgJU.GrOMk2ldBOAq2Xo8.9pQ.yk/vnW6aEUgk.', 1)
ON DUPLICATE KEY UPDATE
    nome = VALUES(nome),
    cargo = VALUES(cargo),
    password_hash = VALUES(password_hash),
    ativo = 1;
