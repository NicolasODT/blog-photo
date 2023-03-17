<?php
session_start();
require_once '../includes/connect.php';

if ($_SESSION["role"] != "admin") {
    header("Location: index.php");
}

$id = $_POST['id'];
$actif = $_POST['actif'];

$query = "UPDATE Utilisateur SET actif = :actif WHERE id = :id";
$stmt = $bdd->prepare($query);
$stmt->bindParam(':actif', $actif);
$stmt->bindParam(':id', $id);
$stmt->execute();

header("Location: ../../templates/panel.php");
exit;
