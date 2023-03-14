<?php
session_start();

if(isset($_SESSION['id']) && ($_SESSION['role'] == 'editeur' || $_SESSION['role'] == 'admin')) {
    if(isset($_POST['id']) && isset($_POST['titre']) && isset($_POST['contenu']) && isset($_POST['slug'])) {
        require_once 'connect.php';
        $id = $_POST['id'];
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];
        $slug = $_POST['slug'];

        $sql = "UPDATE Article SET titre = :titre, contenu = :contenu, slug = :slug WHERE id = :id";
        $query = $bdd->prepare($sql);
        $query->bindParam(":id", $id);
        $query->bindParam(":titre", $titre);
        $query->bindParam(":contenu", $contenu);
        $query->bindParam(":slug", $slug);
        $query->execute();

        header("Location: article.php?id_article=" . $id);
        exit();
    } else {
        echo "Tous les champs ne sont pas renseignés.";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
