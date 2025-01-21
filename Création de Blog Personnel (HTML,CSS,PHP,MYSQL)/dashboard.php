<?php
session_start();

// Si l'utilisateur n'est pas connecté, redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

// Connexion à la base de données
include('include/connection_db.php');

// Récupérer tous les posts depuis la base de données
$sql_posts = "SELECT posts.*, utilisateurs.username FROM posts 
              JOIN utilisateurs ON posts.utilisateur_id = utilisateurs.id 
              ORDER BY posts.date_creation DESC";
$posts_result = mysqli_query($conn, $sql_posts);

// Vérifier si la requête a réussi
if (!$posts_result) {
    die('Erreur de requête SQL : ' . mysqli_error($conn)); // Afficher l'erreur si la requête échoue
}

// Récupérer les commentaires pour chaque post
$sql_comments = "SELECT commentaires.*, utilisateurs.username 
                 FROM commentaires 
                 JOIN utilisateurs ON commentaires.utilisateur_id = utilisateurs.id 
                 WHERE post_id = ? 
                 ORDER BY commentaires.date_creation DESC";

// Ajouter un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'], $_POST['post_id'])) {
    $comment_content = mysqli_real_escape_string($conn, $_POST['comment_content']);
    $post_id = (int) $_POST['post_id'];
    $author = $_SESSION['username'];

    $sql_add_comment = "INSERT INTO commentaires (post_id, utilisateur_id, contenu) 
                        VALUES ($post_id, {$_SESSION['user_id']}, '$comment_content')";
    if (mysqli_query($conn, $sql_add_comment)) {
        header("Location: dashboard.php"); // Recharger la page après avoir ajouté un commentaire
        exit();
    } else {
        echo "Erreur lors de l'ajout du commentaire : " . mysqli_error($conn);
    }
}

?>

<?php include('include/entete.php'); ?>

<div class="dashboard-container">
    <!-- Menu de navigation à gauche -->
    <div class="sidebar">
        <h3>Menu</h3>
        <ul>
            <li><a href="create_new_post.php">Créer un nouveau post</a></li>
            <li><a href="gestion_posts.php">Gérer mes posts</a></li>
        </ul>
    </div>

    <!-- Contenu principal à droite -->
    <div class="main-content">
        <h1>Bienvenue sur votre tableau de bord, <?php echo $_SESSION['username']; ?> !</h1>

        <h2>Postes :</h2>
        <?php if (mysqli_num_rows($posts_result) > 0): ?>
            <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>
                <div class="post">
                    <div class="post-header">
                        <h1 class="author-name"><?php echo $post['username']; ?></h1>
                    </div>

                    <h2 class="post-title"><?php echo $post['titre']; ?></h2>
                    <p class="post-description"><?php echo nl2br($post['contenu']); ?></p>

                    <!-- Affichage de la photo du post si elle existe -->
                    <?php if (!empty($post['image'])): ?>
                        <?php
                        // Vérification de l'existence du fichier image
                        $image_path = "images/" . htmlspecialchars($post['image']);
                        if (file_exists($image_path)) {
                            echo '<img src="' . $image_path . '" alt="Image du post" class="post-image">';
                        } else {
                            echo '<p>Image non trouvée</p>';
                        }
                        ?>
                    <?php endif; ?>

                    <!-- Affichage des commentaires -->
                    <div class="comments-section">
                        <h4>Commentaires :</h4>
                        <?php
                        $stmt = mysqli_prepare($conn, $sql_comments);
                        if ($stmt === false) {
                            die('Erreur de préparation de la requête SQL : ' . mysqli_error($conn)); // Affiche une erreur si la préparation échoue
                        }

                        mysqli_stmt_bind_param($stmt, "i", $post['id']);
                        mysqli_stmt_execute($stmt);
                        $comments_result = mysqli_stmt_get_result($stmt);
                        while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                            <div class="comment">
                                <p class="comment-author"><strong><?php echo $comment['username']; ?>:</strong></p>
                                <p class="comment-content"><?php echo nl2br($comment['contenu']); ?></p>

                                <!-- Si l'utilisateur connecté est l'auteur du commentaire, il peut le supprimer -->
                                <?php if ($_SESSION['user_id'] == $comment['utilisateur_id']): ?>
                                    <form action="delete_comment.php" method="POST" class="delete-comment-form">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                        <button type="submit" class="delete-button">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Formulaire d'ajout de commentaire -->
                    <div class="add-comment">
                        <h4>Ajouter un commentaire :</h4>
                        <form action="dashboard.php" method="POST">
                            <textarea name="comment_content" placeholder="Ajoutez un commentaire" required></textarea>
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <button type="submit">Ajouter un commentaire</button>
                        </form>
                    </div>
                </div>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucun post trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('include/pied.php'); ?>

<!-- Style spécifique au tableau de bord -->
<style>
    .dashboard-container {
        display: flex;
        margin-top: 80px;
    }

    .sidebar {
        width: 200px;
        background-color: #4a148c;
        color: white;
        padding: 20px;
        height: calc(100vh - 60px);
        position: fixed;
        left: 0;
        top: 60px;
    }

    .sidebar h3 {
        text-align: center;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        margin: 15px 0;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        font-size: 18px;
    }

    .sidebar ul li a:hover {
        color: #ffeb3b;
    }

    .main-content {
        margin-left: 220px;
        padding: 20px;
        width: calc(100% - 220px);
        margin-top: 0;
    }

    h1 {
        margin-top: 0;
        color: #6a1b9a;
    }

    .post {
        background-color: #f0f0f0; /* Fond gris clair pour les posts */
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .post-header {
        margin-bottom: 10px;
    }

    .author-name {
        font-size: 28px;
        font-weight: bold;
        color: #d32f2f; /* Couleur du nom de l'auteur */
    }

    .post-title {
        font-size: 22px;
        font-weight: bold;
        color: #1976d2; /* Couleur du titre */
        margin-top: 5px;
    }

    .post-description {
        font-size: 16px;
        line-height: 1.6;
        color: #333;
        margin-top: 5px;
    }

    .post-image {
        width: 100%;
        height: auto;
        margin-top: 20px;
        border-radius: 8px;
    }

    .comments-section h4 {
        font-size: 18px;
        color: #4a148c;
        margin-top: 20px;
    }

    .comment {
        background-color: #e8e8e8;
        padding: 15px;
        border-radius: 5px;
        margin-top: 10px;
        margin-left: 30px; /* Décaler les commentaires légèrement à droite */
    }

    .comment-author {
        font-size: 18px;
        color: #1976d2;
    }

    .comment-content {
        font-size: 16px;
        margin-top: 5px;
    }

    .delete-comment-form {
        margin-top: 10px;
    }

    .delete-button {
        background-color: #d32f2f;
        color: white;
        padding: 8px 18px;
        border-radius: 5px;
        border: none;
    }

    .delete-button:hover {
        background-color: #b71c1c;
    }

    .add-comment form {
        margin-top: 20px;
    }

    .add-comment textarea {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        height: 60px;
        margin-top: 10px;
    }

    .add-comment button {
        background-color: #9c4d97;
        color: white;
        padding: 8px 18px;
        border-radius: 5px;
        border: none;
        margin-top: 10px;
    }

    .add-comment button:hover {
        background-color: #7b1fa2;
    }
</style>
