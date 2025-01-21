<?php
session_start();
include('../include/connection_db.php'); // Inclure le fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Sécuriser les données (prévenir les injections SQL)
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Vérification si l'email est déjà utilisé
    $sql_check_email = "SELECT * FROM utilisateurs WHERE email = '$email'";
    $result = mysqli_query($conn, $sql_check_email);

    if (mysqli_num_rows($result) > 0) {
        // Si l'email existe déjà
        echo "L'email est déjà utilisé. Veuillez en choisir un autre.";
    } else {
        // Hash du mot de passe avant de l'enregistrer
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insérer les données dans la base de données
        $sql_insert = "INSERT INTO utilisateurs (username, email, password) 
                       VALUES ('$username', '$email', '$hashed_password')";
        
        if (mysqli_query($conn, $sql_insert)) {
            // Si l'insertion est réussie
            $_SESSION['user_id'] = mysqli_insert_id($conn); // Récupère l'ID de l'utilisateur inséré
            $_SESSION['username'] = $username; // Stocke le nom d'utilisateur dans la session

            // Rediriger vers la page d'accueil ou tableau de bord
            header('Location: ../dashboard.php');
            exit();
        } else {
            // Si l'insertion échoue
            echo "Erreur : " . mysqli_error($conn);
        }
    }
}
?>
