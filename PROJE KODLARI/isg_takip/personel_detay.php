<?php 
require_once 'header.php';

// Oturum başlat (header.php'de yoksa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// URL'de tc_kimlik varsa, oturuma kaydet ve URL'yi temizle
if (isset($_GET['tc_kimlik'])) {
    $_SESSION['detay_tc_kimlik'] = $_GET['tc_kimlik'];
    header("Location: personel_detay.php"); // tc_kimlik'siz URL'ye yönlendir
    exit;
}

// Oturumdan tc_kimlik al
if (!isset($_SESSION['detay_tc_kimlik'])) {
    // tc_kimlik yoksa, örneğin personel listele sayfasına yönlendir
    header("Location: personel_listele.php");
    exit;
}

$tc = $_SESSION['detay_tc_kimlik'];

// Kişisel Bilgi
$kisisel = $db->prepare("SELECT * FROM personel_kisisel_bilgi WHERE tc_kimlik = ?");
$kisisel->execute([$tc]);
$kisi = $kisisel->fetch(PDO::FETCH_ASSOC);

// Gerekli Belgeler
$belgeler = $db->prepare("SELECT * FROM personel_gerekli_belge WHERE tc_kimlik = ?");
$belgeler->execute([$tc]);
$gerekli = $belgeler->fetch(PDO::FETCH_ASSOC);

// Sağlık Tetkikleri
$saglik = $db->prepare("SELECT * FROM personel_saglik_tetkikleri WHERE tc_kimlik = ?");
$saglik->execute([$tc]);
$saglikVeri = $saglik->fetch(PDO::FETCH_ASSOC);

// Firma & Meslek
$sirket = $db->prepare("
    SELECT psb.*, f.firma_adi, m.meslek_adi FROM personel_sirket_bilgi psb 
    INNER JOIN hazir_firmalar f ON psb.firma_id = f.firma_id 
    INNER JOIN hazir_meslekler m ON psb.meslek_id = m.meslek_id 
    WHERE psb.tc_kimlik = ?");
$sirket->execute([$tc]);
$sirketVeri = $sirket->fetch(PDO::FETCH_ASSOC);

// Alınan Belgeler
$alinan = $db->prepare("
    SELECT b.*, h.belge_adi FROM personel_belgeler b 
    INNER JOIN hazir_belgeler h ON b.belge_id = h.belge_id 
    WHERE b.tc_kimlik = ?");
$alinan->execute([$tc]);
$alinanBelgeler = $alinan->fetchAll(PDO::FETCH_ASSOC);

// Ehliyetler
$ehliyetler = $db->prepare("
    SELECT pe.*, he.ehliyet_adi FROM personel_ehliyetler pe 
    INNER JOIN hazir_ehliyetler he ON pe.ehliyet_id = he.ehliyet_id 
    WHERE pe.tc_kimlik = ?");
$ehliyetler->execute([$tc]);
$ehliyetVerileri = $ehliyetler->fetchAll(PDO::FETCH_ASSOC);

// İş Kazaları
$isKazalari = $db->prepare("
    SELECT pik.*, hik.is_kazasi_tipi_adi, hyd.yaralanma_durum_adi, hyt.yaralanma_tipi_adi 
    FROM personel_is_kazalari pik 
    INNER JOIN hazir_is_kazalari hik ON pik.is_kazasi_tip_id = hik.is_kazasi_tip_id 
    INNER JOIN hazir_yaralanma_durumlar hyd ON pik.yaralanma_durumu_id = hyd.yaralanma_durum_id 
    INNER JOIN hazir_yaralanma_tipler hyt ON pik.yaralanma_tip_id = hyt.yaralanma_tip_id 
    WHERE pik.tc_kimlik = ?");
$isKazalari->execute([$tc]);
$kazaVerileri = $isKazalari->fetchAll(PDO::FETCH_ASSOC);

// Uyarılar
$uyarilar = $db->prepare("
    SELECT pu.*, hu.uyari_tipi_adi FROM personel_uyarilar pu 
    INNER JOIN hazir_uyarilar hu ON pu.uyari_tipi_id = hu.uyari_tip_id 
    WHERE pu.tc_kimlik = ?");
$uyarilar->execute([$tc]);
$uyariVerileri = $uyarilar->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <!-- Profil Resmi -->
    <?php if (!empty($kisi['pp_dosya_yolu'])): ?>
        <div class="text-center mb-4">
            <img src="uploads/<?= htmlspecialchars($kisi['pp_dosya_yolu']) ?>" alt="Profil Resmi" class="img-fluid rounded-circle" style="max-width: 150px; max-height: 150px; object-fit: cover;">
        </div>
    <?php else: ?>
        <div class="text-center mb-4">
            <img src="default-profile.png" alt="Varsayılan Profil Resmi" class="img-fluid rounded-circle" style="max-width: 150px; max-height: 150px; object-fit: cover;">
        </div>
    <?php endif; ?>

    <h3 class="text-primary mb-3">Personel Detayları</h3>

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Kişisel Bilgiler</div>
        <div class="card-body">
            <p><strong>TC Kimlik:</strong> <?= htmlspecialchars($kisi['tc_kimlik']) ?></p>
            <p><strong>Ad Soyad:</strong> <?= htmlspecialchars($kisi['ad_soyad']) ?></p>
            <p><strong>Cinsiyet:</strong> <?php if($kisi['cinsiyet'] == 1) echo 'Erkek'; else echo 'Kadın'; ?></p>
            <p><strong>Doğum Tarihi:</strong> <?= htmlspecialchars($kisi['dogum_tarihi']) ?></p>
            <p><strong>Telefon:</strong> <?= htmlspecialchars($kisi['telefon']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($kisi['email']) ?></p>
            <p><strong>Adres:</strong> <?= htmlspecialchars($kisi['adres']) ?></p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-success text-white">Firma & Meslek</div>
        <div class="card-body">
            <p><strong>Firma:</strong> <?= htmlspecialchars($sirketVeri['firma_adi']) ?></p>
            <p><strong>Meslek:</strong> <?= htmlspecialchars($sirketVeri['meslek_adi']) ?></p>
            <p><strong>İşe Giriş Tarihi:</strong> <?= htmlspecialchars($sirketVeri['ise_giris_tarihi']) ?></p>
            <p><strong>Deneyim:</strong> <?= htmlspecialchars($sirketVeri['toplam_deneyim_yili']) ?> yıl</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-warning text-dark">Gerekli Belgeler</div>
        <div class="card-body">
            <p><strong>İşe Giriş Eğitimi:</strong> <?= $gerekli['ise_giris_egitimi_var_mi'] ? "Var" : "Yok" ?></p>
            <p><strong>Operatörlük Belgesi:</strong> <?= $gerekli['operatorluk_belgesi_var_mi'] ? "Var" : "Yok" ?></p>
            <p><strong>Mesleki Yeterlilik Belgesi:</strong> <?= $gerekli['mesleki_yeterlilik_belgesi_var_mi'] ? "Var" : "Yok" ?></p>
            <p><strong>Sağlık Tetkikleri:</strong> <?= $gerekli['saglik_tetkikleri_oldu_mu'] ? "Yapıldı" : "Yapılmadı" ?></p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-danger text-white">Sağlık Tetkikleri</div>
        <div class="card-body">
            <p><strong>Yapılma Tarihi:</strong> <?= htmlspecialchars($saglikVeri['tarih'] ?? "-") ?></p>
            <p><strong>Geçerlilik Tarihi:</strong> <?= htmlspecialchars($saglikVeri['gecerlilik_tarihi'] ?? "-") ?></p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-info text-white">Alınan Belgeler</div>
        <div class="card-body">
            <ul>
                <?php foreach ($alinanBelgeler as $belge): ?>
                    <li><?= htmlspecialchars($belge['belge_adi']) ?> (<?= htmlspecialchars($belge['alinma_tarihi']) ?>)</li>
                <?php endforeach; ?>
                <?php if (empty($alinanBelgeler)): ?>
                    <li>Alınan belge bulunmamaktadır.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-secondary text-white">Ehliyetler</div>
        <div class="card-body">
            <ul>
                <?php foreach ($ehliyetVerileri as $ehliyet): ?>
                    <li><?= htmlspecialchars($ehliyet['ehliyet_adi']) ?> (<?= htmlspecialchars($ehliyet['alinma_tarihi']) ?>)</li>
                <?php endforeach; ?>
                <?php if (empty($ehliyetVerileri)): ?>
                    <li>Ehliyet bilgisi bulunmamaktadır.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-dark text-white">İş Kazaları</div>
        <div class="card-body">
            <ul>
                <?php foreach ($kazaVerileri as $kaza): ?>
                    <li>
                        <strong>Tarih:</strong> <?= htmlspecialchars($kaza['is_kazasi_tarihi']) ?><br>
                        <strong>Tür:</strong> <?= htmlspecialchars($kaza['is_kazasi_tipi_adi']) ?><br>
                        <strong>Yaralanma Durumu:</strong> <?= htmlspecialchars($kaza['yaralanma_durum_adi']) ?><br>
                        <strong>Yaralanma Tipi:</strong> <?= htmlspecialchars($kaza['yaralanma_tipi_adi']) ?><br>
                        <strong>Neden:</strong> <?= htmlspecialchars($kaza['kaza_nedeni']) ?>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($kazaVerileri)): ?>
                    <li>İş kazası kaydı bulunmamaktadır.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Uyarılar</div>
        <div class="card-body">
            <ul>
                <?php foreach ($uyariVerileri as $uyari): ?>
                    <li>
                        <strong>Tarih:</strong> <?= htmlspecialchars($uyari['uyari_tarihi']) ?><br>
                        <strong>Tür:</strong> <?= htmlspecialchars($uyari['uyari_tipi_adi']) ?><br>
                        <strong>Neden:</strong> <?= htmlspecialchars($uyari['uyari_nedeni']) ?>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($uyariVerileri)): ?>
                    <li>Uyarı kaydı bulunmamaktadır.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php 
// Oturumu temizle (opsiyonel, başka sayfaya yönlendirme yoksa gerekli olmayabilir)
// unset($_SESSION['detay_tc_kimlik']);
require_once 'footer.php'; 
?>