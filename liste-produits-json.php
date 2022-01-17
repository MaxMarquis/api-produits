<?php
	header('Content-Type: application/json');
	include_once 'include/config.php';

	$mysqli = new mysqli($host, $username, $password, $database);

	if ($mysqli -> connect_errno) {	
		echo 'Échec de connexion à la base de données MySQL: ' . $mysqli -> connect_error; 
		exit(); 
	} 

	$res = $mysqli->query('SELECT * FROM produits'); 

	$donnees_tableau = $res->fetch_all(MYSQLI_ASSOC); 
	echo json_encode($donnees_tableau, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

	$mysqli->close(); 
?>