<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(400);
    echo "Invalid CSRF token.";
    exit;
}

$_SESSION = [];
setcookie(session_name(), '', time() - 3600, '/');
session_destroy();
header('Location: index.php');
exit;
