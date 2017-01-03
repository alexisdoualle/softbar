<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/app.js">

    </script>
    <title>softbar</title>
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
            <th>Ventes</th>
            <th>Total</th>
          </tr>
          <tr ng-repeat="item in resultat">
            <td>{{item.produit}}</td>
            <td style="item-align:center">
              <input type="button" ng-click="vendre(item)" value="VENDRE" class="buttonVendre">
              <input type="button" ng-click="annulerVendre(item)" value="Annuler" class="buttonAnnuler">
            </td>
            <td class="stock">
              <input type="number" ng-model="item.stock" class="quantite">
              <input type="button" name="" value="valider" ng-click="set(item)" class="buttonValider">
              <!--<input type="button" name="" value="+" ng-click="increment(item)" class="button2">-->
              <!--<input type="button" name="" value="-" ng-click="decrement(item)" class="button2">-->
            </td>
            <td>{{item.prix | number:2}} €</td>
            <td>{{item.ventes}}</td>
            <td>{{item.prix * item.ventes | number:2}} €</td>
          </tr>
        </table>
    </div>
  </body>
</html>
