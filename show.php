<?php
session_start();
require_once 'connection.php';

if (empty($_SESSION['username']) || empty($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Veuillez vous connecter.";
    exit;
}

function e($s) {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$user_id = (int)($_SESSION['user_id'] ?? 0);
$stmt = $con->prepare("SELECT ID, username FROM login WHERE ID = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Afficher utilisateur</title>
</head>
<body>
<p>Connecté en tant que : <?php echo e($_SESSION['username']); ?></p>

<?php if ($row): ?>
<h1><center> ID de l'utilisateur '<?php echo e($row['username']); ?>' : <?php echo e($row['ID']); ?> </center></h1>
<?php else: ?>
<h1><center> Vérifier le Username </center></h1>
<?php endif; ?>

<form action="logout.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token'] ?? ''); ?>">
    <button type="submit">Logout</button>
</form>
</body>
</html>
<?php
$stmt->close();
$con->close();
