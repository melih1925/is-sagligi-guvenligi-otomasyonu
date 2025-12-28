<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $kul_id = (int)$_SESSION['kul_id'];

    // Araç plakasını al
    $plaka_query = $db->prepare("
        SELECT ab.plaka_no
        FROM aracli_kazalar ak
        INNER JOIN arac_bilgi ab ON ak.arac_id = ab.arac_id
        WHERE ak.id = ?
    ");
    $plaka_query->execute([$id]);
    $plaka_no = $plaka_query->fetchColumn();

    // Silme işlemi
    $db->query("SET @silen_kullanici_id = $kul_id");
    $delete = $db->prepare("DELETE FROM aracli_kazalar WHERE id = ?");
    $success = $delete->execute([$id]);

    if ($success) {
        header("Location: aracli_kazalar_ekle.php?plaka_no=" . urlencode($plaka_no) . "&durum=ok");
    } else {
        header("Location: aracli_kazalar_ekle.php?plaka_no=" . urlencode($plaka_no) . "&durum=no");
    }
    exit;
}
?>