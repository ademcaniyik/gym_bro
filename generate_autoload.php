<?php
// Komut satırı simülasyonu için exec fonksiyonu kullanılarak composer dump-autoload çalıştırılır.
$output = [];
$returnVar = null;

exec('php composer.phar dump-autoload', $output, $returnVar);

if ($returnVar === 0) {
    echo "Autoload dosyası başarıyla güncellendi.\n";
} else {
    echo "Autoload dosyası güncellenirken bir hata oluştu. Çıkış kodu: $returnVar\n";
    echo implode("\n", $output);
}
?>
