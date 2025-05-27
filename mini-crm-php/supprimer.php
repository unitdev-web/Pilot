<!-- supprimer.php -->
<?php
require_once 'db.php';
$pdo = connectDB();
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: liste.php');
exit;
?>
