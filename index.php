<?php
  include('php/login.php');
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login Softbar</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <h1>Bienvenue</h1>
      <div class="corps">
        <form class="" action="" method="post">
          <label for="">Utilisateur: </label>
          <input type="text" name="utilisateur" placeholder="nom d'utilisateur">
          <label for="">Mot de passe: </label>
          <input type="password" name="password" placeholder="*****">
          <input type="submit" name="submit" value="Valider"><br>
          <span style="color:red"><?php echo $error ?></span>

        </form>

      </div>
  </body>
</html>
