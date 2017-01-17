<?php
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
            <th class="produit">Produit</th>
            <th></th>
            <th>Stock</th>
            <th>Prix</th>
            <th>Jour</th>
            <th>Mois</th>
            <th>Total</th>
          </tr>
          <!-- ng-repeat produit la liste des items en fonction de la variable $scope.stock, accessible depuis app.js -->
          <tr ng-repeat="item in stock">
            <td ng-click="showEdit()">{{item.produit}}</td>
            <td ng-show="!edit" style="item-align:center" class="vendre">
              <input ng-show="!option" type="button" ng-click="vendre(item)" value="VENDRE" class="buttonVendre">
              <input ng-show="option" type="button" ng-click="annulerVendre(item)" value="Annuler" title="Annuler vente" class="buttonAnnuler">
              <input ng-show="option" type="button" ng-click="offrir(item)" value="Offrir" title="Offrir" class="buttonOffrir">
              <input ng-show="option" type="button" ng-click="facturer(item)" value="Facturer" title="Facturer" class="buttonFacturer">
              <input type="button" value="..." ng-click="showOption()" class="buttonOption">
            </td>
            <td ng-show="edit">
              <input type="button" name="" value="Supprimer" class="buttonAnnuler2">
            </td>
            <td class="stock">
              <input type="number" ng-model="item.quantite" class="quantite">
              <input type="button" name="" value="mettre à jour" ng-click="set(item)" class="buttonValider">
            </td>
            <td class="prix">{{item.prix | number:2}} €</td>
            <td>{{(ventes | filter:{'produit':item.produit} | filter:{'date_vente':today} ).length }}</td>
            <td>{{(ventes | filter:{'produit':item.produit} | filter:{'date_vente':thisMonth} ).length }}</td>
            <td>{{(ventes | filter:{'produit':item.produit} ).length }}</td>
          </tr>
          <tr>
            <td style="background-color:#EEE"><input type="button" name="" value="Editer" ng-click="showEdit()"></td>
            <td style="background-color:#EEE"></td>
            <td style="background-color:#EEE"></td>
            <td style="background-color:#EEE"></td>
            <td colspan="2">Caisse:</td>
            <td ng-show="!showCaisse">{{caisse | number:2}} €</td>
            <td ng-show="showCaisse" class="caisse"><input type="number" ng-model="caisse" class="quantite2"></td>
          </tr>
          <tr>
            <td>Utilisateur: </td>
            <td style="text-align:center; font-weight:bold"><span style=""><?php echo $util ?></span></td>
            <td><span>Se <a href="php/logout.php">déconnecter</a></span></td>
            <td></td>
            <td></td>
            <td></td>
            <td ng-show="!showCaisse"><input type="button" name="" value="Changer" class="buttonValider2" ng-click="showCaisseUpdate()"></td>
            <td ng-show="showCaisse"><input type="button" name="" value="Valider" class="buttonVendre" ng-click="showCaisseUpdate()"></td>
          </tr>
        </table>

        <table class="historique">

          <tr>
            <td style="background-color:#EEE" colspan="7" >
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
