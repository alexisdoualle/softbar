app = angular.module('softbar', ['angular.filter']);


app.controller('mainCtrl', function($scope, $http, $window) {
  //la variable scope.historique est initié avec ventesGroupées, pour que cette option soit affichée par défaut dans l'historique en bas de page
  $scope.historique = "ventesGroupees";
  //La liste des utilisateurs, pour la page "comptes.php"
  $scope.utilisateurs = ["admin","stagiaire"];
  //nombres de ventes par défaut affichées dans l'historique, un nombre trop élevé ralentira l'appli.
  $scope.nbrVentesHisto = 100;

  // obtient la date et la met au bon format AAAA-MM-JJ dans $scope.today et $scope.thisMonth (pour le montant des ventes par période):
  $scope.ojd = new Date();
  var month = $scope.ojd.getUTCMonth() + 1; //mois de 1 à 12
  var day = $scope.ojd.getDate();
  var year = $scope.ojd.getUTCFullYear();
  $scope.today = year + "-" + (month < 10 ? '0' + month : '' + month) + "-" + (day < 10 ? '0' + day : '' + day);
  $scope.thisMonth = year + "-" + (month < 10 ? '0' + month : '' + month);

  function getMonday(d) {
    d = new Date(d);
    var day = d.getDay(),
        diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
    return new Date(d.setDate(diff));
  }
  function joursSemaine(d) {
    var listeJoursSemaine = [];
    return ;
  }
  $scope.lundi = getMonday($scope.ojd);
  var monthL = $scope.lundi.getUTCMonth() + 1; //mois de 1 à 12
  var dayL = $scope.lundi.getDate();
  var yearL = $scope.lundi.getUTCFullYear();
  $scope.lundi = yearL + "-" + (monthL < 10 ? '0' + monthL : '' + monthL) + "-" + (dayL < 10 ? '0' + dayL : '' + dayL);

  $scope.filterSemaine = function(item) {
    return (item.date_vente == $scope.lundi);
  };


  //se connecte à la DB pour obtenir les informations de caisse et les mets dans $scope.stock
  getCaisse = function() {
    $http.get("php/caisse.php")
    .success(function(data, status, headers, config) {
        $scope.stock = data.resultat;
        $scope.caisse = data.caisse;
        $scope.retraits = data.retraits;
      });
  }
  $scope.getCaisse = getCaisse();

  // de même pour les ventes:
  getVentes = function() {
    $http.get("php/ventes.php")
    .then(function(response) {
      $scope.ventes = response.data.resultat;
    });
  }
  $scope.getVentes = getVentes();


  //met à jour la base de données avec $scope.vendre
  updateStock = function(item) {
    $http({
          method: "post",
          url: "php/majstock.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "produit":item.produit,
            "quantite":item.quantite,
            "prix":item.prix,
            "caisse":$scope.caisse}
      })
      .success(function(data,status,headers,config){
        console.log("requête envoyée");
      })
      .error(function (data, status, header, config) {
      });
  }

  //met à jour la caisse:
  updateCaisse = function(montantRetrait=undefined) {
    $http({
          method: "post",
          url: "php/majstock.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "caisse":$scope.caisse,
            "montantRetrait":montantRetrait
          }
      })
      .success(function(data,status,headers,config){
        console.log("requête envoyée");
      })
      .error(function (data, status, header, config) {
      });
  }

  updateVentes = function(item, offert, facturer, dateVente=$scope.today) {
    $http({
          method: "post",
          url: "php/insertVente.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "produit":item.produit,
            "vendeur":'admin',
            "dateVente":dateVente,
            "offert":offert,
            "facturer":facturer
          }
      })
    .success(function(data, status, headers, config) {
      console.log("requête envoyée");
    })
    .error(function (data, status, header, config) {
    });
  }


  //supprimer une vente
  deleteItem = function(item) {
    $http({
          method: "post",
          url: "php/annulervente.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "produit":item.produit}
      })
    .success(function(data, status, headers, config) {
      console.log("Requête supprimer envoyées");
      //window.location.reload();
    });
  }

  //affiche les options telles que Annnuler, Offrir etc.
  $scope.option = false;
  $scope.showOption = function() {
    if ($scope.option) {
      $scope.option = false;
    } else {
      $scope.option = true;
    }
  }

  $scope.retrait = function() {
    try {
      //Converti la valeur entrée par l'utilisateur en float, et change d'éventuelles virgules en points
      var montantRetrait = parseFloat(prompt('Entrez le montant du retrait en euro (ex: 30,5)').replace(",","."));
      $scope.caisse -= montantRetrait; // met à jour le montant de la caisse
      $scope.retraits.push({"date_retrait":$scope.today,"montant_retrait":montantRetrait}); //ajout le retrait dans la liste des retraits
      updateCaisse(montantRetrait); //met à jour la BdD
    } catch (e) {

    }
  }

  //valide la valeur de stock entrée par l'utilisateur avec le bouton 'mettre à jour'
  $scope.set = function(item){
    updateStock(item);
    //window.location.reload();
  }
  //incrémente la valeur 'quantite' d'un item
  $scope.increment = function(item){
    item.quantite++;
    updateStock(item);
  }
  //décrémente la valeur 'quantite' d'un item:
  $scope.decrement = function(item){
    //empèche un stock négatif:
    if(item.quantite > 0) {
      item.quantite--;
      updateStock(item);
    }
  }
  //vendre un item:
  $scope.vendre = function(item, offert=0, facturer=0, date_vente=$scope.today) {
    if(item.quantite > 0) {
      item.quantite--;
      $scope.ventes.push({"date_vente":$scope.today,"produit":item.produit,"offert":offert,"facturer":facturer});
      if(facturer==0 && offert==0) {
        $scope.caisse += item.prix;
      }
      console.log("vente effectuée");
      updateStock(item);
      updateVentes(item,offert,facturer,date_vente);
    } else {
      alert("Le stock est insuffisant. Augmentez-le avant de vendre");
    }
  } //fin de vendre()

  $scope.quantiteVente = 1;
  $scope.ajouterVente = function(prodVente, offert=0, facturer=0, date_vente=$scope.today) {
    for (var i = 0; i < $scope.quantiteVente; i++) {
      $scope.vendre(prodVente, offert, facturer, date_vente);
    }
  }

  //annuler vendre un item:
  $scope.annulerVendre = function(item) {
    function trouverItem(liste) { //fonction trouvant le premier objet contenant item.produit dans ventes[]
      return liste.produit === item.produit;
    }
    indexItem = $scope.ventes.findIndex(trouverItem); //trouve l'index de l'item si il existe (!= -1)
    //vérifie qu'il y ait au moins une vente correspondant à l'item:
    if (indexItem != -1) {
      $window.alert("vous avez supprimé la dernière vente de: " + item.produit);
      //ne met à jour la caisse que si la vente n'est ni offerte ni facturée:
      if ($scope.ventes[indexItem].facturer==0 && $scope.ventes[indexItem].offert==0) {
        $scope.caisse -= item.prix;
      }
      deleteItem(item); // appelle le formulaire et met à jour la bdd
      item.quantite++;
      updateStock(item);
      getVentes(); //rafraichit $scope.ventes
    } //else: aucune vente correspondante trouvée

  } //fin de annulerVendre()


  //modifier le montant de la caisse:
  $scope.editCaisse = false;
  $scope.modifierCaisse = function() {
    if ($scope.editCaisse) {
      updateCaisse();
      $scope.editCaisse = false;
    } else {
      $scope.editCaisse = true;
    }
  }
  //Ajoute un produit, ou le rend actif si il était inactif (à partir de majproduit.php)
  $scope.ajouterProduit = function(nouveauProduit, nouveauPrix, nouveauStock) {
    $http({
          method: "post",
          url: "php/majproduit.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "nouveauProduit":nouveauProduit,
            "nouveauPrix":nouveauPrix,
            "nouveauStock":nouveauStock}
          })
    .success(function(data, status,headers,config){
      console.log("requête envoyée");
      $window.location.reload();
    });
  }
  //Ne supprime pas le produit de la base de donnée, mais le rends inactif.
  $scope.supprimerProduit = function(item) {
    if (confirm("Etes-vous sûr de vouloir supprimer: " + item.produit + "?")) {
      $http({
            method: "post",
            url: "php/supprimerProduit.php",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: {
              "produit":item.produit}
            })
      .success(function(data, status,headers,config){
        console.log("requête envoyée");
        $window.location.reload();
      });
    }
  }
  //donne une position à un produit, le n°1 sera tout en haut et ainsi de suite
  $scope.reordonner = function(item, ordre) {
    $http({
          method: "post",
          url: "php/reordonner.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "produit":item.produit,
            "ordre":ordre}
          })
    .success(function(data, status,headers,config){
      console.log("requête envoyée");
      $window.location.reload();
    });
  }

  $scope.RenommerProduit = function(item, nouveauNom=item.produit, nouveauPrix=item.prix) {
    $http({
          method: "post",
          url: "php/renommer.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "produit":item.produit,
            "nouveauNom":nouveauNom,
            "nouveauPrix":nouveauPrix}
          })
    .success(function(data, status,headers,config){
      console.log("requête envoyée");
      $window.location.reload();
    });
  }

  $scope.changerCouleur = function(item) {
    var c1 = "DarkTurquoise";
    var c2 = "DarkSalmon";
    var c3 = "Gold";
    var c4 = "LightGreen";
    var c5 = "LightBlue";
    var c6 = "Tomato";
    var c7 = "LightYellow";
    var c8 = "LightPink";
    var c9 = "SandyBrown";
    switch (item.couleur) {
      case c1:
        item.couleur = c2;
        break;
      case c2:
        item.couleur = c3;
        break;
      case c3:
        item.couleur = c4;
        break;
      case c4:
        item.couleur = c5;
        break;
      case c5:
        item.couleur = c6;
        break;
      case c6:
        item.couleur = c7;
        break;
      case c7:
        item.couleur = c8;
        break;
      case c8:
        item.couleur = c9;
        break;
      case c9:
        item.couleur = c1;
        break;
    }
    $http({
          method: "post",
          url: "php/changercouleur.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "produit":item.produit,
            "nouvelleCouleur":item.couleur}
          })
    .success(function(data, status,headers,config){
      console.log("requête envoyée");
    });
  }

});
app.filter('reverse', function() {
  return function(items) {
    if (!items || !items.length) { return; }
    return items.slice().reverse();
  };
});
