<?php
require 'vendor/autoload.php';

session_start();

// Google Client Configuration
$client = new Google\Client();
$client->setClientId('YOUR_CLIENT_ID'); // Google Console'dan alınan ID
$client->setClientSecret('YOUR_CLIENT_SECRET'); // Google Console'dan alınan Secret
$client->setRedirectUri('http://localhost/callback.php');

if (isset($_GET['code'])) {
    // Access Token alma
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Kullanıcı bilgilerini alma
    $oauth2 = new Google\Service\Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Kullanıcı bilgilerini kaydetme veya oturum açma işlemi
    $_SESSION['user'] = [
        'id' => $userInfo->id,
        'name' => $userInfo->name,
        'email' => $userInfo->email,
        'picture' => $userInfo->picture,
    ];

    // Giriş sonrası sayfaya yönlendirme
    header('Location: profile.php');
    exit;
}
?>
