<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "coffee_ordering_system";
$dsn = "mysql:host={$host};dbname={$dbname}";
$pdo = new PDO($dsn, $user, $password);
?>