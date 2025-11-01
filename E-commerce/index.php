<?php
session_start();
require_once 'db.php'; 

// Récupération de quelques produits pour la section "Produits populaires"
$produits_populaires = [];
try {
    if (isset($conn) && !$conn->connect_error) {
        $sql = "SELECT * FROM PRODUIT ORDER BY PRT_ID DESC LIMIT 3";
        $result = $conn->query($sql);
        if ($result) {
            $produits_populaires = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
} catch(Exception $e) {
    // Gestion silencieuse des erreurs
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gainy - Boutique en ligne</title>
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

        @media (prefers-color-scheme: dark) {
            :root {
                color-scheme: dark;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
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

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
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
    </style>
    <script>
        // Fonction pour gérer le thème
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

        document.addEventListener('DOMContentLoaded', initTheme);
    </script>
</head>
<body class="min-h-screen bg-white dark:bg-gray-900">
<!-- Header -->
<header class="theme-transition-ready bg-white dark:bg-gray-900 shadow-lg py-4 w-full" id="En-tête Moderne">
    <div class="mx-auto px-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-8">
                <a href="index.php">
                    <img src="images/g.png" alt="Logo Gainy" class="h-12 hover:scale-105 transition-transform">
                </a>
                <nav class="hidden md:flex space-x-6">
                    <a href="https://curci.sio-chopin.fr" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer rounded">Portfolio</a>
                    <a href="about.php" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer rounded">À propos</a>
                    <a href="produits.php" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer rounded">Produits</a>
                </nav>
            </div>
                <div class="hidden md:flex space-x-4">
                    <?php if(isset($_SESSION['CLT_ID'])): ?>
                        <?php 
                        $avatar = !empty($_SESSION['CLT_AVATAR']) ? $_SESSION['CLT_AVATAR'] : '/E-commerce/uploads/default_avatar.jpg';
                        ?>
                        <a href="profil.php">
                            <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="w-8 h-8 rounded-full object-cover border-2">
                        </a>
                        <a href="/E-commerce/deconnexion.php">
                            <button class="theme-transition-ready px-4 py-2 rounded-lg border border-blue-500 text-blue-500 hover:bg-blue-50 dark:hover:bg-gray-800 cursor-pointer">Déconnexion</button>
                        </a>
                        <a href="/E-commerce/panier.php">
                            <button class="theme-transition-ready px-4 py-2 rounded-lg border border-blue-500 text-blue-500 hover:bg-blue-50 dark:hover:bg-gray-800 cursor-pointer">Panier</button>
                        </a>
                    <?php else: ?>
                        <a href="/E-commerce/login.php">
                            <button class="theme-transition-ready px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white cursor-pointer">Connexion</button>
                        </a>
                        <a href="/E-commerce/register.php">
                            <button class="theme-transition-ready px-4 py-2 rounded-lg border border-blue-500 text-blue-500 hover:bg-blue-50 dark:hover:bg-gray-800 cursor-pointer">Inscription</button>
                        </a>
                    <?php endif; ?>
                </div>
                <button id="modern-menu-button" onclick="this.querySelector('.menu-open').classList.toggle('hidden'); this.querySelector('.menu-close').classList.toggle('hidden'); document.getElementById('modern-mobile-menu').classList.toggle('hidden');" class="md:hidden theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path class="menu-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path class="menu-close hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav id="modern-mobile-menu" class="md:hidden hidden mt-4 space-y-2">
                <a href="https://curci.sio-chopin.fr" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer rounded">Portfolio</a>
                <a href="about.php" class="block text-blue-500 font-semibold">À propos</a>
                <a href="produits.php" class="block theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 py-2 cursor-pointer rounded">Produits</a>
                <div class="flex flex-col space-y-2">
                    <?php if(isset($_SESSION['CLT_ID'])): ?>
                        <?php 
                        $avatar = !empty($_SESSION['CLT_AVATAR']) ? $_SESSION['CLT_AVATAR'] : '/E-commerce/uploads/default_avatar.jpg';
                        ?>
                        <a href="profil.php">
                            <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="w-8 h-8 rounded-full object-cover border-2">
                        </a>
                        <a href="/E-commerce/deconnexion.php">
                            <button class="theme-transition-ready px-4 py-2 rounded-lg border border-blue-500 text-blue-500 hover:bg-blue-50 dark:hover:bg-gray-800 cursor-pointer">Déconnexion</button>
                        </a>
                        <a href="/E-commerce/panier.php">
                            <button class="theme-transition-ready px-4 py-2 rounded-lg border border-blue-500 text-blue-500 hover:bg-blue-50 dark:hover:bg-gray-800 cursor-pointer">Panier</button>
                        </a>
                    <?php else: ?>
                        <a href="/E-commerce/login.php">
                            <button class="theme-transition-ready px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white cursor-pointer">Connexion</button>
                        </a>
                        <a href="/E-commerce/register.php">
                            <button class="theme-transition-ready px-4 py-2 rounded-lg border border-blue-500 text-blue-500 hover:bg-blue-50 dark:hover:bg-gray-800 cursor-pointer">Inscription</button>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <!-- Section Héro -->
    <section class="hero-gradient relative overflow-hidden py-20 md:py-32">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative max-w-6xl mx-auto px-4 text-center text-white">
            <div class="animate-float">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Bienvenue chez <span class="text-yellow-300">Gainy</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
                    Découvrez notre gamme exceptionnelle de produits de qualité pour transformer votre quotidien
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="produits.php" class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-shopping-bag mr-2"></i>Découvrir nos produits
                    </a>
                    <a href="#features" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all transform hover:scale-105">
                        <i class="fas fa-info-circle mr-2"></i>En savoir plus
                    </a>
                </div>
            </div>
        </div>
        <!-- Éléments décoratifs -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full animate-pulse-slow"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-white bg-opacity-10 rounded-full animate-pulse-slow"></div>
    </section>

    <!-- Section Caractéristiques -->
    <section id="features" class="py-20 px-4 theme-transition-ready bg-gray-50 dark:bg-gray-800">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">
                    Pourquoi choisir Gainy ?
                </h2>
                <p class="text-xl theme-transition-ready text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Nous nous engageons à vous offrir la meilleure expérience d'achat avec des produits d'exception
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto mt-6"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card card-hover theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 text-center shadow-lg">
                    <div class="feature-icon w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shipping-fast text-2xl text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">Livraison Rapide</h3>
                    <p class="theme-transition-ready text-gray-600 dark:text-gray-300">
                        Livraison express en 24-48h pour tous vos achats. Profitez d'une expérience d'achat sans stress.
                    </p>
                </div>

                <div class="feature-card card-hover theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 text-center shadow-lg">
                    <div class="feature-icon w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-medal text-2xl text-green-500"></i>
                    </div>
                    <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">Qualité Premium</h3>
                    <p class="theme-transition-ready text-gray-600 dark:text-gray-300">
                        Tous nos produits sont sélectionnés avec soin pour garantir une qualité exceptionnelle.
                    </p>
                </div>

                <div class="feature-card card-hover theme-transition-ready bg-white dark:bg-gray-700 rounded-2xl p-8 text-center shadow-lg">
                    <div class="feature-icon w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-headset text-2xl text-purple-500"></i>
                    </div>
                    <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">Support 24/7</h3>
                    <p class="theme-transition-ready text-gray-600 dark:text-gray-300">
                        Notre équipe est disponible 24h/24 pour répondre à toutes vos questions et vous accompagner.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Produits Populaires -->
    <?php if(!empty($produits_populaires)): ?>
    <section class="py-20 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">
                    Produits Populaires
                </h2>
                <p class="text-xl theme-transition-ready text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Découvrez une sélection de nos produits les plus appréciés par nos clients
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto mt-6"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <?php foreach($produits_populaires as $produit): ?>
                    <div class="product-card theme-transition-ready bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="relative overflow-hidden">
                            <?php if(!empty($produit['PRT_IMG'])): ?>
                                <img src="<?php echo htmlspecialchars($produit['PRT_IMG']); ?>" 
                                     alt="<?php echo htmlspecialchars($produit['PRT_NOM']); ?>" 
                                     class="product-image w-full h-48 object-cover">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center">
                                    <i class="fas fa-image text-4xl theme-transition-ready text-gray-400 dark:text-gray-500"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-2">
                                <?php echo htmlspecialchars($produit['PRT_NOM']); ?>
                            </h3>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold theme-transition-ready text-blue-600 dark:text-blue-400">
                                    <?php echo number_format($produit['PRT_PRIX'], 2, ',', ' '); ?> €
                                </span>
                                <span class="theme-transition-ready text-sm text-gray-500 dark:text-gray-400">
                                    Stock: <?php echo $produit['PRT_STOCK']; ?>
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <?php if($produit['PRT_STOCK'] > 0): ?>
                                    <?php if(isset($_SESSION['CLT_ID'])): ?>
                                        <button onclick="ajouterAuPanier(<?php echo $produit['PRT_ID']; ?>)" 
                                                class="flex-1 theme-transition-ready bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-shopping-cart mr-2"></i>Ajouter
                                        </button>
                                    <?php else: ?>
                                        <a href="/E-commerce/login.php" 
                                           class="flex-1 text-center theme-transition-ready bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-shopping-cart mr-2"></i>Ajouter
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button disabled 
                                            class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 py-2 px-4 rounded-lg font-medium cursor-not-allowed">
                                        Rupture de stock
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center">
                <a href="produits.php" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-full hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-eye mr-2"></i>Voir tous nos produits
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Section Call-to-Action -->
    <section class="theme-transition-ready bg-gradient-to-r from-blue-600 to-purple-700 py-20 px-4">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Prêt à commencer votre parcours avec Gainy ?
            </h2>
            <p class="text-xl mb-8 opacity-90">
                Rejoignez des milliers de clients satisfaits qui nous font confiance pour leurs achats quotidiens
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <?php if(!isset($_SESSION['CLT_ID'])): ?>
                    <a href="/E-commerce/register.php" class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Créer un compte
                    </a>
                <?php endif; ?>
                <a href="produits.php" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all transform hover:scale-105">
                    <i class="fas fa-shopping-bag mr-2"></i>Commencer mes achats
                </a>
            </div>
        </div>
    </section>
                
<footer class="theme-transition-ready bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 py-12 px-6" id="Footer Créatif">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 md:gap-12">
            <div class="text-center md:text-left space-y-6">
                <div class="flex justify-center md:justify-start">
                    <img src="images/logo.png" alt="Logo" class="rounded-xl shadow-lg cursor-pointer rounded">
                </div>
            </div>

            <div class="text-center md:text-left">
                <div class="flex flex-col items-center md:items-start">
                    <h4 class="font-bold mb-2 theme-transition-ready text-gray-800 dark:text-white text-lg">
                        Contact
                    </h4>
                    <span class="block w-12 h-1 bg-gradient-to-r from-purple-600 to-pink-500 mb-6"></span>
                </div>
                <div class="space-y-6">
                    <a href="mailto:contact@example.com" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-purple-500 dark:hover:text-purple-400 flex items-center justify-center md:justify-start cursor-pointer rounded">
                        <span class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                            <i class="far fa-envelope text-purple-500"></i>
                        </span>
                        tommas.curci@outlook.com
                    </a>
                </div>
                <div class="space-y-6 mt-4">
                    <a href="https://www.linkedin.com/in/tommas-curci-6080a3305/" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-purple-500 dark:hover:text-purple-400 flex items-center justify-center md:justify-start cursor-pointer rounded">
                        <span class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512" class="h-5 w-5 text-purple-500">
                                <path d="M100.3 448H7.4V148.9h92.9zM53.8 108.1C24.1 108.1 0 83.5 0 53.8a53.8 53.8 0 0 1 107.6 0c0 29.7-24.1 54.3-53.8 54.3zM447.9 448h-92.7V302.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V448h-92.8V148.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V448z"/>
                            </svg>
                        </span>
                        Linkedin
                    </a>
                </div>
                <div class="space-y-6 mt-4">
                    <a href="https://github.com/Tommas-0" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-purple-500 dark:hover:text-purple-400 flex items-center justify-center md:justify-start cursor-pointer rounded">
                        <span class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-6 w-6 text-purple-500">
                                <path d="M12 0C5.372 0 0 5.372 0 12c0 5.302 3.438 9.798 8.207 11.387.6.111.793-.26.793-.577 0-.287-.01-1.048-.015-2.064-3.338.725-4.038-1.592-4.038-1.592-.546-1.387-1.333-1.758-1.333-1.758-1.091-.746.083-.732.083-.732 1.208.084 1.843 1.24 1.843 1.24 1.072 1.835 2.808 1.304 3.495.997.108-.775.419-1.303.763-1.604-2.666-.301-5.466-1.333-5.466-5.928 0-1.308.468-2.384 1.237-3.219-.124-.301-.536-1.525.117-3.181 0 0 1.005-.322 3.293 1.225.957-.267 1.979-.401 3.004-.405 1.025.004 2.047.138 3.004.405 2.288-1.547 3.293-1.225 3.293-1.225.653 1.656.242 2.88.118 3.181.77.835 1.237 1.911 1.237 3.219 0 4.599-2.805 5.623-5.477 5.917.431.374.816 1.113.816 2.247 0 1.624-.015 2.936-.015 3.34 0 .319.192.691.804.577C20.565 21.798 24 17.302 24 12c0-6.628-5.372-12-12-12z"/>
                            </svg>
                        </span>
                        GitHub
                    </a>
                </div>
            </div>
            <div class="text-center md:text-left">
                <div class="flex flex-col items-center md:items-start">
                    <h4 class="font-bold mb-2 theme-transition-ready text-gray-800 dark:text-white text-lg">
                        Navigation
                    </h4>
                    <span class="block w-12 h-1 bg-gradient-to-r from-purple-600 to-pink-500 mb-6"></span>
                </div>
                <div class="space-y-6 mt-4">
                    <a href="/E-commerce/about.php" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-purple-500 dark:hover:text-purple-400 flex items-center justify-center md:justify-start cursor-pointer rounded">
                        <span class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                            <i class="fa-solid fa-industry text-purple-500"></i>
                        </span>
                        À propos de nous
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

    <!-- Bouton de basculement du thème -->
    <button 
        onclick="toggleTheme()" 
        class="fixed bottom-4 right-4 p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path class="dark:hidden" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            <path class="hidden dark:inline-flex" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>
</body>
</html>