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
          <tr ng-repeat="item in stock">
            <td class="produit">{{item.produit}}</td>
            <td style="item-align:center" class="vendre">
              <input ng-show="!option" type="button" ng-click="vendre(item)" value="VENDRE" class="bouttonVendre">
              <input ng-show="option" type="button" ng-click="annulerVendre(item)" value="Annuler" title="Annuler vente" class="bouttonAnnuler">
              <input ng-show="option" type="button" ng-click="offrir(item)" value="Offrir" title="Offrir" class="bouttonOffrir">
              <input ng-show="option" type="button" ng-click="facturer(item)" value="Facturer" title="Facturer" class="bouttonFacturer">
              <input type="button" value="..." ng-click="showOption()" class="bouttonOption">
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
            <td style="background-color:#EEE" ng-show="!edit"><input type="button" value="Plus d'options" ng-click="showEdit()" class="bouttonValider"></td>
            <td style="background-color:#EEE" ng-show="edit"><input type="button" value="Moins d'options" ng-click="showEdit()" class="bouttonAnnuler"></td>
            <td style="background-color:#EEE"></td>
            <td style="background-color:#EEE"></td>
            <td colspan="2">Caisse:</td>
            <td ng-show="!showCaisse">{{caisse | number:2}} €</td>
            <td ng-show="showCaisse" class="caisse"><input type="number" ng-model="caisse" class="quantite2"></td>
          </tr>
        </table>
        <span>Utilisateur: </span>
        <span style=""><?php echo $util ?></span>
        <span>Se <a href="php/logout.php">déconnecter</a></span>
        <table ng-show="edit" class="tableOptions">
          <tr>
            <td>
              Ajouter un produit:
              <input type="text" placeholder="Nom du produit" ng-model="nouveauProduit" required>
              <input type="number" placeholder="Prix" ng-model="nouveauPrix" required>
              <input type="number" placeholder="Stock initial" ng-model="nouveauStock" required>
              <input type="button" value="Ajouter" ng-click="ajouterProduit(nouveauProduit, nouveauPrix, nouveauStock)">
            </td>
          </tr>
          <tr>
            <td>Supprimer un produit:
              <select ng-model="produitSupprime" ng-options="item.produit for item in stock">
              </select>
              <input type="button" value="Supprimer" ng-click="supprimerProduit(produitSupprime)">
            </td>
          </tr>
          <tr>
            <td>
              Ajouter une vente:
              <input type="date" placeholder="AAAA-MM-JJ">
              <select class="">
                <option ng-repeat="item in stock" value="">{{item.produit}}</option>
              </select>
              <label for="offert">offert</label>
              <input type="checkbox" name="offert" value="">
              <label for="facturé">facturé</label>
              <input type="checkbox" name="facturé" value="">
              <input type="button" value="Valider">
            </td>
          </tr>
        </table>

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
