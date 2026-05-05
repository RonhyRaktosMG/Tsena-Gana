<?php
session_start();

#PDO
require_once '../../core/pdo.php';

if (isset($_GET['delete'])) {
  $delId = (int)$_GET['delete'];
  $stmt = $pdo->prepare('DELETE FROM lot_canard WHERE id = ?');
  $stmt->execute([$delId]);
  header('Location: eleveur-canards.php'); exit;
}

$smtmt = $pdo->prepare('SELECT * FROM lot_canard');
$smtmt->execute();
$canards = $smtmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes canards — Éleveur</title>
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
      <a href="eleveur.php" class="sidebar-link">📊 Tableau de bord</a>
      <a href="eleveur-canards.php" class="sidebar-link" aria-current="page">🦆 Mes canards</a>
      <a href="eleveur-commandes.php" class="sidebar-link">🛍️ Commandes reçues</a>
      <div class="mt-auto pt-4"><a href="connexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>
    <main class="flex-1 p-6 overflow-auto">
      <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
        <h1 class="text-2xl font-bold font-playfair">Mes canards</h1>
        <a href="eleveur-formulaire.php" class="btn-primary text-sm">+ Ajouter un canard</a>
      </div>
      <div class="card overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
          <thead class="bg-[#e0f5de]">
            <tr> <th class="p-3 text-left">Image</th> <th class="p-3 text-left">Race</th><th class="p-3 text-left">Âge</th><th class="p-3 text-left">Poids</th><th class="p-3 text-left">Stock</th><th class="p-3 text-left">Prix</th><th class="p-3"></th></tr>
          </thead>
          <tbody>
            <?php foreach ($canards as $canard) { 
               $age_lot = floor((time() - strtotime($canard['date_naissance'])) / (30 * 24 * 60 * 60));
              ?>
            <tr class="border-t border-gray-100 table-row">
              <td><span class="p-3"><img src="<?= htmlspecialchars($canard['image'] ?: '../../assets/img/no-image.png') ?>" alt="" class="w-16 h-16 object-cover rounded mx-auto"></span></td>
              <td class="p-3 font-medium"><?= htmlspecialchars($canard['race']) ?></td>
              <td class="p-3"><?= $age_lot ?> mois</td>
              <td class="p-3"><?= htmlspecialchars($canard['poids_moyen']) ?></td>
              <td class="p-3"><?= htmlspecialchars($canard['quantite_stock']) ?></td>
              <td class="p-3"><?= htmlspecialchars($canard['prix_unitaire']) ?></td>
              <td class="p-3">
                <a href="eleveur-formulaire.php?id=<?= (int)$canard['id'] ?>" class="btn-sec text-xs py-1 px-2 inline-block">Modifier</a>
                <a href="?delete=<?= (int)$canard['id'] ?>" onclick="return confirm('Supprimer ce lot ?')" class="text-xs text-red-500 ml-2">Supprimer</a>
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
