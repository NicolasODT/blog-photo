<?php

session_start();

function slugify($text)
{
    // Remplace les caractères spéciaux par des tirets
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Convertit en minuscules
    $text = mb_strtolower($text, 'UTF-8');

    // Supprime tout caractère non alphanumérique ou tiret en début et fin de chaîne
    $text = trim($text, '-');

    // Supprime les doubles tirets
    $text = preg_replace('~-+~', '-', $text);

    // Retourne la chaîne convertie
    return $text;
}

if (
    isset($_POST["titre"]) && $_POST["titre"] != ""
    && isset($_POST["story"]) && $_POST["story"] != ""
    && isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK
) {

    $titre = trim(htmlspecialchars($_POST["titre"]));
    $story = trim(htmlspecialchars($_POST["story"]));

    $utilisateur_id = $_SESSION['id'];

    $target_dir = "../../public/media/";
    $image_name = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $image_name;
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);


    // Générer le slug
    $slug = slugify($titre);

    //connexion DB
    require_once "../includes/connect.php";

    $sql = "INSERT INTO article (titre, contenu, image, id_utilisateur, slug) VALUES (:titre,
    :story, :file_path, :utilisateur_id, :slug);";

    $query = $bdd->prepare($sql);
    $query->bindParam(":titre", $titre, PDO::PARAM_STR);
    $query->bindParam(":story", $story, PDO::PARAM_STR);
    $query->bindParam(":file_path", $target_file, PDO::PARAM_STR);
    $query->bindParam(":utilisateur_id", $utilisateur_id, PDO::PARAM_INT);
    $query->bindParam(":slug", $slug, PDO::PARAM_STR);


    if ($query->execute()) {
        echo "<p>L'article a bien été créé</p>";
        header('location: /index.php');
    } else {
        echo "<p>Une erreur s'est produite</p>";
    }
}
require_once '../includes/header.php';
?>
<form action="" method="post" enctype="multipart/form-data" class="form-add-article">
    <input type="text" name="titre" id="" placeholder="Titre de l'article">
    <textarea placeholder="Entrez votre article ici..." id="story" name="story"></textarea>
    <input type="file" name="file">
    <button>Envoyer</button>
</form>


<?php
require_once '../includes/footer.php';
