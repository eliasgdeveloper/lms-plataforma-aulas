<?php
/**
 * Configuração do acesso restrito.
 *
 * MODO 1 (já funciona ao enviar os arquivos): usuários no arquivo users.php
 * MODO 2 (MySQL da Hostinger): preencha os dados abaixo e mude use_database para true.
 *
 * Na Hostinger: painel > Bancos de Dados MySQL
 * - host costuma ser "localhost"
 * - nome do banco, usuário e senha aparecem no painel
 */
return array(
    'use_database' => false,
    'session_name' => 'PLM_SESS',
    'session_hours' => 8,
    'max_attempts' => 6,
    'lock_seconds' => 60,
    'db' => array(
        'host' => 'localhost',
        'name' => 'NOME_DO_BANCO',
        'user' => 'USUARIO_MYSQL',
        'pass' => 'SENHA_MYSQL',
        'charset' => 'utf8mb4',
        'table' => 'usuarios',
    ),
);
