<?php
//récupère les données JSON du POST et les met dans $data:
$data = json_decode(file_get_contents("php://input"));

//connexion à la db:
include 'connexiondb.php';

$nouveauProduit = mysqli_real_escape_string($conn,$data->nouveauProduit);
$nouveauPrix = mysqli_real_escape_string($conn,$data->nouveauPrix);
$nouveauStock = mysqli_real_escape_string($conn,$data->nouveauStock);

$sql = "INSERT INTO `Stock`(`produit`, `prix`, `quantite`) VALUES ('".$nouveauProduit."', ".$nouveauPrix.",".$nouveauStock.")";


if(mysqli_query($conn,$sql)) {
  echo "Mise à jour des données réussie";
} else {
  echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
