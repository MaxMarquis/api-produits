<?php
include_once 'include/config.php';
$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli -> connect_error) {
  echo "Connection failed: " . $mysqli -> connect_error;
  exit();
}

$req = "SELECT * FROM produits";
$res = $mysqli->query($req);

echo "<ul>";
  if ($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
      echo "<li>Nom: " . $row["nom"]. " - Description: " . $row["description"]. " - Prix" . $row["prix"]. " - Quantité: " . $row["qteStock"]. "</li><br>";
    }
  } else {
    echo "0 results";
  }
echo "</ul>";
$mysqli->close();
?>