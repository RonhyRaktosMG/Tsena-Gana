<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Commandes — Vendeur</title>
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
          <div><div class="text-sm font-semibold">VendeurPro Ramy</div><div class="text-xs text-gray-400">Antananarivo</div></div>
        </div>
      </div>
      <a href="vendeur.php" class="sidebar-link">📊 Tableau de bord</a>
      <a href="vendeur-produits.php" class="sidebar-link">🌾 Mes produits</a>
      <a href="vendeur-commandes.php" class="sidebar-link" aria-current="page">🛍️ Commandes reçues</a>
      <div class="mt-auto pt-4"><a href="connexion.php" class="sidebar-link text-red-400">↩ Déconnexion</a></div>
    </aside>
    <main class="flex-1 p-6 overflow-auto">
      <h1 class="text-2xl font-bold mb-5 font-playfair">Commandes reçues</h1>
      <div class="space-y-3">
        <div class="card p-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <div class="font-bold">#CMD-2026-0047</div>
            <div class="text-sm text-gray-500">10 kg Maïs concassé · Client: Jean Rakoto</div>
          </div>
          <div class="flex items-center gap-2 flex-wrap">
            <span class="badge badge-orange">En attente</span>
            <span class="font-bold text-green-main">12 000 Ar</span>
            <span class="btn-green-light text-xs py-1.5 opacity-90">Accepter</span>
          </div>
        </div>
        <div class="card p-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <div class="font-bold">#CMD-2026-0045</div>
            <div class="text-sm text-gray-500">20 kg Granulés · Client: Éleveur Rabe</div>
          </div>
          <div class="flex items-center gap-2 flex-wrap">
            <span class="badge badge-blue">En préparation</span>
            <span class="font-bold text-green-main">50 000 Ar</span>
            <span class="btn-green-light text-xs py-1.5 opacity-90">Marquer prête</span>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
