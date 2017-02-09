<?php
include('php/session.php');
if ($util != "admin") {
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/angular.min.js"></script>
    <script src="node_modules/angular-filter/dist/angular-filter.min.js"></script>
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
            <td class="produit" ng-attr-title="rang: {{item.ordre}}" ng-style="{'background-color':(item.couleur)}" ng-click="changerCouleur(item)">{{item.produit}}</td>
            <td style="item-align:center" class="vendre">
              <input ng-show="!option" type="button" ng-click="vendre(item, 0, 0)" value="VENDRE" class="bouttonVendre">
              <input ng-show="option" type="button" ng-click="annulerVendre(item)" value="Annuler vente" title="Annuler la dernière vente" class="bouttonAnnuler">
              <input ng-show="option" type="button" ng-click="vendre(item, 1, 0)" value="Offrir" title="Offrir" class="bouttonOffrir">
              <input ng-show="option" type="button" ng-click="vendre(item, 0, 1 )" value="Facturé" title="Facturer" class="bouttonFacturer">
              <input ng-show="!option" type="button" value="&#8594;" ng-click="showOption()" class="bouttonOption" title="plus d'options">
              <input ng-show="option" type="button" value="X" ng-click="showOption()" class="bouttonOption" style="font-size:8px" title="Fermer">
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
            <td>Utilisateur: </td>
            <td><?php echo $util ?></td>
            <td><a href="comptes.php">Gérer comptes</a></td>
            <td></td>
            <td colspan="1">Caisse:</td>
            <td colspan="2" ng-show="!editCaisse">
              {{caisse | number:2}} €
            </td>
            <td colspan="2" ng-show="editCaisse">
              <input type="number" ng-model="caisse" class="quantite" style="width:60px">
            </td>
            <tr>
              <td colspan="2">Se <a href="php/logout.php">déconnecter</a></td>
              <td colspan="2"></td>
              <td ng-show="!editCaisse"><input type="button" value="Recompter" ng-click="modifierCaisse()"></td>
              <td ng-show="editCaisse"><input type="button" value="Valider" ng-click="modifierCaisse()"></td>
              <td colspan="2"><input type="button" value="Retrait" ng-click="retrait()"></td>
            </tr>
        </table>
        <table class="tableOptions">
          <tr>
            <td>
              Ajouter un produit:
              <input type="text" placeholder="Nom du produit" ng-model="nouveauProduit" required>
              <input type="number" placeholder="Prix" ng-model="nouveauPrix" required>
              <input type="number" placeholder="Stock initial" ng-model="nouveauStock" required>
              <input type="button" value="Ajouter" ng-click="ajouterProduit(nouveauProduit, nouveauPrix, nouveauStock)" class="btn">
            </td>
          </tr>
          <tr>
            <td>
              Modifier un produit:
              <select ng-model="produitARenommer" ng-options="item.produit for item in stock">
              </select>
              <input type="text" placeholder="Nouveau nom" ng-model="nouveauNom" required>
              <input type="number" placeholder="Prix" ng-model="nouveauPrix2" required style="width:40px">
              <input type="button" value="Renommer" ng-click="RenommerProduit(produitARenommer, nouveauNom, nouveauPrix2)" class="btn">
            </td>
          </tr>
          <tr>
            <td>Supprimer un produit:
              <select ng-model="produitSupprime" ng-options="item.produit for item in stock">
              </select>
              <input type="button" value="Supprimer" ng-click="supprimerProduit(produitSupprime)" class="btn">
            </td>
          </tr>
          <tr>
              <td>
              Ajouter une vente:
              <input type="text" placeholder="AAAA-MM-JJ" ng-model="dateVente">
              <select ng-model="produitVente" ng-options="item.produit for item in stock">
              </select>
              quantité:
              <input type="number" placeholder="quantite" ng-model="quantiteVente" style="width:30px">
              <label for="offert">offert</label>
              <input type="checkbox" name="offert" ng-model="offert">
              <label for="facturé">facturé</label>
              <input type="checkbox" name="facturé" ng-model="facturer">
              <input type="button" value="Valider" ng-click="ajouterVente(produitVente,offert,facturer,dateVente)" class="btn">
            </td>
          </tr>
          <tr>
            <td>
              Réordonner:
              <select ng-model="produitOrdre" ng-options="item.produit for item in stock">
              </select>
              <input type="number" ng-model="nouvelOrdre" placeholder="nouvelle position">
              <input type="button" value="Valider" ng-click="reordonner(produitOrdre, nouvelOrdre)" class="btn">
            </td>
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
            <!--  Ventes Récentes<input type="radio" ng-model="historique" value="histoVentes"> -->
                <td>Mouvements de caisse<input type="radio" ng-model="historique" value="histoCaisse"></td>
                <td>Ventes Détaillées <input type="radio" ng-model="historique" value="histoTotal"></td>
                <td colspan="4"></td>
            </tr>
            <tr ng-show="historique == 'ventesGroupees'" ng-repeat="(key, value) in ventes | reverse | limitTo:100 | groupBy: 'date_vente'  ">
              <td colspan="7"> {{key}} :
                <ul>
                  <li ng-repeat="(k2,v2) in value | groupBy: 'produit'">{{k2}} : {{(value | filter:k2).length}}</li>
                </ul>
              </td>
      <!--  </tr>
              <tr ng-show="historique == 'histoVentes'" ng-repeat="vente in ventes | reverse | limitTo: 50 ">
              <td colspan="7">{{vente.heure_vente}} : {{vente.produit}} {{(vente.facturer ? "- facturé": "" )}}{{(vente.offert ? "- offert": "" )}}</td>
            </tr>
      -->    <tr ng-show="historique == 'histoCaisse'" ng-repeat="mouvement in mouvements | reverse">
              <td colspan="7">{{mouvement.date_mouvement}}, montant: {{mouvement.montant}} €</td>
            </tr>
            <tr ng-show="historique == 'histoTotal'" ng-repeat="vente in ventes | reverse | limitTo:100">
              <td colspan="7">{{vente.heure_vente}} : {{vente.produit}} {{(vente.facturer ? "- facturé": "" )}}{{(vente.offert ? "- offert": "" )}}</td>
            </tr>
          </table>
        </div>
    </div>
  </body>
</html>
