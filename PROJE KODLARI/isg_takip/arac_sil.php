<?php
require_once 'header.php';

// Silme işlemi için gerekli parametre kontrolleri
if (!isset($_GET['sil']) || $_GET['sil'] !== 'sil' || !isset($_GET['plaka_no'])) {
    echo "<div class='container-fluid mt-4'>
            <div class='alert alert-danger'>Geçersiz istek. Lütfen doğru bir şekilde araç silme işlemi başlatın.</div>
          </div>";
    require_once 'footer.php';
    exit;
}

// Plaka numarasını al ve temizle
$plaka_no = urldecode($_GET['plaka_no']);

// Veritabanı bağlantısını kontrol et
if (!$db) {
    echo "<div class='container-fluid mt-4'>
            <div class='alert alert-danger'>Veritabanı bağlantısı kurulamadı.</div>
          </div>";
    require_once 'footer.php';
    exit;
}

try {
    // Plaka numarasına göre aracı kontrol et
    $sorgu = $db->prepare("SELECT arac_id FROM arac_bilgi WHERE plaka_no = ?");
    $sorgu->execute([$plaka_no]);
    $arac = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$arac) {
        echo "<div class='container-fluid mt-4'>
                <div class='alert alert-warning'>Bu plakaya sahip bir araç bulunamadı.</div>
              </div>";
        require_once 'footer.php';
        exit;
    }

    // Silme işlemi için kullanıcıyı belirle (tetikleyici için)
    $silen_kullanici_id = isset($_SESSION['kul_id']) ? $_SESSION['kul_id'] : null;
    $db->query("SET @silen_kullanici_id = " . ($silen_kullanici_id ? $silen_kullanici_id : 'NULL'));

    // Silme işlemini gerçekleştir
    $sil_sorgu = $db->prepare("DELETE FROM arac_bilgi WHERE plaka_no = ?");
    $sil_sorgu->execute([$plaka_no]);

    // Silme işlemi başarılıysa
    echo "<div class='container-fluid mt-4'>
            <div class='alert alert-success'>Araç başarıyla silindi.</div>
          </div>";
    echo "<script>setTimeout(function(){ window.location.href = 'arac_listele.php'; }, 2000);</script>";

} catch (PDOException $e) {
    // Hata durumunda kullanıcıyı bilgilendir
    echo "<div class='container-fluid mt-4'>
            <div class='alert alert-danger'>Silme işlemi sırasında bir hata oluştu: " . htmlspecialchars($e->getMessage()) . "</div>
          </div>";
}

require_once 'footer.php';
?>