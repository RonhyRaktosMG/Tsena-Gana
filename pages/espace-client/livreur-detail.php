<?php
session_start();
require_once '../../core/pdo.php';
require_once '../../core/utils.php';

$sql = "
SELECT 
    l.numero,
    l.statut,

    -- CLIENT (arrivée)
    client.nom AS client_nom,
    client.adresse AS client_adresse,
    client.telephone AS client_telephone,

    -- MARCHAND (départ)
    marchand.nom AS marchand_nom,
    marchand.adresse AS marchand_adresse,
    marchand.telephone AS marchand_telephone,

    -- COLIS
    dc.quantite_a_livrer AS quantite,

    CASE 
        WHEN lc.type_article = 'canard' THEN lc_canard.race
        WHEN lc.type_article = 'produit' THEN p.nom
    END AS nom_article

FROM livraison l

JOIN utilisateur client 
    ON l.client_id = client.id

JOIN utilisateur marchand 
    ON l.marchand_id = marchand.id

JOIN distribution_commande dc 
    ON l.distribution_id = dc.id

JOIN ligne_commande lc 
    ON dc.ligne_commande_id = lc.id

-- jointure conditionnelle
LEFT JOIN lot_canard lc_canard 
    ON lc.type_article = 'canard' AND lc.article_id = lc_canard.id

LEFT JOIN produit p 
    ON lc.type_article = 'produit' AND lc.article_id = p.id

WHERE l.numero = :livraison_numero
";

$numero_livraison = $_GET['numero'] ?? null;

$stmt = $pdo->prepare($sql);
$stmt->execute([':livraison_numero' => $numero_livraison]);
$livraison_detail = $stmt->fetch(PDO::FETCH_ASSOC);


?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détail livraison — Livreur</title>
  <link rel="stylesheet" href="../../assets/css/output.css">
</head>
<body>
<?php
  include '../../components/partials/navbar.php';
?>
  <div class="flex min-h-[calc(100vh-80px)]">
    <aside class="w-56 bg-white shadow-sm p-4 flex-col gap-1 hidden md:flex shrink-0 border-r border-gray-100">
      <div class="mb-4">
        <div class="font-bold text-sm text-gray-400 uppercase tracking-wide mb-2">Livreur</div>
        <div class="flex items-center gap-2 mb-3">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold bg-green-main"><?= substr($_SESSION['user']['nom'], 0, 2) ?></div>
          <div><div class="text-sm font-semibold"><?php echo htmlspecialchars($_SESSION['user']['nom']); ?></div><div class="text-xs text-gray-400"><?= $_SESSION['user']['region'] ?></div></div>
        </div>
      </div>
      <a href="livreur.php" class="sidebar-link">🚚 Mes livraisons</a>
      <div class="mt-auto pt-4"><a href="../../features/auth/deconnexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>
    <main class="flex-1 p-6 overflow-auto">
      <a href="livreur.php" class="text-sm mb-4 inline-flex items-center gap-1 text-green-main no-underline">← Retour aux livraisons</a>
      <h1 class="text-2xl font-bold mb-5 font-playfair">Livraison #<?php echo htmlspecialchars($livraison_detail['numero']); ?></h1>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="card p-5">
          <h2 class="font-bold mb-3">Informations de départ</h2>
          <div class="space-y-2 text-sm">
            <div><span class="text-gray-500">Nom :</span> <?php echo htmlspecialchars($livraison_detail['marchand_nom']); ?></div>
            <div><span class="text-gray-500">Adresse :</span> <?php echo htmlspecialchars($livraison_detail['marchand_adresse']); ?></div>
            <div><span class="text-gray-500">Téléphone :</span> <?php echo htmlspecialchars($livraison_detail['marchand_telephone']); ?></div>
          </div>
        </div>
        <div class="card p-5">
          <h2 class="font-bold mb-3">Informations du destinataire</h2>
          <div class="space-y-2 text-sm">
            <div><span class="text-gray-500">Nom :</span> <?php echo htmlspecialchars($livraison_detail['client_nom']); ?></div>
            <div><span class="text-gray-500">Adresse :</span> <?php echo htmlspecialchars($livraison_detail['client_adresse']); ?></div>
            <div><span class="text-gray-500">Téléphone :</span> <?php echo htmlspecialchars($livraison_detail['client_telephone']); ?></div>
          </div>
        </div>
        <div class="card p-5 col-span-1 md:col-span-2">
          <h2 class="font-bold mb-3">Colis à livrer</h2>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
              <span><?php echo htmlspecialchars($livraison_detail['nom_article']); ?> × <?php echo htmlspecialchars($livraison_detail['quantite']); ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="flex gap-3 mt-5 flex-wrap">
        <span class="btn-primary py-3 px-8 opacity-90">🗺️ Lancer la navigation</span>
        <a href="livreur.php" class="btn-green-light py-3 px-8 text-center">✓ Marquer livrée</a>
      </div>
    </main>
  </div>
</body>
</html>
