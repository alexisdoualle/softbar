<?php
ini_set('display_errors',1);
$data = json_decode(file_get_contents("php://input"));

//connexion à la db:
$conn = new mysqli("localhost", "root", "root", "villalemons");
if ($conn->connect_error) {
    die("Connection échouée: " . $conn->connect_error);
}

$produit = mysqli_real_escape_string($conn,$data->produit);
$vendeur = mysqli_real_escape_string($conn,$data->vendeur);

$sql = "INSERT INTO `Ventes`(`vendeur`, `produit`) VALUES ('alex', '".$produit."')";


if(mysqli_query($conn,$sql)) {
  echo "Mise à jour des données réussie dans 'Ventes'";
} else {
  echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
