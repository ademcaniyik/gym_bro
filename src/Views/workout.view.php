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
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="plan-container">
        <h1>Yeni Antrenman Planı Oluştur</h1>
        <?php if (isset($success)) echo '<div class="success">'.$success.'</div>'; ?>
        <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
        <form method="post" action="workout.php">
            <label>Gün:</label>
            <input type="text" name="day" required placeholder="İtiş Günü - Çekiş Günü" value="<?=htmlspecialchars($day)?>">
            <div id="exercises" class="exercises">
                <div class="exercise-row">
                    <input type="text" name="exercise[]" required placeholder="Hareket">
                    <input type="number" name="set[]" min="1" required placeholder="Set">
                    <input type="number" name="rep[]" min="1" required placeholder="Tekrar">
                    <input type="number" name="weight[]" min="0" step="0.1" required placeholder="Kilo (kg)">
                </div>
            </div>
            <button type="button" onclick="addExerciseRow()">+ Hareket Ekle</button>
            <button type="submit">Günü Kaydet</button>
        </form>
        <a class="back" href="dashboard.php">&larr; Anasayfaya'a Dön</a>
    </div>
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
            <button type="button" class="remove-ex" onclick="this.parentNode.remove()">Kaldır</button>
        `;
        container.appendChild(row);
    }
    </script>
</body>
</html>