<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php'; // Connexion à la base de données

if (!$conn) {
    die("Erreur MySQL : " . mysqli_connect_error());
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparation de la requête pour éviter l'injection SQL
    // Ajoute ici la colonne avatar, par exemple CLT_AVATAR
    $checkEmail = $conn->prepare("SELECT CLT_ID, CLT_MDP, CLT_NOM, CLT_PRENOM, CLT_AVATAR FROM CLIENT WHERE CLT_MAIL = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        // Bind aussi la variable pour avatar
        $checkEmail->bind_result($cltId, $cltMdp, $cltNom, $cltPrenom, $cltAvatar);
        $checkEmail->fetch();

        if (password_verify($password, $cltMdp)) {
            // connexion réussie, on stocke les infos avec les bons noms de session
            $_SESSION['CLT_ID'] = $cltId;
            $_SESSION['CLT_NOM'] = $cltNom;
            $_SESSION['CLT_PRENOM'] = $cltPrenom;
            $_SESSION['CLT_AVATAR'] = $cltAvatar;  // Stocker l'avatar en session

            header('Location: index.php');
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Aucun utilisateur trouvé avec cet email.";
    }

    $checkEmail->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gainy Connexion</title>
    <link rel="icon" href="images/logo.ico" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {} }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
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
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" id="Formulaire de Connexion">
        <form method="POST" action="" class="theme-transition-ready max-w-md mx-auto p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-6 text-center theme-transition-ready text-gray-800 dark:text-white">Connexion</h2>

            <!-- Popup d'erreur -->
            <?php if (!empty($error)): ?>
                <div id="error-popup" class="mb-4 px-4 py-3 rounded relative bg-red-100 border border-red-400 text-red-700" role="alert">
                    <strong class="font-bold">Erreur : </strong>
                    <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
                    <span onclick="document.getElementById('error-popup').remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" title="Fermer">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Fermer</title>
                            <path d="M14.348 5.652a1 1 0 10-1.414-1.414L10 7.172 7.066 4.238a1 1 0 10-1.414 1.414L8.586 8.586l-2.934 2.934a1 1 0 001.414 1.414L10 9.828l2.934 2.934a1 1 0 001.414-1.414L11.414 8.586l2.934-2.934z"/>
                        </svg>
                    </span>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block theme-transition-ready text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" name="email" required
                    class="theme-transition-ready w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent" />
            </div>
            <div class="mb-6">
                <label class="block theme-transition-ready text-gray-700 dark:text-gray-300 mb-2">Mot de passe</label>
                <input type="password" name="password" required
                    class="theme-transition-ready w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent" />
                <a href="forgot_request.php" class="text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 mt-2 inline-block cursor-pointer rounded">Mot de passe oublié ?</a>
            </div>
            <button type="submit"
                class="theme-transition-ready w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white py-2 rounded-lg mb-4 cursor-pointer rounded">Se
                connecter</button>
            <p class="text-center theme-transition-ready text-gray-600 dark:text-gray-400">
                Pas encore de compte ?
                <a href="/E-commerce/register.php" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 cursor-pointer rounded">S'inscrire</a>
            </p>
        </form>
    </div>

    <!-- Bouton de basculement du thème -->
    <button onclick="toggleTheme()"
        class="fixed bottom-4 right-4 p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path class="dark:hidden" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            <path class="hidden dark:inline-flex"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>

    <script>
        // Disparition automatique du popup erreur après 4 secondes
        document.addEventListener('DOMContentLoaded', () => {
            const popup = document.getElementById('error-popup');
            if (popup) {
                setTimeout(() => {
                    popup.remove();
                }, 4000);
            }
        });
    </script>
</body>
</html>
