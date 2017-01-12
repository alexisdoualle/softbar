<?php
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "root");
define("DATABASE", "villalemons");
$conn = new mysqli(HOST,USER,PASSWORD,DATABASE);

if ($conn->connect_error) {
    die("Connection échouée: " . $conn->connect_error);
}
?>
