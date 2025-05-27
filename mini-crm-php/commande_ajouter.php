<?php
require_once 'db.php';
$pdo = connectDB();

$utilisateurs = $pdo->query("SELECT * FROM utilisateurs")->fetchAll(PDO::FETCH_ASSOC);
$articles = $pdo->query("SELECT * FROM stock")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateur_id = $_POST['utilisateur_id'];
    $description = $_POST['description'] ?? '';

    // Créer la commande
    $stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, description) VALUES (?, ?)");
    $stmt->execute([$utilisateur_id, $description]);
    $commande_id = $pdo->lastInsertId();

    // Parcourir les articles envoyés
    foreach ($_POST['article_id'] as $index => $article_id) {
        $quantite = intval($_POST['quantite'][$index]);

        if ($quantite > 0) {
            $stmt = $pdo->prepare("INSERT INTO lignes_commandes (commande_id, article_id, quantite) VALUES (?, ?, ?)");
            $stmt->execute([$commande_id, $article_id, $quantite]);
        }
    }

    header("Location: commande_liste.php?id=$utilisateur_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une commande</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function ajouterLigneArticle() {
      const container = document.getElementById('articles-container');
      const ligne = document.querySelector('.article-row').cloneNode(true);
      ligne.querySelectorAll('input, select').forEach(el => {
        if (el.tagName === 'INPUT') el.value = 1;
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
      });
      container.appendChild(ligne);
    }

    function supprimerLigne(btn) {
      const ligne = btn.closest('.article-row');
      const container = document.getElementById('articles-container');
      if (container.children.length > 1) {
        ligne.remove();
      }
    }
  </script>
</head>
<body class="bg-dark text-white">
<div class="container py-5">
  <h1 class="mb-4">Nouvelle commande</h1>
  <form method="post">
    <div class="mb-3">
      <label for="utilisateur_id" class="form-label">Client</label>
      <select class="form-select" name="utilisateur_id" id="utilisateur_id" required>
        <?php foreach ($utilisateurs as $u): ?>
          <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nom']) ?> (<?= $u['email'] ?>)</option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description de la commande</label>
      <textarea class="form-control" name="description" id="description" rows="2"></textarea>
    </div>

    <div id="articles-container">
      <div class="row g-3 align-items-center mb-3 article-row">
        <div class="col-md-6">
          <label class="form-label">Article</label>
          <select class="form-select" name="article_id[]" required>
            <?php foreach ($articles as $a): ?>
              <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nom']) ?> (Stock : <?= $a['quantite'] ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Quantité</label>
          <input type="number" class="form-control" name="quantite[]" min="1" value="1" required>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="button" class="btn btn-danger w-100" onclick="supprimerLigne(this)">Supprimer</button>
        </div>
      </div>
    </div>

    <div class="mb-3">
      <button type="button" class="btn btn-warning" onclick="ajouterLigneArticle()">+ Ajouter un article</button>
    </div>

    <button type="submit" class="btn btn-success">Créer la commande</button>
    <a href="liste.php" class="btn btn-secondary">Annuler</a>
  </form>
</div>
</body>
</html>
