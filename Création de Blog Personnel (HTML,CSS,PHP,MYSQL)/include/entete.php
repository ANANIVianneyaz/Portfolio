<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Tché</title>
    <style>
        /* Style de l'en-tête */
        header {
            background-color: #6a1b9a; /* Couleur de fond violette */
            color: white;
            display: flex;
            justify-content: space-between; /* Espacement entre le titre et le bouton */
            align-items: center;
            padding: 15px 20px; /* Ajustement de la taille du padding */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Ombre portée */
            position: fixed; /* Fixe l'en-tête en haut de la page */
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Ombre du texte du titre */
        }

        .logout-button {
            background-color: #9c4d97;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-right: 20px; /* Espace à droite pour ne pas être trop collé au bord */
        }

        .logout-button:hover {
            background-color: #7e3575;
        }

        /* Ajouter un espace pour compenser l'en-tête fixe */
        body {
            padding-top: 60px; /* Assurez-vous que le contenu n'est pas caché sous l'en-tête */
        }
    </style>
</head>
<body>

<header>
    <div class="title">Blog Tché</div>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="traitement/deconnexion.php" class="logout-button">Se déconnecter</a>
    <?php endif; ?>
</header>

</body>
</html>
