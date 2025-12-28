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

// Mevcut tarih ve saat için datetime-local kısıtlamaları
$currentDateTime = (new DateTime())->modify('+60 minutes')->format('Y-m-d\TH:i');
$minDateTime = '1950-01-01T00:00';

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
        SELECT r.rapor_basligi, r.rapor_icerigi, r.rapor_durumu, r.rapor_tarihi, k.kul_isim
        FROM raporlar r
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
    $_SESSION['errors'] = ["Veritabanı hatası (rapor çekme): " . $e->getMessage()];
    error_log("Veritabanı hatası (rapor çekme): " . $e->getMessage());
    header("Location: rapor_ekle.php");
    exit;
}

// Rapor durumlarını çek
try {
    $sorgu = $db->prepare("SELECT id, rapor_durum_adi FROM hazir_rapor_durumlari ORDER BY rapor_durum_adi ASC");
    $sorgu->execute();
    $durumlar = $sorgu->fetchAll(PDO::FETCH_ASSOC);
    if (empty($durumlar)) {
        $_SESSION['errors'][] = "Rapor durumları yüklenemedi. Veritabanında durum bulunamadı.";
        error_log("Rapor durumları boş: hazir_rapor_durumlari tablosu kontrol edilmeli.");
    }
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Veritabanı hatası (durumlar): " . $e->getMessage()];
    error_log("Veritabanı hatası (durumlar): " . $e->getMessage());
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['errors'] = ["Geçersiz CSRF token."];
    } else {
        $baslik = trim($_POST['baslik'] ?? '');
        $icerik = trim($_POST['icerik'] ?? '');
        $durum_id = $_POST['durum_id'] ?? '';
        $rapor_tarihi = trim($_POST['rapor_tarihi'] ?? '');

        // Doğrulama
        $errors = [];
        if (empty($baslik)) {
            $errors[] = "Rapor başlığı zorunludur.";
        }
        if (empty($icerik)) {
            $errors[] = "Rapor içeriği zorunludur.";
        }
        if (empty($durum_id)) {
            $errors[] = "Rapor durumu seçilmelidir.";
        }
        if (empty($rapor_tarihi)) {
            $errors[] = "Rapor tarihi zorunludur.";
        } else {
            $raporDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $rapor_tarihi);
            $now = (new DateTime())->modify('+61 minutes');
            if (!$raporDateTime) {
                $errors[] = "Geçersiz rapor tarihi formatı.";
            } elseif ($raporDateTime > $now) {
                $errors[] = "Rapor tarihi bugünden sonraki tarih olamaz.";
            }
        }

        // Durum kontrolü
        if ($durum_id) {
            try {
                $sorgu = $db->prepare("SELECT COUNT(*) FROM hazir_rapor_durumlari WHERE id = ?");
                $sorgu->execute([$durum_id]);
                if ($sorgu->fetchColumn() == 0) {
                    $errors[] = "Seçilen rapor durumu geçersiz.";
                }
            } catch (PDOException $e) {
                $errors[] = "Veritabanı hatası (durum kontrolü): " . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        } else {
            // Tetikleyici için silen kullanıcı ID'sini ayarla
            $stmt = $db->prepare("SET @silen_kullanici_id = ?");
            $stmt->execute([$kullanici_id]);

            // Rapor güncelleme
            try {
                $stmt = $db->prepare("
                    UPDATE raporlar
                    SET rapor_basligi = ?, rapor_icerigi = ?, rapor_durumu = ?, rapor_tarihi = ?, kullanici_id = ?, veri_giris_tarihi = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$baslik, $icerik, $durum_id, $raporDateTime->format('Y-m-d H:i:s'), $kullanici_id, $rapor_id]);

                $_SESSION['success'] = "Rapor başarıyla güncellendi!";
                header("Location: rapor_ekle.php");
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
                <h4 class="mb-0">Rapor Düzenle</h4>
            </div>
            <div class="card-body">
                <form method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <!-- Rapor Başlığı -->
                    <div class="form-group mb-3">
                        <label for="baslik" class="form-label">Rapor Başlığı</label>
                        <input type="text" name="baslik" id="baslik" class="form-control" 
                               value="<?php echo isset($_POST['baslik']) ? htmlspecialchars($_POST['baslik']) : htmlspecialchars(trim($rapor['rapor_basligi'])); ?>" required>
                    </div>

                    <!-- Rapor İçeriği -->
                    <div class="form-group mb-3">
                        <label for="icerik" class="form-label">Rapor İçeriği</label>
                        <textarea name="icerik" id="icerik" class="form-control textarea-top" rows="5" required><?php echo isset($_POST['icerik']) ? htmlspecialchars(trim($_POST['icerik'])) : htmlspecialchars(trim($rapor['rapor_icerigi'])); ?></textarea>
                    </div>

                    <!-- Rapor Durumu -->
                    <div class="form-group mb-3">
                        <label for="durum_id" class="form-label">Rapor Durumu</label>
                        <select name="durum_id" id="durum_id" class="form-control select2" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($durumlar as $durum): ?>
                                <option value="<?php echo htmlspecialchars($durum['id']); ?>" 
                                        <?php echo (isset($_POST['durum_id']) ? $_POST['durum_id'] : $rapor['rapor_durumu']) == $durum['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($durum['rapor_durum_adi']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Rapor Tarihi -->
                    <div class="form-group mb-3">
                        <label for="rapor_tarihi" class="form-label">Rapor Tarihi</label>
                        <input type="datetime-local" name="rapor_tarihi" id="rapor_tarihi" class="form-control"
                               min="<?php echo $minDateTime; ?>" max="<?php echo $currentDateTime; ?>"
                               value="<?php 
                                   echo isset($_POST['rapor_tarihi']) 
                                       ? htmlspecialchars($_POST['rapor_tarihi']) 
                                       : ($rapor['rapor_tarihi'] ? date('Y-m-d\TH:i', strtotime($rapor['rapor_tarihi'])) : ''); 
                               ?>" required>
                        <div class="invalid-feedback">Geçerli bir rapor tarihi giriniz (gelecekte olamaz).</div>
                    </div>

                    <!-- Oluşturan Kullanıcı (Salt Okunur) -->
                    <div class="form-group mb-3">
                        <label for="kul_isim" class="form-label">Oluşturan Kullanıcı</label>
                        <input type="text" id="kul_isim" class="form-control" 
                               value="<?php echo htmlspecialchars(trim($rapor['kul_isim'])); ?>" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Raporu Güncelle</button>
                    <a href="rapor_ekle.php" class="btn btn-secondary">İptal</a>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- JavaScript ile imleç ve kaydırma kontrolü -->
<script>
$(document).ready(function () {
    // Select2'yi başlat
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Seçiniz',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Sonuç bulunamadı"; },
            searching: function() { return "Aranıyor..."; }
        }
    });

    // Textarea yüklendiğinde veya odaklandığında en üste kaydır
    $('#icerik').each(function () {
        this.scrollTop = 0; // Kaydırmayı en üste ayarla
        this.setSelectionRange(0, 0); // İmleci başa al
    }).on('focus', function () {
        this.scrollTop = 0; // Odaklandığında en üste kaydır
        this.setSelectionRange(0, 0); // İmleci başa al
    });

    // Rapor tarihi inputu için doğrulama
    const raporTarihi = $('#rapor_tarihi');
    const form = $('form');

    // Tarih değiştiğinde kontrol
    raporTarihi.on('change', function () {
        const selectedDate = new Date(this.value);
        const now = new Date(Date.now() + 61 * 60 * 1000); // 61 dakika ileri
        if (this.value && selectedDate > now) {
            this.classList.add('is-invalid');
            $(this).next('.invalid-feedback').text('Rapor tarihi bugünden sonrası olamaz.');
        } else if (!this.value) {
            this.classList.add('is-invalid');
            $(this).next('.invalid-feedback').text('Rapor tarihi zorunludur.');
        } else {
            this.classList.remove('is-invalid');
            $(this).next('.invalid-feedback').text('');
        }
    });

    // Form gönderiminde kontrol
    form.on('submit', function (e) {
        const selectedDate = new Date(raporTarihi.val());
        const now = new Date(Date.now() + 61 * 60 * 1000); // 61 dakika ileri
        if (!raporTarihi.val()) {
            e.preventDefault();
            raporTarihi.addClass('is-invalid');
            raporTarihi.next('.invalid-feedback').text('Rapor tarihi zorunludur.');
        } else if (selectedDate > now) {
            e.preventDefault();
            raporTarihi.addClass('is-invalid');
            raporTarihi.next('.invalid-feedback').text('Rapor tarihi bugünden sonrası olamaz.');
        }
    });
});
</script>

<?php require_once 'footer.php'; ?>