<?php

session_start();

require_once '../../core/pdo.php';
require_once '../../core/utils.php';


$smtmt = $pdo->prepare('SELECT * FROM produit');
$smtmt->execute();
$produits = $smtmt->fetchAll(PDO::FETCH_ASSOC);

?>



<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produits — Vendeur</title>
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
          <div><div class="text-sm font-semibold">Vendeur <?= $_SESSION['user']['nom'] ?></div><div class="text-xs text-gray-400"> <?= $_SESSION['user']['region'] ?> </div></div>
        </div>
      </div>
      <a href="vendeur.php" class="sidebar-link">📊 Tableau de bord</a>
      <a href="vendeur-produits.php" class="sidebar-link" aria-current="page">🌾 Mes produits</a>
      <a href="vendeur-commandes.php" class="sidebar-link">🛍️ Commandes reçues</a>
      <div class="mt-auto pt-4"><a href="../../features/auth/deconnexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>
    <main class="flex-1 p-6 overflow-auto">
      <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
        <h1 class="text-2xl font-bold font-playfair">Mes produits</h1>
        <a href="vendeur-formulaire.php" class="btn-primary text-sm opacity-75">+ Ajouter un produit</a>
      </div>
      <div class="card overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
          <thead class="bg-[#e0f5de]">
            <tr><th class="p-3 text-left">Produit</th><th class="p-3 text-left">Catégorie</th><th class="p-3 text-left">Stock</th><th class="p-3 text-left">Prix</th><th class="p-3 text-left">Statut</th><th class="p-3"></th></tr>
          </thead>
          <tbody>
            <?php foreach ($produits as $produit) {
              $produitId = $produit['id'] ?? $produit['id_produit'] ?? null;
              ?>
            <tr class="border-t border-gray-100 table-row"><td class="p-3 font-medium"><div>
              <img src="<?= $produit['image'] ?>" alt="" class="w-16 h-16 object-cover rounded-md mr-3 inline-block">
              <strong><?= $produit['nom'] ?></strong>
            </div></td><td class="p-3"><?= $produit['type'] ?></td><td class="p-3"><?= $produit['stock'] ?> <?= $produit['unite'] ?></td><td class="p-3"><?= number_format($produit['prix_unitaire'], 0, ',', ' ') ?> Ar/<?= $produit['unite'] ?></td><td class="p-3"><span class="badge badge-green">Actif</span></td><td class="p-3">
              <?php if ($produitId !== null): ?>
                <a href="vendeur-formulaire.php?id=<?= (int) $produitId ?>" class="btn-sec text-xs py-1 px-2 inline-block">Modifier</a>
              <?php else: ?>
                <span class="btn-sec text-xs py-1 px-2 inline-block">Modifier</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
