<?php
include('php/session.php');
include('php/majmdp.php');
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
    <script src="node_modules/angular-filter/dist/angular-filter.min.js"></script>
    <script src="js/app.js"></script>
  </head>
  <body ng-app="softbar">
    <h1>Gestion comptes</h1>
      <div class="corps" ng-controller="mainCtrl">
        <div class="">
          Choisir un compte:
          <select ng-model="utilmdp" ng-options="util for util in utilisateurs"></select>
        </div><br>
        <form class="" action="" method="POST">
          Utilisateur:
          <input type="text" name="utilisateur" ng-model="utilmdp"><br>
          Ancien mot de passe:
          <input name="password" type="password"><br>
          Nouveau mot de passe:
          <input type="password" name="newpassw"><br>
          <input type="submit" name="submit" value="Valider"><br>
          <span style="color:red"><?php echo $error ?></span>
          <span style="color:blue"><?php echo $message ?></span><br>
          <a href="admin.php">revenir</a>
        </form>
      </div>

      </div>
  </body>
</html>
