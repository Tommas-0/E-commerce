<?php 
    session_start(); // Démarrer la session pour stocker les données de l'utilisateur
    include 'header.php'; // Inclure le header.php
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programme personnalisé</title>
    <link rel="stylesheet" href="style-E.css">
</head>

<main class="page-perso">
    <section class="container">
        <h2>Crée ton programme personnalisé</h2>
        <form id="personalized-program-form" method="POST">
            <!-- Question 1 -->
            <div class="form-group">
                <label for="objectif">Quel est ton objectif ?</label>
                <select name="objectif" id="objectif" required>
                    <option value="">Sélectionne un objectif</option>
                    <option value="perte_de_poids">Perte de poids</option>
                    <option value="gain_de_muscle">Gain de muscle</option>
                    <option value="maintien">Maintien</option>
                </select>
            </div>

            <!-- Question 2 -->
            <div class="form-group">
                <label for="frequence">Combien de jours par semaine veux-tu t'entraîner ?</label>
                <input type="number" name="frequence" id="frequence" min="1" max="7" required>
            </div>

            <!-- Question 3 -->
            <div class="form-group">
                <label for="niveau">Quel est ton niveau ?</label>
                <select name="niveau" id="niveau" required>
                    <option value="">Sélectionne un niveau</option>
                    <option value="debutant">Débutant</option>
                    <option value="intermediaire">Intermédiaire</option>
                    <option value="avance">Avancé</option>
                </select>
            </div>

            <!-- Question 4 -->
            <div class="form-group">
                <label for="temps">Combien de temps souhaites-tu consacrer à chaque séance ? (en minutes)</label>
                <input type="number" name="temps" id="temps" min="15" max="120" required>
            </div>

            <button type="submit" class="submit-btn">Obtenir mon programme</button>
        </form>
        
        <!-- Zone de message (succès / erreur) -->
        <div id="program-result" class="success-message" style="display:none;">
            Ton programme personnalisé est prêt ! <a href="download.php">Télécharge-le ici</a>.
        </div>
    </section>
</main>

<?php include 'footer.php'; ?> <!-- Inclure le footer.php -->
