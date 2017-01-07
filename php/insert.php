<?php
ini_set('display_errors',1);
$data = json_decode(file_get_contents("php://input"));

//connexion à la db:
$conn = new mysqli("localhost", "root", "root", "villalemons");
if ($conn->connect_error) {
    die("Connection échouée: " . $conn->connect_error);
}

$produit = mysqli_real_escape_string($conn,$data->produit);
$stock = mysqli_real_escape_string($conn,$data->stock);
$prix = mysqli_real_escape_string($conn,$data->prix);
$ventes = mysqli_real_escape_string($conn,$data->ventes);


$sql = "UPDATE CaisseVL SET `stock`=$stock, `ventes`=$ventes WHERE `produit`='".$produit."'";


if(mysqli_query($conn,$sql)) {
  echo "Mise à jour des données réussie";
} else {
  echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
