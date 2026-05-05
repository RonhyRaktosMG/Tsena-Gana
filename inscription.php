<?php

require_once 'core/pdo.php';

$stmt = $pdo->prepare('SELECT id, nom FROM region');
$stmt->execute();
$regions = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $role = $_POST['role'];
    $id_region = $_POST['id_region'];

    try {

        $stmt = $pdo->prepare('INSERT INTO utilisateur (email, nom, motdepasse, adresse, telephone, id_region, role) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$email, $nom, $motdepasse, $adresse, $telephone, $id_region, $role]);
    } catch (Throwable $th) {
        die('Erreur lors de l\'inscription : ' . $th->getMessage());
    }

    echo 'Inscription réussie !';
}
?>

<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription — TsenaGana</title>
  <link rel="stylesheet" href="assets/css/output.css">
</head>
<body>
  <main class="min-h-screen px-4 py-12 md:py-16">
    <div class="mx-auto grid max-w-6xl items-stretch gap-8 lg:grid-cols-2">
      <div class="relative overflow-hidden rounded-[2rem] border border-white/30 bg-[linear-gradient(160deg,rgba(77,119,78,0.96),rgba(107,191,102,0.84))] p-6 text-white shadow-[0_24px_80px_rgba(0,0,0,0.18)] md:p-8">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.24),transparent_35%),radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.14),transparent_35%)]"></div>
        <div class="relative h-full">
          <h1 class="mt-6 max-w-md text-4xl font-extrabold leading-tight font-playfair sm:text-5xl">Créez votre compte TsenaGana</h1>
          <div class="mt-8 overflow-hidden rounded-[1.75rem] border border-white/20 bg-white/10 p-3 shadow-[0_18px_40px_rgba(0,0,0,0.18)] backdrop-blur-md">
            <img src="assets/images/happy-duck-grouping.jpg" alt="Canards heureux regroupés" class="h-[300px] w-full rounded-[1.35rem] object-cover" />
          </div>
        </div>
      </div>

      <div class="card flex flex-col justify-center p-8 md:p-10 fade-in">
        <div class="text-center mb-6">
          <img src="assets/images/logo.png" alt="" width="64" height="64" class="mx-auto mb-2 h-16 w-16 object-contain" />
          <h2 class="text-2xl font-bold font-playfair">Créer un compte</h2>
        </div>
        <form class="space-y-4" action="" method="post">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium mb-1" for="nom">Nom</label>
              <input id="nom" name="nom" class="input-field" placeholder="Rakoto">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" for="email">Email</label>
            <input id="email" name="email" type="email" class="input-field" placeholder="vous@example.com">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" for="telephone">Téléphone</label>
            <input id="telephone" name="telephone" class="input-field" placeholder="+261 34 ...">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" for="adresse">Adresse</label>
            <input id="adresse" name="adresse" class="input-field" placeholder="Lot II AB 123 ...">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" for="role">Votre rôle</label>
            <select id="role" name="role" class="input-field" required>
              <?php
                $roles = ['client', 'vendeur', 'eleveur', 'livreur'];
                foreach ($roles as $role) {
                    echo "<option value=\"$role\">$role</option>";
                }
              ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" for="id_region">Votre Région</label>
            <select id="id_region" name="id_region" class="input-field" required>
              <?php
                foreach ($regions as $region) {
                    echo "<option value=\"{$region['id']}\">{$region['nom']}</option>";
                }
              ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" for="motdepasse">Mot de passe</label>
            <input id="motdepasse" name="motdepasse" type="password" class="input-field" placeholder="••••••••">
          </div>

          <button type="submit" class="btn-primary w-full py-3">S'inscrire</button>

          <p class="text-center text-sm text-gray-500">
            Déjà un compte ? <a href="connexion.php" class="font-semibold text-green-main no-underline">Se connecter</a>
          </p>
        </form>
      </div>
    </div>
  </main>

</body>
</html>
