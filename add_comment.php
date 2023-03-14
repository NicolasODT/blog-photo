<?php
session_start();
require_once 'connect.php';

if (isset($_POST['message']) && isset($_POST['id_article'])) {
    $message = $_POST['message'];
    $id_article = $_POST['id_article'];

    if (isset($_SESSION['id']) && (($_SESSION['role'] == 'utilisateur') || ($_SESSION['role'] == 'editeur') || ($_SESSION['role'] == 'admin'))) {
        $user_id = $_SESSION['id'];
        $sql = "INSERT INTO commentaire (message, id_article, id_utilisateur) VALUES (:message, :id_article, :user_id)";
        $query = $bdd->prepare($sql);
        $query->bindParam(":message", $message);
        $query->bindParam(":id_article", $id_article);
        $query->bindParam(":user_id", $user_id);
        $query->execute();

        header("Location: article.php?slug=" . $_GET["slug"]);
        exit();
    } else {
        echo "Vous devez être connecté et avoir un rôle autorisé pour ajouter un commentaire.";
    }
} else {
    echo "Une erreur s'est produite lors de l'ajout du commentaire.";
}
?>