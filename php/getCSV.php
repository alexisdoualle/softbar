<?php
//ini_set('display_errors',1);
//include 'connexiondb.php';
$row = 1;
ini_set('auto_detect_line_endings',TRUE);
if (($handle = fopen("caisse.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 100, ";")) !== FALSE) {
        $nbrVentes = $data[2];
        $produit = $data[1];
        $date = $data[0];
        $date .= " 00:00:00";
        $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $date);
        for ($c=0; $c < $nbrVentes; $c++) {
            $sql = "INSERT INTO `Ventes`(`vendeur`, `produit`, `date_vente`) VALUES ('admin', '".$produit."','".$date."');";
             if(mysqli_query($conn,$sql)) {
              echo "Mise à jour des données réussie";
            } else {
              echo "Erreur dans la mise à jour des données: " . mysqli_error($conn);
            }
            print_r($sql);
            echo "<br />\n";

        }
    }
    fclose($handle);
}



mysqli_close($conn);
?>
