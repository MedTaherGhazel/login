<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed.";
    exit;
}

// CSRF check
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(400);
    echo "Invalid CSRF token.";
    exit;
}

$username = trim($_POST['user'] ?? '');
$password = $_POST['pass'] ?? '';

if ($username === '' || $password === '') {
    http_response_code(400);
    echo "Missing credentials.";
    exit;
}

$stmt = $con->prepare("SELECT ID, username, password FROM login WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "Invalid credentials.";
    $stmt->close();
    $con->close();
    exit;
}

$stored = $row['password'];
$authenticated = false;

if (password_verify($password, $stored)) {
    $authenticated = true;
} else {
    // legacy plaintext password check
    if (hash_equals($stored, $password)) {
        $authenticated = true;
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $u = $con->prepare("UPDATE login SET password = ? WHERE ID = ?");
        if ($u) {
            $u->bind_param('si', $newHash, $row['ID']);
            $u->execute();
            $u->close();
        }
    }
}

if ($authenticated) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$row['ID'];
    $_SESSION['username'] = $row['username'];
    header("Location: show.php");
    exit;
} else {
    echo "Invalid credentials.";
}

$stmt->close();
$con->close();
