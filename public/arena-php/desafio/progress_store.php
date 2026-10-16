<?php
/**
 * Persistencia provisoria do progresso (arquivo JSON por usuario).
 */

function desafio_progress_defaults()
{
    return array(
        'pontos' => 0,
        'concluidas' => array(),
        'surpresas' => array(),
        'avatar' => 'starter',
        'extra_done' => false,
        'updated_at' => null,
    );
}

function desafio_progress_dir()
{
    $dir = __DIR__ . '/data';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    return $dir;
}

function desafio_progress_bucket()
{
    return defined('DESAFIO_BUCKET') && DESAFIO_BUCKET !== ''
        ? preg_replace('/[^a-zA-Z0-9_-]/', '', DESAFIO_BUCKET)
        : 'default';
}

function desafio_progress_filename($username)
{
    $safe = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', trim($username)));
    if ($safe === '') {
        $safe = 'anonimo';
    }
    $bucket = desafio_progress_bucket();
    if ($bucket === 'default') {
        return desafio_progress_dir() . '/progress_' . $safe . '.json';
    }
    return desafio_progress_dir() . '/progress_' . $bucket . '_' . $safe . '.json';
}

function desafio_session_key()
{
    $bucket = desafio_progress_bucket();
    return $bucket === 'default' ? 'desafio' : ('desafio_' . $bucket);
}

function desafio_progress_normalize($data)
{
    if (!is_array($data)) {
        return desafio_progress_defaults();
    }

    $concluidas = array();
    if (!empty($data['concluidas']) && is_array($data['concluidas'])) {
        foreach ($data['concluidas'] as $id) {
            $concluidas[] = (int) $id;
        }
        $concluidas = array_values(array_unique($concluidas));
        sort($concluidas);
    }

    $surpresas = array();
    if (!empty($data['surpresas']) && is_array($data['surpresas'])) {
        foreach ($data['surpresas'] as $item) {
            if (is_string($item) && $item !== '') {
                $surpresas[] = $item;
            }
        }
        $surpresas = array_values(array_unique($surpresas));
    }

    return array(
        'pontos' => isset($data['pontos']) ? (int) $data['pontos'] : 0,
        'concluidas' => $concluidas,
        'surpresas' => $surpresas,
        'avatar' => !empty($data['avatar']) ? (string) $data['avatar'] : 'starter',
        'extra_done' => !empty($data['extra_done']),
        'updated_at' => isset($data['updated_at']) ? $data['updated_at'] : null,
    );
}

function desafio_progress_read_file($username)
{
    $file = desafio_progress_filename($username);
    if (!is_file($file)) {
        return desafio_progress_defaults();
    }
    $raw = @file_get_contents($file);
    if ($raw === false || $raw === '') {
        return desafio_progress_defaults();
    }
    return desafio_progress_normalize(json_decode($raw, true));
}

function desafio_progress_write_file($username, $state)
{
    $dir = desafio_progress_dir();
    if (!is_dir($dir) || !is_writable($dir)) {
        return false;
    }

    $payload = desafio_progress_normalize($state);
    $payload['updated_at'] = date('c');
    $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }

    $file = desafio_progress_filename($username);
    $tmp = $file . '.tmp';
    if (@file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }
    return @rename($tmp, $file);
}

function desafio_progress_current_username()
{
    $user = plm_current_user();
    if (!$user || empty($user['username'])) {
        return null;
    }
    return $user['username'];
}

function desafio_progress_load_into_session()
{
    plm_start_session();
    $username = desafio_progress_current_username();
    $key = desafio_session_key();
    if ($username === null) {
        $_SESSION[$key] = desafio_progress_defaults();
        return $_SESSION[$key];
    }
    $_SESSION[$key] = desafio_progress_read_file($username);
    return $_SESSION[$key];
}

function desafio_progress_save_from_session()
{
    plm_start_session();
    $username = desafio_progress_current_username();
    if ($username === null) {
        return false;
    }
    $key = desafio_session_key();
    if (!isset($_SESSION[$key]) || !is_array($_SESSION[$key])) {
        $_SESSION[$key] = desafio_progress_defaults();
    }
    return desafio_progress_write_file($username, $_SESSION[$key]);
}

function desafio_progress_boot()
{
    return desafio_progress_load_into_session();
}

function &desafio_progress_state_ref()
{
    $key = desafio_session_key();
    if (!isset($_SESSION[$key]) || !is_array($_SESSION[$key])) {
        $_SESSION[$key] = desafio_progress_defaults();
    }
    return $_SESSION[$key];
}