app = angular.module('softbar', []);

app.controller('mainCtrl', function($scope, $http) {
  //se connecte à la DB pour obtenir les informations de caisse et les mets dans $scope.resultat
  $http.get("php/caisse.php")
  .then(function(response) {
    $scope.resultat = response.data.resultat;
  });


  //met à jour la base de données avec $scope.vendre
  updateDB = function(item) {
    $http.post("php/insert.php", {
      "produit":item.produit,
      "stock":item.stock,
      "prix":item.prix,
      "ventes":item.ventes})
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
    item.stock++;
    updateDB(item);
  }
  //décrémente la valeur 'stock' d'un item:
  $scope.decrement = function(item){
    //empèche un stock négatif:
    if(item.stock > 0) {
      item.stock--;
      updateDB(item);
    }
  }
  //vendre un item:
  $scope.vendre = function(item) {
    if(item.stock > 0) {
      item.stock--;
      item.ventes++;
      updateDB(item);
    }
  } //fin de vendre()

  //annuler vendre un item:
  $scope.annulerVendre = function(item) {
    item.stock++;
    item.ventes--;
    updateDB(item);
  } //fin de annulerVendre()
});
