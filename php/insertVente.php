<?php
//récupère les données JSON du POST et les met dans $data:
$data = json_decode(file_get_contents("php://input"));
date_default_timezone_set('UTC+1');

//connexion à la db:
include 'connexiondb.php';

$produit = mysqli_real_escape_string($conn,$data->produit);
$vendeur = mysqli_real_escape_string($conn,$data->vendeur);
$dateVente = mysqli_real_escape_string($conn,$data->dateVente);
$offert = mysqli_real_escape_string($conn,$data->offert);
$facturer = mysqli_real_escape_string($conn,$data->facturer);

$sql = "INSERT INTO `Ventes`(`vendeur`, `produit`, `date_vente`, `offert`, `facturer`) VALUES ('".$vendeur."', '".$produit."','".$dateVente." ".date('H:i:s')."',".$offert.",".$facturer.")";
if(mysqli_query($conn,$sql)) { //envoie la requete
  echo "Mise à jour des données";
} else {
  echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
