<?php
session_start();
require_once '../core/includes/connect.php';


if ($_SESSION["role"] != "admin") {
    header("Location: ../index.php");
}

// Récupère la liste des utilisateurs
$query = "SELECT id, pseudo, email, role, actif FROM Utilisateur";
$stmt = $bdd->query($query);
$utilisateurs = $stmt->fetchAll();

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT id, pseudo, email, role, actif FROM Utilisateur WHERE pseudo LIKE '%$search%' OR email LIKE '%$search%'";
    $stmt = $bdd->query($query);
    $utilisateurs = $stmt->fetchAll();
}
require_once '../core/includes/header.php';
?>


<body>
    <h1>Liste des utilisateurs</h1>

    <form action="" method="get">
        <label for="search">Recherche</label>
        <input type="text" name="search" id="search">
        <button>Rechercher</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actif</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur) : ?>
                <tr>
                    <td><?= $utilisateur['pseudo'] ?></td>
                    <td><?= $utilisateur['email'] ?></td>
                    <td><?= $utilisateur['role'] ?></td>
                    <td><?= $utilisateur['actif'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <form action="../core/actions/modifier_utilisateur.php" method="post">
                            <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
                            <select name="role">
                                <option value="utilisateur" <?= $utilisateur['role'] == 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="editeur" <?= $utilisateur['role'] == 'editeur' ? 'selected' : '' ?>>Editeur</option>
                                <option value="admin" <?= $utilisateur['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button>Modifier le rôle</button>
                        </form>
                        <form action="../core/actions/desactiver_utilisateur.php" method="post">
                            <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
                            <input type="hidden" name="actif" value="<?= $utilisateur['actif'] ? 0 : 1 ?>">
                            <button><?= $utilisateur['actif'] ? 'Désactiver' : 'Activer' ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <?php
    require_once '../core/includes/footer.php';
