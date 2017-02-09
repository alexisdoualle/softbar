<?php
//récupère les données JSON du POST et les met dans $data:
$data = json_decode(file_get_contents("php://input"));

//connexion à la db:
include 'connexiondb.php';

$nouveauProduit = mysqli_real_escape_string($conn,$data->nouveauProduit);
$nouveauPrix = mysqli_real_escape_string($conn,$data->nouveauPrix);
$nouveauStock = mysqli_real_escape_string($conn,$data->nouveauStock);

//Vérifie si le produit existe:
$sql1 = "SELECT produit, quantite, prix, ordre FROM Stock WHERE produit = '".$nouveauProduit."'";
$result = $conn->query($sql1);
if ($result->fetch_array(MYSQLI_ASSOC)) {
  //Si oui, le rends actif
  $sql2 = "UPDATE Stock SET `actif` = 1 WHERE `produit`= '".$nouveauProduit."'";
  if(mysqli_query($conn,$sql2)) {
    echo "Mise à jour des données réussie";
  } else {
    echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
  }
} else {
  //Sinon, le crée:
  $sql = "INSERT INTO `Stock`(`produit`, `prix`, `quantite`) VALUES ('".$nouveauProduit."', ".$nouveauPrix.",".$nouveauStock.")";

  if(mysqli_query($conn,$sql)) {
    echo "Mise à jour des données réussie";
  } else {
    echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
  }
}

mysqli_close($conn);

?>
