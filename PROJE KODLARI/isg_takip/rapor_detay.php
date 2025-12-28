<?php
session_start();
require_once 'header.php';

// Hata raporlamasını etkinleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Rapor ID'sini al
$rapor_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($rapor_id <= 0) {
    $_SESSION['errors'] = ["Geçersiz rapor ID'si."];
    header("Location: rapor_ekle.php");
    exit;
}

// Rapor detaylarını çek
try {
    $sorgu = $db->prepare("
        SELECT r.rapor_basligi, r.rapor_icerigi, hrd.rapor_durum_adi, r.rapor_tarihi, k.kul_isim, r.veri_giris_tarihi
        FROM raporlar r
        INNER JOIN hazir_rapor_durumlari hrd ON r.rapor_durumu = hrd.id
        INNER JOIN kullanicilar k ON r.kullanici_id = k.kul_id
        WHERE r.id = ?
    ");
    $sorgu->execute([$rapor_id]);
    $rapor = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$rapor) {
        $_SESSION['errors'] = ["Rapor bulunamadı."];
        header("Location: rapor_ekle.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Veritabanı hatası: " . $e->getMessage()];
    header("Location: rapor_ekle.php");
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

    <!-- Rapor Detayları -->
    <div class="mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Rapor Detayları</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h5>Rapor Başlığı</h5>
                        <p><?php echo htmlspecialchars($rapor['rapor_basligi']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h5>Rapor Durumu</h5>
                        <p><?php echo htmlspecialchars($rapor['rapor_durum_adi']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h5>Rapor Tarihi</h5>
                        <p><?php echo htmlspecialchars($rapor['rapor_tarihi']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h5>Oluşturan Kullanıcı</h5>
                        <p><?php echo htmlspecialchars($rapor['kul_isim']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h5>Veri Giriş Tarihi</h5>
                        <p><?php echo htmlspecialchars($rapor['veri_giris_tarihi']); ?></p>
                    </div>
                    <div class="col-12 mb-3">
                        <h5>Rapor İçeriği</h5>
                        <div class="border p-3 bg-light">
                            <?php echo nl2br(htmlspecialchars($rapor['rapor_icerigi'])); ?>
                        </div>
                    </div>
                </div>
                <a href="rapor_ekle.php" class="btn btn-secondary">Geri</a>
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