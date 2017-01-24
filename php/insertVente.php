<?php
//récupère les données JSON du POST et les met dans $data:
$data = json_decode(file_get_contents("php://input"));
date_default_timezone_set('UTC+1');

//connexion à la db:
include 'connexiondb.php';

$produit = mysqli_real_escape_string($conn,$data->produit);
$vendeur = mysqli_real_escape_string($conn,$data->vendeur);
$dateVente = mysqli_real_escape_string($conn,$data->dateVente);

$sql = "INSERT INTO `Ventes`(`vendeur`, `produit`, `date_vente`) VALUES ('admin', '".$produit."','".$dateVente." ".date('H:i:s')."')";


if(mysqli_query($conn,$sql)) {
  echo "Mise à jour des données réussie";
  print_r($sql);
} else {
  echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
