<!-- ajouter.php -->
<?php
require_once 'db.php';
$pdo = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, age) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['nom'], $_POST['email'], $_POST['age']]);
    header('Location: liste.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ajouter un utilisateur</title>
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

<body class="bg-dark">
  <div class="container py-5">
    <h1 class="mb-4">Ajouter un utilisateur</h1>
    <form method="post">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" name="nom" id="nom" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email">
      </div>
      <div class="mb-3">
        <label for="age" class="form-label">Âge</label>
        <input type="number" class="form-control" name="age" id="age">
      </div>
      <button type="submit" class="btn btn-success">Ajouter</button>
      <a href="liste.php" class="btn btn-secondary">Retour</a>
    </form>
  </div>
</body>
</html>