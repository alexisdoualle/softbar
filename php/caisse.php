<?php
include 'connexiondb.php';

header("Content-Type: application/json; charset=UTF-8");

//requête:
$sql = "SELECT produit, quantite, prix, ordre FROM Stock WHERE actif=1";
$sql2 = "SELECT fond_de_caisse, date_heure FROM Caisse ORDER BY date_heure DESC LIMIT 1";
$sql3 = "SELECT nouveau_montant, date_mouvement FROM Mouvements LIMIT 50";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);
$result3 = $conn->query($sql3);

//chaine '$outp' qui va servir de réponse en JSON:
$outp = "";
//tranforme chaque ligne '$rs' en JSON:
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";} //ajoute une virgule entre chaque élément, sauf le premier
    $outp .= '{"produit":"'  . $rs["produit"]  . '",';
    $outp .= '"quantite":'   . $rs["quantite"] . ',';
    $outp .= '"prix":'. $rs["prix"]            . ',';
    $outp .= '"ordre":'   . $rs["ordre"] . '}';
}
$rs2 = $result2->fetch_array(MYSQLI_ASSOC);



$outp2= "";
while($rs = $result3->fetch_array(MYSQLI_ASSOC)) {
    if ($outp2 != "") {$outp2 .= ",";} //ajoute une virgule entre chaque élément, sauf le premier
    $outp2 .= '{"montant":'  . $rs["nouveau_montant"]  . ',';
    $outp2 .= '"date_mouvement":"'   . $rs["date_mouvement"] . '"}';
}

$outp ='{"resultat":['.$outp.'],"caisse":'.$rs2["fond_de_caisse"].',"mouvements":['.$outp2.']}';

mysqli_close($conn); //ferme la connexion

echo($outp);//renvoie la requête (sous format JSON)
?>
