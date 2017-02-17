<?php
include 'connexiondb.php';
  ini_set('display_errors',1);
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=data.csv');
  $output = fopen('php://output', 'w');
  $sql = 'SELECT date_vente, vendeur, produit,
          REPLACE(REPLACE(Offert,1,"offert"),0,""),
          REPLACE(REPLACE(Facturer,1,"facturé"),0,"")
          FROM Ventes
          ORDER BY date_vente DESC';
  $rows = mysqli_query($conn, $sql);
  $titres = array('Date', 'Vendeur', 'Produit', 'Offert', 'Facturé');
  fputcsv($output, $titres, ';');
  while ($row = mysqli_fetch_assoc($rows)) fputcsv($output, $row, ';');

?>
