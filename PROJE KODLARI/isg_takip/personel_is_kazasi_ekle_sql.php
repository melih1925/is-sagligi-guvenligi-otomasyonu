<?php
require_once 'config.php';

// Oturum başlat (config.php'de yoksa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kaza_ekle'])) {
    $tc_kimlik = trim($_POST['tc_kimlik']);
    $is_kazasi_tip_id = $_POST['is_kazasi_tip_id'];
    $yaralanma_durumu_id = $_POST['yaralanma_durumu_id'];
    $yaralanma_tip_id = $_POST['yaralanma_tip_id'];
    $kaza_nedeni = $_POST['kaza_nedeni'];
    $is_kazasi_tarihi = $_POST['is_kazasi_tarihi'];

    // tc_kimlik'i oturuma kaydet
    $_SESSION['kaza_tc_kimlik'] = $tc_kimlik;

    // Kullanıcı ID'sini oturumdan al
    $kul_id = (int)$_SESSION['kul_id'];

    // Veritabanına ekleme işlemi
    $ekle = $db->prepare("INSERT INTO personel_is_kazalari 
        (tc_kimlik, is_kazasi_tip_id, yaralanma_durumu_id, yaralanma_tip_id, kaza_nedeni, is_kazasi_tarihi, kullanici_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $ok = $ekle->execute([$tc_kimlik, $is_kazasi_tip_id, $yaralanma_durumu_id, $yaralanma_tip_id, $kaza_nedeni, $is_kazasi_tarihi, $kul_id]);

    // Yönlendirme (tc_kimlik URL'de olmayacak)
    header("Location: personel_is_kazasi_ekle.php?durum=" . ($ok ? "ok" : "no"));
    exit;
}
?>