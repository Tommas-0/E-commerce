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
    
    // Récupération et validation des données
    $produit_id = isset($_POST['produit_id']) ? intval($_POST['produit_id']) : 0;
    $client_id = $_SESSION['CLT_ID'];
    
    if ($produit_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID produit invalide']);
        exit;
    }
    
    // Suppression du produit du panier
    $stmt = $conn->prepare("DELETE FROM PANIER WHERE CLT_ID = ? AND PRT_ID = ?");
    $stmt->bind_param("ii", $client_id, $produit_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Produit supprimé du panier']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Produit non trouvé dans le panier']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>