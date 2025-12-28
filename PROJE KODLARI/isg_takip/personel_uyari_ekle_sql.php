<?php
require_once 'config.php';

// Oturum başlat (config.php'de yoksa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uyari_ekle'])) {
    $tc = trim($_POST['tc_kimlik']);
    $uyari_tipi_id = $_POST['uyari_tipi_id'];
    $uyari_nedeni = $_POST['uyari_nedeni'];
    $uyari_tarihi = $_POST['uyari_tarihi'];

    // tc_kimlik'i oturuma kaydet
    $_SESSION['uyari_tc_kimlik'] = $tc;

    // Kullanıcı ID'sini oturumdan al
    $kul_id = (int)$_SESSION['kul_id'];

    // Veritabanına ekleme işlemi
    $ekle = $db->prepare("INSERT INTO personel_uyarilar 
        (tc_kimlik, uyari_tipi_id, uyari_nedeni, uyari_tarihi, kullanici_id) 
        VALUES (?, ?, ?, ?, ?)");
    $ok = $ekle->execute([$tc, $uyari_tipi_id, $uyari_nedeni, $uyari_tarihi, $kul_id]);

    // Yönlendirme (tc_kimlik URL'de olmayacak)
    header("Location: personel_uyari_ekle.php?durum=" . ($ok ? "ok" : "no"));
    exit;
}
?>