<?php
$username = 'root';
$password = '';
$host = 'localhost';
$database = 'relatorios';
try {
  $conn = new PDO( 'mysql:host=' . $host . ';dbname=' . $database, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo 'ERROR: ' . $e->getMessage();
}

?>