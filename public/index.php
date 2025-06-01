<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\AuthController;

$auth = new AuthController();
$loginUrl = $auth->getLoginUrl();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Google ile Giriş</title>
</head>
<body>
    <h1>Google ile Giriş</h1>
    <a href="<?php echo htmlspecialchars($loginUrl); ?>">Google ile Giriş Yap</a>
</body>
</html>
