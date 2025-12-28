<?php
session_start();
require_once 'config.php'; // Veritabanı bağlantısı için
require_once 'header.php';

// Hata raporlamasını etkinleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Oturum kontrolü
if (!isset($_SESSION['kullanici_id']) && !isset($_SESSION['kul_id']) && !isset($_SESSION['user_id']) && !isset($_SESSION['id'])) {
    $_SESSION['errors'] = ["Oturum bulunamadı. Lütfen tekrar oturum açın."];
    error_log("Oturum hatası: Hiçbir kullanıcı ID anahtarı tanımlı değil. SESSION: " . print_r($_SESSION, true));
    header("Location: giris.php");
    exit;
}

// Kullanıcı ID'sini al
$kullanici_id = null;
if (isset($_SESSION['kullanici_id'])) {
    $kullanici_id = (int)$_SESSION['kullanici_id'];
} elseif (isset($_SESSION['kul_id'])) {
    $kullanici_id = (int)$_SESSION['kul_id'];
} elseif (isset($_SESSION['user_id'])) {
    $kullanici_id = (int)$_SESSION['user_id'];
} elseif (isset($_SESSION['id'])) {
    $kullanici_id = (int)$_SESSION['id'];
}

if ($kullanici_id <= 0) {
    $_SESSION['errors'] = ["Geçersiz kullanıcı ID'si. Lütfen tekrar oturum açın."];
    error_log("Geçersiz kullanıcı ID'si: $kullanici_id");
    header("Location: giris.php");
    exit;
}

// CSRF token oluştur
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['errors'] = ["Geçersiz CSRF token."];
        header("Location: rapor_ekle.php");
        exit;
    }

    $rapor_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($rapor_id <= 0) {
        $_SESSION['errors'] = ["Geçersiz rapor ID'si."];
        header("Location: rapor_ekle.php");
        exit;
    }

    // Raporun varlığını kontrol et
    try {
        $sorgu = $db->prepare("SELECT COUNT(*) FROM raporlar WHERE id = ?");
        $sorgu->execute([$rapor_id]);
        if ($sorgu->fetchColumn() == 0) {
            $_SESSION['errors'] = ["Rapor bulunamadı."];
            header("Location: rapor_ekle.php");
            exit;
        }

        // Tetikleyici için silen kullanıcı ID'sini ayarla
        $stmt = $db->prepare("SET @silen_kullanici_id = ?");
        $stmt->execute([$kullanici_id]);

        // Raporu sil
        $stmt = $db->prepare("DELETE FROM raporlar WHERE id = ?");
        $stmt->execute([$rapor_id]);

        $_SESSION['success'] = "Rapor başarıyla silindi!";
        header("Location: rapor_ekle.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['errors'] = ["Veritabanı hatası (silme): " . $e->getMessage()];
        header("Location: rapor_ekle.php");
        exit;
    }
} else {
    $_SESSION['errors'] = ["Geçersiz istek."];
    header("Location: rapor_ekle.php");
    exit;
}
?>

<?php require_once 'footer.php'; ?>