<?php
require_once 'db.php';
$pdo = connectDB();

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: stock_liste.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM stock WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) { echo "Article introuvable."; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $quantite = intval($_POST['quantite']);

    if ($nom !== '') {
        $stmt = $pdo->prepare("UPDATE stock SET nom = ?, quantite = ? WHERE id = ?");
        $stmt->execute([$nom, $quantite, $id]);
        header('Location: stock_liste.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier un article</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
  <div class="container py-5">
    <h1 class="mb-4">Modifier l’article</h1>
    <form method="post">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom de l’article</label>
        <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($article['nom']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="quantite" class="form-label">Quantité</label>
        <input type="number" name="quantite" id="quantite" class="form-control" value="<?= $article['quantite'] ?>" min="0" required>
      </div>
      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <a href="stock_liste.php" class="btn btn-secondary">Annuler</a>
    </form>
  </div>
</body>
</html>
