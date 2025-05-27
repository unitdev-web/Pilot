<!-- commande_supprimer.php -->
<?php
require_once 'db.php';
$pdo = connectDB();
$id = $_GET['id'] ?? null;
$utilisateur_id = $_GET['utilisateur'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM commandes WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: commande_liste.php?id=$utilisateur_id");
exit;
?>

