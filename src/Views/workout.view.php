<!DOCTYPE html>
<html>
<head>
    <title>Antremanlar</title>
</head>
<body>
    <h1>Antremanlarınız</h1>
    <a href="create_workout.php">Yeni Antreman Oluştur</a>
    <ul>
        <?php foreach ($workouts as $workout): ?>
            <li>
                <strong><?php echo htmlspecialchars($workout['name']); ?></strong> 
                (<?php echo htmlspecialchars($workout['date']); ?>) 
                <a href="edit_workout.php?id=<?php echo $workout['id']; ?>">Düzenle</a> 
                <a href="delete_workout.php?id=<?php echo $workout['id']; ?>">Sil</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
