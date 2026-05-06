<?php

session_start();

  # PDO
require_once '../../core/pdo.php';
require_once '../../core/utils.php';


$smtmt = $pdo->prepare('SELECT * FROM lot_canard');
$smtmt->execute();
$canards = $smtmt->fetchAll(PDO::FETCH_ASSOC);


# Nombre total de canards en élevage
$total_canards = array_reduce($canards, function($carry, $item) {
    return $carry + $item['quantite_stock'];
}, 0);

# Nombre total de canards en vente (exclut les canards réservés ou non disponibles)
$total_en_vente = array_reduce($canards, function($carry, $item) {
    if (!$item['disponible']) {
        return $carry;
    }

    return $carry + max(0, $item['quantite_stock'] - $item['quantite_reserve']);
}, 0);

# Total de commande en attente
$smtmt = $pdo->prepare('SELECT COUNT(*) FROM distribution_commande WHERE vendeur_id = :eleveur_id AND statut = "en_attente"');
$smtmt->execute(['eleveur_id' => $_SESSION['user']['id']]);
$total_commandes = $smtmt->fetchColumn();

# Total de revenus
$total_revenus = 30000; // Exemple statique pour les revenus

?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Éleveur — TsenaGana</title>
  <link rel="stylesheet" href="../../assets/css/output.css">
</head>
<body>
  <?php include '../../components/partials/navbar.php'; ?>

  <div class="flex min-h-[calc(100vh-80px)]">
    <aside class="w-56 bg-white shadow-sm p-4 flex-col gap-1 hidden md:flex shrink-0 border-r border-gray-100">
      <div class="mb-4">
        <div class="font-bold text-sm text-gray-400 uppercase tracking-wide mb-2">Éleveur</div>
        <div class="flex items-center gap-2 mb-3">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold bg-green-main"><?php echo substr($_SESSION['user']['nom'], 0, 2); ?></div>
          <div><div class="text-sm font-semibold">Éleveur <?php echo $_SESSION['user']['nom']; ?></div><div class="text-xs text-gray-400"><?php echo $_SESSION['user']['region']; ?></div></div>
        </div>
      </div>
      <a href="eleveur.php" class="sidebar-link" aria-current="page">📊 Tableau de bord</a>
      <a href="eleveur-canards.php" class="sidebar-link">🦆 Mes canards</a>
      <a href="eleveur-commandes.php" class="sidebar-link">🛍️ Commandes reçues</a>
      <div class="mt-auto pt-4"><a href="../../features/auth/deconnexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>
    <main class="flex-1 p-6 overflow-auto">
      <h1 class="text-2xl font-bold mb-5 font-playfair">Tableau de bord</h1>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card text-center"><div class="text-2xl font-bold text-green-main"><?php echo $total_canards; ?></div><div class="text-xs text-gray-500 mt-1">Canards en élevage</div></div>
        <div class="stat-card text-center"><div class="text-2xl font-bold text-green-main"><?php echo $total_en_vente; ?></div><div class="text-xs text-gray-500 mt-1">En vente</div></div>
        <div class="stat-card text-center"><div class="text-2xl font-bold text-green-main"><?php echo $total_commandes; ?></div><div class="text-xs text-gray-500 mt-1">Commandes en attentes</div></div>
        <div class="stat-card text-center"><div class="text-2xl font-bold text-green-main"><?php echo number_format($total_revenus, 0, ',', ' '); ?></div><div class="text-xs text-gray-500 mt-1">Revenus (Ar)</div></div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-1 gap-5">
        <div class="card p-5">
          <h2 class="font-bold mb-3">Stocks par race</h2>
          <div class="space-y-3">
            <?php foreach ($canards as $canard) { ?>
            <div><div class="flex justify-between text-sm mb-1"><span>Canard <?= $canard['race'] ?></span><span class="font-bold"><?= $canard['quantite_stock']- $canard['quantite_reserve'] ?>/<?= $canard['quantite_stock'] ?></span></div><div class="h-2 rounded-full bg-gray-100"><div class="h-2 rounded-full bg-green-main" style="width:<?= ($canard['quantite_stock'] - $canard['quantite_reserve']) / $canard['quantite_stock'] * 100 ?>%"></div></div></div>
            <?php } ?>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
