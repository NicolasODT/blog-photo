<?php
session_start();
if (isset($_GET["slug"])) {
    require_once "connect.php";

    $slug = $_GET["slug"];

    $sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id WHERE a.slug = :slug";
    $query = $bdd->prepare($sql);
    $query->bindParam(":slug", $slug);
    $query->execute();
    $article = $query->fetch();

    if ($article) {
        require_once 'header.php';
?>

<main class="main-article">
    <img class="img-article" src="<?= $article["image"] ?>" alt="<?= $article["titre"] ?>">
    <h2><?= $article["titre"] ?></h2>
    <p><?= $article["contenu"] ?></p>

    <?php if (isset($_SESSION["id"])) { ?>
        <form method="post" action="add_comment.php?slug=<?= $slug ?>">
            <label for="message">Laissez un commentaire :</label>
            <textarea name="message" id="message" rows="4" cols="50"></textarea>
            <input type="hidden" name="id_article" value="<?= $article["id"] ?>">
            <button>Envoyer</button>
        </form>
    <?php } ?>

    <section class="commentaires">
        <h3>Commentaires</h3>
        <?php
        $sql = "SELECT c.*, u.pseudo FROM Commentaire c JOIN Utilisateur u ON c.id_utilisateur = u.id WHERE c.id_article = :id_article ORDER BY c.date_creation DESC";
        $query = $bdd->prepare($sql);
        $query->bindParam(":id_article", $article["id"]);
        $query->execute();
        $commentaires = $query->fetchAll();

        if (count($commentaires) > 0) {
            foreach ($commentaires as $commentaire) {
        ?>
                <div class="commentaire">
                    <h4><?= $commentaire["pseudo"] ?></h4>
                    <p><?= $commentaire["message"] ?></p>
                    <p class="date"><?= $commentaire["date_creation"] ?></p>
                </div>
        <?php
            }
        } else {
            echo "Aucun commentaire pour le moment.";
        }
        ?>
    </section>

    <?php if (isset($_SESSION["id"]) && ($_SESSION["role"] == "editeur" || $_SESSION["role"] == "admin")) { ?>
        <div class="edit-link">
        <a href="edit-article.php?id_article=<?= $article['id'] ?>">Editer</a>
        </div>
    <?php } ?>
</main>

<?php
    } else {
        echo "L'article n'existe pas.";
    }
} else {
    header("Location: index.php");
    exit();
}
?>
