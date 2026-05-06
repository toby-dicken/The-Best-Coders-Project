<?php
function secure_session_start(): void {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => $isHttps,
    ]);

    ini_set('session.use_strict_mode', '1');
    session_start();

    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
    }
}

function enforce_idle_timeout(int $seconds = 900): void {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $seconds) {
        session_unset();
        session_destroy();
        header("Location: login(NS).php");
        exit;
    }
    $_SESSION['last_activity'] = time();
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_verify(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
