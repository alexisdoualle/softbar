app = angular.module('softbar', []);

app.controller('mainCtrl', function($scope, $http) {
  //se connecte à la DB pour obtenir les informations de caisse et les mets dans $scope.stock
  $http.get("php/caisse.php")
  .success(function(data, status, headers, config) {
      $scope.stock = data.resultat;
    });

  // de même pour les ventes:
  $http.get("php/ventes.php")
  .then(function(response) {
    $scope.ventes = response.data.resultat;
    console.log($scope.ventes);
  });

  $scope.compterVentes = function(prod) {

  }

  //met à jour la base de données avec $scope.vendre
  updateDB = function(item) {
    $http.post("php/insert.php", {
      "produit":item.produit,
      "quantite":item.quantite,
      "prix":item.prix})
      .success(function(data,status,headers,config){
        console.log("Data Sent Successfully");
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
      //item.ventes++;
      updateDB(item);
    }
  } //fin de vendre()

  //annuler vendre un item:
  $scope.annulerVendre = function(item) {
    item.quantite++;
    //item.ventes--;
    updateDB(item);
  } //fin de annulerVendre()
});
