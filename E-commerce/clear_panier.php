<?php
session_start();
header('Content-Type: application/json');

// Vérification de la connexion utilisateur
if (!isset($_SESSION['CLT_ID'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

// Vérification de la méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

try {
    require_once 'db.php';
    
    $client_id = $_SESSION['CLT_ID'];
    
    // Suppression de tous les articles du panier pour ce client
    $stmt = $conn->prepare("DELETE FROM PANIER WHERE CLT_ID = ?");
    $stmt->bind_param("i", $client_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Panier vidé avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors du vidage du panier']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>