<?php 
require __DIR__ . '/../src/Helpers/Database.php';

use App\Helpers\Database;

$db = Database::getConnection();

// Örnek sorgu
$query = $db->query("SELECT * FROM users");
$users = $query->fetchAll(PDO::FETCH_ASSOC);

print_r($users);
