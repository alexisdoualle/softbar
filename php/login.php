<?php
  ini_set('display_errors',1);
  session_start(); //démarre la session
  $error=""; //message d'erreur eventuel
  if (isset($_POST['submit'])) {
    if (empty($_POST['utilisateur']) || empty($_POST['password'])) {
      $error = "Veuillez renseigner l'utilisateur et/ou le mot de passe";
    } else {
      $utilisateur = $_POST['utilisateur'];
      $password = $_POST['password'];
      //connexion à la bdd:
      $conn = new mysqli("localhost", "root", "root", "villalemons");
      //protège contre les injections SQL:
      $utilisateur = stripslashes($utilisateur);
      $password = stripslashes($password);
      $utilisateur = mysqli_real_escape_string($conn, $utilisateur);
      $password = mysqli_real_escape_string($conn, $password);
      $sql = "SELECT * FROM Utilisateurs WHERE mdp = '".$password."' AND username = '".$utilisateur."'";
      $requete = $conn->query($sql);
      //vérifie l'utilisateur et redirige vers l'application:
      mysqli_close($conn);
      if (mysqli_num_rows($requete) == 1) {
        $_SESSION['login_user']=$utilisateur; // initialise la session
        header("location: application.php");
      } else {
        $error = "utilisateur inconnu";
      }
    } //fin de else
  } // fin de if (isset($_POST['submit']))
?>
