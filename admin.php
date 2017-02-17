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
    <title>Caisse</title>
  </head>
  <!-- ng-app initialise l'application angularjs, telle que définie dans app.js-->
  <body ng-app="softbar">
    <!-- Le controleur 'mainCtrl' manipule l'application.
        Il contient les variables et méthodes qui seront utilisées, ainsi que les données
    -->
    <div class="corps" ng-controller="mainCtrl">
      <h1>Caisse</h1>
        <table class="tableCaisse">
          <tr>
            <th class="produit">Produit</th>
            <th></th>
            <th>Stock</th>
            <th>Prix</th>
            <th style="width:44px">Jour</th>
            <th>Mois</th>
            <th>Total</th>
          </tr>
          <!-- ng-repeat produit la liste des items en fonction de la variable $scope.stock, accessible depuis app.js
              Cela créera donc un tableau avec une ligne par produit dans l'objet stock
          -->
          <tr ng-repeat="item in stock | orderBy:'ordre'">
            <!-- ng-attr-title attribut la valeur 'titre' qui en html s'affiche quand on survole avec le pointeur -->
            <!-- ng-click lance la fonction correspondante dans app.js -->
            <!-- ng-style modifie le style CSS en fonction de la valeur de item.couleur -->
            <!-- les données exprimées comme ça: {{ }} sont affichées en fonction de leur valeur dans $scope,
                et s'actualisent instantanément. Le symbole | sert à formater l'affichage, ici, un nombre à 2 décimales
           -->
            <td class="produit" ng-attr-title="rang: {{item.ordre}}" ng-style="{'background-color':(item.couleur)}" ng-click="changerCouleur(item)">{{item.produit}}</td>
            <td style="item-align:center" class="vendre">
              <!-- ng-show ne montre que les élements en fonction de la variable option, de base FALSE -->
              <input ng-show="!option" type="button" ng-click="vendre(item, admin, 0, 0)" value="VENDRE" class="bouttonVendre">
              <input ng-show="option" type="button" ng-click="annulerVendre(item)" value="Annuler vente" title="Annuler la dernière vente" class="bouttonAnnuler">
              <input ng-show="option" type="button" ng-click="vendre(item, admin, 1, 0)" value="Offrir" title="Offrir" class="bouttonOffrir">
              <input ng-show="option" type="button" ng-click="vendre(item, admin, 0, 1 )" value="Facturé" title="Facturer" class="bouttonFacturer">
              <input ng-show="!option" type="button" value="&#8594;" ng-click="showOption()" class="bouttonOption" title="plus d'options">
              <input ng-show="option" type="button" value="X" ng-click="showOption()" class="bouttonOption" style="font-size:8px" title="Fermer">
            </td>

            <td class="stock">
              <!-- ng-model affiche les données dans un double-sens, si l'utilisateur les modifie, elles sont
                  modifiées dans $scope. On peut donc ensuite les manipuler.
              -->
              <input type="number" ng-model="item.quantite" class="quantite">
              <input type="button" name="" value="mettre à jour" ng-click="set(item)" class="bouttonValider">
            </td>
            <td class="prix">{{item.prix | number:2}} €</td>
            <td>{{(ventes | filter: {'produit':item.produit} : true | filter:{'date_vente':today} ).length }}</td>
      <!-- test "semaine" <td>{{(ventes | filter:{'produit':item.produit} | filter:filterSemaine ).length }}</td> -->
            <td>{{(ventes | filter:{'produit':item.produit} : true | filter:{'date_vente':thisMonth} ).length }}</td>
            <td>{{(ventes | filter:{'produit':item.produit} : true ).length }}</td>
          </tr>
          <tr>
            <td>Utilisateur: </td>
            <!-- La variable PHP $util de la sesssion est affichée ici -->
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
              <td ng-show="!editCaisse"><input type="button" value="Recompter" ng-click="modifierCaisse()" class="bouttonValider"></td>
              <td ng-show="editCaisse"><input type="button" value="Valider" ng-click="modifierCaisse()" class="bouttonValider"></td>
              <td colspan="2"><input type="button" value="Retrait" ng-click="retrait()" class="bouttonValider"></td>
            </tr>
        </table><!-- Fin de la première table -->

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
              <input type="button" value="Valider" ng-click="ajouterVente(produitVente,admin,offert,facturer,dateVente)" class="btn">
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
        </table> <!-- fin de la deuxième table -->
        <div class="" style="min-height:500px">

          <!-- HISTORIQUE -->
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
                <td><input type="button" value="Exporter CSV" ng-click="exporterCSV()" class="btn"></td>
                <td colspan="2"></td>
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
              <td colspan="7">{{vente.heure_vente}} : {{vente.produit}} {{(vente.facturer ? "- facturé": "" )}}{{(vente.offert ? "- offert": "" )}} - (vendeur: {{vente.vendeur}})</td>
            </tr>
          </table>
        </div>
    </div><!-- fin div corps -->
  </body>
</html>
