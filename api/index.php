<?php 
  include_once '../include/config.php';

	header('Content-Type: application/json'); 
	header('Access-Control-Allow-Origin: *'); 

  $mysqli = new mysqli($host, $username, $password, $database);
  if ($mysqli -> connect_errno) {	
		echo 'Échec de connexion à la base de données MySQL: ' . $mysqli -> connect_error; 
		exit(); 
	} 

	switch($_SERVER['REQUEST_METHOD']) 
  { 
		case 'GET': // GESTION DES DEMANDES DE TYPE GET 
			if(isset($_GET['id'])) { 
				// CODE PERMETTANT DE RÉCUPÉRER L'ENREGISTREMENT CORRESPONDANT À L'IDENTIFIANT PASSÉ EN PARAMÈTRE
        if ($requete = $mysqli->prepare("SELECT * FROM produits WHERE id = ?")) {
        $requete->bind_param("i", $_GET['id']);
        $requete->execute();

        $resultat_requete = $requete->get_result(); 
        $objet = $resultat_requete->fetch_assoc();

        echo json_encode($objet, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $requete->close();
        } 
			} else { 
				// CODE PERMETTANT DE RÉCUPÉRER TOUT LES ENREGISTREMENTS 
        $requete = $mysqli->query('SELECT * FROM produits'); 
        $donnees_tableau = $requete->fetch_all(MYSQLI_ASSOC); 
        echo json_encode($donnees_tableau, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $requete->close();
			} 
			break; 
		case 'POST': // GESTION DES DEMANDES DE TYPE POST 
			// CODE PERMETTANT DE D'AJOUTER UN ENREGISTREMENT 
      $dataJSON = file_get_contents('php://input'); 
      $data = json_decode($dataJSON, TRUE);

      $reponse = new stdClass(); 
      $reponse->message = "Ajout du produit: "; 

      if(isset($data['nom']) && isset($data['description']) && isset($data['prix']) && isset($data['qteStock'])) {

        if ($requete = $mysqli->prepare("INSERT INTO produits(nom, description, prix, qteStock) VALUES(?, ?, ?, ?)")) { 
          $requete->bind_param("ssii", $data['nom'], $data['description'],$data['prix'],$data['qteStock']); 
          if($requete->execute()) { 
            $reponse->message .= "Succès"; 
          } else { 
            $reponse->message .= "Erreur dans l'exécution de la requête"; 
          } 
          $requete->close();
        } else { 
          $reponse->message .= "Erreur dans la préparation de la requête"; 
        } 
      } else { 
        $reponse->message .= "Erreur dans le corps de l'objet fourni"; 
      } 
      echo json_encode($reponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
			break; 
		case 'PUT': // GESTION DES DEMANDES DE TYPE PUT 
      // CODE PERMETTANT DE METTRE À JOUR L'ENREGISTREMENT CORRESPONDANT À L'IDENTIFIANT PASSÉ EN PARAMÈTRE 
      $reponse = new stdClass(); 
      $reponse->message = "Édition du client: "; 

      $dataJSON = file_get_contents('php://input'); 
      $data = json_decode($dataJSON, TRUE); 

      if(isset($_GET['id'])) { 
        if(isset($data['nom']) && isset($data['description']) && isset($data['prix']) && isset($data['qteStock'])) { 
          if ($requete = $mysqli->prepare("UPDATE produits SET nom=?, description=?, prix=?, qteStock=? WHERE id=?")) {
            $requete->bind_param("ssiii", $data['nom'], $data['description'],$data['prix'], $data['qteStock'], $_GET['id']); 
            if($requete->execute()) { 
              $reponse->message .= "Succès"; 
            } else { 
              $reponse->message .= "Erreur dans l'exécution de la requête"; 
            } 
            $requete->close();
          } else { 
            $reponse->message .= "Erreur dans la préparation de la requête"; 
          } 
        } else { 
          $reponse->message .= "Erreur dans le corps de l'objet fourni"; 
        } 
      } else { 
        $reponse->message .= "Erreur dans les paramètres (aucun identifiant fourni)"; 
      } 
      echo json_encode($reponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
			break; 
		case 'DELETE': // GESTION DES DEMANDES DE TYPE DELETE 
      $reponse = new stdClass(); 
      $reponse->message = "Suppression du client: ";

			if(isset($_GET['id'])) { 
				// CODE PERMETTANT DE SUPPRIMER L'ENREGISTREMENT CORRESPONDANT À L'IDENTIFIANT PASSÉ EN PARAMÈTRE 
        if ($requete = $mysqli->prepare("DELETE FROM produits WHERE id=?")) { 
          $requete->bind_param("i", $_GET['id']); 
          if($requete->execute()) { 
            $reponse->message .= "Succès"; 
          } else { 
            $reponse->message .= "Erreur dans l'exécution de la requête"; 
          } 
          $requete->close(); 
        } else { 
          $reponse->message .= "Erreur dans la préparation de la requête"; 
        } 
      } else { 
        $reponse->message .= "Erreur dans les paramètres (aucun identifiant fourni)"; 
      } 
      echo json_encode($reponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      break;
		default: 
      $reponse = new stdClass();
      $reponse->message = "Opération non supportée";	
      echo json_encode($reponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} 
$mysqli->close();
?>