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

// CSRF token oluştur
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF token kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['errors'] = ["Geçersiz CSRF token. Lütfen sayfayı yenileyip tekrar deneyin."];
        // Yeni token oluştur
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header("Location: sifre_yenile.php?email=" . urlencode($email) . "&token=" . urlencode($token));
        exit;
    }

    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $token = isset($_POST['token']) ? trim($_POST['token']) : '';
    $yeni_sifre = isset($_POST['sifre']) ? $_POST['sifre'] : '';
    $sifre_tekrar = isset($_POST['sifre_tekrar']) ? $_POST['sifre_tekrar'] : '';

    // Validasyon
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors'] = ["Geçerli bir e-posta adresi gerekli."];
    } elseif (empty($token)) {
        $_SESSION['errors'] = ["Geçersiz token."];
    } elseif (empty($yeni_sifre) || strlen($yeni_sifre) < 6) {
        $_SESSION['errors'] = ["Şifre en az 6 karakter olmalıdır."];
    } elseif ($yeni_sifre !== $sifre_tekrar) {
        $_SESSION['errors'] = ["Şifreler eşleşmiyor."];
    } else {
        try {
            $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE kul_mail = ? AND verify_token = ?");
            $sorgu->execute([$email, $token]);
            $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

            if ($kullanici) {
                $hashed = md5($yeni_sifre); // MD5 mevcut sistemle uyumlu
                $guncelle = $db->prepare("UPDATE kullanicilar SET kul_sifre = ?, verify_token = NULL WHERE kul_mail = ?");
                $guncelle->execute([$hashed, $email]);
                $_SESSION['success'] = "Şifreniz başarıyla değiştirildi. Giriş yapabilirsiniz.";
                // Yeni CSRF token oluştur
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                header("Location: giris.php");
                exit;
            } else {
                $_SESSION['errors'] = ["Geçersiz ya da süresi geçmiş bağlantı."];
            }
        } catch (Exception $e) {
            $_SESSION['errors'] = ["Bir hata oluştu: " . $e->getMessage()];
        }
    }
    // Yeni CSRF token oluştur
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    header("Location: sifre_yenile.php?email=" . urlencode($email) . "&token=" . urlencode($token));
    exit;
}

// Token ve e-posta geçerli mi kontrol et
if (empty($email) || empty($token)) {
    $_SESSION['errors'] = ["Geçersiz bağlantı."];
    header("Location: sifremi_unuttum.php");
    exit;
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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-weight-bold text-primary text-center">Yeni Şifre Belirle</h3>
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

                        <form action="" method="POST" accept-charset="utf-8">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Yeni Şifre</label>
                                    <input required type="password" name="sifre" placeholder="Yeni Şifre" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Yeni Şifre (Tekrar)</label>
                                    <input required type="password" name="sifre_tekrar" placeholder="Yeni Şifre (Tekrar)" class="form-control">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Şifreyi Güncelle</button>
                            </div>
                        </form>
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
</body>
</html>