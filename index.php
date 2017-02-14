<?php
  include('php/login.php');
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login Caisse</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body style="padding: 8% 0 0">

      <div class="login">
        <span><h2>Caisse</h2></span>
        <form class="" action="" method="post">
          <label for=""></label>
          <input type="text" name="utilisateur" placeholder="Utilisateur"><br>
          <label for=""></label>
          <input type="password" name="password" placeholder="Mot de passe"><br>
          <input type="submit" name="submit" value="Valider" class="btn_submit"><br>
          <span style="color:red"><?php echo $error ?></span>

        </form>

      </div>
  </body>
</html>
