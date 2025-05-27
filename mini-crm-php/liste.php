<!-- liste.php -->
<?php
require_once 'db.php';
$pdo = connectDB();
$utilisateurs = $pdo->query("SELECT * FROM utilisateurs")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Liste des utilisateurs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      align-items: center;
      padding-top: 40px;
      padding-bottom: 40px;
      background-color: #212529;
    }
    .container {
      max-width: 960px;
    }
  </style>
</head>
<body>
  <div class="container text-center">
    <h1 class="mb-4">Liste des utilisateurs</h1>
    <div class="d-flex justify-content-center gap-2 mb-3">
  <a class="btn btn-success" href="ajouter.php">Ajouter un utilisateur</a>
  <a class="btn btn-warning" href="stock_liste.php">Modifier le stock</a>
</div>
    
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Âge</th><th>Actions</th></tr>
      </thead>
      <tbody>
      <?php foreach ($utilisateurs as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['nom']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= $u['age'] ?></td>
          <td>
            <a class="btn btn-sm btn-primary" href="modifier.php?id=<?= $u['id'] ?>">Modifier</a>
            <a class="btn btn-sm btn-danger" href="supprimer.php?id=<?= $u['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
            <a class="btn btn-sm btn-warning" href="commande_liste.php?id=<?= $u['id'] ?>">Commandes</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>