<?php
  ini_set('display_errors',1);
  include('php/session.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/app.js"></script>

    <title>Application</title>
  </head>
  <body ng-app="softbar">
    <div class="corps" ng-controller="mainCtrl">
      <h1>Caisse</h1>
        <table>
          <tr>
            <th>Produit</th>
            <th></th>
            <th>Stock</th>
            <th>Prix</th>
            <th>Jour</th>
            <th>Mois</th>
            <th>Total</th>
          </tr>
          <tr ng-repeat="item in stock">
            <td>{{item.produit}}</td>
            <td style="item-align:center">
              <input type="button" ng-click="vendre(item)" value="VENDRE" class="buttonVendre">
              <input type="button" ng-click="annulerVendre(item)" value="Annuler" class="buttonAnnuler">
            </td>
            <td class="stock">
              <input type="number" ng-model="item.quantite" class="quantite">
              <input type="button" name="" value="mettre à jour" ng-click="set(item)" class="buttonValider">
            </td>
            <td>{{item.prix | number:2}} €</td>
            <td>{{(ventes | filter:{'produit':item.produit} | filter:{'date_vente':today} ).length }}</td>
            <td>{{(ventes | filter:{'produit':item.produit} | filter:{'date_vente':thisMonth} ).length }}</td>
            <td>{{(ventes | filter:{'produit':item.produit} ).length }}</td>
          </tr>
        </table>
        <table>
          <tr>
            <td>
              <span>Utilisateur: <span style=""><?php echo $util ?></span> </span>
              <span>Se <a href="php/logout.php">déconnecter</a></span>
            </td>

          </tr>
        </table>

    </div>
  </body>
</html>
