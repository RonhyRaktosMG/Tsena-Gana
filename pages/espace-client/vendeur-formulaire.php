<?php
require_once '../../core/pdo.php';

function e(string $value = null): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function loadProduit(PDO $pdo, int $id): array
{
    foreach (['id', 'id_produit'] as $pkField) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM produit WHERE {$pkField} = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produit) {
                return [$produit, $pkField];
            }
        } catch (PDOException $e) {
            // ...existing code...
        }
    }

    return [null, null];
}

$isEdit = isset($_GET['id']) && ctype_digit((string) $_GET['id']);
$produit = [
    'nom' => '',
    'type' => '',
    'stock' => '',
    'unite' => '',
    'prix_unitaire' => '',
    'image' => '',
];
$primaryKey = 'id';
$errors = [];

if ($isEdit) {
    [$loadedProduit, $loadedPrimaryKey] = loadProduit($pdo, (int) $_GET['id']);

    if ($loadedProduit) {
        $produit = array_merge($produit, $loadedProduit);
        $primaryKey = $loadedPrimaryKey;
    } else {
        $errors[] = 'Produit introuvable.';
        $isEdit = false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $stock = trim($_POST['stock'] ?? '');
    $unite = trim($_POST['unite'] ?? '');
    $prixUnitaire = trim($_POST['prix_unitaire'] ?? '');
    $image = trim($_POST['image'] ?? '');

    $produit = [
        'nom' => $nom,
        'type' => $type,
        'stock' => $stock,
        'unite' => $unite,
        'prix_unitaire' => $prixUnitaire,
        'image' => $image,
    ];

    if ($nom === '') {
        $errors[] = 'Le nom du produit est obligatoire.';
    }
    if ($type === '') {
        $errors[] = 'La catégorie est obligatoire.';
    }
    if ($unite === '') {
        $errors[] = 'L’unité est obligatoire.';
    }
    if ($stock === '' || !is_numeric($stock) || (int) $stock < 0) {
        $errors[] = 'Le stock doit être un nombre positif.';
    }
    if ($prixUnitaire === '' || !is_numeric($prixUnitaire) || (float) $prixUnitaire < 0) {
        $errors[] = 'Le prix doit être un nombre positif.';
    }

    if (!$errors) {
        if ($isEdit && isset($_POST['id'])) {
            $stmt = $pdo->prepare("
                UPDATE produit
                SET nom = :nom,
                    type = :type,
                    stock = :stock,
                    unite = :unite,
                    prix_unitaire = :prix_unitaire,
                    image = :image
                WHERE {$primaryKey} = :id
            ");
            $stmt->execute([
                'nom' => $nom,
                'type' => $type,
                'stock' => (int) $stock,
                'unite' => $unite,
                'prix_unitaire' => (float) $prixUnitaire,
                'image' => $image,
                'id' => (int) $_POST['id'],
            ]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO produit (nom, type, stock, unite, prix_unitaire, image)
                VALUES (:nom, :type, :stock, :unite, :prix_unitaire, :image)
            ");
            $stmt->execute([
                'nom' => $nom,
                'type' => $type,
                'stock' => (int) $stock,
                'unite' => $unite,
                'prix_unitaire' => (float) $prixUnitaire,
                'image' => $image,
            ]);
        }

        header('Location: vendeur-produits.php');
        exit;
    }
}

$pageTitle = $isEdit ? 'Modifier un produit' : 'Ajouter un produit';
?>

<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?> — Vendeur</title>
  <link rel="stylesheet" href="../../assets/css/output.css">
</head>
<body>
  <?php include '../../components/partials/navbar.php'; ?>

  <div class="flex min-h-[calc(100vh-80px)]">
    <aside class="w-56 bg-white shadow-sm p-4 flex-col gap-1 hidden md:flex shrink-0 border-r border-gray-100">
      <div class="mb-4">
        <div class="font-bold text-sm text-gray-400 uppercase tracking-wide mb-2">Vendeur</div>
        <div class="flex items-center gap-2 mb-3">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold bg-green-main">PR</div>
          <div><div class="text-sm font-semibold">VendeurPro Ramy</div><div class="text-xs text-gray-400">Antananarivo</div></div>
        </div>
      </div>
      <a href="vendeur.php" class="sidebar-link">📊 Tableau de bord</a>
      <a href="vendeur-produits.php" class="sidebar-link" aria-current="page">🌾 Mes produits</a>
      <a href="vendeur-commandes.php" class="sidebar-link">🛍️ Commandes reçues</a>
      <div class="mt-auto pt-4"><a href="connexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>

    <main class="flex-1 p-6 overflow-auto flex justify-center">
      <div class="max-w-2xl">
        <div class="flex items-center justify-between gap-3 mb-5">
          <h1 class="text-2xl font-bold font-playfair"><?= e($pageTitle) ?></h1>
        </div>

        <?php if ($errors): ?>
          <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <?php foreach ($errors as $error): ?>
              <div><?= e($error) ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form method="post" class="card space-y-4 p-5">
          <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= (int) $_GET['id'] ?>">
          <?php endif; ?>

          <div>
            <label class="block text-sm font-medium mb-1">Nom du produit</label>
            <input type="text" name="nom" value="<?= e($produit['nom']) ?>" class="w-full border rounded-md px-3 py-2" required>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Catégorie</label>
            <input type="text" name="type" value="<?= e($produit['type']) ?>" class="w-full border rounded-md px-3 py-2" required>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Stock</label>
              <input type="number" min="0" name="stock" value="<?= e((string) $produit['stock']) ?>" class="w-full border rounded-md px-3 py-2" required>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Unité</label>
              <input type="text" name="unite" value="<?= e($produit['unite']) ?>" class="w-full border rounded-md px-3 py-2" placeholder="kg, pièce, sac..." required>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Prix unitaire</label>
              <input type="number" min="0" step="0.01" name="prix_unitaire" value="<?= e((string) $produit['prix_unitaire']) ?>" class="w-full border rounded-md px-3 py-2" required>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Image</label>
            <input type="text" name="image" value="<?= e($produit['image']) ?>" class="w-full border rounded-md px-3 py-2" placeholder="URL ou chemin de l'image">
          </div>

          <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">
              <?= $isEdit ? 'Modifier le produit' : 'Ajouter le produit' ?>
            </button>
            <a href="vendeur-produits.php" class="btn-sec">Annuler</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>