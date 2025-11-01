<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$panier_items = [];
$error_message = null;
$total_panier = 0;

if (!file_exists('db.php')) {
    $error_message = "Fichier de configuration de base de données introuvable.";
} else {
    try {
        require_once 'db.php';
        
        if (!isset($conn) || $conn->connect_error) {
            $error_message = "Connexion à la base de données non établie.";
        } else {
            if (isset($_SESSION['CLT_ID'])) {
                // Version simplifiée sans la jointure CATEGORIE pour l'instant
                $sql = "SELECT p.*, pr.PRT_NOM, pr.PRT_PRIX, pr.PRT_IMG, pr.PRT_STOCK 
                        FROM PANIER p 
                        JOIN PRODUIT pr ON p.PRT_ID = pr.PRT_ID 
                        WHERE p.CLT_ID = ? 
                        ORDER BY p.DATE_AJOUT DESC";
                        
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_SESSION['CLT_ID']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result) {
                    $panier_items = $result->fetch_all(MYSQLI_ASSOC);
                    
                    foreach ($panier_items as $item) {
                        $total_panier += $item['PRT_PRIX'] * $item['QUANTITE'];
                    }
                }
            }
        }
    } catch(Exception $e) {
        $error_message = "Erreur lors de la récupération du panier : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Panier - Gainy</title>
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

        .cart-item {
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-2px);
        }

        .quantity-input {
            -moz-appearance: textfield;
        }

        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
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
    <header class="theme-transition-ready bg-white dark:bg-gray-900 shadow-lg py-4 w-full">
        <div class="mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-8">
                    <nav class="hidden md:flex space-x-6">
                        <a href="index.php">
                            <img src="images/g.png" alt="Logo Gainy" class="h-12 hover:scale-105 transition-transform">
                        </a>
                        <a href="https://curci.sio-chopin.fr" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer rounded">Portfolio</a>
                        <a href="index.php" class="theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer rounded">Accueil</a>
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
                <a href="index.php" class="block theme-transition-ready text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 py-2 cursor-pointer rounded">Accueil</a>
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

    <!-- Section héro -->
    <section class="theme-transition-ready bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-800 dark:to-gray-900 py-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-4">
                <i class="fas fa-shopping-cart mr-4"></i>Votre Panier
            </h1>
            <p class="text-xl theme-transition-ready text-gray-600 dark:text-gray-300 mb-8">
                Finalisez votre commande et découvrez nos produits sélectionnés
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto"></div>
        </div>
    </section>

    <!-- Section du panier -->
    <section class="py-16 px-4">
        <div class="max-w-7xl mx-auto">
            <?php if($error_message): ?>
                <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-8">
                    <strong>Erreur :</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if(!isset($_SESSION['CLT_ID'])): ?>
                <!-- Message pour les utilisateurs non connectés -->
                <div class="text-center py-16">
                    <i class="fas fa-user-lock text-6xl theme-transition-ready text-gray-400 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-2xl font-semibold theme-transition-ready text-gray-600 dark:text-gray-400 mb-2">Connexion requise</h3>
                    <p class="theme-transition-ready text-gray-500 dark:text-gray-500 mb-6">Vous devez être connecté pour accéder à votre panier</p>
                    <a href="/E-commerce/login.php" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Se connecter
                    </a>
                </div>
            <?php elseif(empty($panier_items)): ?>
                <!-- Panier vide -->
                <div class="text-center py-16">
                    <i class="fas fa-shopping-cart text-6xl theme-transition-ready text-gray-400 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-2xl font-semibold theme-transition-ready text-gray-600 dark:text-gray-400 mb-2">Votre panier est vide</h3>
                    <p class="theme-transition-ready text-gray-500 dark:text-gray-500 mb-6">Découvrez nos produits et ajoutez-les à votre panier !</p>
                    <a href="produits.php" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Voir nos produits
                    </a>
                </div>
            <?php else: ?>
                <!-- Contenu du panier -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Articles du panier -->
                    <div class="lg:col-span-2 space-y-4">
                        <h2 class="text-2xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-6">
                            Articles dans votre panier (<?php echo count($panier_items); ?>)
                        </h2>
                        
                        <?php foreach($panier_items as $item): ?>
                            <div class="cart-item theme-transition-ready bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-6">
                                        <!-- Image du produit -->
                                        <div class="flex-shrink-0">
                                            <?php if(!empty($item['PRT_IMG'])): ?>
                                                <img src="<?php echo htmlspecialchars($item['PRT_IMG']); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['PRT_NOM']); ?>" 
                                                     class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-lg">
                                            <?php else: ?>
                                                <div class="w-24 h-24 md:w-32 md:h-32 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center rounded-lg">
                                                    <i class="fas fa-image text-2xl theme-transition-ready text-gray-400 dark:text-gray-500"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Informations du produit -->
                                        <div class="flex-grow">
                                            <div class="flex flex-col md:flex-row md:justify-between">
                                                <div class="mb-4 md:mb-0">
                                                    <?php if(isset($item['CATEGORIE_NOM']) && !empty($item['CATEGORIE_NOM'])): ?>
                                                        <span class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs px-2 py-1 rounded-full font-medium mb-2">
                                                            <?php echo htmlspecialchars($item['CATEGORIE_NOM']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <h3 class="text-lg font-bold theme-transition-ready text-gray-900 dark:text-white mb-2">
                                                        <?php echo htmlspecialchars($item['PRT_NOM']); ?>
                                                    </h3>
                                                    <p class="text-xl font-bold theme-transition-ready text-blue-600 dark:text-blue-400">
                                                        <?php echo number_format($item['PRT_PRIX'], 2, ',', ' '); ?> €
                                                    </p>
                                                    <p class="theme-transition-ready text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        Stock disponible: <?php echo $item['PRT_STOCK']; ?>
                                                    </p>
                                                </div>

                                                <!-- Contrôles de quantité et suppression -->
                                                <div class="flex flex-col md:items-end space-y-4">
                                                    <div class="flex items-center space-x-3">
                                                        <label class="theme-transition-ready text-sm text-gray-600 dark:text-gray-400">Quantité:</label>
                                                        <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                                                            <button onclick="updateQuantity(<?php echo $item['PRT_ID']; ?>, <?php echo $item['QUANTITE'] - 1; ?>)" 
                                                                    class="px-3 py-1 theme-transition-ready text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-l-lg">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" 
                                                                   value="<?php echo $item['QUANTITE']; ?>" 
                                                                   min="1" 
                                                                   max="<?php echo $item['PRT_STOCK']; ?>"
                                                                   class="quantity-input w-16 py-1 text-center theme-transition-ready bg-transparent text-gray-900 dark:text-white border-none focus:outline-none"
                                                                   onchange="updateQuantity(<?php echo $item['PRT_ID']; ?>, this.value)">
                                                            <button onclick="updateQuantity(<?php echo $item['PRT_ID']; ?>, <?php echo $item['QUANTITE'] + 1; ?>)" 
                                                                    class="px-3 py-1 theme-transition-ready text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-r-lg">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center space-x-4">
                                                        <span class="text-lg font-bold theme-transition-ready text-gray-900 dark:text-white">
                                                            <?php echo number_format($item['PRT_PRIX'] * $item['QUANTITE'], 2, ',', ' '); ?> €
                                                        </span>
                                                        <button onclick="removeFromCart(<?php echo $item['PRT_ID']; ?>)" 
                                                                class="theme-transition-ready text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Résumé de la commande -->
                    <div class="lg:col-span-1">
                        <div class="theme-transition-ready bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 sticky top-4">
                            <h3 class="text-xl font-bold theme-transition-ready text-gray-900 dark:text-white mb-6">
                                Résumé de la commande
                            </h3>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between theme-transition-ready text-gray-600 dark:text-gray-400">
                                    <span>Sous-total</span>
                                    <span><?php echo number_format($total_panier, 2, ',', ' '); ?> €</span>
                                </div>
                                <div class="flex justify-between theme-transition-ready text-gray-600 dark:text-gray-400">
                                    <span>Livraison</span>
                                    <span class="text-green-600 dark:text-green-400">Gratuite</span>
                                </div>
                                <hr class="theme-transition-ready border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between text-xl font-bold theme-transition-ready text-gray-900 dark:text-white">
                                    <span>Total</span>
                                    <span><?php echo number_format($total_panier, 2, ',', ' '); ?> €</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <button class="w-full theme-transition-ready bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-credit-card mr-2"></i>Procéder au paiement
                                </button>
                                <button onclick="clearCart()" class="w-full theme-transition-ready bg-red-500 hover:bg-red-600 text-white py-3 px-6 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-trash mr-2"></i>Vider le panier
                                </button>
                                <a href="produits.php" class="block w-full text-center theme-transition-ready bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 py-3 px-6 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>Continuer mes achats
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="theme-transition-ready bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 py-12 px-6">
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
                </div>
            </div>
        </div>
    </footer>

    <!-- Bouton de basculement du thème -->
    <button 
        onclick="toggleTheme()" 
        class="fixed bottom-4 right-4 p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white shadow-lg hover:shadow-xl transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path class="dark:hidden" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            <path class="hidden dark:inline-flex" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>

    <!-- Inclusion du script externe -->
    <script src="js/panier.js"></script>
</body>
</html>