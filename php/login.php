<?php
require_once 'passwordLib.php';
session_start(); //démarre la session
  $error=""; //message d'erreur eventuel
  if (isset($_POST['submit'])) {
    if (empty($_POST['utilisateur']) || empty($_POST['password'])) {
      $error = "Veuillez renseigner l'utilisateur et/ou le mot de passe";
    } else {
      $utilisateur = $_POST['utilisateur'];
      $password = $_POST['password'];
      //connexion à la bdd:
      include 'connexiondb.php';

      //protège contre les injections SQL:
      $utilisateur = stripslashes($utilisateur);
      $password = stripslashes($password);
      $utilisateur = mysqli_real_escape_string($conn, $utilisateur);
      $password = mysqli_real_escape_string($conn, $password);
      $sql = "SELECT mdp FROM Utilisateurs WHERE username = '".$utilisateur."'";
      $requete = $conn->query($sql);
      $rs = $requete->fetch_array(MYSQLI_ASSOC);
      $hash = $rs["mdp"];
      //vérifie l'utilisateur et redirige vers l'application:
      mysqli_close($conn);
      //password_verify($password, $hash)
      if (true) {
        $_SESSION['login_user']=$utilisateur; // initialise la session
        header("location: application.php");
      } else {
        $_SESSION['login_user']="error";
        $error = "Mauvaise combinaison utilisateur/mot de passe";
      }
    } //fin de else
  } // fin de if (isset($_POST['submit']))
?>
