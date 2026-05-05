<?php

require_once '../core/pdo.php';

$stmt = $pdo->prepare('SELECT * FROM lot_canard');
$stmt->execute();
$canards = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>




<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Canards — TsenaGana</title>
  <link rel="stylesheet" href="../assets/css/output.css">
</head>
<body>
  <?php include '../components/partials/navbar.php'; ?>

  <main class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-3">
      <div>
        <h1 class="text-3xl font-bold font-playfair">Canards disponibles</h1>
        <p class="text-gray-500"><?= count($canards) ?> résultats</p>
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
      foreach ($canards as $canard) {
      # Calcul de l'âge du lot de canards en mois
      $age_lot = floor((time() - strtotime($canard['date_naissance'])) / (30 * 24 * 60 * 60));
      ?>
        <a href="canard-detail.html" class="card no-underline text-inherit block">
          <div class="h-62 flex items-center justify-center bg-[#e0f5de]">
            <img src="<?= $canard['image'] ?>" alt="Canard <?= $canard['race']?>" class="h-full w-full object-cover rounded">
          </div>
          <div class="p-4">
            <div class="flex items-center justify-between mb-1">
              <h2 class="font-bold text-lg">Canard <?= $canard['race']?></h2>
              <span class="badge badge-green"><?= $canard['quantite_stock'] ?> en stock</span>
            </div>
            <p class="text-sm text-gray-500 mb-2">Éleveur Rakoto · Antananarivo</p>
            <p class="text-xs text-gray-400 mb-3">Âge : <?= $age_lot ?> mois · Poids moyen : <?= $canard['poids_moyen'] ?> kg</p>
            <div class="flex items-center justify-between">
              <span class="font-bold text-xl text-green-main"> <?= $canard['prix_unitaire'] ?> Ar</span>
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
