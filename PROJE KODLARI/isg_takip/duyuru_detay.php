<?php
session_start();
require_once 'header.php';

// Hata raporlamasını etkinleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Duyuru ID'sini al
$duyuru_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($duyuru_id <= 0) {
    $_SESSION['errors'] = ["Geçersiz duyuru ID'si."];
    header("Location: duyuru_ekle.php");
    exit;
}

// Duyuru detaylarını çek
try {
    $sorgu = $db->prepare("
        SELECT d.duyuru_basligi, d.duyuru_icerigi, d.duyuru_tarihi, k.kul_isim, d.veri_giris_tarihi
        FROM duyurular d
        INNER JOIN kullanicilar k ON d.kullanici_id = k.kul_id
        WHERE d.id = ?
    ");
    $sorgu->execute([$duyuru_id]);
    $duyuru = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$duyuru) {
        $_SESSION['errors'] = ["Duyuru bulunamadı."];
        header("Location: duyuru_ekle.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Veritabanı hatası: " . $e->getMessage()];
    header("Location: duyuru_ekle.php");
    exit;
}
?>

<div class="container-fluid mt-4">

    <!-- Hata mesajları -->
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <!-- Duyuru Detayları -->
    <div class="mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Duyuru Detayları</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h5>Duyuru Başlığı</h5>
                        <p><?php echo htmlspecialchars($duyuru['duyuru_basligi']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h5>Duyuru Tarihi</h5>
                        <p><?php echo htmlspecialchars($duyuru['duyuru_tarihi']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h5>Oluşturan Kullanıcı</h5>
                        <p><?php echo htmlspecialchars($duyuru['kul_isim']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h5>Veri Giriş Tarihi</h5>
                        <p><?php echo htmlspecialchars($duyuru['veri_giris_tarihi']); ?></p>
                    </div>
                    <div class="col-12 mb-3">
                        <h5>Duyuru İçeriği</h5>
                        <div class="border p-3 bg-light">
                            <?php echo nl2br(htmlspecialchars($duyuru['duyuru_icerigi'])); ?>
                        </div>
                    </div>
                </div>
                <button onclick="history.back()" class="btn btn-secondary">Geri</button>
            </div>
        </div>
    </div>
</div>

<!-- Stil Dosyası -->
<style>
    .card {
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
    }

    .card-header {
        padding: 1rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    h5 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    p {
        font-size: 1rem;
        margin-bottom: 0;
    }

    .border {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .bg-light {
        background-color: #f8f9fa;
    }

    .btn {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }

    /* Mobil düzenlemeler */
    @media (max-width: 575.98px) {
        .card-body {
            padding: 1rem;
        }

        h5 {
            font-size: 0.9rem;
        }

        p {
            font-size: 0.85rem;
        }

        .btn {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }
    }
</style>

<!-- Bağımlılıklar -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?php require_once 'footer.php'; ?>