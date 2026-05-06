<?php
session_start();

require_once '../../core/pdo.php';
require_once '../../core/utils.php';


$user_id = $_SESSION['user']['id'] ?? null;


$sql = "
SELECT 
    l.numero,
    l.statut,

    client.nom AS client_nom,
    client.adresse AS client_adresse,
    client.telephone AS client_telephone,

    marchand.nom AS marchand_nom,
    marchand.adresse AS marchand_adresse,
    marchand.telephone AS marchand_telephone

FROM livraison l

JOIN utilisateur client 
    ON l.client_id = client.id

JOIN utilisateur marchand 
    ON l.marchand_id = marchand.id

WHERE l.livreur_id = :user_id
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$livraisons = $stmt->fetchAll(PDO::FETCH_ASSOC);


$nombre_livraisons_en_cours = $livraisons ? count(array_filter($livraisons, fn($l) => $l['statut'] === 'en_cours')) : 0;
$nombre_livraison_en_preparation = $livraisons ? count(array_filter($livraisons, fn($l) => $l['statut'] === 'en_preparation')) : 0;
?>



<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Livreur — TsenaGana</title>
  <link rel="stylesheet" href="../../assets/css/output.css">
</head>
<body>
  
  <?php include '../../components/partials/navbar.php'; ?>

  <div class="flex min-h-[calc(100vh-80px)]">
    <aside class="w-56 bg-white shadow-sm p-4 flex-col gap-1 hidden md:flex shrink-0 border-r border-gray-100">
      <div class="mb-4">
        <div class="font-bold text-sm text-gray-400 uppercase tracking-wide mb-2">Livreur</div>
        <div class="flex items-center gap-2 mb-3">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold bg-green-main"><?= substr($_SESSION['user']['nom'], 0, 2) ?></div>
          <div><div class="text-sm font-semibold"><?php echo htmlspecialchars($_SESSION['user']['nom']); ?></div><div class="text-xs text-gray-400"><?= $_SESSION['user']['region'] ?></div></div>
        </div>
      </div>
      <a href="livreur.php" class="sidebar-link" aria-current="page">🚚 Mes livraisons</a>
      <div class="mt-auto pt-4"><a href="../../features/auth/deconnexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>
    <main class="flex-1 p-6 overflow-auto">
      <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
        <h1 class="text-2xl font-bold font-playfair">Mes livraisons</h1>
        <div class="flex gap-2">
          <span class="badge badge-blue px-3 py-1.5"><?php echo $nombre_livraisons_en_cours; ?> en cours</span>
          <span class="badge badge-orange px-3 py-1.5"><?php echo $nombre_livraison_en_preparation; ?> en préparation</span>  
        </div>
      </div>
      <div class="space-y-3">
        <?php foreach ($livraisons as $livraison) { 
        $badge_class = '';
        switch ($livraison['statut']) {
          case 'en_preparation':
            $badge_class = 'badge-orange';
            break;
          case 'en_cours':
            $badge_class = 'badge-blue';
            break;
          case 'livree':
            $badge_class = 'badge-green'; 
            break;
          case 'annulée':
            $badge_class = 'badge-red';
            break;
          default:
            $badge_class = 'badge-gray';
        }
        ?>
        <div class="card p-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span class="font-bold"><?php echo htmlspecialchars($livraison['numero']); ?></span>
                <span class="badge <?php echo $badge_class; ?>"><?= htmlspecialchars($livraison['statut']) ?></span>
              </div>
              <div class="text-sm text-gray-500">
                <span class="font-semibold">Destination (client) :</span>
                <?php echo htmlspecialchars($livraison['client_nom']); ?> ·
                <?php echo htmlspecialchars($livraison['client_telephone']); ?> ·
                <?php echo htmlspecialchars($livraison['client_adresse']); ?>
              </div>
              <div class="text-sm text-gray-500 mt-1">
                <span class="font-semibold">Début (marchand) :</span>
                <?php echo htmlspecialchars($livraison['marchand_nom']); ?> ·
                <?php echo htmlspecialchars($livraison['marchand_telephone']); ?> ·
                <?php echo htmlspecialchars($livraison['marchand_adresse']); ?>
              </div>
            </div>
            <div class="flex gap-2">
              <span class="btn-primary text-sm opacity-90 text-center">Démarrer</span>
              <a href="livreur-detail.php?numero=<?php echo htmlspecialchars($livraison['numero']); ?>" class="btn-sec text-sm text-center">Détail</a>
            </div>
          </div>
        </div>
        <?php } ?>  
        <!-- <div class="card p-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span class="font-bold">#CMD-2026-0042</span>
                <span class="badge badge-blue">En cours</span>
              </div>
              <div class="text-sm text-gray-500">Marie Rabe · Ankadifotsy, Antananarivo</div>
              <div class="text-xs text-gray-400 mt-1">📞 034 98 765 43 · 1 × Canard Mulard</div>
            </div>
            <div class="flex gap-2">
              <span class="btn-green-light text-sm opacity-90 text-center">Marquer livrée</span>
              <a href="livreur-detail.html" class="btn-sec text-sm text-center">Détail</a>
            </div>
          </div>
        </div>
        <div class="card p-4 opacity-60">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span class="font-bold">#CMD-2026-0038</span>
                <span class="badge badge-green">Livrée</span>
              </div>
              <div class="text-sm text-gray-500">Pierre Andry · Ivandry, Antananarivo</div>
              <div class="text-xs text-gray-400 mt-1">5 × Canard Pékin · Livrée le 05/01/2026</div>
            </div>
            <div class="font-bold text-green-main">15 000 Ar</div>
          </div>
        </div> -->
      </div>
    </main>
  </div>
</body>
</html>
