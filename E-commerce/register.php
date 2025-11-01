<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php'; 
if (!$conn) {
    die("Erreur MySQL : " . mysqli_connect_error());
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et sécurisation des données
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification des champs obligatoires
    if (empty($prenom) || empty($nom) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse e-mail invalide.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérification si l'email existe déjà
        $checkEmail = $conn->prepare("SELECT CLT_ID FROM CLIENT WHERE CLT_MAIL = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $checkEmail->store_result();

        if ($checkEmail->num_rows > 0) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Hachage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $stmt = $conn->prepare("INSERT INTO CLIENT (CLT_PRENOM, CLT_NOM, CLT_MAIL, CLT_MDP, CLT_DTE_INSCR) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $prenom, $nom, $email, $hashedPassword);

  if ($stmt->execute()) {
    header("Location: index.php");
    exit(); 
}
            $stmt->close();
        }
        $checkEmail->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gainy Inscription</title>
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
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" id="Formulaire d'Inscription">
    <form method="POST" action="" class="theme-transition-ready max-w-md mx-auto p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-center theme-transition-ready text-gray-800 dark:text-white">Créer un compte</h2>
        
        <!-- Affichage des messages d'erreur ou de succès -->
            <?php if (!empty($error)): ?>
                <p class="mb-4 text-red-500 text-center"><?= $error ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p class="mb-4 text-green-500 text-center"><?= $success ?></p>
            <?php endif; ?>
            
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block theme-transition-ready text-gray-700 dark:text-gray-300 mb-2">Prénom</label>
                <input type="text" name="prenom" required class="theme-transition-ready w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
            </div>
            <div>
                <label class="block theme-transition-ready text-gray-700 dark:text-gray-300 mb-2">Nom</label>
                <input type="text" name="nom" required class="theme-transition-ready w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
            </div>
        </div>
        <div class="mb-4">
            <label class="block theme-transition-ready text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input type="email" name="email" required class="theme-transition-ready w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm sm:text-base focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
        </div>
        <div class="mb-4">
            <label class="block theme-transition-ready text-gray-700 dark:text-gray-300 mb-2">Mot de passe</label>
            <input type="password" name="password" required class="theme-transition-ready w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
        </div>
        <div class="mb-6">
            <label class="block theme-transition-ready text-gray-700 dark:text-gray-300 mb-2">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" required class="theme-transition-ready w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
        </div>
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="conditions" required class="form-checkbox text-blue-500 dark:text-blue-400">
                <span class="ml-2 theme-transition-ready text-gray-600 dark:text-gray-400">J'accepte les conditions d'utilisation</span>
            </label>
        </div>
        <button type="submit" class="theme-transition-ready w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white py-2 rounded-lg mb-4 cursor-pointer">S'inscrire</button>
        <p class="text-center theme-transition-ready text-gray-600 dark:text-gray-400">
            Déjà inscrit ? 
            <a href="/E-commerce/login.php" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 cursor-pointer">Se connecter</a>
        </p>
    </form>
</div>

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
