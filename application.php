<?php
  ini_set('display_errors',1);
  include('php/session.php');
  if (empty($util) || ($util == "error")) {
    header("Location: index.php");
    exit;
  }
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
          <tr>
            <td style="background-color:#EEE"></td>
            <td style="background-color:#EEE"></td>
            <td style="background-color:#EEE"></td>
            <td style="background-color:#EEE"></td>
            <td colspan="2">Fond de caisse:</td>
            <td>{{caisse | number:2}} €</td>
          </tr>
          <tr>
            <td>Utilisateur: </td>
            <td style="text-align:center; font-weight:bold"><span style=""><?php echo $util ?></span></td>
            <td><span>Se <a href="php/logout.php">déconnecter</a></span></td>
          </tr>
        </table>

        <table class="historique">
          <tr>
            <td style="background-color:#EEE" colspan="7" ><h3>Historique des ventes</h3></td>
          </tr>
          <tr>

          </tr>
          <tr ng-repeat="vente in ventes | reverse | filter:{'date_vente':today} ">
            <td colspan="7">{{vente.date_vente}} : {{vente.produit}}</td>
          </tr>
        </table>



    </div>
  </body>
</html>
