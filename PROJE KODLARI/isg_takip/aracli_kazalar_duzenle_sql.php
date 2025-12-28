<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kaza_duzenle'])) {
    $id = (int)$_POST['id'];
    $arac_id = (int)$_POST['arac_id'];
    $tc_kimlik = $_POST['tc_kimlik'];
    $is_kazasi_tip_id = $_POST['is_kazasi_tip_id'] === 'diger' ? null : (int)$_POST['is_kazasi_tip_id'];
    $yaralanma_durumu_id = $_POST['yaralanma_durumu_id'] === 'diger' ? null : (int)$_POST['yaralanma_durumu_id'];
    $yaralanma_tip_id = $_POST['yaralanma_tip_id'] === 'diger' ? null : (int)$_POST['yaralanma_tip_id'];
    $kaza_aciklamasi = trim($_POST['kaza_aciklamasi']);
    $is_kazasi_tarihi = $_POST['is_kazasi_tarihi'];
    $kul_id = (int)$_SESSION['kul_id'];

    // Diğer seçenekleri kontrol et ve yeni kayıt ekle
    if ($_POST['is_kazasi_tip_id'] === 'diger' && !empty($_POST['is_kazasi_diger'])) {
        $insert_type = $db->prepare("INSERT INTO hazir_is_kazalari (is_kazasi_tipi_adi, kullanici_id) VALUES (?, ?)");
        $insert_type->execute([$_POST['is_kazasi_diger'], $kul_id]);
        $is_kazasi_tip_id = $db->lastInsertId();
    }
    if ($_POST['yaralanma_durumu_id'] === 'diger' && !empty($_POST['yaralanma_durumu_diger'])) {
        $insert_status = $db->prepare("INSERT INTO hazir_yaralanma_durumlar (yaralanma_durum_adi, kullanici_id) VALUES (?, ?)");
        $insert_status->execute([$_POST['yaralanma_durumu_diger'], $kul_id]);
        $yaralanma_durumu_id = $db->lastInsertId();
    }
    if ($_POST['yaralanma_tip_id'] === 'diger' && !empty($_POST['yaralanma_tip_diger'])) {
        $insert_type = $db->prepare("INSERT INTO hazir_yaralanma_tipler (yaralanma_tipi_adi, kullanici_id) VALUES (?, ?)");
        $insert_type->execute([$_POST['yaralanma_tip_diger'], $kul_id]);
        $yaralanma_tip_id = $db->lastInsertId();
    }

    // Kaza kaydını güncelle
    $update = $db->prepare("
        UPDATE aracli_kazalar 
        SET tc_kimlik = ?, is_kazasi_tip_id = ?, yaralanma_durumu_id = ?, yaralanma_tip_id = ?, 
            kaza_aciklamasi = ?, is_kazasi_tarihi = ?, kullanici_id = ?
        WHERE id = ?
    ");
    $success = $update->execute([$tc_kimlik, $is_kazasi_tip_id, $yaralanma_durumu_id, $yaralanma_tip_id, $kaza_aciklamasi, $is_kazasi_tarihi, $kul_id, $id]);

    // Araç plakasını al
    $plaka_query = $db->prepare("SELECT plaka_no FROM arac_bilgi WHERE arac_id = ?");
    $plaka_query->execute([$arac_id]);
    $plaka_no = $plaka_query->fetchColumn();

    if ($success) {
        header("Location: aracli_kazalar_ekle.php?plaka_no=" . urlencode($plaka_no) . "&durum=ok");
    } else {
        header("Location: aracli_kazalar_ekle.php?plaka_no=" . urlencode($plaka_no) . "&durum=no");
    }
    exit;
}
?>