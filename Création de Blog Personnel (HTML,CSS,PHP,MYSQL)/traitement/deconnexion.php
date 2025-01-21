<?php
// Démarrer la session
session_start();

// Vérifier si une session utilisateur est active
if (isset($_SESSION['user_id'])) {
    // Détruire toutes les données de session
    session_unset(); // Supprime toutes les variables de session
    session_destroy(); // Détruit la session en cours

    // Rediriger vers la page de connexion
    header('Location: ../connexion.php');
    exit();
} else {
    // Si aucune session active, rediriger directement vers la page de connexion
    header('Location: connexion.php');
    exit();
}
