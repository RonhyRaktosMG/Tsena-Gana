<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: ' . url('features/auth/connexion.php'));
  exit; 
}

require_once '../../core/pdo.php';
require_once '../../core/utils.php';

$stmt = $pdo->prepare('SELECT * FROM produit');
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>




<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produits — TsenaGana</title>
  <link rel="stylesheet" href="<?= url('assets/css/output.css') ?>">
</head>
<body>
  <?php include '../../components/partials/navbar.php'; ?>

  <main class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-3">
      <div>
        <h1 class="text-3xl font-bold font-playfair">Produits disponibles</h1>
        <p class="text-gray-500"><?= count($produits) ?> résultats</p>
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
      foreach ($produits as $produit) {
      ?>
        <div class="card no-underline text-inherit block">
          <div class="h-62 flex items-center justify-center bg-[#f9fbe7]">
            <img src="<?= $produit['image'] ?>" alt="<?= $produit['nom'] ?>" class="h-full w-full object-cover rounded">
          </div>
          <div class="p-4">
            <div class="flex items-center justify-between mb-1">
              <h2 class="font-bold"><?= $produit['nom'] ?></h2>
              <span class="badge badge-green"><?= $produit['stock'] ?> <?= $produit['unite'] ?></span>
            </div>
            <p class="text-sm text-gray-500 mb-2"><?= $produit['type'] ?></p>
            <div class="flex items-center justify-between">
              <span class="font-bold text-green-main"><?= $produit['prix_unitaire'] ?> Ar/<?= $produit['unite'] ?></span>
              <button type="button" class="btn-primary text-sm btn-add-panier" data-type="produit" data-id="<?= $produit['id'] ?>" data-label="<?= htmlspecialchars($produit['nom'], ENT_QUOTES) ?>">+ Panier</button>
            </div>
          </div>
        </div>
      
      <?php
      }
      ?>
      


    </div>
  </main>

  <?php include '../../components/partials/footer.php'; ?>
  
  <script>
    const baseUrl = '<?= url("") ?>';
    document.querySelectorAll('.btn-add-panier').forEach(btn => {
      btn.addEventListener('click', function() {
        const articleId = this.dataset.id;
        const articleLabel = this.dataset.label;
        const typeArticle = this.dataset.type;

        const formData = new FormData();
        formData.append('type_article', typeArticle);
        formData.append('article_id', articleId);
        formData.append('quantite', 1);

        fetch(baseUrl + 'pages/commande/ajouter_panier.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('✓ ' + articleLabel + ' ajouté au panier');
          } else {
            alert('✗ Erreur : ' + data.message);
          }
        })
        .catch(error => console.error('Erreur:', error));
      });
    });
  </script>
  
</body>
</html>
