<?php
require_once 'config.php';

// Oturum başlat (config.php'de yoksa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['belge_ekle'])) {
    $tc_kimlik = trim($_POST['tc_kimlik']);
    $belge_id = $_POST['belge_id'];
    $alinma_tarihi = $_POST['alinma_tarihi'];

    // tc_kimlik'i oturuma kaydet
    $_SESSION['belge_tc_kimlik'] = $tc_kimlik;

    // Kullanıcı ID'sini oturumdan al
    $kul_id = (int)$_SESSION['kul_id'];

    // Veritabanına ekleme işlemi
    $ekle = $db->prepare("INSERT INTO personel_belgeler 
        (tc_kimlik, belge_id, alinma_tarihi, kullanici_id) 
        VALUES (?, ?, ?, ?)");
    $ok = $ekle->execute([$tc_kimlik, $belge_id, $alinma_tarihi, $kul_id]);

    // Yönlendirme (tc_kimlik URL'de olmayacak)
    header("Location: personel_belge_ekle.php?durum=" . ($ok ? "ok" : "no"));
    exit;
}
?>