<?php
//affiche les erreurs php eventuelles:
//ini_set('display_errors',1);
include 'connexiondb.php';

//header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//requête:
$sql = "SELECT produit, quantite, prix FROM Stock";
$sql2 = "SELECT fond_de_caisse, date_heure FROM Caisse ORDER BY date_heure DESC LIMIT 1";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);

//chaine '$outp' qui va servir de réponse en JSON:
$outp = "";
//tranforme chaque ligne '$rs' en JSON:
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";} //ajoute une virgule entre chaque élément, sauf le premier
    $outp .= '{"produit":"'  . $rs["produit"] . '",';
    $outp .= '"quantite":'   . $rs["quantite"]        . ',';
    $outp .= '"prix":'. $rs["prix"]     . '}';
}
$rs2 = $result2->fetch_array(MYSQLI_ASSOC);
$outp ='{"resultat":['.$outp.'],"caisse":'.$rs2["fond_de_caisse"].'}';
//

mysqli_close($conn); //ferme la connexion

echo($outp);//retourne la requête (sous format JSON)

?>
