<?php
$servername = "{hostname}";
$username = "{user}";
$password = "{password}";

try {
  $conn = new PDO("mysql:host=$servername;dbname={database name}", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 // echo "conectado";
} catch(PDOException $e) {
 // echo "falha: " . $e->getMessage();
}

?>