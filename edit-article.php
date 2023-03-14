<?php
session_start();
if(isset($_SESSION['id']) && ($_SESSION['role'] == 'editeur' || $_SESSION['role'] == 'admin')) {
    if(isset($_GET['id_article'])) {
        require_once 'connect.php';
        $id = $_GET['id_article'];
        $sql = "SELECT * FROM Article WHERE id = :id";
        $query = $bdd->prepare($sql);
        $query->bindParam(":id", $id);
        $query->execute();
        $article = $query->fetch();
        if($article) {
            $titre = $article['titre'];
            $contenu = $article['contenu'];
            $slug = $article['slug'];
        } else {
            echo "Cet article n'existe pas.";
            exit();
        }
    } else {
        echo "L'identifiant de l'article n'est pas défini.";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer l'article</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php require_once 'header.php'; ?>
    <main class="main-form">
        <h1>Editer l'article</h1>
        <form method="post" action="update_article.php">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div>
                <label for="titre">Titre :</label>
                <input type="text" name="titre" id="titre" value="<?= $titre ?>">
            </div>
            <div>
                <label for="contenu">Contenu :</label>
                <textarea name="contenu" id="contenu" rows="10"><?= $contenu ?></textarea>
            </div>
            <div>
                <label for="slug">Slug :</label>
                <input type="text" name="slug" id="slug" value="<?= $slug ?>">
            </div>
            <button>Enregistrer</button>
        </form>
    </main>
    <?php require_once 'footer.php'; ?>
</body>
</html>
