app = angular.module('softbar', []);


app.controller('mainCtrl', function($scope, $http, $window) {
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
  updateDB = function(item) {
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
        console.log("Data Sent");
      })
      .error(function (data, status, header, config) {
      });
  }

  updateCaisse = function() {
    $http({
          method: "post",
          url: "php/majstock.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {"caisse":$scope.caisse}})
      .success(function(data,status,headers,config){
        console.log("Data Sent");
      })
      .error(function (data, status, header, config) {
      });
  }

  updateDBVentes = function(item,dateVente=$scope.today) {
    $http({
          method: "post",
          url: "php/insertVente.php",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: {
            "produit":item.produit,
            "vendeur":'admin',
            "dateVente":dateVente}
      })
    .success(function(data, status, headers, config) {
      console.log("Data Sent");
    })
    .error(function (data, status, header, config) {
    });
  }
  updateDBOffres = function(item) {
    $http.post("php/insertOffert.php", {
      "produit":item.produit,
      "vendeur":'alex'
    })
    .success(function(data, status, headers, config) {
      console.log("Data Sent");
    });
  }
  updateDBFactures = function(item) {
    $http.post("php/insertFacturer.php", {
      "produit":item.produit,
      "vendeur":'alex'
    })
    .success(function(data, status, headers, config) {
      console.log("Data Sent");
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

  //valide la valeur de stock entrée par l'utilisateur dans le bouton 'set'
  $scope.set = function(item){
    updateDB(item);
  }
  //incrémente la valeur 'stock' d'un item
  $scope.increment = function(item){
    item.quantite++;
    updateDB(item);
  }
  //décrémente la valeur 'stock' d'un item:
  $scope.decrement = function(item){
    //empèche un stock négatif:
    if(item.quantite > 0) {
      item.quantite--;
      updateDB(item);
    }
  }
  //vendre un item:
  $scope.vendre = function(item, date_vente=$scope.today) {
    if(item.quantite > 0) {
      item.quantite--;
      $scope.ventes.push({"date_vente":$scope.today,"produit":item.produit});
      $scope.caisse += item.prix;
      updateDB(item);
      updateDBVentes(item,date_vente);
    }
  } //fin de vendre()

  //annuler vendre un item:
  $scope.annulerVendre = function(item) {
    function trouverItem(liste) { //fonction trouvant le premier objet contenant item.produit dans ventes[]
      return liste.produit === item.produit;
    }
    indexItem = $scope.ventes.findIndex(trouverItem); //trouve l'index de l'item si il existe (!= -1)
    //vérifie qu'il y ait au moins une vente correspondant à l'item:
    if (indexItem != -1) {
      $window.alert("vous avez supprimé la dernière vente de: " + item.produit);
      deleteItem(item); // appelle le formulaire et met à jour la bdd
      $scope.caisse -= item.prix;
      item.quantite++;
      updateDB(item);
      getVentes(); //rafraichit $scope.ventes
    }

  } //fin de annulerVendre()

  $scope.offrir = function(item) {
    if(item.quantite > 0) {
      item.quantite--;
      //$scope.ventes.push({"date_vente":$scope.today,"produit":item.produit});
      updateDB(item);
      updateDBOffres(item);
    }
  }

  $scope.facturer = function(item) {
    if(item.quantite > 0) {
      item.quantite--;
      //$scope.ventes.push({"date_vente":$scope.today,"produit":item.produit});
      updateDB(item);
      updateDBFactures(item);
    }
  }

  $scope.edit = false;
  $scope.showEdit = function(item) {
    if ($scope.edit) {
      //updateCaisse(item);
      $scope.edit = false;
    } else {
      $scope.edit = true;
    }
  }

  $scope.option = false;
  $scope.showOption = function() {
    if ($scope.option) {
      $scope.option = false;
    } else {
      $scope.option = true;
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
      console.log("Data Sent");
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
        console.log("Data Sent");
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
      console.log("Data Sent");
      $window.location.reload();
    });
  }


});
app.filter('reverse', function() {
  return function(items) {
    if (!items || !items.length) { return; }
    return items.slice().reverse();
  };
});
