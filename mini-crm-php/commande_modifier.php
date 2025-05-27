<!-- commande_modifier.php -->
<?php
require_once 'db.php';
$pdo = connectDB();
$id = $_GET['id'] ?? null;
$utilisateur_id = $_GET['utilisateur'] ?? null;
if (!$id || !$utilisateur_id) { header('Location: liste.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
$stmt->execute([$id]);
$commande = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE commandes SET description = ? WHERE id = ?");
    $stmt->execute([$_POST['description'], $id]);
    header("Location: commande_liste.php?id=$utilisateur_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier la commande</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
  <div class="container py-5">
    <h1 class="mb-4">Modifier la commande</h1>
    <form method="post">
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($commande['description']) ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <a href="commande_liste.php?id=<?= $utilisateur_id ?>" class="btn btn-secondary">Retour</a>
    </form>
  </div>
</body>
</html>
