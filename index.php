<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>PHP login system</title>
</head>
<body>
<div id="frm">
    <h1>Login</h1>
    <form name="f1" action="authentication.php" method="POST" autocomplete="off">
        <p>
            <label> UserName: </label>
            <input type="text" id="user" name="user" required maxlength="50" />
        </p>
        <p>
            <label> Password: </label>
            <input type="password" id="pass" name="pass" required />
        </p>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
        <p>
            <input type="submit" id="btn" value="Login" />
        </p>
    </form>
</div>
</body>
</html>
