<?php
// workout.view.php

// Antrenman planı ekleme formu
?>
<!DOCTYPE html>
<html>
<head>
    <title>Antrenman Planı Oluştur</title>
</head>
<body>
    <h1>Yeni Antrenman Planı Oluştur</h1>
    <form method="post" action="workout.php">
        <label>Gün:</label>
        <input type="text" name="day" required placeholder="Pazartesi, Salı...">
        <br>
        <label>Hareket:</label>
        <input type="text" name="exercise" required>
        <br>
        <label>Set:</label>
        <input type="number" name="set" min="1" required>
        <br>
        <label>Tekrar:</label>
        <input type="number" name="rep" min="1" required>
        <br>
        <label>Kilo (kg):</label>
        <input type="number" name="weight" min="0" step="0.1" required>
        <br>
        <button type="submit">Planı Kaydet</button>
    </form>
</body>
</html>