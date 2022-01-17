<?php
header('Content-Type: application/json');
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

if ($requete = $mysqli->prepare("SELECT * FROM produits WHERE id = ?")) {// Création d'une requête préparée 

  $requete->bind_param("i", $_GET['id']); // Envoi des paramètres à la requête 
  $requete->execute(); // Exécution de la requête 

  $resultat_requete = $requete->get_result(); // Récupération de résultats de la requête 			
  $objet = $resultat_requete->fetch_assoc(); // Récupération de l'enregistrement 
  echo json_encode($objet, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // Transmission de l’objet au format JSON
  $requete->close(); // Fermeture du traitement
} 

$mysqli->close();
?>