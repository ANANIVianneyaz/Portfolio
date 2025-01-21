<?php
session_start();
include('../include/connection_db.php'); // Inclure le fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sécuriser les données (prévenir les injections SQL)
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Vérifier si l'email existe dans la base de données
    $sql = "SELECT * FROM utilisateurs WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        // Si la requête échoue, afficher une erreur
        die("Erreur de requête : " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        // Si l'email existe, récupérer les données de l'utilisateur
        $user = mysqli_fetch_assoc($result);

        // Vérifier le mot de passe (avec password_verify)
        if (password_verify($password, $user['password'])) {
            // Si le mot de passe est correct, créer une session
            $_SESSION['user_id'] = $user['id']; // ID de l'utilisateur
            $_SESSION['username'] = $user['username']; // Nom d'utilisateur
            $_SESSION['email'] = $user['email']; // Email de l'utilisateur

            // Rediriger vers la page d'accueil ou le tableau de bord
            header('Location: ../dashboard.php');
            exit();
        } else {
            // Si le mot de passe est incorrect
            echo "Mot de passe incorrect.";
        }
    } else {
        // Si aucun utilisateur n'est trouvé avec cet email
        echo "Aucun utilisateur trouvé avec cet email.";
    }
}
?>
