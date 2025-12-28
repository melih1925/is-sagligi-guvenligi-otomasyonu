<?php
session_start();
require_once 'config.php';

// Atama ID'sini al
$atama_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Atama var mı kontrol et
try {
    $sorgu = $db->prepare("SELECT COUNT(*) FROM arac_operator_atama WHERE id = ?");
    $sorgu->execute([$atama_id]);
    if ($sorgu->fetchColumn() == 0) {
        $_SESSION['errors'] = ["Atama kaydı bulunamadı."];
        header("Location: arac_operator_atama.php");
        exit;
    }

    // Silme işlemi
    $db->beginTransaction();
    $sorgu = $db->prepare("DELETE FROM arac_operator_atama WHERE id = ?");
    $sorgu->execute([$atama_id]);
    $db->commit();

    $_SESSION['success'] = "Atama başarıyla silindi.";
    $_SESSION['from_sql'] = true;
    header("Location: arac_operator_atama.php");
    exit;
} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['errors'] = ["Veritabanı hatası: " . $e->getMessage()];
    header("Location: arac_operator_atama.php");
    exit;
}
?>