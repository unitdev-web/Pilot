<?php
require_once 'db.php';
$pdo = connectDB();

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM stock WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: stock_liste.php');
exit;
