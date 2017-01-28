<?php
include('php/session.php');
if ($util != "admin") {
  header("Location: index.php");
  exit;
}
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login Softbar</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/angular.min.js"></script>
    <script src="js/app.js"></script>
  </head>
  <body ng-app="softbar">
    <h1>Gestion comptes</h1>
      <div class="corps" ng-controller="mainCtrl">
        <div class="">
          Choisir un compte:
          <select ng-model="utilmdp">
            <option value="">admin</option>
            <option value="">stagiaire</option>
          </select>
          {{utilmdp}}
        </div><br>
        <div class="">
          Ancien mot de passe:
          <input type="text" ng-model="oldpassw"><br>
          Nouveau mot de passe:
          <input type="text" ng-model="newpassw"><br>
          <input type="button" value="Valider">
        </div>
      </div>

      </div>
  </body>
</html>
