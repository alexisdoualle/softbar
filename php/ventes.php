<?php
header("Content-Type: application/json; charset=UTF-8");

$date = date("Y-m-d"); // aujourd'hui

//connexion:
include 'connexiondb.php';

//requête pour la journée:
$req_jour = "SELECT date_vente, produit, offert, facturer FROM Ventes WHERE offert=0";
//WHERE date(date_vente) = '".$date."'
$result = $conn->query($req_jour);

//chaine '$outp' qui va servir de réponse en JSON:
$outp = "";
//tranforme chaque ligne '$rs' en JSON:
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";} //ajoute une virgule entre chaque élément, sauf le premier
    $outp .= '{"date_vente":"'  . $rs["date_vente"] . '",';
    $outp .= '"produit":"'. $rs["produit"]                . '",';
    $outp .= '"offert":'. $rs["offert"]                . ',';
    $outp .= '"facturer":'. $rs["facturer"]  .'}';
}
$outp ='{"resultat":['.$outp.']}';

mysqli_close($conn); //ferme la connexion

echo($outp);//retourne la requête (sous format JSON)



?>
