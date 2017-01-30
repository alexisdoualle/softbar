<?php
//récupère les données JSON du POST et les met dans $data:
$data = json_decode(file_get_contents("php://input"));

//connexion à la db:
include 'connexiondb.php';

$produit = mysqli_real_escape_string($conn,$data->produit);
$nouveauNom = mysqli_real_escape_string($conn,$data->nouveauNom);
$nouveauPrix = mysqli_real_escape_string($conn,$data->nouveauPrix);


$sql = "UPDATE Stock SET `produit`='".$nouveauNom."', `prix`=".$nouveauPrix." WHERE `produit`='".$produit."'";

if(mysqli_query($conn,$sql)) {
  echo "Mise à jour des données réussie";
} else {
  echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
}


mysqli_close($conn);
?>
