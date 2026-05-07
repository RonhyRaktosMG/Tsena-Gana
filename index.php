<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TsenaGana — Marketplace Collaborative</title>
  <link rel="stylesheet" href="assets/css/output.css">
</head>
<body>
  <?php include 'components/partials/navbar.php'; ?>

  <main>
    <section class="hero-pattern relative overflow-hidden px-4 py-12 md:py-20">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.24),_transparent_45%),radial-gradient(circle_at_bottom_right,_rgba(255,255,255,0.12),_transparent_40%)]"></div>
      <div class="relative max-w-7xl mx-auto">
        <div class="grid items-center gap-10 rounded-[2rem] border border-white/20 bg-white/10 p-6 shadow-[0_24px_80px_rgba(0,0,0,0.18)] backdrop-blur-xl md:grid-cols-2 md:p-10 lg:p-14">
          <div class="order-2 md:order-1">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/15 px-4 py-2 text-sm font-semibold tracking-wide text-white/90 shadow-lg">
              <span class="h-2.5 w-2.5 rounded-full bg-[var(--green-sec)]"></span>
              Tsena Gana
            </span>
            <h1 class="mt-6 max-w-xl text-4xl font-extrabold leading-tight text-white font-playfair sm:text-5xl ">
              La marketplace élégante des éleveurs de canards
            </h1>
            <p class="mt-5 max-w-xl text-base leading-8 text-white/85 sm:text-lg">
              Que vous soyez éleveur, vendeur ou acheteur, TsenaGana vous offre une expérience fluide et agréable pour acheter et vendre des canards et des produits connexes à Madagascar.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
              <a href="<?= url('features/auth/inscription.php') ?>" class="inline-flex items-center justify-center rounded-full bg-white px-8 py-4 text-base font-semibold text-green-main no-underline shadow-[0_12px_30px_rgba(255,255,255,0.28)] transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_18px_36px_rgba(255,255,255,0.35)]">
                Rejoindre la communauté
              </a>
            </div>
          </div>

          <div class="order-1 md:order-2">
            <div class="relative mx-auto max-w-xl">
              <div class="absolute -inset-4 rounded-[2rem] bg-[linear-gradient(135deg,rgba(107,191,102,0.35),rgba(255,255,255,0.08))] blur-2xl"></div>
              <div class="relative overflow-hidden rounded-[2rem] border border-white/20 bg-white/15 p-4 shadow-[0_24px_60px_rgba(0,0,0,0.22)]">
                <img
                  src="assets/images/happy-duck-drinking.jpg"
                  alt="Canard heureux buvant de l'eau"
                  class="h-[320px] w-full rounded-[1.5rem] object-cover sm:h-[350px] lg:h-[500px]"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

    <?php include 'components/partials/footer.php'; ?>
</body>
</html>
