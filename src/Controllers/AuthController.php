<?php
namespace App\Controllers;

use App\Helpers\Database;
use Dotenv\Dotenv;

class AuthController
{
    private $client;

    public function __construct()
    {
        if (!file_exists(__DIR__ . '/../../.env')) {
            die('Error: .env file is missing in the project root directory.');
        }
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->client = new \Google_Client();
        $this->client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    public function getLoginUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function handleCallback()
    {
        session_start();
        if (isset($_GET['code'])) {
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            $this->client->setAccessToken($token);

            // Google_Service_Oauth2 yerine Google\Service\Oauth2 kullanılmalı
            $oauth2 = new \Google\Service\Oauth2($this->client);
            $userInfo = $oauth2->userinfo->get();

            $_SESSION['user'] = [
                'id' => $userInfo->id,
                'name' => $userInfo->name,
                'email' => $userInfo->email,
                'picture' => $userInfo->picture,
            ];

            // Kullanıcıyı veritabanına kaydet veya güncelle
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO users (google_id, name, email, profile_picture) VALUES (:google_id, :name, :email, :profile_picture)
                ON DUPLICATE KEY UPDATE name = :name, email = :email, profile_picture = :profile_picture");
            $stmt->bindParam(':google_id', $userInfo->id);
            $stmt->bindParam(':name', $userInfo->name);
            $stmt->bindParam(':email', $userInfo->email);
            $stmt->bindParam(':profile_picture', $userInfo->picture);
            $stmt->execute();

            // Kullanıcının veritabanındaki INT id'sini çek
            $stmt2 = $db->prepare("SELECT id FROM users WHERE google_id = :google_id LIMIT 1");
            $stmt2->bindParam(':google_id', $userInfo->id);
            $stmt2->execute();
            $dbUser = $stmt2->fetch(\PDO::FETCH_ASSOC);
            $_SESSION['user']['db_id'] = $dbUser ? $dbUser['id'] : null;

            header('Location: dashboard.php');
            exit;
        }
    }
}
