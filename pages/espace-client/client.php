
<?php

#PDO
require_once '../../core/pdo.php';

$user_id = 4;

$sql = "
SELECT 
    c.id,
    c.date_commande,
    c.statut,
    c.numero,
    c.statut,

    SUM(
        lc.quantite * 
        CASE 
            WHEN lc.type_article = 'produit' THEN p.prix_unitaire
            WHEN lc.type_article = 'canard' THEN lc2.prix_unitaire
        END
    ) AS total

FROM commande c

JOIN ligne_commande lc ON lc.commande_id = c.id

LEFT JOIN produit p 
    ON lc.article_id = p.id AND lc.type_article = 'produit'

LEFT JOIN lot_canard lc2 
    ON lc.article_id = lc2.id AND lc.type_article = 'canard'

WHERE c.client_id = :user_id

GROUP BY c.id
ORDER BY c.date_commande DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);

$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);



function getArticles($pdo, $commande_id) {
    $sql = "
    SELECT 
        lc.type_article,
        lc.quantite,

        -- produit
        p.nom,
        p.prix_unitaire AS prix_produit,

        -- canard
        lc2.race,
        lc2.prix_unitaire AS prix_canard

    FROM ligne_commande lc

    LEFT JOIN produit p 
        ON lc.article_id = p.id AND lc.type_article = 'produit'

    LEFT JOIN lot_canard lc2 
        ON lc.article_id = lc2.id AND lc.type_article = 'canard'

    WHERE lc.commande_id = :id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $commande_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>



<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon espace client — TsenaGana</title>
  <link rel="stylesheet" href="../../assets/css/output.css">
</head>
<body>
  <?php include '../../components/partials/navbar.php'; ?>

  <div class="flex min-h-[calc(100vh-80px)]">
    <aside class="w-56 bg-white shadow-sm p-4 flex-col gap-1 hidden md:flex shrink-0 border-r border-gray-100">
      <div class="mb-4">
        <div class="font-bold text-sm text-gray-400 uppercase tracking-wide mb-2">Client</div>
        <div class="flex items-center gap-2 mb-3">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold bg-green-main">JR</div>
          <div><div class="text-sm font-semibold">Jean Rakoto</div><div class="text-xs text-gray-400">Client</div></div>
        </div>
      </div>
      <a href="espace-client.html" class="sidebar-link" aria-current="page">📦 Mes commandes</a>
      <div class="mt-auto pt-4"><a href="connexion.html" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>
    <main class="flex-1 p-6 overflow-auto">
      <h1 class="text-2xl font-bold mb-5 font-playfair">Mes commandes</h1>
      <div class="space-y-3">
        <?php foreach ($commandes as $commande) { 
            $articles = getArticles($pdo, $commande['id']);
            $badge = '';
            switch ($commande['statut']) {
                case 'en_attente': $badge = 'badge-orange'; break;
                case 'en_cours': $badge = 'badge-blue'; break;
                case 'livree': $badge = 'badge-green'; break;
                case 'annulee': $badge = 'badge-red'; break;
                default: $badge = 'badge-gray';
            }
        ?>
        <div class="card p-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <div class="font-bold">#<?php echo $commande['numero']; ?></div>
            <div class="text-sm text-gray-500">
              <ul>
                <?php foreach ($articles as $art) { ?>
                    <li>
                        <?php 
                            if ($art['type_article'] === 'produit') {
                                echo $art['quantite'] . ' × ' . $art['nom'];
                            } else {
                                echo $art['quantite'] . ' × Canard ' . $art['race'];
                            }
                        ?>
                    </li>
                <?php } ?>
              </ul>
            </div>
            <div class="text-xs text-gray-400 mt-2"><?php echo $commande['date_commande']; ?></div>
          </div>
          <div class="flex items-center gap-3 flex-wrap">
            <span class="badge <?= $badge ?>"> <?= $commande['statut'] ?> </span>
            <span class="font-bold text-green-main"> <?= number_format($commande['total'], 0, ',', ' ') ?> Ar</span>
            <a href="commande-detail.html" class="btn-sec text-xs py-1.5 px-3">Détail</a>
          </div>
        </div>
        <?php } ?>
      </div>
    </main>
  </div>
</body>
</html>
