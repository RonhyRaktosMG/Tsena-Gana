<?php

session_start();

require_once '../../core/pdo.php';
require_once '../../core/utils.php';

$smtmt = $pdo->prepare('SELECT * FROM produit');
$smtmt->execute();
# Produits les plus vendus (exemple statique pour les 3 premiers produits)
$top_produits = $smtmt->fetchAll(PDO::FETCH_ASSOC);

# Nombre total de produits 
$total_produits = $smtmt->rowCount();

# Nombre de commandes en attente 
$smtmt = $pdo->prepare('SELECT COUNT(*) FROM distribution_commande WHERE vendeur_id = :vendeur_id AND statut = "en_attente"');
$smtmt->execute(['vendeur_id' => $_SESSION['user']['id']]);
$commandes_attente = $smtmt->fetchColumn();


?>


<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendeur — TsenaGana</title>
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
      <a href="vendeur.php" class="sidebar-link" aria-current="page">📊 Tableau de bord</a>
      <a href="vendeur-produits.php" class="sidebar-link">🌾 Mes produits</a>
      <a href="vendeur-commandes.php" class="sidebar-link">🛍️ Commandes reçues</a>
      <div class="mt-auto pt-4"><a href="../../features/auth/deconnexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>
    <main class="flex-1 p-6 overflow-auto">
      <h1 class="text-2xl font-bold mb-5 font-playfair">Tableau de bord vendeur</h1>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card text-center"><div class="text-2xl font-bold text-green-main"><?= $total_produits ?></div><div class="text-xs text-gray-500 mt-1">Produits</div></div>
        <div class="stat-card text-center"><div class="text-2xl font-bold text-green-main"><?= $commandes_attente ?></div><div class="text-xs text-gray-500 mt-1">Commandes en attente</div></div>
      </div>
      <div class="card p-5">
        <h2 class="font-bold mb-3">Produits les plus vendus</h2>
        <div class="space-y-3">
            <?php foreach ($top_produits as $produit) { ?>
          <div class="flex items-center gap-3">
            <img src="<?= $produit['image'] ?>" alt="" class="w-16 h-16 object-cover rounded">
                <div class="flex-1">
                    <div class="flex justify-between text-sm mb-1">
                        <span><?= $produit['nom'] ?></span>
                        <span><?= 0 ?> <?= $produit['unite'] ?> vendus</span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100">
                        <div class="h-2 rounded-full bg-green-main" style="width:23%">

                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
