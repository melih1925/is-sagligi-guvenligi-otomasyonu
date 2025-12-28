<?php
session_start();
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

// Mevcut tarih ve saat için datetime-local kısıtlamaları
$currentDateTime = (new DateTime())->modify('+181 minutes')->format('Y-m-d\TH:i');
$minDateTime = '1950-01-01T00:00';

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
        SELECT d.duyuru_basligi, d.duyuru_icerigi, d.duyuru_tarihi, k.kul_isim
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
    $_SESSION['errors'] = ["Veritabanı hatası (duyuru çekme): " . $e->getMessage()];
    error_log("Veritabanı hatası (duyuru çekme): " . $e->getMessage());
    header("Location: duyuru_ekle.php");
    exit;
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['errors'] = ["Geçersiz CSRF token."];
    } else {
        $baslik = trim($_POST['baslik'] ?? '');
        $icerik = trim($_POST['icerik'] ?? '');
        $duyuru_tarihi = trim($_POST['duyuru_tarihi'] ?? '');

        // Doğrulama
        $errors = [];
        if (empty($baslik)) {
            $errors[] = "Duyuru başlığı zorunludur.";
        }
        if (empty($icerik)) {
            $errors[] = "Duyuru içeriği zorunludur.";
        }
        if (empty($duyuru_tarihi)) {
            $errors[] = "Duyuru tarihi zorunludur.";
        } else {
            $duyuruDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $duyuru_tarihi);
            $now = (new DateTime())->modify('+61 minutes');
            echo $duyuru_tarihi;
            if (!$duyuruDateTime) {
                $errors[] = "Geçersiz duyuru tarihi formatı.";
            } elseif ($duyuruDateTime > $now) {
                $errors[] = "Duyuru tarihi bugünden sonrası olamaz.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        } else {
            
            // Tetikleyici için silen kullanıcı ID'sini ayarla
                $stmt = $db->prepare("SET @silen_kullanici_id = ?");
                $stmt->execute([$kullanici_id]);

             // Duyuru güncelleme
            try {
                $stmt = $db->prepare("
                    UPDATE duyurular
                    SET duyuru_basligi = ?, duyuru_icerigi = ?, duyuru_tarihi = ?, kullanici_id = ?, veri_giris_tarihi = NOW() 
                    WHERE id = ?
                ");
                $stmt->execute([$baslik, $icerik, $duyuruDateTime->format('Y-m-d H:i:s'), $kullanici_id, $duyuru_id]);

                $_SESSION['success'] = "Duyuru başarıyla güncellendi!";
                header("Location: duyuru_ekle.php");
                exit;
            } catch (PDOException $e) {
                $_SESSION['errors'] = ["Veritabanı hatası (güncelleme): " . $e->getMessage()];
                error_log("Veritabanı hatası (güncelleme): " . $e->getMessage());
            }
        }
    }
}
?>

<div class="container-fluid mt-4">

    <!-- Hata ve başarı mesajları -->
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success']); ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Form Section -->
    <div class="mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Duyuru Düzenle</h4>
            </div>
            <div class="card-body">
                <form method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <!-- Duyuru Başlığı -->
                    <div class="form-group mb-3">
                        <label for="baslik" class="form-label">Duyuru Başlığı</label>
                        <input type="text" name="baslik" id="baslik" class="form-control" 
                               value="<?php echo isset($_POST['baslik']) ? htmlspecialchars($_POST['baslik']) : htmlspecialchars(trim($duyuru['duyuru_basligi'])); ?>" required>
                    </div>

                    <!-- Duyuru İçeriği -->
                    <div class="form-group mb-3">
                        <label for="icerik" class="form-label">Duyuru İçeriği</label>
                        <textarea name="icerik" id="icerik" class="form-control textarea-top" rows="5" required><?php echo isset($_POST['icerik']) ? htmlspecialchars(trim($_POST['icerik'])) : htmlspecialchars(trim($duyuru['duyuru_icerigi'])); ?></textarea>
                    </div>

                    <!-- Duyuru Tarihi -->
                    <div class="form-group mb-3">
                        <label for="duyuru_tarihi" class="form-label">Duyuru Tarihi</label>
                        <input type="datetime-local" name="duyuru_tarihi" id="duyuru_tarihi" class="form-control"
                               min="<?php echo $minDateTime; ?>" max="<?php echo $currentDateTime; ?>"
                               value="<?php 
                                   echo isset($_POST['duyuru_tarihi']) 
                                       ? htmlspecialchars($_POST['duyuru_tarihi']) 
                                       : ($duyuru['duyuru_tarihi'] ? date('Y-m-d\TH:i', strtotime($duyuru['duyuru_tarihi'])) : ''); 
                               ?>" required>
                        <div class="invalid-feedback">Geçerli bir duyuru tarihi giriniz (gelecekte olamaz).</div>
                    </div>

                    <!-- Oluşturan Kullanıcı (Salt Okunur) -->
                    <div class="form-group mb-3">
                        <label for="kul_isim" class="form-label">Oluşturan Kullanıcı</label>
                        <input type="text" id="kul_isim" class="form-control" 
                               value="<?php echo htmlspecialchars(trim($duyuru['kul_isim'])); ?>" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Duyuruyu Güncelle</button>
                    <a href="duyuru_ekle.php" class="btn btn-secondary">İptal</a>
                </form>
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

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        font-size: 1.0rem;
        padding: 0.5rem 0.75rem;
    }

    .btn {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }

    .textarea-top {
        vertical-align: top;
        line-height: 1.5;
        padding: 0.5rem 0.75rem;
        resize: vertical;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
    }

    .is-invalid ~ .invalid-feedback {
        display: block;
    }

    /* Mobil düzenlemeler */
    @media (max-width: 575.98px) {
        .card-body {
            padding: 1rem;
        }

        .form-label {
            font-size: 0.85rem;
        }

        .form-control {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
        }

        .btn {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }

        .textarea-top {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
        }
    }
</style>

<!-- Bağımlılıklar -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- JavaScript ile imleç ve kaydırma kontrolü -->
<script>
$(document).ready(function () {
    // Textarea yüklendiğinde veya odaklandığında en üste kaydır
    $('#icerik').each(function () {
        this.scrollTop = 0; // Kaydırmayı en üste ayarla
        this.setSelectionRange(0, 0); // İmleci başa al
    }).on('focus', function () {
        this.scrollTop = 0; // Odaklandığında en üste kaydır
        this.setSelectionRange(0, 0); // İmleci başa al
    });

    // Duyuru tarihi inputu için doğrulama
    const duyuruTarihi = $('#duyuru_tarihi');
    const form = $('form');

    // Tarih değiştiğinde kontrol
    duyuruTarihi.on('change', function () {
        const selectedDate = new Date(this.value);
        const now = new Date();
        if (this.value && selectedDate > now) {
            this.classList.add('is-invalid');
            $(this).next('.invalid-feedback').text('Duyuru tarihi bugünden sonrası olamaz.');
        } else if (!this.value) {
            this.classList.add('is-invalid');
            $(this).next('.invalid-feedback').text('Duyuru tarihi zorunludur.');
        } else {
            this.classList.remove('is-invalid');
            $(this).next('.invalid-feedback').text('');
        }
    });

    // Form gönderiminde kontrol
    form.on('submit', function (e) {
        const selectedDate = new Date(duyuruTarihi.val());
        const now = new Date();
        if (!duyuruTarihi.val()) {
            e.preventDefault();
            duyuruTarihi.addClass('is-invalid');
            duyuruTarihi.next('.invalid-feedback').text('Duyuru tarihi zorunludur.');
        } else if (selectedDate > now) {
            e.preventDefault();
            duyuruTarihi.addClass('is-invalid');
            duyuruTarihi.next('.invalid-feedback').text('Duyuru tarihi bugünden sonrası olamaz.');
        }
    });
});
</script>

<?php require_once 'footer.php'; ?>