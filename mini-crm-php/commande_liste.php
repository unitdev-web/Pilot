<?php
require_once 'db.php';
$pdo = connectDB();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: liste.php');
    exit;
}

// Récupérer le client
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch();

if (!$utilisateur) {
    echo "Client introuvable.";
    exit;
}

// Récupérer les commandes du client
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE utilisateur_id = ? ORDER BY date_commande DESC");
$stmt->execute([$id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer une requête pour charger les lignes de chaque commande
$requeteLignes = $pdo->prepare("
    SELECT lc.quantite, s.nom 
    FROM lignes_commandes lc
    JOIN stock s ON lc.article_id = s.id
    WHERE lc.commande_id = ?
");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Commandes de <?= htmlspecialchars($utilisateur['nom']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
<div class="container py-5">
  <h1 class="mb-4">Commandes de <?= htmlspecialchars($utilisateur['nom']) ?></h1>

  <a class="btn btn-success mb-3" href="commande_ajouter.php">Créer une nouvelle commande</a>
  <a class="btn btn-secondary mb-3" href="liste.php">Retour à la liste des clients</a>

  <?php if (count($commandes) === 0): ?>
    <p class="text-warning">Aucune commande trouvée.</p>
  <?php else: ?>
    <?php foreach ($commandes as $commande): ?>
      <div class="card mb-4 bg-secondary text-white">
        <div class="card-header d-flex justify-content-between">
          <div>
            <strong>Commande #<?= $commande['id'] ?></strong><br>
            <small><?= $commande['date_commande'] ?></small>
          </div>
          <div>
            <?= $commande['statut'] ? '<span class="badge bg-success">Complétée</span>' : '<span class="badge bg-warning text-dark">En attente</span>' ?>
          </div>
        </div>
        <div class="card-body">
          <?php if ($commande['description']): ?>
            <p><strong>Description :</strong> <?= htmlspecialchars($commande['description']) ?></p>
          <?php endif; ?>

          <h6>Articles commandés :</h6>
          <ul>
            <?php
              $requeteLignes->execute([$commande['id']]);
              $articles = $requeteLignes->fetchAll(PDO::FETCH_ASSOC);
              foreach ($articles as $article) {
                  echo "<li>" . htmlspecialchars($article['nom']) . " — Quantité : " . $article['quantite'] . "</li>";
              }
            ?>
          </ul>

          <div class="mt-3">
            <?php if (!$commande['statut']): ?>
              <a href="commande_completer.php?id=<?= $commande['id'] ?>&utilisateur=<?= $id ?>" class="btn btn-success btn-sm">Marquer comme complétée</a>
            <?php endif; ?>
            <a href="commande_supprimer.php?id=<?= $commande['id'] ?>&utilisateur=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette commande ?')">Supprimer</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body>
</html>
