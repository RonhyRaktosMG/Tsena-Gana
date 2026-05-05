<?php
require_once 'core/pdo.php';

session_start();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['motdepasse'];

    $stmt = $pdo->prepare('SELECT * FROM utilisateur WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['motdepasse'])) {
        $_SESSION['user'] = $user;
        echo 'Connexion réussie !';

        Header('Location: index.php');
        
        
        exit();
    } else {
        
        echo 'Email ou mot de passe incorrect.';
    }
}


?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — TsenaGana</title>
  <link rel="stylesheet" href="assets/css/output.css">
</head>
<body>
  <main class="min-h-screen px-4 py-12 md:py-16">
    <div class="mx-auto grid max-w-6xl items-stretch gap-8 lg:grid-cols-2">
      <div class="relative overflow-hidden rounded-[2rem] border border-white/30 bg-[linear-gradient(160deg,rgba(26,26,26,0.96),rgba(77,119,78,0.92))] p-6 text-white shadow-[0_24px_80px_rgba(0,0,0,0.18)] md:p-8">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.18),transparent_35%),radial-gradient(circle_at_bottom_left,rgba(107,191,102,0.18),transparent_35%)]"></div>
        <div class="relative h-full">
          <h1 class="mt-6 max-w-md text-4xl font-extrabold leading-tight font-playfair sm:text-5xl">Ravi de vous revoir sur TsenaGana</h1>
          <div class="mt-8 overflow-hidden rounded-[1.75rem] border border-white/20 bg-white/10 p-3 shadow-[0_18px_40px_rgba(0,0,0,0.18)] backdrop-blur-md">
            <img src="assets/images/happy-duck-grouping.jpg" alt="Canards heureux regroupés" class="h-[300px] w-full rounded-[1.35rem] object-cover" />
          </div>
        </div>
      </div>

      <div class="card flex flex-col justify-center p-8 md:p-10 fade-in">
        <div class="text-center mb-6">
          <img src="assets/images/logo.png" alt="" width="64" height="64" class="mx-auto mb-2 h-16 w-16 object-contain" />
          <h2 class="text-2xl font-bold font-playfair">Connexion</h2>
          <p class="text-gray-500 text-sm mt-1">Bienvenue sur TsenaGana</p>
        </div>
        <form class="space-y-4" action="" method="POST">
          <div>
            <label class="block text-sm font-medium mb-1" for="email">Email</label>
            <input id="email" name="email" type="email" class="input-field" placeholder="vous@example.com" autocomplete="email">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" for="motdepasse">Mot de passe</label>
            <input id="motdepasse" name="motdepasse" type="motdepasse" class="input-field" placeholder="••••••••" autocomplete="current-motdepasse">
          </div>
          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2"><input type="checkbox" name="remember"> Se souvenir</label>
            <span class="text-green-main cursor-default">Mot de passe oublié ?</span>
          </div>
          <button type="submit" class="btn-primary w-full py-3">Se connecter</button>
          <p class="text-center text-sm text-gray-500">
            Pas encore de compte ? <a href="inscription.php" class="font-semibold text-green-main no-underline">S'inscrire</a>
          </p>
        </form>
      </div>
    </div>
  </main>

</body>
</html>
