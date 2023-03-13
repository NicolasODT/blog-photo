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
<body>
    <header>
        <ul>
            <li><a href="#">Accueil</a></li>
            <li><a href="#">Articles</a></li>
            <li><a href="#">Connexion</a></li>
            <li><a href="#">Inscription</a></li>
        </ul>
    </header>
    <main>
        <div class="logotext">
            <img src="./media/FOCALE_CREATIVE.jpg" alt="">
            <h1>FOCALE CREATIVE</h1>
        </div>
        <form action="">
            <input type="search" name="" id="">
            <button>Recherche <i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <div class="flex-card">
        <?php
require_once 'connect.php';
$sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id ORDER BY a.date_creation DESC LIMIT 10";
$stmt = $bdd->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

if (count($result) > 0) {
    foreach($result as $row) {
?>
<div class="card-article">
    <img src="<?= $row["image"]; ?>" alt="">
    <div class="card-article-text">
        <h2><?= $row["titre"]; ?></h2>
        <p><?= substr($row["contenu"], 0, 50); ?>...</p>
        <p><?= $row["pseudo"] ?? ""; ?></p>
        <p><a href="<?php echo $row["slug"]; ?>">Lien vers l'article</a></p>
    </div>
</div>
<?php
    }
}
?>
    </div>
</main>
<footer>

</footer>
