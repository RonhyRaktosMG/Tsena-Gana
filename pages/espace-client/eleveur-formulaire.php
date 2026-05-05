<?php
require_once '../../core/pdo.php';

$errors = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$lot = [
  'race' => '',
  'date_naissance' => '',
  'poids_moyen' => '',
  'quantite_stock' => '',
  'prix_unitaire' => '',
  'image' => ''
];

if ($id) {
  $stmt = $pdo->prepare('SELECT * FROM lot_canard WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  $f = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$f) {
    header('Location: eleveur-canards.php'); exit;
  }
  $lot = array_merge($lot, $f);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $race = trim($_POST['race'] ?? '');
  $date_naissance = $_POST['date_naissance'] ?? '';
  $poids_moyen = $_POST['poids_moyen'] ?? '';
  $quantite_stock = $_POST['quantite_stock'] ?? '';
  $prix_unitaire = $_POST['prix_unitaire'] ?? '';
  $imagePath = $lot['image'] ?? '';

  if ($race === '') $errors[] = 'La race est requise.';
  if ($date_naissance === '') $errors[] = 'La date de naissance est requise.';

  // gestion upload image optionnel
  if (!empty($_FILES['image']['name'])) {
    $uploadDir = __DIR__ . '/../../uploads';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
      $errors[] = 'Impossible de créer le dossier d\'upload.';
    } else {
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $filename = uniqid('canard_') . '.' . ($ext ?: 'jpg');
      $dest = $uploadDir . '/' . $filename;
      if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        $errors[] = 'Échec de l\'upload de l\'image.';
      } else {
        // chemin relatif utilisé par les pages dans le même dossier
        $imagePath = '../../uploads/' . $filename;
      }
    }
  }

  if (empty($errors)) {
    if ($id) {
      $stmt = $pdo->prepare('UPDATE lot_canard SET race = ?, date_naissance = ?, poids_moyen = ?, quantite_stock = ?, prix_unitaire = ?, image = ? WHERE id = ?');
      $stmt->execute([$race, $date_naissance, $poids_moyen, $quantite_stock, $prix_unitaire, $imagePath, $id]);
    } else {
      $stmt = $pdo->prepare('INSERT INTO lot_canard (race, date_naissance, poids_moyen, quantite_stock, prix_unitaire, image) VALUES (?, ?, ?, ?, ?, ?)');
      $stmt->execute([$race, $date_naissance, $poids_moyen, $quantite_stock, $prix_unitaire, $imagePath]);
    }
    header('Location: eleveur-canards.php'); exit;
  } else {
    // réinjecter valeurs pour affichage en cas d'erreur
    $lot = [
      'race' => $race,
      'date_naissance' => $date_naissance,
      'poids_moyen' => $poids_moyen,
      'quantite_stock' => $quantite_stock,
      'prix_unitaire' => $prix_unitaire,
      'image' => $imagePath
    ];
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $id ? 'Modifier' : 'Ajouter' ?> un lot de canards</title>
  <link rel="stylesheet" href="../../assets/css/output.css">
</head>
<body>
  <?php include '../../components/partials/navbar.php'; ?>
  <div class="flex min-h-[calc(100vh-80px)]">
    <aside class="w-56 bg-white shadow-sm p-4 flex-col gap-1 hidden md:flex shrink-0 border-r border-gray-100">
      <div class="mb-4">
        <div class="font-bold text-sm text-gray-400 uppercase tracking-wide mb-2">Éleveur</div>
        <div class="flex items-center gap-2 mb-3">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold bg-green-main">RA</div>
          <div><div class="text-sm font-semibold">Éleveur Rakoto</div><div class="text-xs text-gray-400">Antananarivo</div></div>
        </div>
      </div>
      <a href="eleveur.php" class="sidebar-link">📊 Tableau de bord</a>
      <a href="eleveur-canards.php" class="sidebar-link">🦆 Mes canards</a>
      <a href="eleveur-commandes.php" class="sidebar-link">🛍️ Commandes reçues</a>
      <div class="mt-auto pt-4"><a href="connexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>

    <main class="flex-1 p-6 overflow-auto flex justify-center">
      <div class="max-w-2xl">
        <div class="flex items-center justify-between gap-3 mb-5">
          <h1 class="text-2xl font-bold font-playfair"><?= $id ? 'Modifier' : 'Ajouter' ?> un lot de canards</h1>
        </div>

        <?php if ($errors): ?>
          <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <?php foreach ($errors as $error): ?>
              <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="card space-y-4 p-5">
          <div>
            <label class="block text-sm font-medium mb-1">Race</label>
            <input type="text" name="race" value="<?= htmlspecialchars($lot['race']) ?>" class="w-full border rounded-md px-3 py-2" required>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Date de naissance</label>
            <input type="date" name="date_naissance" value="<?= htmlspecialchars($lot['date_naissance']) ?>" class="w-full border rounded-md px-3 py-2" required>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Poids moyen</label>
              <input type="text" name="poids_moyen" value="<?= htmlspecialchars($lot['poids_moyen']) ?>" class="w-full border rounded-md px-3 py-2">
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Quantité en stock</label>
              <input type="number" min="0" name="quantite_stock" value="<?= htmlspecialchars($lot['quantite_stock']) ?>" class="w-full border rounded-md px-3 py-2">
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Prix unitaire</label>
              <input type="number" min="0" step="0.01" name="prix_unitaire" value="<?= htmlspecialchars($lot['prix_unitaire']) ?>" class="w-full border rounded-md px-3 py-2">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Image</label>
            <input type="file" name="image" accept="image/*" class="w-full border rounded-md px-3 py-2">
            <p class="text-xs text-gray-500 mt-1">Laisser vide pour conserver l'image actuelle</p>
          </div>

          <?php if (!empty($lot['image'])): ?>
            <div>
              <div class="text-sm font-medium mb-2">Image actuelle :</div>
              <img src="<?= htmlspecialchars($lot['image']) ?>" alt="" class="w-28 h-28 object-cover rounded">
            </div>
          <?php endif; ?>

          <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">
              <?= $id ? 'Modifier le lot' : 'Ajouter le lot' ?>
            </button>
            <a href="eleveur-canards.php" class="btn-sec">Annuler</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>