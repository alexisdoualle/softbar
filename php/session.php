<?php
session_start();
if (isset($_SESSION['login_user'])) {
  $util = $_SESSION['login_user'];
}
?>
