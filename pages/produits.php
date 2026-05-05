<?php

require_once '../core/pdo.php';

$stmt = $pdo->prepare('SELECT * FROM produit');
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>




<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produits — TsenaGana</title>
  <link rel="stylesheet" href="../assets/css/output.css">
</head>
<body>
  <?php include '../components/partials/navbar.php'; ?>

  <main class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-3">
      <div>
        <h1 class="text-3xl font-bold font-playfair">Produits disponibles</h1>
        <p class="text-gray-500"><?= count($produits) ?> résultats</p>
      </div>

      <!-- FILTRE -->
      <!-- <div class="flex flex-wrap gap-2">
        <input class="input-field w-48 max-w-full" type="search" placeholder="Rechercher...">
        <select class="input-field w-40 max-w-full">
          <option>Tous les éleveurs</option>
          <option>Éleveur Rakoto</option>
          <option>Éleveur Rabe</option>
          <option>Éleveur Andry</option>
        </select>
        <a href="aliments.html" class="btn-sec text-sm self-center">Voir aliments</a>
      </div> -->
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
      
      <?php
      foreach ($produits as $produit) {
      ?>
        <a href="aliment-detail.html" class="card no-underline text-inherit block">
          <div class="h-62 flex items-center justify-center bg-[#f9fbe7]">
            <img src="<?= $produit['image'] ?>" alt="<?= $produit['nom'] ?>" class="h-full w-full object-cover rounded">
          </div>
          <div class="p-4">
            <div class="flex items-center justify-between mb-1">
              <h2 class="font-bold"><?= $produit['nom'] ?></h2>
              <span class="badge badge-green"><?= $produit['stock'] ?> <?= $produit['unite'] ?></span>
            </div>
            <p class="text-sm text-gray-500 mb-2"><?= $produit['type'] ?></p>
            <div class="flex items-center justify-between">
              <span class="font-bold text-green-main"><?= $produit['prix_unitaire'] ?> Ar/<?= $produit['unite'] ?></span>
              <span class="btn-primary text-sm pointer-events-none">+ Panier</span>
            </div>
          </div>
        </a>
      
      <?php
      }
      ?>
      


    </div>
  </main>

  <footer class="bg-[var(--black)] text-white py-8 px-4 mt-8">
    <div class="max-w-6xl mx-auto text-center text-gray-500 text-sm">© 2026 TsenaGana</div>
  </footer>
</body>
</html>
