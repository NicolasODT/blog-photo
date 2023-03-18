<?php
session_start();
require_once '../core/includes/connect.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

// Récupère l'utilisateur
$query = "SELECT id, pseudo, email, ville, pays FROM Utilisateur WHERE id = ?";
$stmt = $bdd->prepare($query);
$stmt->execute([$_SESSION['id']]);
$utilisateur = $stmt->fetch();

// Récupère les commentaires de l'utilisateur
$limit = 4;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT c.*, a.titre AS article_titre, a.image AS article_image FROM Commentaire c JOIN Article a ON c.id_article = a.id WHERE c.id_utilisateur = :id_utilisateur ORDER BY c.date_creation DESC LIMIT :limit OFFSET :offset";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(":id_utilisateur", $_SESSION['id']);
$stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
$stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$commentaires = $stmt->fetchAll();

// Met à jour les informations de l'utilisateur
if (isset($_POST['submit'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $ville = htmlspecialchars($_POST['ville']);
    $pays = htmlspecialchars($_POST['pays']);

    $query = "UPDATE Utilisateur SET hash = ?, ville = ?, pays = ? WHERE id = ?";
    $stmt = $bdd->prepare($query);
    $stmt->execute([$password, $ville, $pays, $_SESSION['id']]);

    header("Location: profil.php");
    exit;
}

require_once '../core/includes/header.php';

?>

<h1>Profil de <?= $utilisateur['pseudo'] ?></h1>
<form action="" method="post" class="form-profil">
    <label for="E-mail">E-mail</label>
    <input type="email" name="email" id="email" value="<?=$utilisateur['email']?>" disabled>
    <label for="password">Nouveau mot de passe:</label>
    <input type="password" name="password" id="password" placeholder="*********" required>
    <label for="pseudo">Pseudo</label>
    <input type="pseudo" name="pseudo" id="pseudo" value="<?=$utilisateur['pseudo']?>" disabled>
    <label for="ville">Ville:</label>
    <input type="text" name="ville" id="ville" value="<?= $utilisateur['ville'] ?>">
    <label for="pays">Pays:</label>
    <input type="text" name="pays" id="pays" value="<?= $utilisateur['pays'] ?>">
    <button name="submit">Enregistrer</button>
</form>
<section class="commentaires">
    <h2>Vos commentaires</h2>
    <?php
    if (count($commentaires) > 0) {
        foreach ($commentaires as $commentaire) {
    ?>
            <div class="commentaire">
                <h3>Article : <?= $commentaire["article_titre"] ?></h3>
                <img src="<?= $commentaire["article_image"] ?>" alt="<?= $commentaire["article_titre"] ?>" style="width: 100px; height: auto;">
                <p><?= $commentaire["message"] ?></p>
                <p class="date"><?= $commentaire["date_creation"] ?></p>
            </div>
    <?php
        }
    } else {
        echo "Aucun commentaire trouvé.";
    }
    ?>
</section>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="pagination-prev"> précédents</a>
    <?php endif; ?>
    <?php if (count($commentaires) == $limit): ?>
        <a href="?page=<?= $page + 1 ?>" class="pagination-next"> suivants</a>
    <?php endif; ?>
</div>

<?php
require_once '../core/includes/footer.php';
?>