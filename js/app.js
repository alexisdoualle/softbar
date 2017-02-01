app = angular.module('softbar', []);


app.controller('mainCtrl', function($scope, $http, $window) {
  $scope.utilisateurs = ["admin","stagiaire"];
  $scope.histoVentes = true;
  // optient la date et la met au bon format AAAA-MM-JJ:
  $scope.ojd = new Date();
  var month = $scope.ojd.getUTCMonth() + 1; //mois de 1 à 12
  var day = $scope.ojd.getDate();
  var year = $scope.ojd.getUTCFullYear();

  $scope.today = year + "-" + (month < 10 ? '0' + month : '' + month) + "-" + (day < 10 ? '0' + day : '' + day);
  $scope.thisMonth = year + "-" + (month < 10 ? '0' + month : '' + month);

  //se connecte à la DB pour obtenir les informations de caisse et les mets dans $scope.stock
  getCaisse = function() {
    $http.get("php/caisse.php")
    .success(function(data, status, headers, config) {
        $scope.stock = data.resultat;
        $scope.caisse = data.caisse;
        $scope.mouvements = data.mouvements;
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
  updateCaisse = function() {
    $http({
          method: "post",
          url: "php/majstock.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "caisse":$scope.caisse,
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
          url: "php/deleteitem.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "produit":item.produit}
      })
    .success(function(data, status, headers, config) {
      console.log("Deleted command sent");
    });
  }

  //valide la valeur de stock entrée par l'utilisateur avec le bouton 'mettre à jour'
  $scope.set = function(item){
    updateStock(item);
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
      updateStock(item);
      updateVentes(item,offert,facturer,date_vente);
    }
  } //fin de vendre()

  $scope.quantiteVente = 1;
  $scope.ajouterVente = function(item, offert=0, facturer=0, date_vente=$scope.today) {
    for (var i = 0; i < $scope.quantiteVente; i++) {
      $scope.vendre(item, offert, facturer, date_vente);
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
      console.log($scope.ventes[indexItem]);
      $window.alert("vous avez supprimé la dernière vente de: " + item.produit);
      if ($scope.ventes[indexItem].facturer==0 && $scope.ventes[indexItem].offert==0) {
        $scope.caisse -= item.prix;
      }
      deleteItem(item); // appelle le formulaire et met à jour la bdd
      item.quantite++;
      updateStock(item);
      getVentes(); //rafraichit $scope.ventes
    } //else: aucune vente correspondante trouvée

  } //fin de annulerVendre()


  //affiche les options telles que Annnuler, Offrir etc.
  $scope.option = false;
  $scope.showOption = function() {
    if ($scope.option) {
      $scope.option = false;
    } else {
      $scope.option = true;
    }
  }

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

  $scope.RenommerProduit = function(item, nouveauNom, nouveauPrix) {
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
