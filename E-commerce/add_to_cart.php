<?php
session_start();

// Configuration stricte pour éviter tout output avant le JSON
ob_start();
error_reporting(0); // Désactiver l'affichage des erreurs pour éviter de corrompre le JSON
ini_set('display_errors', 0);

// Nettoyer tout output buffer existant
if (ob_get_length()) ob_clean();

// Headers obligatoires
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

try {
    // Vérification de la connexion utilisateur
    if (!isset($_SESSION['CLT_ID'])) {
        echo json_encode([
            'success' => false, 
            'message' => 'Vous devez être connecté pour ajouter des produits au panier'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Vérification de la méthode POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'success' => false, 
            'message' => 'Méthode non autorisée'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Inclusion du fichier de base de données
    if (!file_exists('db.php')) {
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur de configuration serveur'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    require_once 'db.php';
    
    // Vérification de la connexion à la base de données
    if (!isset($conn) || $conn->connect_error) {
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur de connexion à la base de données'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Récupération et validation des données
    $produit_id = isset($_POST['produit_id']) ? intval($_POST['produit_id']) : 0;
    $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 1;
    $client_id = intval($_SESSION['CLT_ID']);
    
    if ($produit_id <= 0 || $quantite <= 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'Données invalides'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Vérification de l'existence du produit et de son stock
    $stmt = $conn->prepare("SELECT PRT_NOM, PRT_STOCK FROM PRODUIT WHERE PRT_ID = ?");
    if (!$stmt) {
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur de préparation de la requête'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $stmt->bind_param("i", $produit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'Produit introuvable'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $produit = $result->fetch_assoc();
    
    if ($quantite > $produit['PRT_STOCK']) {
        echo json_encode([
            'success' => false, 
            'message' => 'Stock insuffisant'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Vérifier si le produit est déjà dans le panier
    $stmt = $conn->prepare("SELECT QUANTITE FROM PANIER WHERE CLT_ID = ? AND PRT_ID = ?");
    if (!$stmt) {
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur de préparation de la requête'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $stmt->bind_param("ii", $client_id, $produit_id);
    $stmt->execute();
    $existing_result = $stmt->get_result();
    
    if ($existing_result->num_rows > 0) {
        // Le produit existe déjà, mettre à jour la quantité
        $existing_item = $existing_result->fetch_assoc();
        $nouvelle_quantite = $existing_item['QUANTITE'] + $quantite;
        
        if ($nouvelle_quantite > $produit['PRT_STOCK']) {
            echo json_encode([
                'success' => false, 
                'message' => 'Stock insuffisant pour cette quantité'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $stmt = $conn->prepare("UPDATE PANIER SET QUANTITE = ?, DATE_MODIFICATION = NOW() WHERE CLT_ID = ? AND PRT_ID = ?");
        if (!$stmt) {
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur de préparation de la requête de mise à jour'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $stmt->bind_param("iii", $nouvelle_quantite, $client_id, $produit_id);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Quantité mise à jour dans le panier'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors de la mise à jour: ' . $stmt->error
            ], JSON_UNESCAPED_UNICODE);
        }
    } else {
        // Nouveau produit, l'ajouter au panier
        $stmt = $conn->prepare("INSERT INTO PANIER (CLT_ID, PRT_ID, QUANTITE, DATE_AJOUT) VALUES (?, ?, ?, NOW())");
        if (!$stmt) {
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur de préparation de la requête d\'insertion'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $stmt->bind_param("iii", $client_id, $produit_id, $quantite);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Produit ajouté au panier avec succès'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors de l\'ajout: ' . $stmt->error
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Erreur serveur: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} finally {
    // Nettoyer et fermer les ressources
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    ob_end_flush();
}
?>