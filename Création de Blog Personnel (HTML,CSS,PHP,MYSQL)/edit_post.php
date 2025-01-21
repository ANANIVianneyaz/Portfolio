<?php
session_start();

 
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

 
include('include/connection_db.php');

 
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: gestion_posts.php');
    exit();
}

$post_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

 
$sql_post = "SELECT * FROM posts WHERE id = $post_id AND utilisateur_id = $user_id";
$post_result = mysqli_query($conn, $sql_post);

if (mysqli_num_rows($post_result) !== 1) {
    header('Location: gestion_posts.php');
    exit();
}

$post = mysqli_fetch_assoc($post_result);

 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = mysqli_real_escape_string($conn, $_POST['titre']);
    $contenu = mysqli_real_escape_string($conn, $_POST['contenu']);
    $image = $post['image'];  

     
    if (!empty($_FILES['image']['name'])) {
        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $image_path = 'images/' . $image_name;

        
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            echo 'Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.';
            exit();
        }

         
        if (!empty($post['image']) && file_exists('images/' . $post['image'])) {
            unlink('images/' . $post['image']);
        }

         
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = $image_name;
        } else {
            echo 'Erreur lors du téléchargement de l\'image.';
            exit();
        }
    }

     
    $sql_update = "UPDATE posts 
                   SET titre = '$titre', contenu = '$contenu', image = '$image', date_creation = NOW() 
                   WHERE id = $post_id AND utilisateur_id = $user_id";

    if (mysqli_query($conn, $sql_update)) {
        header('Location: gestion_posts.php');
        exit();
    } else {
        echo 'Erreur lors de la mise à jour du post : ' . mysqli_error($conn);
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
        <h1>Modifier le post</h1>

        <form action="edit_post.php?id=<?php echo $post_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($post['titre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="contenu">Description :</label>
                <textarea id="contenu" name="contenu" rows="5" required><?php echo htmlspecialchars($post['contenu']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image (facultatif) :</label>
                <input type="file" id="image" name="image">
                <?php if (!empty($post['image'])): ?>
                    <p>Image actuelle :</p>
                    <img src="images/<?php echo htmlspecialchars($post['image']); ?>" alt="Image du post" class="post-image-preview">
                <?php endif; ?>
            </div>

            <button type="submit" class="submit-button">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<?php include('include/pied.php'); ?>

<!-- Styles -->
<style>
    /* Structure flexible pour garantir que le pied de page reste en bas lorsque nécessaire */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }

    .dashboard-container {
        display: flex;
        margin-top: 80px;
        flex: 1; /* Prend l'espace disponible entre l'en-tête et le pied */
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
        margin-left: 240px; /* Augmenter la marge pour mieux espacer la barre latérale */
        padding: 20px;
        width: calc(100% - 240px); /* Ajustement de la largeur en fonction de la nouvelle marge */
    }

    h1 {
        color: #6a1b9a;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-size: 16px;
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"], textarea {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    input[type="file"] {
        margin-top: 10px;
    }

    .post-image-preview {
        width: 100px;
        height: auto;
        margin-top: 10px;
        border-radius: 5px;
    }

    .submit-button {
        background-color: #6a1b9a;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .submit-button:hover {
        background-color: #4a148c;
    }

    /* Pied de page ajusté */
    footer {
        background-color: #4a148c;
        color: white;
        text-align: center;
        padding: 10px;
        position: relative;
        width: 100%;
    }
</style>

