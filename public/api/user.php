<?php
require_once 'api_helper.php';
$user = api_require_login();
api_json([
    'id' => $user['db_id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'picture' => $user['picture']
]);
