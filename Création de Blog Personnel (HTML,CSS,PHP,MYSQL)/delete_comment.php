<?php
session_start();

// Si l'utilisateur n'est pas connecté, redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

// Connexion à la base de données
include('include/connection_db.php');

// Vérifier si l'ID du commentaire est passé dans la requête
if (isset($_POST['comment_id'])) {
    $comment_id = (int) $_POST['comment_id'];

    // Récupérer l'utilisateur qui a écrit le commentaire
    $sql_comment = "SELECT utilisateur_id FROM commentaires WHERE id = $comment_id";
    $result_comment = mysqli_query($conn, $sql_comment);

    if ($result_comment && mysqli_num_rows($result_comment) > 0) {
        $comment = mysqli_fetch_assoc($result_comment);

        // Vérifier si l'utilisateur connecté est l'auteur du commentaire
        if ($comment['utilisateur_id'] == $_SESSION['user_id']) {
            // Supprimer le commentaire
            $sql_delete_comment = "DELETE FROM commentaires WHERE id = $comment_id";
            if (mysqli_query($conn, $sql_delete_comment)) {
                header("Location: dashboard.php"); // Recharger la page du tableau de bord
                exit();
            } else {
                echo "Erreur lors de la suppression du commentaire : " . mysqli_error($conn);
            }
        } else {
            echo "Vous ne pouvez pas supprimer ce commentaire car vous n'êtes pas l'auteur.";
        }
    } else {
        echo "Le commentaire spécifié n'existe pas.";
    }
} else {
    echo "Aucun commentaire spécifié.";
}
?>
