<?php
//met à jour la caisse et le stock
//récupère les données JSON du POST et les met dans $data:
$data = json_decode(file_get_contents("php://input"));

//connexion à la db:
include 'connexiondb.php';

$produit = mysqli_real_escape_string($conn,$data->produit);
$quantite = mysqli_real_escape_string($conn,$data->quantite);
$prix = mysqli_real_escape_string($conn,$data->prix);
$caisse = mysqli_real_escape_string($conn,$data->caisse);
$montantRetrait = mysqli_real_escape_string($conn,$data->montantRetrait);



if ($montantRetrait) { //si montantRetrait est défini, on le rajoute à la BdD
  $sql3 = "INSERT INTO Retraits (`montant_retrait`) VALUES (".$montantRetrait.")";
  if(mysqli_query($conn,$sql3)) {
    echo "Mise à jour des retraits";
  } else {
    echo "Erreur dans la mise à jour des retraits " . mysqli_error($conn);
  }
}
if ($produit) {
  //on met à jour le stock
  $sql = "UPDATE Stock SET `quantite`=$quantite WHERE `produit`='".$produit."'";
  print_r($sql2);
  if(mysqli_query($conn,$sql)) {
    echo "Mise à jour des données réussie";
  } else {
    echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
  }
}
//met à jour le montant de la caisse
$sql2 = "UPDATE Caisse SET `fond_de_caisse`=".$caisse." ORDER BY `date_heure` DESC LIMIT 1";
if(mysqli_query($conn,$sql2)) {
  echo "Mise à jour du montant de la caisse";
} else {
  echo "Erreur dans la mise à jour des données caisse: " . mysqli_error($conn);
}




mysqli_close($conn);
?>
