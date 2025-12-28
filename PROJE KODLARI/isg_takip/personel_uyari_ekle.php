<?php
require_once 'header.php';

// Oturum başlat (header.php'de yoksa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$personel = null;
$uyarilar = null;

// URL'de tc_kimlik varsa, oturuma kaydet ve URL'yi temizle
if (isset($_GET['tc_kimlik'])) {
    $_SESSION['uyari_tc_kimlik'] = trim($_GET['tc_kimlik']);
    $durum = $_GET['durum'] ?? '';
    header("Location: personel_uyari_ekle.php" . ($durum ? "?durum=$durum" : ""));
    exit;
}

// POST ile tc_kimlik sorgulama
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tc_sorgula'])) {
    $tc = trim($_POST['tc_kimlik']);
    if (!empty($tc)) {
        $_SESSION['uyari_tc_kimlik'] = $tc;

        // Personel bilgilerini çek
        $sorgu = $db->prepare("
            SELECT * FROM personel_kisisel_bilgi 
            LEFT JOIN personel_sirket_bilgi USING(tc_kimlik)
            WHERE tc_kimlik = ?");
        $sorgu->execute([$tc]);
        $personel = $sorgu->fetch(PDO::FETCH_ASSOC);

        // Geçmiş uyarılar
        $uyariSorgu = $db->prepare("
            SELECT pu.*, hu.uyari_tipi_adi 
            FROM personel_uyarilar pu
            LEFT JOIN hazir_uyarilar hu ON pu.uyari_tipi_id = hu.uyari_tip_id 
            WHERE pu.tc_kimlik = ?");
        $uyariSorgu->execute([$tc]);
        $uyarilar = $uyariSorgu->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Durum mesajları
if (isset($_GET['durum'])) {
    if ($_GET['durum'] === 'ok') {
        echo '<div class="alert alert-success">Uyarı başarıyla eklendi.</div>';
    } elseif ($_GET['durum'] === 'no') {
        echo '<div class="alert alert-danger">Uyarı eklenemedi.</div>';
    }
}
?>

<div class="container mt-4 mb-4">
    <h2 class="text-center text-primary font-weight-bold">Personel Uyarı Sorgulama Ekranı</h2>
    <hr>
</div>

<div class="container mt-5">
<!-- Personel Sorgulama Box -->
<div class="card mb-4 mx-auto" style="max-width: 500px;">
    <div class="card-header text-center">
        <strong>Personel Sorgulama</strong>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <input type="text" name="tc_kimlik" class="form-control mb-2 text-center" pattern="[1-9]\d{10}" placeholder="TC Kimlik No" maxlength="11" required>
            </div>
            <button type="submit" name="tc_sorgula" class="btn btn-primary btn-block">Sorgula</button>
        </form>
    </div>
</div>

<?php if ($personel): ?>
    <!-- Yeni Uyarı Ekle -->
    <div class="card mb-4">
        <div class="card-header"><strong>Yeni Uyarı Ekle</strong></div>
        <div class="card-body">
            <form action="personel_uyari_ekle_sql.php" method="POST">
                <input type="hidden" name="tc_kimlik" value="<?php echo htmlspecialchars($personel['tc_kimlik']); ?>">
                <div class="form-group">
                    <label>Uyarı Tipi</label>
                    <select name="uyari_tipi_id" class="form-control" required>
                        <option value="">Seçiniz</option>
                        <?php
                        $uyarilarHazir = $db->query("SELECT * FROM hazir_uyarilar");
                        foreach ($uyarilarHazir as $uyari) {
                            echo '<option value="' . htmlspecialchars($uyari['uyari_tip_id']) . '">' . htmlspecialchars($uyari['uyari_tipi_adi']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Uyarı Nedeni</label>
                    <textarea name="uyari_nedeni" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>Uyarı Tarihi ve Saati</label>
                    <input type="datetime-local" name="uyari_tarihi" min="1950-01-01T00:00" class="form-control" required>
                </div>
                <button type="submit" name="uyari_ekle" class="btn btn-success">Uyarı Ekle</button>
            </form>
        </div>
    </div>

    <!-- Geçmiş Uyarılar Kutusu -->
    <div class="card">
        <div class="card-header"><strong>Geçmiş Uyarılar</strong></div>
        <div class="card-body">
            <?php if (count($uyarilar) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Uyarı Tipi</th>
                        <th>Nedeni</th>
                        <th>Tarihi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($uyarilar as $uyari): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($uyari['uyari_tipi_adi']); ?></td>
                            <td><?php echo htmlspecialchars($uyari['uyari_nedeni']); ?></td>
                            <td><?php echo htmlspecialchars($uyari['uyari_tarihi']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Geçmiş uyarı bulunamadı.</p>
            <?php endif; ?>
        </div>
    </div>
                <br>
    <!-- Kişisel Bilgiler Kutusu -->
    <div class="card mb-4">
        <div class="card-header"><strong>Kişisel Bilgiler</strong></div>
        <div class="card-body">
            <p><strong>TC:</strong> <?php echo htmlspecialchars($personel['tc_kimlik']); ?></p>
            <p><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($personel['ad_soyad']); ?></p>
            <p><strong>Cinsiyet:</strong> <?php if ($personel['cinsiyet'] == 1) echo 'Erkek'; else echo 'Kadın'; ?></p>
            <p><strong>Telefon:</strong> <?php echo htmlspecialchars($personel['telefon']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($personel['email']); ?></p>
            <p><strong>Doğum Tarihi:</strong> <?php echo htmlspecialchars($personel['dogum_tarihi']); ?></p>
            <p><strong>İşe Giriş Tarihi:</strong> <?php echo htmlspecialchars($personel['ise_giris_tarihi']); ?></p>
        </div>
    </div>
<?php endif; ?>
</div>

<script>
    // Tarih input'larına max tarihi ayarla
    function setMaxDate(input) {
        const now = new Date();
        now.setMinutes(now.getMinutes() + 180);
        let maxDate;
        maxDate = now.toISOString().slice(0, 16); // Örnek: "2025-05-26T15:53"
        input.setAttribute('max', maxDate);
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
            setMaxDate(input);
        });
    });
</script>

<?php require_once 'footer.php'; ?>