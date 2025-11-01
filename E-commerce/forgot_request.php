<?php
require_once 'db.php';

// Définit le fuseau horaire PHP (ici Paris)
date_default_timezone_set('Europe/Paris');

// Synchronise le fuseau horaire MySQL avec PHP
$offset = date('P'); // ex: "+02:00" ou "+01:00"
$conn->query("SET time_zone = '$offset'");

$error = '';
$success = '';

// Le reste de ton code inchangé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Générer un token sécurisé (32 caractères hexadécimaux)
        $token = bin2hex(random_bytes(16));
        
        // Expiration du token dans 1 heure (heure locale Paris)
        $expiration = date('Y-m-d H:i:s', time() + 3600);

        // Met à jour le token et sa date d'expiration dans la table CLIENT
        $stmt = $conn->prepare("UPDATE CLIENT SET TOKEN = ?, TOKEN_EXPIRATION = ? WHERE CLT_MAIL = ?");
        $stmt->bind_param("sss", $token, $expiration, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Préparer le lien de réinitialisation
            $resetLink = "https://curci.sio-chopin.fr/E-commerce/forgot.php?token=$token";

            // Message à envoyer
            $message = "Bonjour,\n\nVoici votre lien pour réinitialiser votre mot de passe :\n$resetLink\n\nCe lien est valable 1 heure.\n\nCordialement,\nL'équipe";

            // En-têtes mail
            $headers = "From: no_reply@curci.sio-chopin.fr\r\n";
            $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

            // Envoi du mail
            if (mail($email, 'Réinitialisation de votre mot de passe', $message, $headers)) {
                $success = "Un lien de réinitialisation a été envoyé à votre adresse email.";
            } else {
                $error = "Erreur lors de l'envoi de l'email. Veuillez réessayer plus tard.";
            }
        } else {
            $error = "Aucun compte trouvé avec cet email.";
        }
        $stmt->close();
    } else {
        $error = "Adresse email invalide.";
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Mot de passe oublié</h2>

        <?php if ($error): ?>
            <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($success): ?>
            <p class="text-green-500 text-center mb-4"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="POST" action="">
                <label for="email" class="block mb-2 text-gray-700">Votre adresse email</label>
                <input type="email" name="email" id="email" required
                       class="w-full px-4 py-2 mb-4 border border-gray-300 rounded"
                       placeholder="Entrez votre email">
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                    Envoyer le lien de réinitialisation
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
