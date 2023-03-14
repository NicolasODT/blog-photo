<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="imgLogoFond">
<header>
    <ul>
        <li><a href="./index.php">Accueil</a></li>
        <?php if (isset($_SESSION["id"])) {
                if ($_SESSION["role"] == "admin") { ?>
                    <li><a href="./panel.php">Panel</a></li>
                <?php }
                if ($_SESSION["role"] == "admin" || $_SESSION["role"] == "editeur") { ?>
                    <li><a href="./add-article.php">Ajouter un article</a></li>
                <?php }
                ?>
                <li><a href="./profil.php">Profil</a></li>
                <li><a href="./logout.php">Déconnexion</a></li>
        <?php } else { ?>
            <li><a href="./login.php">Connexion</a></li>
            <li><a href="./register.php">Inscription</a></li>
        <?php } ?>
    </ul>
</header>


    <main>