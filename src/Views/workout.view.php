<?php
// workout.view.php
// Bir güne birden fazla hareket ekleme desteği
if (!isset($day)) $day = '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenman Planı Oluştur</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fa; margin: 0; }
        .plan-container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #0001; padding: 32px; text-align: center; }
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
        .exercises { margin-bottom: 16px; }
        .exercises label { color: #2196f3; }
    </style>
    <script>
    function addExerciseRow() {
        const container = document.getElementById('exercises');
        const row = document.createElement('div');
        row.className = 'exercise-row';
        row.innerHTML = `
            <input type="text" name="exercise[]" required placeholder="Hareket">
            <input type="number" name="set[]" min="1" required placeholder="Set">
            <input type="number" name="rep[]" min="1" required placeholder="Tekrar">
            <input type="number" name="weight[]" min="0" step="0.1" required placeholder="Kilo (kg)">
            <button type="button" onclick="this.parentNode.remove()">Kaldır</button>
        `;
        container.appendChild(row);
    }
    </script>
</head>
<body>
    <div class="plan-container">
        <h1>Yeni Antrenman Planı Oluştur</h1>
        <?php if (isset($success)) echo '<div class="success">'.$success.'</div>'; ?>
        <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
        <form method="post" action="workout.php">
            <label>Gün:</label>
            <input type="text" name="day" required placeholder="Pazartesi, Salı..." value="<?=htmlspecialchars($day)?>">
            <div id="exercises" class="exercises">
                <div class="exercise-row">
                    <input type="text" name="exercise[]" required placeholder="Hareket">
                    <input type="number" name="set[]" min="1" required placeholder="Set">
                    <input type="number" name="rep[]" min="1" required placeholder="Tekrar">
                    <input type="number" name="weight[]" min="0" step="0.1" required placeholder="Kilo (kg)">
                </div>
            </div>
            <button type="button" onclick="addExerciseRow()">+ Hareket Ekle</button>
            <button type="submit">Planı Kaydet</button>
        </form>
        <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
    </div>
</body>
</html>