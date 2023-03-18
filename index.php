<?php
session_start();

require_once './core/includes/header.php';

?>

<main>
    <div class="logotext">
        <img src="./public/media/FOCALE_CREATIVE.jpg" alt="">
        <h1>FOCALE CREATIVE</h1>
    </div>
    <form action="" method="get">
        <input type="search" name="search" id="">
        <button>Recherche <i class="fa-solid fa-magnifying-glass"></i></button>
    </form>

    <div class="flex-card">
        <?php
        require_once './core/includes/connect.php';
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id WHERE a.titre LIKE '%$search%' OR u.pseudo LIKE '%$search%' ORDER BY a.date_creation DESC LIMIT 10";
        } else {
            $sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id ORDER BY a.date_creation DESC LIMIT 10";
        }

        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) > 0) {
            foreach ($result as $row) {
        ?>
                <div class="card-article">
                    <img src="<?= $row["image"] ?>" alt="">
                    <div class="card-article-text">
                        <h2><?= $row["titre"]; ?></h2>
                        <p><?= substr($row["contenu"], 0, 50); ?>...</p>
                        <p><?= $row["pseudo"] ?? ""; ?></p>
                        <p><a href="./templates/article.php?slug=<?= $row["slug"] ?>">Lien vers l'article</a></p>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "Aucun article trouvé.";
        }
        ?>
    </div>
</main>

<?php
require_once './core/includes/footer.php';

?>