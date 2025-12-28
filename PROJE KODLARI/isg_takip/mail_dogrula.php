<?php
// Hata raporlamasını etkinleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Oturumu başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

// Veritabanı bağlantısını kontrol et
if (!isset($db)) {
    $_SESSION['errors'] = ["Veritabanı bağlantısı kurulamadı. config.php dosyasını kontrol edin."];
}

// Parametreleri al
$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

// Parametre kontrolü
if (empty($email) || empty($token)) {
    $_SESSION['errors'] = ["Geçersiz doğrulama bağlantısı. E-posta veya token eksik."];
    error_log("Geçersiz doğrulama bağlantısı: email=$email, token=$token");
} else {
    try {
        // Kullanıcıyı kontrol et
        $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE kul_mail = :kul_mail AND verify_token = :verify_token AND email_verified = 0");
        $sorgu->execute(['kul_mail' => $email, 'verify_token' => $token]);
        $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

        if ($kullanici) {
            // E-posta doğrulama
            $guncelle = $db->prepare("UPDATE kullanicilar SET email_verified = 1, verify_token = NULL WHERE kul_mail = :kul_mail");
            $guncelle->execute(['kul_mail' => $email]);
            $_SESSION['success'] = "E-posta adresiniz başarıyla doğrulandı. Artık giriş yapabilirsiniz.";
            error_log("E-posta doğrulandı: email=$email");
        } else {
            // Hata nedenini daha ayrıntılı kontrol et
            $sorgu_check = $db->prepare("SELECT email_verified, verify_token FROM kullanicilar WHERE kul_mail = :kul_mail");
            $sorgu_check->execute(['kul_mail' => $email]);
            $kullanici_check = $sorgu_check->fetch(PDO::FETCH_ASSOC);

            if ($kullanici_check) {
                if ($kullanici_check['email_verified'] == 1) {
                    $_SESSION['errors'] = ["Bu e-posta adresi zaten doğrulanmış."];
                } elseif ($kullanici_check['verify_token'] !== $token) {
                    $_SESSION['errors'] = ["Geçersiz doğrulama token'ı."];
                }
            } else {
                $_SESSION['errors'] = ["Bu e-posta adresi sistemde bulunamadı."];
            }
            error_log("Doğrulama başarısız: email=$email, token=$token, mevcut_token={$kullanici_check['verify_token']}, email_verified={$kullanici_check['email_verified']}");
        }
    } catch (PDOException $e) {
        $_SESSION['errors'] = ["Veritabanı hatası: " . $e->getMessage()];
        error_log("Veritabanı hatası: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo isset($ayarcek) ? htmlspecialchars($ayarcek['site_baslik']) : 'ISG Takip'; ?></title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-weight-bold text-primary text-center">E-posta Doğrulama</h3>
                    </div>
                    <div class="card-body">
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

                        <p class="text-center">E-posta doğrulama işlemi gerçekleştiriliyor...</p>
                        <div class="text-center mt-3">
                            <a href="giris.php" class="btn btn-link">Giriş Sayfasına Dön</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Yönlendirme için JavaScript -->
    <script>
        // 2 saniye sonra giriş sayfasına yönlendir
        setTimeout(function() {
            window.location.href = 'giris.php';
        }, 2000);
    </script>
</body>
</html>