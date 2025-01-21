<?php
session_start();
 
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}
 
include('include/connection_db.php');
 
$error = '';

 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = mysqli_real_escape_string($conn, $_POST['titre']);
    $contenu = mysqli_real_escape_string($conn, $_POST['contenu']);
    $utilisateur_id = $_SESSION['user_id'];

     
    if (empty($titre) || empty($contenu)) {
        $error = 'Le titre et le contenu sont obligatoires.';
    } else {
         
        $image_name = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $original_image_name = basename($_FILES['image']['name']);
            $image_extension = pathinfo($original_image_name, PATHINFO_EXTENSION);

             
            $unique_image_name = uniqid('img_', true) . '.' . $image_extension;
            $image_path = 'images/' . $unique_image_name;

            
            if (!move_uploaded_file($image_tmp_name, $image_path)) {
                $error = "Erreur lors de l'upload de l'image.";
            } else {
                $image_name = $unique_image_name;  
            }
        }

        
        if (empty($error)) {
            $sql = "INSERT INTO posts (utilisateur_id, titre, contenu, image, date_creation) 
                    VALUES ('$utilisateur_id', '$titre', '$contenu', '$image_name', NOW())";

            if (mysqli_query($conn, $sql)) {
                 
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Erreur lors de la création du post : ' . mysqli_error($conn);
            }
        }
    }
}
?>

<?php include('include/entete.php'); ?>

<div class="create-post-container">
    <h1>Créer un nouveau post</h1>

    <!-- Afficher les messages d'erreur -->
    <?php if (!empty($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="create_new_post.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" id="titre" required>
        </div>

        <div class="form-group">
            <label for="contenu">Description :</label>
            <textarea name="contenu" id="contenu" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="image">Ajouter une image (optionnel) :</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        <button type="submit" class="btn-submit">Publier</button>
    </form>
</div>

<?php include('include/pied.php'); ?>

<!-- Styles spécifiques pour la création d'un post -->
<style>
    .create-post-container {
        width: 60%;
        margin: 80px auto;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .create-post-container h1 {
        text-align: center;
        color: #6a1b9a;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }

    .form-group input, 
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-group input[type="file"] {
        padding: 5px;
    }

    .btn-submit {
        display: block;
        width: 100%;
        background-color: #6a1b9a;
        color: white;
        font-size: 18px;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background-color: #4a148c;
    }

    .error-message {
        color: #d32f2f;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
    }
</style>
