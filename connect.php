<?php 

$serveur = "localhost";
$utilisateur = "root";
$mdp = "";
$nom_bdd = "blogphoto";

  $bdd = new PDO("mysql:host=$serveur;dbname=$nom_bdd", $utilisateur, $mdp);