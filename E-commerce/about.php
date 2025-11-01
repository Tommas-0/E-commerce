
<?php
session_start();
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>À propos | Gainy</title>
  <link rel="icon" href="images/logo.ico" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {}
      }
    }
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .theme-transition-ready {
      transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
    
    .hero-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card-hover {
      transition: all 0.3s ease;
    }
    
    .card-hover:hover {
      transform: translateY(-10px);
    }
    
    .feature-icon {
      transition: all 0.3s ease;
    }
    
    .feature-card:hover .feature-icon {
      transform: scale(1.1);
    }
    
    .animate-float {
      animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
    }
    
    .animate-pulse-slow {
      animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .timeline-item {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }
    
    .timeline-item.visible {
      opacity: 1;
      transform: translateY(0);
    }
    
    .parallax-bg {
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
  
  <script>
    function initTheme() {
      const savedTheme = localStorage.getItem('theme') || 'light';
      document.documentElement.classList.toggle('dark', savedTheme === 'dark');

      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('theme')) {
          document.documentElement.classList.toggle('dark', e.matches);
        }
      });
    }

    function toggleTheme() {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    // Animation au scroll
    function observeElements() {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
          }
        });
      }, { threshold: 0.1 });

      document.querySelectorAll('.timeline-item, .card-hover').forEach(el => {
        observer.observe(el);
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      initTheme();
      observeElements();
    });
  </script>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white theme-transition-ready">

  <!-- Header -->
  <header class="theme-transition-ready bg-white dark:bg-gray-900 shadow-lg py-4 w-full">
    <div class="mx-auto px-4">
      <div class="flex justify-between items-center">
        <div class="flex items-center space-x-8">
          <a href="index.php">
            <img src="images/g.png" alt="Logo Gainy" class="h-12 hover:scale-105 transition-transform">
          </a>
          <nav class="hidden md:flex space-x-6">
            <a href="https://curci.sio-chopin.fr" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer rounded">Portfolio</a>
            <a href="index.php" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer rounded">Accueil</a>
            <a href="produits.php" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer rounded">Produits</a>
          </nav>
        </div>
        
        <div class="hidden md:flex space-x-4 items-center">
          <?php if(isset($_SESSION['CLT_ID'])): 
            $avatar = !empty($_SESSION['CLT_AVATAR']) ? $_SESSION['CLT_AVATAR'] : '/E-commerce/uploads/default_avatar.jpg';
          ?>
            <a href="profil.php"><img src="<?= htmlspecialchars($avatar) ?>" class="w-8 h-8 rounded-full object-cover border-2 hover:scale-110 transition-transform" alt="Avatar"></a>
            <a href="/E-commerce/deconnexion.php" class="theme-transition-ready px-4 py-2 rounded-lg border border-blue-500 text-blue-500 hover:bg-blue-50 dark:hover:bg-gray-800">Déconnexion</a>
            <a href="/E-commerce/panier.php" class="theme-transition-ready px-4 py-2 rounded-lg border border-blue-500 text-blue-500 hover:bg-blue-50 dark:hover:bg-gray-800">Panier</a>
          <?php else: ?>
            <a href="/E-commerce/login.php" class="theme-transition-ready px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white">Connexion</a>
            <a href="/E-commerce/register.php" class="theme-transition-ready px-4 py-2 rounded-lg border border-blue-500 text-blue-500 hover:bg-blue-50 dark:hover:bg-gray-800">Inscription</a>
          <?php endif; ?>
        </div>

        <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500">
          <i class="fas fa-bars text-xl"></i>
        </button>
      </div>

      <div id="mobile-menu" class="md:hidden hidden px-4 mt-4 space-y-2">
        <a href="https://curci.sio-chopin.fr" class="block hover:text-blue-500">Portfolio</a>
        <a href="index.php" class="block hover:text-blue-500">Accueil</a>
        <a href="produits.php" class="block hover:text-blue-500">Produits</a>
        <?php if(isset($_SESSION['CLT_ID'])): ?>
          <a href="profil.php" class="block"><img src="<?= htmlspecialchars($avatar) ?>" class="w-8 h-8 rounded-full object-cover border-2" alt="Avatar"></a>
          <a href="/E-commerce/deconnexion.php" class="block text-blue-500">Déconnexion</a>
          <a href="/E-commerce/panier.php" class="block text-blue-500">Panier</a>
        <?php else: ?>
          <a href="/E-commerce/login.php" class="block text-blue-500">Connexion</a>
          <a href="/E-commerce/register.php" class="block text-blue-500">Inscription</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero-gradient relative overflow-hidden py-20 md:py-32">
    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    <div class="relative max-w-6xl mx-auto px-4 text-center text-white">
      <div class="animate-float">
        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
          Découvrez <span class="text-yellow-300">Gainy</span>
        </h1>
        <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
          Une entreprise passionnée qui révolutionne votre expérience sportive depuis 2020
        </p>
      </div>
    </div>
    
    <!-- Éléments décoratifs -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full animate-pulse-slow"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 bg-white bg-opacity-10 rounded-full animate-pulse-slow"></div>
  </section>

  <!-- Section principale À propos -->
  <section class="py-20 px-4">
    <div class="max-w-6xl mx-auto">
      <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
        <div class="timeline-item">
          <img src="images/h.png" alt="À propos de Gainy" class="w-full max-w-md mx-auto rounded-2xl shadow-2xl hover:shadow-3xl transition-shadow">
        </div>
        <div class="timeline-item space-y-6">
          <h2 class="text-3xl md:text-4xl font-bold theme-transition-ready text-gray-900 dark:text-white">
            Notre Mission
          </h2>
          <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600"></div>
          <p class="text-lg leading-relaxed theme-transition-ready text-gray-600 dark:text-gray-300">
            Gainy est une entreprise engagée dans la vente en ligne de produits sportifs de qualité. 
            Nous mettons un point d'honneur à proposer des articles innovants à des prix compétitifs, 
            pour tous les passionnés de sport.
          </p>
          <p class="text-lg leading-relaxed theme-transition-ready text-gray-600 dark:text-gray-300">
            Notre vision est de démocratiser l'accès aux équipements sportifs de haute qualité, 
            tout en maintenant un service client exceptionnel et un engagement écologique fort.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Timeline - Notre Histoire -->
  <section class="py-20 px-4 theme-transition-ready bg-gray-50 dark:bg-gray-800">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">
          Notre Histoire
        </h2>
        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto"></div>
      </div>

        <!-- Étape 2020 -->
        <div class="timeline-item relative mb-16">
          <div class="flex items-center justify-center">
            <div class="theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-lg max-w-md mx-4 md:mr-8 md:ml-0">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4">
                  <i class="fas fa-rocket text-blue-500 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white">2020</h3>
              </div>
              <p class="theme-transition-ready text-gray-600 dark:text-gray-300">
                <strong>Création de l'entreprise</strong> - Naissance de Gainy avec une vision claire : 
                rendre le sport accessible à tous avec des produits de qualité.
              </p>
            </div>
          </div>
        </div>

        <!-- Étape 2022 -->
        <div class="timeline-item relative">
          <div class="flex items-center justify-center">
            <div class="theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-lg max-w-md mx-4 md:ml-8 md:mr-0">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-4">
                  <i class="fas fa-store text-green-500 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white">2022</h3>
              </div>
              <p class="theme-transition-ready text-gray-600 dark:text-gray-300">
                <strong>Lancement de notre boutique en ligne</strong> - Ouverture officielle de notre 
                plateforme e-commerce avec une sélection soigneusement choisie de produits sportifs.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Nos Engagements -->
  <section class="py-20 px-4">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">
          Nos Engagements
        </h2>
        <p class="text-xl theme-transition-ready text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
          Des valeurs fortes qui guident chacune de nos actions
        </p>
        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto mt-6"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="feature-card card-hover theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 text-center shadow-lg">
          <div class="feature-icon w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check-circle text-2xl text-blue-500"></i>
          </div>
          <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">Qualité Accessible</h3>
          <p class="theme-transition-ready text-gray-600 dark:text-gray-300">
            Proposer des produits accessibles sans jamais faire de compromis sur la qualité. 
            Chaque article est sélectionné avec soin.
          </p>
        </div>

        <div class="feature-card card-hover theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 text-center shadow-lg">
          <div class="feature-icon w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-headset text-2xl text-purple-500"></i>
          </div>
          <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">Service Client</h3>
          <p class="theme-transition-ready text-gray-600 dark:text-gray-300">
            Offrir un service client à l'écoute et réactif. Notre équipe est là pour vous accompagner 
            dans tous vos projets sportifs.
          </p>
        </div>

        <div class="feature-card card-hover theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 text-center shadow-lg">
          <div class="feature-icon w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-leaf text-2xl text-green-500"></i>
          </div>
          <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">Engagement Écologique</h3>
          <p class="theme-transition-ready text-gray-600 dark:text-gray-300">
            Maintenir un engagement écologique dans notre logistique et nos choix de partenaires. 
            L'environnement est au cœur de nos préoccupations.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Témoignages -->
  <section class="py-20 px-4 theme-transition-ready bg-gray-50 dark:bg-gray-800">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">
          Témoignages Clients
        </h2>
        <p class="text-xl theme-transition-ready text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
          Ce que nos clients pensent de Gainy
        </p>
        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto mt-6"></div>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
        <div class="card-hover theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-lg">
          <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
              C
            </div>
            <div>
              <h4 class="font-bold theme-transition-ready text-gray-900 dark:text-white">Claire D.</h4>
              <div class="flex text-yellow-400">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
          </div>
          <p class="italic theme-transition-ready text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
            "Un service de qualité et des produits que j'adore, je recommande vivement Gainy ! 
            L'équipe est toujours à l'écoute et les livraisons sont ultra rapides."
          </p>
        </div>

        <div class="card-hover theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-lg">
          <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
              J
            </div>
            <div>
              <h4 class="font-bold theme-transition-ready text-gray-900 dark:text-white">Jean P.</h4>
              <div class="flex text-yellow-400">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
          </div>
          <p class="italic theme-transition-ready text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
            "Livraison rapide et produits conformes, je suis très satisfait de mon achat. 
            La qualité est au rendez-vous et les prix sont très compétitifs."
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Call-to-Action -->
  <section class="theme-transition-ready bg-gradient-to-r from-blue-600 to-purple-700 py-20 px-4">
    <div class="max-w-4xl mx-auto text-center text-white">
      <h2 class="text-3xl md:text-4xl font-bold mb-6">
        Rejoignez l'aventure Gainy
      </h2>
      <p class="text-xl mb-8 opacity-90">
        Découvrez notre gamme complète de produits sportifs et rejoignez notre communauté de passionnés
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="produits.php" class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
          <i class="fas fa-shopping-bag mr-2"></i>Découvrir nos produits
        </a>
        <?php if(!isset($_SESSION['CLT_ID'])): ?>
          <a href="/E-commerce/register.php" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all transform hover:scale-105">
            <i class="fas fa-user-plus mr-2"></i>Créer un compte
          </a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="theme-transition-ready bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 py-12 px-6">
    <div class="max-w-6xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 md:gap-12">
        <div class="text-center md:text-left space-y-6">
          <div class="flex justify-center md:justify-start">
            <img src="images/logo.png" alt="Logo" class="rounded-xl shadow-lg cursor-pointer">
          </div>
        </div>
        
        <div class="text-center md:text-left">
          <div class="flex flex-col items-center md:items-start">
            <h4 class="font-bold mb-2 theme-transition-ready text-gray-800 dark:text-white text-lg">Contact</h4>
            <span class="block w-12 h-1 bg-gradient-to-r from-purple-600 to-pink-500 mb-6"></span>
          </div>
          <div class="space-y-6">
            <a href="mailto:tommas.curci@outlook.com" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-purple-500 dark:hover:text-purple-400 flex items-center justify-center md:justify-start cursor-pointer rounded">
              <span class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                <i class="far fa-envelope text-purple-500"></i>
              </span>
              tommas.curci@outlook.com
            </a>
          </div>
        </div>

        <div class="text-center md:text-left">
          <div class="flex flex-col items-center md:items-start">
            <h4 class="font-bold mb-2 theme-transition-ready text-gray-800 dark:text-white text-lg">Réseaux</h4>
            <span class="block w-12 h-1 bg-gradient-to-r from-purple-600 to-pink-500 mb-6"></span>
          </div>
          <div class="space-y-4">
            <a href="https://www.linkedin.com/in/tommas-curci-6080a3305/" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-purple-500 dark:hover:text-purple-400 flex items-center justify-center md:justify-start cursor-pointer rounded">
              <span class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                <i class="fab fa-linkedin text-purple-500"></i>
              </span>
              LinkedIn
            </a>
            <a href="https://github.com/Tommas-0" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-purple-500 dark:hover:text-purple-400 flex items-center justify-center md:justify-start cursor-pointer rounded">
              <span class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                <i class="fab fa-github text-purple-500"></i>
              </span>
              GitHub
            </a>
          </div>
        </div>

        <div class="text-center md:text-left">
          <div class="flex flex-col items-center md:items-start">
            <h4 class="font-bold mb-2 theme-transition-ready text-gray-800 dark:text-white text-lg">Navigation</h4>
            <span class="block w-12 h-1 bg-gradient-to-r from-purple-600 to-pink-500 mb-6"></span>
          </div>
          <div class="space-y-4">
            <a href="index.php" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-purple-500 dark:hover:text-purple-400 flex items-center justify-center md:justify-start cursor-pointer rounded">
              <span class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                <i class="fas fa-home text-purple-500"></i>
              </span>
              Accueil
            </a>
            <a href="produits.php" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-purple-500 dark:hover:text-purple-400 flex items-center justify-center md:justify-start cursor-pointer rounded">
              <span class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                <i class="fas fa-shopping-bag text-purple-500"></i>
              </span>
              Produits
            </a>
          </div>
        </div>
      </div>
      
      <div class="border-t border-gray-200 dark:border-gray-700 mt-12 pt-8 text-center">
        <p class="theme-transition-ready text-gray-600 dark:text-gray-300">&copy; 2024 Gainy. Tous droits réservés.</p>
      </div>
    </div>
  </footer>

<!-- Bouton de basculement du thème -->
<button 
  onclick="toggleTheme()" 
  class="fixed bottom-4 right-4 p-3 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white shadow-lg"
  title="Changer le thème">
  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path class="dark:hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
    <path class="hidden dark:inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
  </svg>
</button>


  <!-- Pied de page -->
  <footer class="text-center py-6 border-t border-gray-300 dark:border-gray-700 mt-16">
    <p>&copy; 2024 Gainy. Tous droits réservés.</p>
  </footer>
</body>
</html>
