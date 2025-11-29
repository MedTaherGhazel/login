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
    <title>Ajout des utilisateurs</title>
</head>
<body>
    <div id="frm">
        <h1>Ajout</h1>
        <form name="f1" action="add.php" method="POST" autocomplete="off">
            <p>
                <label> Nom d'utilisateur: </label>
                <input type="text" id="user" name="user" required maxlength="50" />
            </p>
            <p>
                <label> Mot de passe: </label>
                <input type="password" id="pass" name="pass" required minlength="8" />
            </p>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
            <p>
                <input type="submit" id="btn" value="ADD" />
            </p>
        </form>
    </div>
</body>
</html>
