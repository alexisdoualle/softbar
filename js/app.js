app = angular.module('softbar', []);

app.controller('mainCtrl', function($scope, $http) {

  $http.get("php/caisse.php")
  .then(function(response) {
    $scope.resultat = response.data.resultat;
  });
  
/*
  var stock = [
    {produit:"boisson 1", stock:10, prix:1, venteUnitaire:0},
    {produit:"boisson 2", stock:10, prix:1.5, venteUnitaire:0},
    {produit:"boisson 3", stock:10, prix:2, venteUnitaire:0},
    {produit:"snack 1", stock:10, prix:1, venteUnitaire:0},
    {produit:"snack 2", stock:10, prix:1, venteUnitaire:0},
    {produit:"snack 3", stock:10, prix:1, venteUnitaire:0}
  ];
  $scope.stock = stock;
*/
  //incrémente la valeur 'quantite' d'un item de stock:
  $scope.increment = function(item){
    item.stock++;
  }
  //décrémente la valeur 'quantite' d'un item de stock:
  $scope.decrement = function(item){
    //empèche un stock négatif:
    if(item.stock > 0) {
      item.stock--;
    }
  }
  //vendre un item:
  $scope.vendre = function(item) {
    if(item.stock > 0) {
      item.stock--;
      item.ventes++;
    }

  }
});
