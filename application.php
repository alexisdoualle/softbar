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
    <script src="js/angular.min.js"></script>
    <script src="node_modules/angular-filter/dist/angular-filter.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/app.js"></script>

    <title>Caisse</title>
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
            <td class="produit" ng-attr-title="{{item.ordre}}" ng-style="{'background-color':(item.couleur)}">{{item.produit}}</td>
            <td style="item-align:center" class="vendre">
              <input type="button" ng-click="vendre(item, 0, 0)" value="VENDRE" class="bouttonVendre">
            </td>
            <td class="stock">
              {{item.quantite}}
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
          <tr>
            <td>Utilisateur: </td>
            <td style=""><?php echo $util ?></td>
            <td>Se <a href="php/logout.php">déconnecter</a></td>
          </tr>
        </table>
          <div class="" style="min-height:500px">
            <table class="tableHisto">
              <tr>
                <td colspan="7" >
                  <h3>Historique</h3>
                </td>
              </tr>
              <tr>
                  <td>Ventes <input type="radio" ng-model="historique" value="ventesGroupees"></td>
              <!--   -->
                  <td>Retraits<input type="radio" ng-model="historique" value="histoRetrait"></td>
                  <td>Ventes Détaillées <input type="radio" ng-model="historique" value="histoTotal"></td>
                  <td>Afficher: <input type="number" ng-model="nbrVentesHisto" class="quantite" style="width:50px"> ventes sur {{ventes.length}}</td>
                  <td colspan="3"></td>
              </tr>
              <!-- Le premier groupBy réuni en une ligne (<tr>) chaque date, en commençant par la plus recente avec (reverse) -->
              <tr ng-show="historique == 'ventesGroupees'" ng-repeat="(key, value) in ventes | reverse | limitTo:nbrVentesHisto | groupBy: 'date_vente'  ">
                <td colspan="7"> {{key}} :
                  <ul>
                    <!-- Le second groupBy réuni les ventes de même produits, et indique son nom (key2) et le nombre (value.length) -->
                    <li ng-repeat="(k2,v2) in value | groupBy: 'produit'">{{k2}} : {{(value | filter:k2).length}}</li>
                  </ul>
                </td>
              <tr ng-show="historique == 'histoRetrait'" ng-repeat="retrait in retraits | reverse">
                <td colspan="7">{{retrait.date_retrait}}, montant du retrait: {{retrait.montant_retrait}} €</td>
              </tr>
              <tr ng-show="historique == 'histoTotal'" ng-repeat="vente in ventes | reverse | limitTo:nbrVentesHisto">
                <td colspan="7">{{vente.heure_vente}} : {{vente.produit}} {{(vente.facturer ? "- facturé": "" )}}{{(vente.offert ? "- offert": "" )}}</td>
              </tr>
            </table>
          </div><!-- fin div historique -->
    </div><!-- fin div corps -->
  </body>
</html>
