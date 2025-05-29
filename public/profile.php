<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
</head>
<body>
    <h1>Hoş Geldiniz, <?php echo htmlspecialchars($user['name']); ?></h1>
    <img src="<?php echo htmlspecialchars($user['picture']); ?>" alt="Profile Picture">
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <a href="logout.php">Çıkış Yap</a>
</body>
</html>
