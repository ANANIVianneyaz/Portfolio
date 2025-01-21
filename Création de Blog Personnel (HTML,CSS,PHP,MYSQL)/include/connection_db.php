<?php
// Paramètres de connexion à la base de données
$servername = "localhost";  // Nom de l'hôte
$username = "root";         // Nom d'utilisateur MySQL
$password = "";             // Mot de passe MySQL
$dbname = "blog_personnel"; // Nom de la base de données

// Connexion à la base de données MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
