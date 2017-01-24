<?php
  //ini_set('display_errors',1);
  session_start();
  if(session_destroy()) // détruit les session
  {
    header("Location: ../index.php"); // page d'accueil
  }
?>
