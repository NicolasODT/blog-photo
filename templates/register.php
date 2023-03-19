<?php
session_start();

// Vérification que les champs requis ont été remplis
if (
    isset($_POST["email"]) && $_POST["email"] != ""
    && isset($_POST["password"]) && $_POST["password"] != ""
    && isset($_POST["password2"]) && $_POST["password2"] != ""
) {

    // Vérification que les deux mots de passe correspondent
    if ($_POST["password"] == $_POST["password2"]) {
        
        // Validation de l'adresse email
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            echo "<p>Adresse email invalide</p>";
            exit();
        }

        // Vérification que le mot de passe est suffisamment long
        if (strlen($_POST["password"]) < 8) {
            echo "<p>Le mot de passe doit contenir au moins 8 caractères</p>";
            exit();
        }

        // Nettoyage des données
        $email = htmlspecialchars(trim($_POST["email"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $options = [
            'cost' => 12,
        ];
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT, $options);
        $pseudo = htmlspecialchars(trim(isset($_POST["pseudo"]) ? $_POST["pseudo"] : ""));
        $ville = htmlspecialchars(trim(isset($_POST["ville"]) ? $_POST["ville"] : ""));
        $pays = htmlspecialchars(trim(isset($_POST["pays"]) ? $_POST["pays"] : ""));

        require_once "../core/includes/connect.php";

        // Construction de la requête SQL d'insertion
        $sql = "INSERT INTO utilisateur (email, hash, pseudo, ville, pays) VALUES (:email,
        :password, :pseudo, :ville, :pays);";

        $query = $bdd->prepare($sql);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":password", $password, PDO::PARAM_STR);
        $query->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $query->bindParam(":ville", $ville, PDO::PARAM_STR);
        $query->bindParam(":pays", $pays, PDO::PARAM_STR);

        if ($query->execute()) {
            echo "<p>Le compte a bien été créé</p>";
            header('location: ../index.php');
        } else {
            echo "<p>Une erreur s'est produite</p>";
        }
    } else {
        echo "<p>mots de passe différents</p>";
    }
}
require_once '../core/includes/header.php';
?>

<main>
    <form action="" method="post" class="form-register">
        <input type="email" name="email" placeholder="Email *" required>
        <input type="password" name="password" placeholder="Mot de passe *" required>
        <input type="password" name="password2" placeholder="Confirmation mot de passe *" required>
        <input type="text" name="pseudo" placeholder="Pseudo">
        <input type="text" name="ville" placeholder="Ville">
        <input type="text" name="pays" placeholder="Pays">
        <button>Créer</button>
    </form>

</main>

<?php

require_once '../core/includes/footer.php';

?>