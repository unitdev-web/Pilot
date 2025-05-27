<?php
require_once 'db.php';
$pdo = connectDB();
$articles = $pdo->query("SELECT * FROM stock")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Stock</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
  <div class="container py-5">
    <h1 class="mb-4">Gestion du stock</h1>
    <a class="btn btn-success mb-3" href="stock_ajouter.php">Ajouter un article</a>
    <a class="btn btn-secondary mb-3" href="liste.php">Retour aux clients</a>
    <table class="table table-dark table-bordered text-center">
      <thead>
        <tr><th>ID</th><th>Nom</th><th>Quantité</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($articles as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= htmlspecialchars($a['nom']) ?></td>
          <td><?= $a['quantite'] ?></td>
          <td>
            <a class="btn btn-sm btn-primary" href="stock_modifier.php?id=<?= $a['id'] ?>">Modifier</a>
            <a class="btn btn-sm btn-danger" href="stock_supprimer.php?id=<?= $a['id'] ?>" onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
