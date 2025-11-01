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
    $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 0;
    $client_id = $_SESSION['CLT_ID'];
    
    if ($produit_id <= 0 || $quantite <= 0) {
        echo json_encode(['success' => false, 'message' => 'Données invalides']);
        exit;
    }
    
    // Vérification du stock disponible
    $stmt = $conn->prepare("SELECT PRT_STOCK FROM PRODUIT WHERE PRT_ID = ?");
    $stmt->bind_param("i", $produit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Produit introuvable']);
        exit;
    }
    
    $produit = $result->fetch_assoc();
    
    if ($quantite > $produit['PRT_STOCK']) {
        echo json_encode(['success' => false, 'message' => 'Stock insuffisant']);
        exit;
    }
    
    // Mise à jour de la quantité dans le panier
    $stmt = $conn->prepare("UPDATE PANIER SET QUANTITE = ?, DATE_MODIFICATION = NOW() WHERE CLT_ID = ? AND PRT_ID = ?");
    $stmt->bind_param("iii", $quantite, $client_id, $produit_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Quantité mise à jour']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Produit non trouvé dans le panier']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>