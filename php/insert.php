<?php
ini_set('display_errors',1);
$data = json_decode(file_get_contents("php://input"));

$produit = $data->produit;
$stock = $data->stock;
$prix = $data->prix;
$ventes = $data->ventes;

$conn = new mysqli("localhost", "root", "root", "villalemons");
$sql = "UPDATE CaisseVL SET `stock`=$stock, `ventes`=$ventes WHERE `produit`='".$produit."'";
mysqli_query($conn,$sql);

print_r($sql);

?>
