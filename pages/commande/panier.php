<?php 

session_start();

require_once __DIR__ . '/../../core/utils.php';
require_once __DIR__ . '/../../core/pdo.php';

if (!isset($_SESSION['panier'])) {
  $_SESSION['panier'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $panierKey = $_POST['panier_key'] ?? null;
  $action = $_POST['action'] ?? null;

  if ($panierKey && isset($_SESSION['panier'][$panierKey])) {
    if ($action === 'decrease') {
      $_SESSION['panier'][$panierKey]['quantite']--;
      if ($_SESSION['panier'][$panierKey]['quantite'] <= 0) {
        unset($_SESSION['panier'][$panierKey]);
      }
    } elseif ($action === 'increase') {
      $_SESSION['panier'][$panierKey]['quantite']++;
    } elseif ($action === 'remove') {
      unset($_SESSION['panier'][$panierKey]);
    }
  }

  header('Location: panier.php');
  exit;
}

$panier = $_SESSION['panier'];
$total = 0;
foreach ($panier as $article) {
  $total += (float)$article['prix_unitaire'] * (int)$article['quantite'];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panier — TsenaGana</title>
  <link rel="stylesheet" href="<?= url('assets/css/output.css') ?>">
</head>
<body>
  <?php include '../../components/partials/navbar.php'; ?>

  <main class="max-w-5xl mx-auto px-4 py-8 fade-in">
    <h1 class="text-3xl font-bold mb-6 font-playfair">🛒 Mon panier</h1>
    
    <?php if (empty($panier)): ?>
      <div class="card p-8 text-center">
        <p class="text-gray-500 text-lg mb-4">Votre panier est vide</p>
        <a href="<?= url('pages/catalogue/canards.php') ?>" class="btn-primary inline-block">Voir les canards disponibles →</a>
      </div>
    <?php else: ?>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="md:col-span-2 space-y-3">
        
        <?php foreach ($panier as $panierKey => $article): ?>
        <div class="card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div class="flex-1">
            <div class="font-bold"><?= htmlspecialchars($article['nom']) ?></div>
            <div class="text-sm text-gray-500">
              <?= number_format((float)$article['prix_unitaire'], 0, ',', ' ') ?> Ar / <?= htmlspecialchars($article['unite'] ?? 'unité') ?>
            </div>
            <div class="text-xs text-gray-400 mt-1">Type : <?= htmlspecialchars($article['type_label'] ?? $article['type_article']) ?></div>
          </div>
          <div class="flex items-center gap-3">
            <form method="POST" style="display: flex; align-items: center; gap: 10px;">
              <input type="hidden" name="panier_key" value="<?= htmlspecialchars($panierKey) ?>">
              <button type="submit" name="action" value="decrease" class="btn-sec text-xs px-2 py-1">−</button>
              <span class="font-bold w-8 text-center"><?= $article['quantite'] ?></span>
              <button type="submit" name="action" value="increase" class="btn-sec text-xs px-2 py-1">+</button>
              <button type="submit" name="action" value="remove" class="btn-sec text-xs px-2 py-1 ml-2">✕</button>
            </form>
            <span class="font-bold text-green-main w-32 text-right"><?= number_format(((float)$article['prix_unitaire']) * ((int)$article['quantite']), 0, ',', ' ') ?> Ar</span>
          </div>
        </div>
        <?php endforeach; ?>
        
      </div>
      <div>
        <div class="card p-5 md:sticky md:top-24">
          <h2 class="font-bold text-lg mb-4">Récapitulatif</h2>
          <div class="space-y-2 text-sm mb-4">
            <?php foreach ($panier as $article): ?>
            <div class="flex justify-between"><span class="text-gray-500"><?= htmlspecialchars($article['nom']) ?> × <?= $article['quantite'] ?></span><span><?= number_format(((float)$article['prix_unitaire']) * ((int)$article['quantite']), 0, ',', ' ') ?> Ar</span></div>
            <?php endforeach; ?>
          </div>
          <div class="border-t pt-3">
            <div class="flex justify-between font-bold text-lg">
              <span>Total</span>
              <span class="text-green-main"><?= number_format($total, 0, ',', ' ') ?> Ar</span>
            </div>
          </div>
          <a href="#" class="btn-primary w-full mt-4 py-3 text-center block">Passer la commande →</a>
          <a href="<?= url('pages/catalogue/canards.php') ?>" class="btn-sec w-full mt-2 text-sm text-center block">Continuer les achats</a>
        </div>
      </div>
    </div>
    
    <?php endif; ?>
  </main>

  
  <?php include '../../components/partials/footer.php'; ?>
</body>
</html>
