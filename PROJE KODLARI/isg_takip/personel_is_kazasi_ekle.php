<?php
require_once 'header.php';

// Oturum başlat (header.php'de yoksa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$personel = null;
$kazalar = [];

// URL'de tc_kimlik varsa, oturuma kaydet ve URL'yi temizle
if (isset($_GET['tc_kimlik'])) {
    $_SESSION['kaza_tc_kimlik'] = trim($_GET['tc_kimlik']);
    $durum = $_GET['durum'] ?? '';
    header("Location: personel_is_kazasi_ekle.php" . ($durum ? "?durum=$durum" : ""));
    exit;
}

// POST ile tc_kimlik sorgulama
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tc_sorgula'])) {
    $tc = trim($_POST['tc_kimlik']);
    if (!empty($tc)) {
        $_SESSION['kaza_tc_kimlik'] = $tc;

        // Personel bilgilerini çek
        $sorgu = $db->prepare("SELECT * FROM personel_kisisel_bilgi 
            LEFT JOIN personel_sirket_bilgi USING(tc_kimlik)
            WHERE tc_kimlik = ?");
        $sorgu->execute([$tc]);
        $personel = $sorgu->fetch(PDO::FETCH_ASSOC);

        // Geçmiş kaza kayıtları
        $kazaSorgu = $db->prepare("SELECT pik.*, hik.is_kazasi_tipi_adi, 
            hytd.yaralanma_tipi_adi, hydd.yaralanma_durum_adi
            FROM personel_is_kazalari pik
            LEFT JOIN hazir_is_kazalari hik ON pik.is_kazasi_tip_id = hik.is_kazasi_tip_id
            LEFT JOIN hazir_yaralanma_tipler hytd ON pik.yaralanma_tip_id = hytd.yaralanma_tip_id
            LEFT JOIN hazir_yaralanma_durumlar hydd ON pik.yaralanma_durumu_id = hydd.yaralanma_durum_id
            WHERE tc_kimlik = ?");
        $kazaSorgu->execute([$tc]);
        $kazalar = $kazaSorgu->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Hazır veri listelerini çek
$hazirKazaTipleri = $db->prepare("SELECT * FROM hazir_is_kazalari");
$hazirKazaTipleri->execute();
$isKazasiTipleri = $hazirKazaTipleri->fetchAll(PDO::FETCH_ASSOC);

$hazirYaralanmaDurumlari = $db->prepare("SELECT * FROM hazir_yaralanma_durumlar");
$hazirYaralanmaDurumlari->execute();
$yaralanmaDurumlari = $hazirYaralanmaDurumlari->fetchAll(PDO::FETCH_ASSOC);

$hazirYaralanmaTipleri = $db->prepare("SELECT * FROM hazir_yaralanma_tipler");
$hazirYaralanmaTipleri->execute();
$yaralanmaTipleri = $hazirYaralanmaTipleri->fetchAll(PDO::FETCH_ASSOC);

// Durum mesajları
if (isset($_GET['durum'])) {
    if ($_GET['durum'] === 'ok') {
        echo '<div class="alert alert-success">İş kazası kaydı başarıyla eklendi.</div>';
    } elseif ($_GET['durum'] === 'no') {
        echo '<div class="alert alert-danger">İş kazası kaydı eklenemedi.</div>';
    }
}
?>

<div class="container mt-4 mb-4">
    <h2 class="text-center text-danger font-weight-bold">Personel İş Kazası Kayıt Ekranı</h2>
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
            <div class="card-header"><strong>Yeni İş Kazası Kaydı Ekle</strong></div>
            <div class="card-body">
                <form action="personel_is_kazasi_ekle_sql.php" method="POST">
                    <input type="hidden" name="tc_kimlik" value="<?php echo htmlspecialchars($personel['tc_kimlik']); ?>">
                    <div class="form-group">
                        <label for="is_kazasi_tip_id">İş Kazası Tipi</label>
                        <select name="is_kazasi_tip_id" id="is_kazasi_tip_id" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($isKazasiTipleri as $tip): ?>
                                <option value="<?php echo htmlspecialchars($tip['is_kazasi_tip_id']); ?>"><?php echo htmlspecialchars($tip['is_kazasi_tipi_adi']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="yaralanma_durumu_id">Yaralanma Durumu</label>
                        <select name="yaralanma_durumu_id" id="yaralanma_durumu_id" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($yaralanmaDurumlari as $durum): ?>
                                <option value="<?php echo htmlspecialchars($durum['yaralanma_durum_id']); ?>"><?php echo htmlspecialchars($durum['yaralanma_durum_adi']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="yaralanma_tip_id">Yaralanma Tipi</label>
                        <select name="yaralanma_tip_id" id="yaralanma_tip_id" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($yaralanmaTipleri as $tip): ?>
                                <option value="<?php echo htmlspecialchars($tip['yaralanma_tip_id']); ?>"><?php echo htmlspecialchars($tip['yaralanma_tipi_adi']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kaza_nedeni">Kaza Nedeni</label>
                        <textarea name="kaza_nedeni" id="kaza_nedeni" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="is_kazasi_tarihi">Kaza Tarihi</label>
                        <input type="datetime-local" name="is_kazasi_tarihi" min="1950-01-01T00:00" id="is_kazasi_tarihi" class="form-control" required>
                    </div>
                    <button type="submit" name="kaza_ekle" class="btn btn-danger btn-block">Kaza Kaydet</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><strong>Geçmiş İş Kazası Kayıtları</strong></div>
            <div class="card-body">
                <?php if ($kazalar): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kaza Tipi</th>
                                <th>Yaralanma Durumu</th>
                                <th>Yaralanma Tipi</th>
                                <th>Kaza Nedeni</th>
                                <th>Kaza Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kazalar as $kaza): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($kaza['is_kazasi_tipi_adi']); ?></td>
                                    <td><?php echo htmlspecialchars($kaza['yaralanma_durum_adi']); ?></td>
                                    <td><?php echo htmlspecialchars($kaza['yaralanma_tipi_adi']); ?></td>
                                    <td><?php echo htmlspecialchars($kaza['kaza_nedeni']); ?></td>
                                    <td><?php echo htmlspecialchars($kaza['is_kazasi_tarihi']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Bu personele ait iş kazası kaydı bulunamadı.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><strong>Kişisel Bilgiler</strong></div>
            <div class="card-body">
                <p><strong>TC:</strong> <?php echo htmlspecialchars($personel['tc_kimlik']); ?></p>
                <p><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($personel['ad_soyad']); ?></p>
                <p><strong>Cinsiyet:</strong> <?php if($personel['cinsiyet'] == 1) echo 'Erkek'; else echo 'Kadın'; ?></p>
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
        let maxDate = now.toISOString().slice(0, 16);
        input.setAttribute('max', maxDate);
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
            setMaxDate(input);
        });
    });
</script>

<?php require_once 'footer.php'; ?>