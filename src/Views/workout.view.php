<?php
// workout.view.php

// Antrenman planı ekleme formu
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenman Planı Oluştur</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fa; margin: 0; }
        .plan-container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #0001; padding: 32px; text-align: center; }
        h1 { color: #333; }
        form { display: flex; flex-direction: column; gap: 14px; }
        label { font-weight: 500; color: #444; }
        input[type=text], input[type=number] { padding: 8px; border-radius: 6px; border: 1px solid #bbb; font-size: 1em; }
        button { background: #4caf50; color: #fff; border: none; border-radius: 8px; padding: 12px; font-size: 1.1em; font-weight: 500; cursor: pointer; margin-top: 10px; }
        button:hover { background: #388e3c; }
        .back { display: inline-block; margin-top: 18px; color: #2196f3; text-decoration: none; font-size: 1em; }
        .back:hover { text-decoration: underline; }
        .success { color: #388e3c; margin-bottom: 10px; }
        .error { color: #e53935; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="plan-container">
        <h1>Yeni Antrenman Planı Oluştur</h1>
        <?php if (isset($success)) echo '<div class="success">'.$success.'</div>'; ?>
        <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
        <form method="post" action="workout.php">
            <label>Gün:</label>
            <input type="text" name="day" required placeholder="Pazartesi, Salı...">
            <label>Hareket:</label>
            <input type="text" name="exercise" required>
            <label>Set:</label>
            <input type="number" name="set" min="1" required>
            <label>Tekrar:</label>
            <input type="number" name="rep" min="1" required>
            <label>Kilo (kg):</label>
            <input type="number" name="weight" min="0" step="0.1" required>
            <button type="submit">Planı Kaydet</button>
        </form>
        <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
    </div>
</body>
</html>