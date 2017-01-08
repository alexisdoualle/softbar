<?php
//affiche les erreurs php eventuelles (à n'utiliser que pour développer)
ini_set('display_errors',1);

//header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$date = date("Y-m-d"); // aujourd'hui

//connexion:
$conn = new mysqli("localhost", "root", "root", "villalemons");
if ($conn->connect_error) {
    die("Connection échouée: " . $conn->connect_error);
}

//requête pour la journée:
$req_jour = "SELECT date_vente, produit FROM Ventes WHERE date(date_vente) = '".$date."'";
$result = $conn->query($req_jour);

//chaine '$outp' qui va servir de réponse en JSON:
$outp = "";
//tranforme chaque ligne '$rs' en JSON:
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";} //ajoute une virgule entre chaque élément, sauf le premier
    $outp .= '{"date_vente":"'  . $rs["date_vente"] . '",';
    $outp .= '"produit":"'. $rs["produit"]     . '"}';
}
$outp ='{"resultat":['.$outp.']}';

mysqli_close($conn); //ferme la connexion

echo($outp);//retourne la requête (sous format JSON)



?>
