<?php
session_start();
require_once '../includes/connect.php';

if ($_SESSION["role"] != "admin") {
    header("Location: ../../index.php");
}

$id = $_POST['id'];
$role = $_POST['role'];

$query = "UPDATE Utilisateur SET role = :role WHERE id = :id";
$stmt = $bdd->prepare($query);
$stmt->bindParam(':role', $role);
$stmt->bindParam(':id', $id);
$stmt->execute();


header("Location: ../../templates/panel.php");
exit;
