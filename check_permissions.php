<?php


// Kontrol edilecek klasör yolu
// Kontrol edilecek klasör yolu
// Kontrol edilecek klasör yolu
$directory = '/home/acd1f4ftwarecom/acdisoftware.online/gym_bro/vendor';

// Fonksiyon izinleri kontrol eder
function checkAndSetPermissions($directory)
{
    if (!is_dir($directory)) {
        echo "Dizin bulunamadı: $directory";
        return;
    }

    // Geçerli izinleri al
    $permissions = substr(sprintf('%o', fileperms($directory)), -3);

    echo "Mevcut izinler: $permissions\n";

    if ($permissions !== '755') {
        echo "İzinler doğru değil. Düzeltme yapılıyor...\n";

        // İzinleri düzelt
        if (chmod($directory, 0755)) {
            echo "İzinler başarıyla 755 olarak ayarlandı.\n";
        } else {
            echo "İzinler ayarlanırken hata oluştu.\n";
        }
    } else {
        echo "İzinler zaten doğru.\n";
    }
}

// Fonksiyonu çalıştır
checkAndSetPermissions($directory);
?>
