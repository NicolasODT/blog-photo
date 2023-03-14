<?php
session_start();
require_once 'connect.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

// Récupère l'utilisateur
$query = "SELECT id, pseudo, email, ville, pays FROM Utilisateur WHERE id = ?";
$stmt = $bdd->prepare($query);
$stmt->execute([$_SESSION['id']]);
$utilisateur = $stmt->fetch();

// Met à jour les informations de l'utilisateur
if (isset($_POST['submit'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $ville = $_POST['ville'];
    $pays = $_POST['pays'];

    $query = "UPDATE Utilisateur SET hash = ?, ville = ?, pays = ? WHERE id = ?";
    $stmt = $bdd->prepare($query);
    $stmt->execute([$password, $ville, $pays, $_SESSION['id']]);

    header("Location: profil.php");
    exit;
}

require_once 'header.php';

?>

<body>
    <h1>Profil de <?= $utilisateur['pseudo'] ?></h1>
    <form action="" method="post">
        <label for="password">Nouveau mot de passe:</label>
        <input type="password" name="password" id="password">

        <label for="ville">Ville:</label>
        <input type="text" name="ville" id="ville" value="<?= $utilisateur['ville'] ?>">

        <label for="pays">Pays:</label>
        <input type="text" name="pays" id="pays" value="<?= $utilisateur['pays'] ?>">

        <button name="submit">Enregistrer</button>
    </form>
</body>

</html>