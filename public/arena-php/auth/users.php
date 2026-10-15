<?php
/**
 * Usuários locais (usados enquanto use_database = false).
 * As senhas NÃO ficam em texto puro: apenas o hash bcrypt.
 */
return array(
    array(
        'username' => 'Elias',
        'name' => 'Elias Gomes',
        'role' => 'Engenheiro de Software e Professor',
        'password_hash' => '$2y$12$J5g47JB7dcgUBSvgaMSjqu87HsRPtHZTVuoea9ImrbwDCV4HkwyxK',
    ),
    array(
        'username' => 'Leandro',
        'name' => 'Leandro',
        'role' => 'Aluno PHP',
        'password_hash' => '$2y$12$KFeSsbpQJ86Doeg5oNcRU.Y/fyypjR6OJmEj6lQdsdPAh7mnNBUmy',
    ),
    array(
        'username' => 'Convidado',
        'name' => 'Convidado',
        'role' => 'Visitante',
        'password_hash' => '$2y$12$2IGPJQhouJR1ZvVgJU.GrOMk2ldBOAq2Xo8.9pQ.yk/vnW6aEUgk.',
    ),
    array(
        'username' => 'testeAluno',
        'name' => 'Aluno Teste',
        'role' => 'Aluno PHP',
        'password_hash' => '$2y$12$s.Pg.mqpl34.gcrl2IRX2u6iLO/gRRc0mF7hJNFFaU4bWKXBlH9by',
    ),
);
