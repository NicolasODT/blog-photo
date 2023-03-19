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
        <input type="search" name="search" id="" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button>Recherche <i class="fa-solid fa-magnifying-glass"></i></button>
    </form>

    <div class="flex-card">
        <?php
        require_once './core/includes/connect.php';

        // Définition des variables de pagination
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        // Construction de la requête SQL en fonction de la présence ou non d'un mot-clé de recherche
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id WHERE a.titre LIKE '%$search%' OR u.pseudo LIKE '%$search%' ORDER BY a.date_creation DESC LIMIT $limit OFFSET $offset";
        } else {
            $sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id ORDER BY a.date_creation DESC LIMIT $limit OFFSET $offset";
        }
        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        // Affichage des cartes d'articles
        if (count($result) > 0) {
            foreach ($result as $row) {
        ?>
                <div class="card-article">
                    <img src="<?= $row["image"] ?>" alt="">
                    <div class="card-article-text">
                        <h2><?= $row["titre"]; ?></h2>
                        <p><?= strip_tags(substr($row["contenu"], 0, 50)); ?>...</p>
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

    <?php

    // Construction de la chaîne de requête pour la pagination en fonction de la présence ou non d'un mot-clé de recherche
    $search_query = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
    ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <!-- Lien vers la page précédente si la page actuelle n'est pas la première -->
            <a href="?page=<?= $page - 1 . $search_query ?>" class="pagination-prev"> précédents</a>
        <?php endif; ?>
        <!-- Lien vers la page suivante -->
        <a href="?page=<?= $page + 1 . $search_query ?>" class="pagination-next"> suivants</a>
    </div>
</main>

<?php
require_once './core/includes/footer.php';
?>
