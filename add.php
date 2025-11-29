<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed.";
    exit;
}

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(400);
    echo "Invalid CSRF token.";
    exit;
}

$username = trim($_POST['user'] ?? '');
$password = $_POST['pass'] ?? '';

if ($username === '' || $password === '') {
    http_response_code(400);
    echo "Username and password required.";
    exit;
}

if (strlen($username) > 50 || strlen($password) < 8) {
    http_response_code(400);
    echo "Invalid username or password length.";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $con->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
$stmt->bind_param('ss', $username, $hash);

if ($stmt->execute()) {
    echo "Ajout avec succès";
} else {
    if ($con->errno === 1062) {
        echo "Username déjà utilisé.";
    } else {
        error_log("Insert failed (add.php): " . $stmt->error);
        echo "Erreur lors de l'ajout.";
    }
}

$stmt->close();
$con->close();
