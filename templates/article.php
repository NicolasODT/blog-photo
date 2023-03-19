<?php
session_start();
if (isset($_GET["slug"])) {
    require_once "../core/includes/connect.php";

    $slug = $_GET["slug"];

    // Recherche l'article correspondant au slug dans la base de données
    $sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id WHERE a.slug = :slug";
    $query = $bdd->prepare($sql);
    $query->bindParam(":slug", $slug);
    $query->execute();
    $article = $query->fetch();

    if ($article) {
        require_once '../core/includes/header.php';
?>

        <main class="main-article">
            <img class="img-article" src="<?= $article["image"] ?>" alt="<?= $article["titre"] ?>">
            <h2><?= $article["titre"] ?></h2>
            <p class="message-article"><?= $article["contenu"] ?></p>

            <?php if (isset($_SESSION["id"])) { ?>
                <!-- Formulaire pour ajouter un commentaire -->
                <form method="post" action="../core/actions/add_comment.php?slug=<?= $slug ?>">
                    <label for="message">Laissez un commentaire :</label>
                    <textarea name="message" id="message" rows="4" cols="50"></textarea>
                    <input type="hidden" name="id_article" value="<?= $article["id"] ?>">
                    <button>Envoyer</button>
                </form>
            <?php } ?>

            <section class="commentaires">
                <h3>Commentaires</h3>
                <?php
                // Récupère les commentaires de l'article depuis la base de données
                $sql = "SELECT c.*, u.pseudo FROM Commentaire c JOIN Utilisateur u ON c.id_utilisateur = u.id WHERE c.id_article = :id_article ORDER BY c.date_creation DESC";
                $query = $bdd->prepare($sql);
                $query->bindParam(":id_article", $article["id"]);
                $query->execute();
                $commentaires = $query->fetchAll();

                if (count($commentaires) > 0) {
                    // Affiche chaque commentaire
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
                <!-- Lien pour éditer l'article si l'utilisateur est un éditeur ou un administrateur -->
                <div class="edit-link">
                    <a href="../core/actions/edit-article.php?id_article=<?= $article['id'] ?>">Editer</a>
                </div>
            <?php } ?>
        </main>

<?php
    } else {
        echo "L'article n'existe pas.";
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>