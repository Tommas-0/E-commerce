<?php
require_once 'db.php';

$token = $_GET['token'] ?? '';
$success = '';
$error = '';

// Étape 1 : Vérification du token
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $token) {
    $stmt = $conn->prepare("SELECT CLT_ID FROM CLIENT WHERE TOKEN = ? AND TOKEN_EXPIRATION > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $error = "Lien invalide ou expiré.";
    }

    $stmt->close();
}

// Étape 2 : Soumission du nouveau mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'], $_POST['password'])) {
    $newPassword = $_POST['password'];
    $token = $_POST['token'];

    // Vérifie que le token est toujours actif
    $stmt = $conn->prepare("SELECT CLT_ID FROM CLIENT WHERE TOKEN = ? AND TOKEN_EXPIRATION > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($clientId);
    $stmt->fetch();

    if ($clientId) {
        // Mise à jour du mot de passe et suppression du token
        $stmt->close();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE CLIENT SET CLT_MDP = ?, TOKEN = NULL, TOKEN_EXPIRATION = NULL WHERE CLT_ID = ?");
        $update->bind_param("si", $hashedPassword, $clientId);
        if ($update->execute()) {
            $success = "Votre mot de passe a été réinitialisé avec succès.";
        } else {
            $error = "Erreur lors de la mise à jour du mot de passe.";
        }
        $update->close();
    } else {
        $error = "Lien invalide ou expiré.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Réinitialiser le mot de passe</h2>

        <?php if ($error): ?>
            <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($success): ?>
            <p class="text-green-500 text-center mb-4"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if (!$success && empty($error) && $token): ?>
            <form method="POST" action="">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <label class="block text-gray-700 mb-2">Nouveau mot de passe</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 mb-4 border border-gray-300 rounded bg-white text-gray-900">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">Réinitialiser</button>
            </form>
        <?php elseif (!$token): ?>
            <p class="text-center text-gray-600">Aucun token fourni.</p>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="login.php" class="text-blue-500 hover:underline">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
