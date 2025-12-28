<?php
require_once 'header.php';

// Oturum başlat (header.php'de yoksa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$personel = null;
$belgeler = [];

// URL'de tc_kimlik varsa, oturuma kaydet ve URL'yi temizle
if (isset($_GET['tc_kimlik'])) {
    $_SESSION['belge_tc_kimlik'] = trim($_GET['tc_kimlik']);
    $durum = $_GET['durum'] ?? '';
    header("Location: personel_belge_ekle.php" . ($durum ? "?durum=$durum" : ""));
    exit;
}

// POST ile tc_kimlik sorgulama
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tc_sorgula'])) {
    $tc = trim($_POST['tc_kimlik']);
    if (!empty($tc)) {
        $_SESSION['belge_tc_kimlik'] = $tc;

        // Personel bilgilerini çek
        $sorgu = $db->prepare("SELECT * FROM personel_kisisel_bilgi 
            LEFT JOIN personel_sirket_bilgi USING(tc_kimlik)
            WHERE tc_kimlik = ?");
        $sorgu->execute([$tc]);
        $personel = $sorgu->fetch(PDO::FETCH_ASSOC);

        // Personelin mevcut belgelerini çek
        $belgeSorgu = $db->prepare("SELECT pb.*, hb.belge_adi 
            FROM personel_belgeler pb
            LEFT JOIN hazir_belgeler hb ON pb.belge_id = hb.belge_id 
            WHERE tc_kimlik = ?");
        $belgeSorgu->execute([$tc]);
        $belgeler = $belgeSorgu->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Hazır belgeleri çek
$hazirBelgeSorgu = $db->prepare("SELECT * FROM hazir_belgeler");
$hazirBelgeSorgu->execute();
$hazir_belgeler = $hazirBelgeSorgu->fetchAll(PDO::FETCH_ASSOC);

// Durum mesajları
if (isset($_GET['durum'])) {
    if ($_GET['durum'] === 'ok') {
        echo '<div class="alert alert-success">Belge başarıyla eklendi.</div>';
    } elseif ($_GET['durum'] === 'no') {
        echo '<div class="alert alert-danger">Belge eklenemedi.</div>';
    }
}
?>

<div class="container mt-4 mb-4">
    <h2 class="text-center text-primary font-weight-bold">Personel Belge Ekleme Ekranı</h2>
    <hr>
</div>

<div class="container mt-5">
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
        <div class="card mb-4">
            <div class="card-header"><strong>Yeni Belge Ekle</strong></div>
            <div class="card-body">
                <form action="personel_belge_ekle_sql.php" method="POST">
                    <input type="hidden" name="tc_kimlik" value="<?= htmlspecialchars($personel['tc_kimlik']) ?>">
                    <div class="form-group">
                        <label for="belge_id">Belge Seçiniz</label>
                        <select name="belge_id" id="belge_id" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($hazir_belgeler as $belge): ?>
                                <option value="<?= htmlspecialchars($belge['belge_id']) ?>"><?= htmlspecialchars($belge['belge_adi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="alinma_tarihi">Alınma Tarihi</label>
                        <input type="date" name="alinma_tarihi" id="alinma_tarihi" min="1950-01-01" class="form-control" required>
                    </div>
                    <button type="submit" name="belge_ekle" class="btn btn-success btn-block">Belge Ekle</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><strong>Geçmiş Belgeler</strong></div>
            <div class="card-body">
                <?php if ($belgeler): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Belge Adı</th>
                                <th>Alınma Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($belgeler as $belge): ?>
                                <tr>
                                    <td><?= htmlspecialchars($belge['belge_adi']) ?></td>
                                    <td><?= htmlspecialchars($belge['alinma_tarihi']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Bu personele ait belge bulunamadı.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><strong>Kişisel Bilgiler</strong></div>
            <div class="card-body">
                <p><strong>TC:</strong> <?= htmlspecialchars($personel['tc_kimlik']) ?></p>
                <p><strong>Ad Soyad:</strong> <?= htmlspecialchars($personel['ad_soyad']) ?></p>
                <p><strong>Cinsiyet:</strong> <?php if($personel['cinsiyet'] == 1) echo 'Erkek'; else echo 'Kadın'; ?></p>
                <p><strong>Telefon:</strong> <?= htmlspecialchars($personel['telefon']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($personel['email']) ?></p>
                <p><strong>Doğum Tarihi:</strong> <?= htmlspecialchars($personel['dogum_tarihi']) ?></p>
                <p><strong>İşe Giriş Tarihi:</strong> <?= htmlspecialchars($personel['ise_giris_tarihi']) ?></p>
            </div>
        </div>

    <?php endif; ?>
</div>

<script>
    // Tarih input'larına max tarihi ayarla
    function setMaxDate(input) {
        const now = new Date();
        now.setMinutes(now.getMinutes() + 180);
        let maxDate = now.toISOString().slice(0, 16); // Örnek: "2025-05-03T14:30"
        input.setAttribute('max', maxDate);
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
            setMaxDate(input);
        });
    });
</script>

<?php require_once 'footer.php'; ?>