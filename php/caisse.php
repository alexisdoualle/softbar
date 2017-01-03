<?php
//affiche les erreurs php eventuelles:
ini_set('display_errors',1);

//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");


$conn = new mysqli("localhost", "root", "root", "villalemons");

$result = $conn->query("SELECT produit, stock, prix, ventes FROM CaisseVL");

$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"produit":"'  . $rs["produit"] . '",';
    $outp .= '"stock":'   . $rs["stock"]        . ',';
    $outp .= '"prix":'   . $rs["prix"]        . ',';
    $outp .= '"ventes":'. $rs["ventes"]     . '}';
}
$outp ='{"resultat":['.$outp.']}';
$conn->close();

echo($outp);

?>
