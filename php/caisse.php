<?php
//affiche les erreurs php eventuelles:
ini_set('display_errors',1);

//header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//connexion:
$conn = new mysqli("localhost", "root", "root", "villalemons");
if ($conn->connect_error) {
    die("Connection échouée: " . $conn->connect_error);
}

//requete:
$sql = "SELECT produit, stock, prix, ventes FROM CaisseVL";
$result = $conn->query($sql);

//chaine '$outp' qui va servir de réponse en JSON:
$outp = "";
//tranforme chaque ligne '$rs' en JSON:
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";} //ajoute une virgule entre chaque élément, sauf le premier
    $outp .= '{"produit":"'  . $rs["produit"] . '",';
    $outp .= '"stock":'   . $rs["stock"]        . ',';
    $outp .= '"prix":'   . $rs["prix"]        . ',';
    $outp .= '"ventes":'. $rs["ventes"]     . '}';
}
$outp ='{"resultat":['.$outp.']}';

mysqli_close($conn); //ferme la connexion

echo($outp);//retourne la requête (sous format JSON)

?>
