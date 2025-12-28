<?php
require_once 'header.php'; // Veritabanı bağlantısı ve oturum kontrolü

// Oturum kontrolü
oturum();

// URL'de tc_kimlik varsa, oturuma kaydet ve URL'yi temizle
if (isset($_GET['tc_kimlik']) && !empty(trim($_GET['tc_kimlik']))) {
    $_SESSION['sil_tc_kimlik'] = trim($_GET['tc_kimlik']);
    header("Location: personel_sil.php"); // tc_kimlik'siz URL'ye yönlendir
    exit;
}

// Oturumdan tc_kimlik al
if (!isset($_SESSION['sil_tc_kimlik']) || empty(trim($_SESSION['sil_tc_kimlik']))) {
    $_SESSION['errors'] = ["T.C. Kimlik numarası belirtilmedi."];
    header("Location: personel_listele.php?durum=no_tc");
    exit;
}

$tc = trim($_SESSION['sil_tc_kimlik']);

// TC kimlik doğrulama: 11 haneli sayısal olmalı
if (!preg_match('/^\d{11}$/', $tc)) {
    $_SESSION['errors'] = ["Geçersiz T.C. Kimlik numarası."];
    unset($_SESSION['sil_tc_kimlik']); // Oturumu temizle
    header("Location: personel_listele.php?durum=invalid_tc");
    exit;
}

try {
    // Transaction başlat
    $db->beginTransaction();

    // Kullanıcı ID'sini oturumdan al
    $kul_id = (int)$_SESSION['kul_id'];
    $db->exec("SET @silen_kullanici_id = $kul_id");

    // İlişkili tüm tabloları sırayla sil
    $tables = [
        'arac_operator_atama',
        'aracli_kazalar',
        'personel_belgeler',
        'personel_gerekli_belge',
        'personel_saglik_tetkikleri',
        'personel_sirket_bilgi',
        'personel_ehliyetler',
        'personel_uyarilar',
        'personel_is_kazalari',
        'personel_kisisel_bilgi' // Son olarak ana tabloyu sil
    ];

    foreach ($tables as $table) {
        $stmt = $db->prepare("DELETE FROM $table WHERE tc_kimlik = ?");
        $stmt->execute([$tc]);
    }

    // İşlem başarılıysa commit et
    $db->commit();
    $_SESSION['success'] = "Personel başarıyla silindi.";
    unset($_SESSION['sil_tc_kimlik']); // Oturumu temizle
    header("Location: personel_listele.php?durum=ok");
    exit;
} catch (Exception $e) {
    // Hata olursa rollback yap
    $db->rollBack();

    // Hata mesajını logla
    error_log("Personel silme hatası [TC: $tc]: " . $e->getMessage() . " | Dosya: " . __FILE__ . " | Satır: " . __LINE__);

    // Kullanıcıya hata mesajı göster
    $_SESSION['errors'] = ["Personel silme işlemi başarısız oldu: " . htmlspecialchars($e->getMessage())];
    unset($_SESSION['sil_tc_kimlik']); // Oturumu temizle
    header("Location: personel_listele.php?durum=error");
    exit;
}

require_once 'footer.php';
?>