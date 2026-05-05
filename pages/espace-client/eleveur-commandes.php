<?php

require_once '../../core/pdo.php';

$vendeur_id = 2;

$sql = "
SELECT 
    c.numero AS numero_commande,
    c.date_commande,
    u.nom AS nom_acheteur,

    MIN(dc.statut) AS statut,

    SUM(dc.quantite_a_livrer * lc2.prix_unitaire) AS total

FROM distribution_commande dc

JOIN ligne_commande lc ON lc.id = dc.ligne_commande_id
JOIN lot_canard lc2 ON lc.article_id = lc2.id
JOIN commande c ON c.id = lc.commande_id
JOIN utilisateur u ON u.id = c.client_id

WHERE 
    dc.vendeur_id = :vendeur_id
    AND lc.type_article = 'canard'

GROUP BY c.id
ORDER BY c.date_commande DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['vendeur_id' => $vendeur_id]);

$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);


function getCanards($pdo, $commande_id, $vendeur_id) {

    $sql = "
    SELECT 
        lc2.race,
        lc2.prix_unitaire,
        dc.quantite_a_livrer

    FROM distribution_commande dc

    JOIN ligne_commande lc ON lc.id = dc.ligne_commande_id
    JOIN lot_canard lc2 ON lc.article_id = lc2.id

    WHERE 
        dc.vendeur_id = :vendeur_id
        AND lc.type_article = 'canard'
        AND lc.commande_id = :commande_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'vendeur_id' => $vendeur_id,
        'commande_id' => $commande_id
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Commandes — Éleveur</title>
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
      <a href="eleveur-commandes.php" class="sidebar-link" aria-current="page">🛍️ Commandes reçues</a>
      <div class="mt-auto pt-4"><a href="connexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>
    <main class="flex-1 p-6 overflow-auto">
      <h1 class="text-2xl font-bold mb-5 font-playfair">Commandes reçues</h1>
      <div class="space-y-3">
        <?php foreach ($commandes as $commande) { 
          $canards = getCanards($pdo, $commande['numero_commande'], $vendeur_id);
          $action_label = "";
          $action_class = "";
          if ($commande['statut'] == 'en_attente') {
            $action_label = "Préparer";
            $action_class = "btn-green-light";
          } else if ($commande['statut'] == 'en_preparation') {
            $action_label = "Marquer prête";
            $action_class = "btn-blue-light";
          } else {
            $action_label = "Aucune action";
            $action_class = "btn-gray-light cursor-not-allowed";
          }
          ?>
        <div class="card p-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <div class="font-bold"><?= $commande['numero_commande'] ?></div>
            <div class="text-sm text-gray-500">
              <?php foreach ($canards as $canard) { 
                echo $canard['quantite_a_livrer'] . " × " . $canard['race'] . " · ";
              } ?>  
            </div>
            <div class="text-sm text-green-300">Client: <?= $commande['nom_acheteur'] ?></div>
            <div class="text-xs text-gray-400"><?= $commande['date_commande'] ?></div>
          </div>
          <div class="flex items-center gap-2 flex-wrap">
            <span class="badge badge-orange"><?php echo $commande['statut']; ?></span>
            <span class="font-bold text-green-main"><?= $commande['total'] ?> Ar</span>
            <span class="<?= $action_class ?> text-xs py-1.5 px-3 opacity-90"><?= $action_label ?></span>
          </div>
        </div>
        <?php } ?>
        <!-- <div class="card p-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <div class="font-bold">#CMD-2026-0044</div>
            <div class="text-sm text-gray-500">5 × Canard Pékin · Client: Marie Rabe</div>
            <div class="text-xs text-gray-400">12 Jan 2026 · 14:15</div>
          </div>
          <div class="flex items-center gap-2 flex-wrap">
            <span class="badge badge-blue">En préparation</span>
            <span class="font-bold text-green-main">175 000 Ar</span>
            <span class="btn-green-light text-xs py-1.5 px-3 opacity-90">Marquer prête</span>
          </div>
        </div> -->
      </div>
    </main>
  </div>
</body>
</html>
