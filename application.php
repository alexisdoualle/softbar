<?php
include('php/session.php');
if ($util == 'admin') {
  header("Location: admin.php");
}
if (empty($util) || ($util == "error")) {
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/app.js"></script>

    <title>Application</title>
  </head>
  <body ng-app="softbar">
    <div class="corps" ng-controller="mainCtrl">
      <h1>Caisse</h1>
        <table class="tableCaisse">
          <tr>
            <th class="produit">Produit</th>
            <th></th>
            <th>Stock</th>
            <th>Prix</th>
            <th>Jour</th>
            <th>Mois</th>
            <th>Total</th>
          </tr>
          <!-- ng-repeat produit la liste des items en fonction de la variable $scope.stock, accessible depuis app.js -->
          <tr ng-repeat="item in stock | orderBy:'ordre'">
            <td class="produit" ng-attr-title="{{item.ordre}}">{{item.produit}}</td>
            <td style="item-align:center" class="vendre">
              <input type="button" ng-click="vendre(item, 0, 0)" value="VENDRE" class="bouttonVendre">
            </td>
            <td class="stock">
              <input type="number" ng-model="item.quantite" class="quantite">
              <input type="button" name="" value="mettre à jour" ng-click="set(item)" class="bouttonValider">
            </td>
            <td class="prix">{{item.prix | number:2}} €</td>
            <td>{{(ventes | filter:{'produit':item.produit} | filter:{'date_vente':today} ).length }}</td>
            <td>{{(ventes | filter:{'produit':item.produit} | filter:{'date_vente':thisMonth} ).length }}</td>
            <td>{{(ventes | filter:{'produit':item.produit} ).length }}</td>
          </tr>
          <tr>
            <td style="background-color:#EEE"></td>
            <td style="background-color:#EEE"></td>
            <td style="background-color:#EEE"></td>
            <td colspan="2">Caisse:</td>
            <td colspan="2">{{caisse | number:2}} €</td>
          </tr>
        </table>
        <span>Utilisateur: </span>
        <span style=""><?php echo $util ?></span>
        <span>Se <a href="php/logout.php">déconnecter</a></span>


        <table style="background-color:#EEE">

          <tr>
            <td colspan="7" >
              <h3>Historique des ventes <input type="checkbox" ng-model="histo"></h3>
            </td>
          </tr>
          <tr>

          </tr>
          <tr ng-show="histo" ng-repeat="vente in ventes | reverse | limitTo: 20 ">
            <td colspan="7">{{vente.date_vente}} : {{vente.produit}}</td>
          </tr>
        </table>



    </div>
  </body>
</html>
