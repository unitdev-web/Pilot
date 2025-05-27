<!-- modifier.php -->
<?php
require_once 'db.php';
$pdo = connectDB();
$id = $_GET['id'] ?? null;

if (!$id) { header('Location: liste.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom=?, email=?, age=? WHERE id=?");
    $stmt->execute([$_POST['nom'], $_POST['email'], $_POST['age'], $id]);
    header('Location: liste.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id=?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier un utilisateur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #212529;
      color: white;
    }
    .form-control {
      background-color: #343a40;
      color: white;
      border: 1px solid #495057;
    }
    .form-control:focus {
      background-color: #343a40;
      color: white;
      border-color: #6c757d;
      box-shadow: none;
    }
    label {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h1 class="mb-4">Modifier un utilisateur</h1>
    <form method="post">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" name="nom" id="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($utilisateur['email']) ?>">
      </div>
      <div class="mb-3">
        <label for="age" class="form-label">Âge</label>
        <input type="number" class="form-control" name="age" id="age" value="<?= $utilisateur['age'] ?>">
      </div>
      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <a href="liste.php" class="btn btn-secondary">Retour</a>
    </form>
  </div>
</body>
</html>
