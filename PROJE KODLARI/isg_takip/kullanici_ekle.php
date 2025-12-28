<?php
// Hata raporlamasını etkinleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Oturumu başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'header.php';
require_once 'mail_gonder.php';

// CSRF token oluştur
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Veritabanı bağlantısını kontrol et
if (!isset($db)) {
    die("Hata: Veritabanı bağlantısı kurulamadı. header.php dosyasını kontrol edin.");
}

// Oturum kontrolü
if (!isset($_SESSION['kul_id'])) {
    header("Location: giris.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['errors'] = ["Geçersiz CSRF token. Lütfen sayfayı yenileyip tekrar deneyin."];
        // Yeni token oluştur
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header("Location: kullanici_ekle.php");
        exit;
    }

    // Form verilerini al
    $kul_isim = trim($_POST['kul_isim'] ?? '');
    $kul_mail = trim($_POST['kul_mail'] ?? '');
    $kul_sifre = trim($_POST['kul_sifre'] ?? '');
    $olusturan_kullanici_id = (int)$_SESSION['kul_id'];

    // Doğrulama
    $errors = [];
    if (empty($kul_isim) || strlen($kul_isim) < 3) {
        $errors[] = "Kullanıcı adı zorunludur ve en az 3 karakter olmalıdır.";
    }
    if (empty($kul_mail) || !filter_var($kul_mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Geçerli bir e-posta adresi giriniz.";
    }
    if (empty($kul_sifre) || strlen($kul_sifre) < 6) {
        $errors[] = "Şifre zorunludur ve en az 6 karakter olmalıdır.";
    }

    // E-posta benzersiz mi?
    if (empty($errors)) {
        $sorgu = $db->prepare("SELECT COUNT(*) FROM kullanicilar WHERE kul_mail = :kul_mail");
        $sorgu->execute(['kul_mail' => $kul_mail]);
        if ($sorgu->fetchColumn() > 0) {
            $errors[] = "Bu e-posta adresi zaten kullanılıyor.";
        }
    }

    // Hata yoksa kaydet
    if (empty($errors)) {
        try {
            $verify_token = bin2hex(random_bytes(16)); // Doğrulama token'ı
            $sorgu = $db->prepare("
                INSERT INTO kullanicilar (
                    kul_isim, kul_mail, kul_sifre, email_verified, verify_token, olusturan_kullanici_id, veri_giris_tarihi
                ) VALUES (
                    :kul_isim, :kul_mail, :kul_sifre, :email_verified, :verify_token, :olusturan_kullanici_id, CURRENT_TIMESTAMP
                )
            ");
            $sorgu->execute([
                'kul_isim' => $kul_isim,
                'kul_mail' => $kul_mail,
                'kul_sifre' => md5($kul_sifre), // MD5 hash
                'email_verified' => 0,
                'verify_token' => $verify_token,
                'olusturan_kullanici_id' => $olusturan_kullanici_id
            ]);

            // Doğrulama e-postası gönder
            $link = "http://localhost/isg_takip/mail_dogrula.php?email=" . urlencode($kul_mail) . "&token=$verify_token";
            $mesaj = "Hesabınızı doğrulamak için <a href='$link'>buraya tıklayın</a>.";
            if (mailGonder($kul_mail, "E-posta Doğrulama", $mesaj)) {
                $_SESSION['success'] = "Kullanıcı başarıyla eklendi. Doğrulama bağlantısı e-posta adresine gönderildi.";
            } else {
                $_SESSION['errors'] = ["Kullanıcı eklendi, ancak doğrulama e-postası gönderilemedi."];
            }
        } catch (PDOException $e) {
            $_SESSION['errors'] = ["Hata oluştu: " . $e->getMessage()];
        }
        // Yeni CSRF token oluştur
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header("Location: kullanici_ekle.php");
        exit;
    } else {
        $_SESSION['errors'] = $errors;
        // Yeni CSRF token oluştur
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header("Location: kullanici_ekle.php");
        exit;
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Yeni Kullanıcı Ekle</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                        <div class="alert alert-danger" id="error-message">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                            <?php unset($_SESSION['errors']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success" id="success-message">
                            <?php echo htmlspecialchars($_SESSION['success']); ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="kullanici-ekle-form">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        <div class="mb-3">
                            <label for="kul_isim" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="kul_isim" name="kul_isim" value="<?php echo isset($kul_isim) ? htmlspecialchars($kul_isim) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="kul_mail" class="form-label">E-posta Adresi</label>
                            <input type="email" class="form-control" id="kul_mail" name="kul_mail" value="<?php echo isset($kul_mail) ? htmlspecialchars($kul_mail) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="kul_sifre" class="form-label">Şifre</label>
                            <input type="password" class="form-control" id="kul_sifre" name="kul_sifre" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Kullanıcıyı Ekle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<style>
    .form-label {
        font-weight: bold;
    }
    .form-control {
        border-radius: 0.25rem;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Hata mesajını 2 saniye sonra gizle
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 2000);
    }

    // Başarı mesajını 2 saniye sonra gizle ve formu sıfırla
    const successMessage = document.getElementById('success-message');
    const form = document.getElementById('kullanici-ekle-form');
    if (successMessage) {
        if (!form) {
            console.error('Form elementi bulunamadı (#kullanici-ekle-form)');
        } else {
            setTimeout(() => {
                successMessage.style.display = 'none';
                form.reset(); // Form inputlarını sıfırla
                console.log('Form sıfırlandı');
            }, 2000);
        }
    }
});
</script>