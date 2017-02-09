<?php
  $error=""; //message d'erreur eventuel
  if (isset($_POST['submit'])) {
    if (empty($_POST['utilisateur']) || empty($_POST['password'])) {
      $error = "Veuillez renseigner l'utilisateur et/ou le mot de passe";
    } else {
      $utilisateur = $_POST['utilisateur'];
      $password = $_POST['password'];
      $newpassw = $_POST['newpassw'];
      //connexion à la bdd:
      include 'connexiondb.php';

      //protège contre les injections SQL:
      $utilisateur = stripslashes($utilisateur);
      $password = stripslashes($password);
      $utilisateur = mysqli_real_escape_string($conn, $utilisateur);
      $password = mysqli_real_escape_string($conn, $password);
      $sql = "SELECT * FROM Utilisateurs WHERE mdp = '".$password."' AND username = '".$utilisateur."'";
      $requete = $conn->query($sql);
      //vérifie l'utilisateur:
      if (mysqli_num_rows($requete) == 1) {
        $sql2 = "UPDATE Utilisateurs SET `mdp`='".$newpassw."' WHERE `username` = '".$utilisateur."'";
        if(mysqli_query($conn,$sql2)) {
          $message = "Mise à jour du mot de passe réussie";
        } else {
          echo "Erreur dans la mise à jour du mot de passe: " . mysqli_error($conn);
          $error = "erreur dans la mise à jour du mot de passe";
        }
      } else {
        $error = "Mauvais mot de passe";
      }
    } //fin de else
  } // fin de if (isset($_POST['submit']))
?>
