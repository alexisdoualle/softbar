<?php
ini_set('display_errors',1);
$data = json_decode(file_get_contents("php://input"));

//connexion à la db:
include 'connexion_db.php';

$produit = mysqli_real_escape_string($conn,$data->produit);
$quantite = mysqli_real_escape_string($conn,$data->quantite);
$prix = mysqli_real_escape_string($conn,$data->prix);
$caisse = mysqli_real_escape_string($conn,$data->caisse);

$sql = "UPDATE Stock SET `quantite`=$quantite WHERE `produit`='".$produit."'";
$sql2 = "UPDATE Caisse SET `fond_de_caisse`=$caisse";

if(mysqli_query($conn,$sql)) {
  echo "Mise à jour des données réussie";
} else {
  echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
}
if(mysqli_query($conn,$sql2)) {
  echo "Mise à jour des données caisse réussie";
} else {
  echo "Erreur dans la mise à jour des données caisse: " . mysqli_error($conn);
}


mysqli_close($conn);

?>
