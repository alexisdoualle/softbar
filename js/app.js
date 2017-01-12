app = angular.module('softbar', []);


app.controller('mainCtrl', function($scope, $http) {

  $scope.ojd = new Date();
  var month = $scope.ojd.getUTCMonth() + 1; //months from 1-12
  var day = $scope.ojd.getUTCDate();
  var year = $scope.ojd.getUTCFullYear();

  $scope.today = year + "-" + (month < 10 ? '0' + month : '' + month) + "-" + (day < 10 ? '0' + day : '' + day);
  $scope.thisMonth = year + "-" + (month < 10 ? '0' + month : '' + month);

  //se connecte à la DB pour obtenir les informations de caisse et les mets dans $scope.stock
  $http.get("php/caisse.php")
  .success(function(data, status, headers, config) {
      $scope.stock = data.resultat;
      $scope.caisse = data.caisse;
    });


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
    $http.post("php/insert.php", {
      "produit":item.produit,
      "quantite":item.quantite,
      "prix":item.prix,
      "caisse":$scope.caisse
      })
      .success(function(data,status,headers,config){
        console.log("Data Sent Successfully");
      });
  }

  updateCaisse = function() {
    $http.post("php/insert.php", {
      "caisse":$scope.caisse
      })
      .success(function(data,status,headers,config){
        console.log("Data Sent Successfully");
      });
  }

  updateDBVentes = function(item) {
    $http.post("php/insertVente.php", {
      "produit":item.produit,
      "vendeur":'alex'
    })
    .success(function(data, status, headers, config) {
      console.log("Data Sent Successfully");
    });
  }

  deleteItem = function(item) {
    $http.post("php/deleteitem.php", {
      "produit":item.produit
    })
    .success(function(data, status, headers, config) {
      console.log("Deleted Successfully");
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
  $scope.vendre = function(item) {
    if(item.quantite > 0) {
      item.quantite--;
      $scope.ventes.push({"date_vente":$scope.today,"produit":item.produit});
      $scope.caisse += item.prix;
      updateDB(item);
      updateDBVentes(item);

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
      deleteItem(item); // appelle le formulaire et met à jour la bdd
      $scope.caisse -= item.prix;
      item.quantite++;
      updateDB(item);
      getVentes(); //rafraichit $scope.ventes
    }

  } //fin de annulerVendre()

  $scope.showCaisse = false;
  $scope.showCaisseUpdate = function(item) {
    if ($scope.showCaisse) {
      updateCaisse();
      $scope.showCaisse = false;
    } else {
        $scope.showCaisse = true;
    }

  }
  $scope.edit = false;
  $scope.showEdit = function(item) {
    if ($scope.edit) {
      updateCaisse(item);
      $scope.edit = false;
    } else {
      $scope.edit = true;
    }
  }

});
app.filter('reverse', function() {
  return function(items) {
    if (!items || !items.length) { return; }
    return items.slice().reverse();
  };
});
