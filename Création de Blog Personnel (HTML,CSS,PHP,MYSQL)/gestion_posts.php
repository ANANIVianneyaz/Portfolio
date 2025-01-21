<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

 
include('include/connection_db.php');

 
$user_id = $_SESSION['user_id'];
$sql_posts = "SELECT * FROM posts WHERE utilisateur_id = $user_id ORDER BY date_creation DESC";
$posts_result = mysqli_query($conn, $sql_posts);

 
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $post_id = (int)$_GET['delete'];

     
    $sql_get_image = "SELECT image FROM posts WHERE id = $post_id AND utilisateur_id = $user_id";
    $image_result = mysqli_query($conn, $sql_get_image);
    if ($image_result && $image_row = mysqli_fetch_assoc($image_result)) {
        if (!empty($image_row['image'])) {
            $image_path = 'images/' . $image_row['image'];
            if (file_exists($image_path)) {
                unlink($image_path);  
            }
        }
    }

     
    $sql_delete = "DELETE FROM posts WHERE id = $post_id AND utilisateur_id = $user_id";
    if (mysqli_query($conn, $sql_delete)) {
        header('Location: gestion_posts.php'); 
        exit();
    } else {
        echo 'Erreur lors de la suppression du post : ' . mysqli_error($conn);
    }
}
?>

<?php include('include/entete.php'); ?>

<div class="dashboard-container">
    <!-- Menu de navigation à gauche -->
    <div class="sidebar">
        <h3>Menu</h3>
        <ul>
            <li><a href="dashboard.php">Tableau de bord</a></li>
            <li><a href="create_new_post.php">Créer un nouveau post</a></li>
            <li><a href="gestion_posts.php">Gérer mes posts</a></li>
        </ul>
    </div>

    <!-- Contenu principal -->
    <div class="main-content">
        <h1>Gérer mes posts</h1>

        <?php if (mysqli_num_rows($posts_result) > 0): ?>
            <table class="posts-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['titre']); ?></td>
                            <td><?php echo htmlspecialchars($post['contenu']); ?></td>
                            <td>
                                <?php if (!empty($post['image'])): ?>
                                    <img src="images/<?php echo htmlspecialchars($post['image']); ?>" alt="Image du post" class="post-image">
                                <?php else: ?>
                                    <p>Pas d'image</p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="edit-button">Modifier</a>
                                <a href="gestion_posts.php?delete=<?php echo $post['id']; ?>" class="delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez aucun post.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('include/pied.php'); ?>

<!-- Styles mis à jour -->
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
        color: #6a1b9a;
    }

    .posts-table {
        width: 100%;
        border-collapse: collapse;
    }

    .posts-table th, .posts-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    .posts-table th {
        background-color: #6a1b9a;
        color: white;
    }

    .posts-table img {
        width: 100px;
        height: auto;
        border-radius: 5px;
    }

    .edit-button, .delete-button {
        display: inline-block;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        color: white;
    }

    .edit-button {
        background-color: #1976d2;
    }

    .edit-button:hover {
        background-color: #1565c0;
    }

    .delete-button {
        background-color: #d32f2f;
    }

    .delete-button:hover {
        background-color: #b71c1c;
    }
</style>
