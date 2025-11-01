# 🛒 Plateforme E-commerce en PHP

Ce projet est une plateforme e-commerce complète, développée en PHP natif avec une base de données MySQL. Il a été conçu pour mettre en pratique les fondamentaux du développement web back-end, en créant une expérience utilisateur fonctionnelle de la navigation à la finalisation de la commande.

## ✨ Fonctionnalités

-   Catalogue de produits : Affichage des produits avec leurs détails depuis la base de données.
-   Panier d'achat : Ajout, modification de quantité et suppression de produits du panier, avec persistance des données grâce aux sessions PHP.
-   Système d'authentification : Inscription et connexion des utilisateurs avec gestion des sessions.
-   Gestion des commandes : Processus de validation de commande pour les utilisateurs connectés.
-   Interface responsive : Le design a été réalisé avec Tailwind CSS pour s'adapter aux différentes tailles d'écran.

## 🛠️ Technologies Utilisées

-   Back-end : PHP pour toute la logique serveur (gestion des sessions, traitement des formulaires, interaction avec la base de données).
-   Base de données : MySQL pour le stockage des utilisateurs, des produits et des commandes.
-   Front-end :
    -   HTML5 et JavaScript pour la structure et l'interactivité côté client.
    -   Tailwind CSS pour un design moderne et responsive rapidement.

## 🚀 Lancer le projet localement

Pour faire fonctionner ce projet sur votre machine, vous aurez besoin d'un environnement de serveur local comme WAMP, MAMP ou XAMPP.

1.  Clonez le dépôt :
    ```bash
    git clone https://github.com/Tommas-0/E-commerce.git
    ```

2.  Base de données :
    -   Importez le fichier `.sql` dans votre système de gestion de base de données (par exemple, phpMyAdmin).
    -   Assurez-vous que les informations de connexion à la base de données dans les fichiers PHP (`config.php` ou similaire) correspondent à votre configuration locale.

3.  Lancez le serveur :
    -   Placez le dossier du projet dans le répertoire `www` ou `htdocs` de votre serveur local.
    -   Démarrez les services Apache et MySQL.
    -   Ouvrez votre navigateur et accédez à `http://localhost/E-commerce/` (ou le nom du dossier que vous avez utilisé).

## 🎯 Objectifs et Apprentissages

Ce projet m'a permis de solidifier mes compétences sur des aspects clés du développement web :

-   Développement d'une application **full-stack** de A à Z.
-   Maîtrise de **PHP natif** et de la gestion des **sessions**.
-   Conception et interaction avec une base de données **MySQL**.
-   Implémentation d'un système d'**authentification** sécurisé.
-   Utilisation de **Tailwind CSS** pour créer une interface utilisateur moderne.
