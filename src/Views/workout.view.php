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
    <script>
    function addExerciseRow() {
        const container = document.getElementById('exercises');
        const exIndex = container.children.length;
        const row = document.createElement('div');
        row.className = 'exercise-row';
        row.innerHTML = `
            <input type="text" name="exercise[${exIndex}][name]" required placeholder="Hareket">
            <div class="sets" id="sets-${exIndex}"></div>
            <button type="button" onclick="addSetRow(${exIndex})">+ Set Ekle</button>
            <button type="button" class="remove-ex" onclick="this.parentNode.remove()">Hareketi Kaldır</button>
        `;
        container.appendChild(row);
        addSetRow(exIndex); // Varsayılan 1 set
    }
    function addSetRow(exIndex) {
        const setsDiv = document.getElementById('sets-' + exIndex);
        const setIndex = setsDiv ? setsDiv.children.length : 0;
        const setRow = document.createElement('div');
        setRow.className = 'set-row';
        setRow.innerHTML = `
            <span>${setIndex + 1}. set:</span>
            <input type="number" name="exercise[${exIndex}][sets][${setIndex}][rep]" min="1" required placeholder="Tekrar">
            <input type="number" name="exercise[${exIndex}][sets][${setIndex}][weight]" min="0" step="0.1" required placeholder="Kilo (kg)">
            <button type="button" class="remove-ex" onclick="this.parentNode.remove()">Seti Kaldır</button>
        `;
        setsDiv.appendChild(setRow);
    }
    window.onload = function() { addExerciseRow(); };
    </script>
    <style>
    .set-row { display: flex; align-items: center; gap: 8px; margin: 4px 0; justify-content: center; }
    .set-row input { width: 80px; }
    .sets { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="plan-container">
        <h1>Yeni Antrenman Planı Oluştur</h1>
        <?php if (isset($success)) echo '<div class="success">'.$success.'</div>'; ?>
        <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
        <form method="post" action="workout.php">
            <label>Gün:</label>
            <input type="text" name="day" required placeholder="İtiş günü, Çekiş günü" value="<?=htmlspecialchars($day)?>">
            <div id="exercises" class="exercises"></div>
            <button type="button" onclick="addExerciseRow()">+ Hareket Ekle</button>
            <button type="submit">Planı Kaydet</button>
        </form>
        <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
    </div>
</body>
</html>