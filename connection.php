<?php
// connection.php
// mysqli connection, sets utf8mb4 and exposes $con

$host = "localhost";
$user = "root";
$password = "";
$db_name = "hr";

$con = new mysqli($host, $user, $password, $db_name);
if ($con->connect_errno) {
    // In production, log and show generic message
    error_log("DB connect error: " . $con->connect_error);
    http_response_code(500);
    die("Database connection failed.");
}
$con->set_charset("utf8mb4");
