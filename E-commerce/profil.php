<?php
session_start();
include 'db.php';

if (!isset($_SESSION['CLT_ID'])) {
    die("Utilisateur non authentifié.");
}

$clt_id = $_SESSION['CLT_ID'];

$sql = "SELECT CLT_NOM, CLT_PRENOM, CLT_MAIL FROM CLIENT WHERE CLT_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $clt_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Utilisateur introuvable.");
}

$avatarPath = "uploads/avatar_$clt_id.jpg";
if (!file_exists($avatarPath)) {
    $avatarPath = "uploads/default_avatar.jpg"; // avatar par défaut
}
$avatar = $avatarPath;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profil Utilisateur</title>
  <link rel="icon" href="images/logo.ico" type="image/x-icon" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {}
      }
    }
  </script>
  <style>
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
</head>
<body class="min-h-screen bg-white dark:bg-gray-900 flex items-center justify-center">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-md">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-center">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-center">
  <form method="POST" action="update_profile.php" enctype="multipart/form-data">
        <label for="avatar" class="cursor-pointer">
          <img class="w-24 h-24 rounded-full mx-auto mb-4" src="<?php echo $avatar; ?>" alt="Avatar">
        </label>
        <input type="file" id="avatar" name="avatar" class="hidden">
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
          <?php echo htmlspecialchars($user['CLT_PRENOM'] . " " . $user['CLT_NOM']); ?>
        </h2>

        <div class="mt-4">
          <label class="block text-gray-600 dark:text-gray-400">Email</label>
          <input type="email" name="CLT_MAIL"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent"
            value="<?php echo htmlspecialchars($user['CLT_MAIL']); ?>">
        </div>

        <div class="mt-4">
          <label class="block text-gray-600 dark:text-gray-400">Nouveau mot de passe</label>
          <input type="password" name="CLT_MDP" placeholder="Laisser vide pour ne pas changer"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
        </div>

        <div class="mt-4">
          <label class="block text-gray-600 dark:text-gray-400">Confirmer le mot de passe</label>
          <input type="password" name="confirm_password" placeholder="Confirmez le nouveau mot de passe"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
        </div>

       <div class="mt-6">
  <button type="submit"
    class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg">
    Enregistrer les modifications
  </button>
</div>

<div class="mt-4">
  <a href="index.php" class="inline-block text-blue-600 hover:underline dark:text-blue-400">
    &larr; Retour à l'accueil
  </a>
</div>


  <!-- Bouton de thème -->
  <button onclick="toggleTheme()"
    class="fixed bottom-4 right-4 p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path class="dark:hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
      <path class="hidden dark:inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
  </button>

  <script>
    function toggleTheme() {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    document.addEventListener('DOMContentLoaded', () => {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
      }
    });
  </script>
</body>
</html>
