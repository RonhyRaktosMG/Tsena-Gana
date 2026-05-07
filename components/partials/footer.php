
<?php

    require_once __DIR__ . '/../../core/utils.php';

?>


<footer class="bg-[var(--black)] text-white py-10 px-4">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
      <div>
        <div class="flex items-center gap-2 mb-3">
          <img src="<?= url('assets/images/logo.png') ?>" alt="" width="32" height="32" class="h-8 w-8 shrink-0 object-contain" />
          <span class="font-bold text-lg font-playfair">TsenaGana</span>
        </div>
        <p class="text-gray-400 text-sm">La plateforme collaborative qui réunit toute la filière canard à Madagascar.</p>
      </div>
      <div>
        <h4 class="font-bold mb-3">Navigation</h4>
        <div class="flex flex-col gap-1 text-gray-400 text-sm">
          <a href="<?= url('/') ?>" class="hover:text-white no-underline text-inherit">Accueil</a>
          <a href="<?= url('pages/catalogue/canards.php') ?>" class="hover:text-white no-underline text-inherit">Canards</a>
          <a href="<?= url('pages/catalogue/produits.php') ?>" class="hover:text-white no-underline text-inherit">Aliments</a>
        </div>
      </div>
      <div>
        <h4 class="font-bold mb-3">Rejoindre</h4>
        <div class="flex flex-col gap-1 text-gray-400 text-sm">
          <a href="<?= url('features/auth/inscription.php') ?>" class="hover:text-white no-underline text-inherit">Créer un compte</a>
          <a href="<?= url('features/auth/connexion.php') ?>" class="hover:text-white no-underline text-inherit">Se connecter</a>

        </div>
      </div>
    </div>
    <div class="max-w-6xl mx-auto mt-8 pt-6 border-t border-gray-700 text-center text-gray-500 text-sm">
      © 2026 TsenaGana — Tous droits réservés
    </div>
  </footer>