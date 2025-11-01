<?php
session_start();
include 'db.php';

if (!isset($_SESSION['CLT_ID'])) {
    die("Utilisateur non authentifié.");
}

$clt_id = $_SESSION['CLT_ID'];
$email = $_POST['CLT_MAIL'] ?? '';
$new_password = $_POST['CLT_MDP'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email invalide.");
}

// Si un mot de passe est entré, vérifier confirmation
if (!empty($new_password)) {
    if ($new_password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
}

// Traitement de l'avatar
$avatar_path = null;
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['avatar']['tmp_name'];
    $fileName = $_FILES['avatar']['name'];
    $fileSize = $_FILES['avatar']['size'];
    $fileType = $_FILES['avatar']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileExtension, $allowedExtensions)) {
        $newFileName = 'avatar_' . $clt_id . '.' . $fileExtension;
        $uploadFileDir = './uploads/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }
        $dest_path = $uploadFileDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $avatar_path = $dest_path;
        } else {
            die("Erreur lors du téléchargement de l'image.");
        }
    } else {
        die("Extension de fichier non autorisée.");
    }
}

// Construction de la requête dynamique
$sql = "UPDATE CLIENT SET CLT_MAIL = ?";
$params = [$email];
$types = "s";

if (!empty($new_password)) {
    $sql .= ", CLT_MDP = ?";
    $params[] = $hashed_password;
    $types .= "s";
}

if ($avatar_path !== null) {
    $sql .= ", CLT_AVATAR = ?";
    $params[] = $avatar_path;
    $types .= "s";
}

$sql .= " WHERE CLT_ID = ?";
$params[] = $clt_id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    die("Erreur lors de la mise à jour.");
}
?>
