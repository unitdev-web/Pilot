<?php
require_once 'db.php';
$pdo = connectDB();

$id = $_GET['id'] ?? null;
$utilisateur_id = $_GET['utilisateur'] ?? null;

if (!$id || !$utilisateur_id) {
    header("Location: commande_liste.php?id=$utilisateur_id");
    exit;
}

// Vérifier si la commande est déjà complétée
$stmt = $pdo->prepare("SELECT statut FROM commandes WHERE id = ?");
$stmt->execute([$id]);
$statut = $stmt->fetchColumn();

if ($statut === false || $statut == 1) {
    header("Location: commande_liste.php?id=$utilisateur_id");
    exit;
}

// Récupérer les lignes de commande
$stmt = $pdo->prepare("SELECT article_id, quantite FROM lignes_commandes WHERE commande_id = ?");
$stmt->execute([$id]);
$lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Diminuer le stock pour chaque article
foreach ($lignes as $ligne) {
    $stmt = $pdo->prepare("UPDATE stock SET quantite = quantite - ? WHERE id = ?");
    $stmt->execute([$ligne['quantite'], $ligne['article_id']]);
}

// Marquer la commande comme complétée
$stmt = $pdo->prepare("UPDATE commandes SET statut = 1 WHERE id = ?");
$stmt->execute([$id]);

header("Location: commande_liste.php?id=$utilisateur_id");
exit;
