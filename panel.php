<?php
session_start();
require_once 'connect.php';

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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
</head>
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
                        <form action="modifier_utilisateur.php" method="post">
                            <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
                            <select name="role">
                                <option value="utilisateur" <?= $utilisateur['role'] == 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="editeur" <?= $utilisateur['role'] == 'editeur' ? 'selected' : '' ?>>Editeur</option>
                                <option value="admin" <?= $utilisateur['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button>Modifier le rôle</button>
                        </form>
                        <form action="desactiver_utilisateur.php" method="post">
                            <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
                            <input type="hidden" name="actif" value="<?= $utilisateur['actif'] ? 0 : 1 ?>">
                            <button><?= $utilisateur['actif'] ? 'Désactiver' : 'Activer' ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</body>
</html>
