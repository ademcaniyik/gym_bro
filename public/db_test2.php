<?php
$dsn = "mysql:host=localhost;dbname=acd1f4ftwarecom_gym_bro;charset=utf8mb4";
$username = "acd1f4ftwarecom_root";
$password = "acdi'root321.";

try {
    $pdo = new PDO($dsn, $username, $password);
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
