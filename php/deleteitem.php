<?php
ini_set('display_errors',1);
$data = json_decode(file_get_contents("php://input"));

//connexion à la db:
$conn = new mysqli("localhost", "root", "root", "villalemons");
if ($conn->connect_error) {
    die("Connection échouée: " . $conn->connect_error);
}

$produit = mysqli_real_escape_string($conn,$data->produit);

$sql = "DELETE FROM Ventes
        WHERE `produit`= '".$produit."'
        ORDER BY id_vente DESC
        LIMIT 1";


if(mysqli_query($conn,$sql)) {
  echo "Mise à jour des données réussie";
} else {
  echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
