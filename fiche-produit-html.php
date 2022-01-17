<?php
include_once 'include/config.php';

if (!isset($_GET['id'])) {
  echo "Id's missing";
  exit();
}

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli -> connect_error) {
  echo "Connection failed: " . $mysqli -> connect_error;
  exit();
}

if ($req = $mysqli->prepare("SELECT * FROM produits WHERE id = ?")) {// Création d'une requête préparée 

  $req->bind_param("i", $_GET['id']); // Envoi des paramètres à la requête 
  $req->execute(); // Exécution de la requête 

  $res = $req->get_result(); // Récupération de résultats de la requête 	
  $produit = $res->fetch_assoc();
  $req->close(); // Fermeture du traitement
} $mysqli->close();?>

  <h1><?= $produit["nom"] ?></h1>
  <p>Description: <?= $produit["description"] ?></p>
  <p>Prix: <?= $produit["prix"] ?>$</p>
