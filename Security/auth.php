<?php
require_once __DIR__ . '/session.php';

function require_login(): void {
    secure_session_start();
    enforce_idle_timeout(900);

    if (!isset($_SESSION["UserID"])) {
        header("Location: login(NS).php");
        exit;
    }
}
