<?php
session_start();
require_once 'header.php';

// Plaka no kontrolü
if (!isset($_GET['plaka_no']) || empty(trim($_GET['plaka_no']))) {
    $_SESSION['hatalar'] = ["Plaka no belirtilmedi!"];
    header("Location: arac_listele.php");
    exit;
}

$plaka = trim($_GET['plaka_no']);

// Araç bilgilerini çek
$sorgu = $db->prepare("
    SELECT ab.*, tipi.arac_tipi_adi, marka.marka_adi, model.model_adi, firma.firma_adi, durum.arac_durum_adi
    FROM arac_bilgi ab
    LEFT JOIN hazir_arac_tipleri tipi ON ab.arac_tipi_id = tipi.arac_tipi_id
    LEFT JOIN hazir_markalar marka ON ab.marka_id = marka.marka_id
    LEFT JOIN hazir_modeller model ON ab.model_id = model.model_id
    LEFT JOIN hazir_firmalar firma ON ab.firma_id = firma.firma_id
    LEFT JOIN hazir_arac_durumlari durum ON ab.arac_durum_id = durum.arac_durum_id
    WHERE ab.plaka_no = ?
");
$sorgu->execute([$plaka]);
$arac = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$arac) {
    $_SESSION['hatalar'] = ["Bu plakaya ait araç bulunamadı!"];
    header("Location: arac_listele.php");
    exit;
}

// Araç ID’sini al
$arac_id = $arac['arac_id'];

// Kaza kayıtları
$kazalar = $db->prepare("
    SELECT ak.*, hik.is_kazasi_tipi_adi, hyd.yaralanma_durum_adi, hyt.yaralanma_tipi_adi, pkb.ad_soyad
    FROM aracli_kazalar ak
    INNER JOIN hazir_is_kazalari hik ON ak.is_kazasi_tip_id = hik.is_kazasi_tip_id
    INNER JOIN hazir_yaralanma_durumlar hyd ON ak.yaralanma_durumu_id = hyd.yaralanma_durum_id
    INNER JOIN hazir_yaralanma_tipler hyt ON ak.yaralanma_tip_id = hyt.yaralanma_tip_id
    LEFT JOIN personel_kisisel_bilgi pkb ON ak.tc_kimlik = pkb.tc_kimlik
    WHERE ak.arac_id = ?
    ORDER BY ak.is_kazasi_tarihi DESC
");
$kazalar->execute([$arac_id]);
$kazaVerileri = $kazalar->fetchAll(PDO::FETCH_ASSOC);

// Muayene geçmişi
$muayeneler = $db->prepare("
    SELECT * FROM arac_muayene
    WHERE arac_id = ?
    ORDER BY muayene_tarihi DESC
");
$muayeneler->execute([$arac_id]);
$muayeneVerileri = $muayeneler->fetchAll(PDO::FETCH_ASSOC);

// Operatör atamaları
$atamalar = $db->prepare("
    SELECT ao.*, pkb.ad_soyad
    FROM arac_operator_atama ao
    LEFT JOIN personel_kisisel_bilgi pkb ON ao.tc_kimlik = pkb.tc_kimlik
    WHERE ao.arac_id = ?
    ORDER BY ao.atama_tarihi DESC
");
$atamalar->execute([$arac_id]);
$atamaVerileri = $atamalar->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h3 class="text-primary mb-3">Araç Detayları</h3>

    <!-- Hata veya başarı mesajları -->
    <?php if (isset($_SESSION['hatalar']) && !empty($_SESSION['hatalar'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['hatalar'] as $hata): ?>
                <p><?php echo htmlspecialchars($hata); ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['hatalar']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['basari'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['basari']); ?>
            <?php unset($_SESSION['basari']); ?>
        </div>
    <?php endif; ?>

    <!-- Araç Bilgileri -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Araç Bilgileri</div>
        <div class="card-body">
            <p><strong>Plaka No:</strong> <?php echo htmlspecialchars($arac['plaka_no']); ?></p>
            <p><strong>Araç Tipi:</strong> <?php echo htmlspecialchars($arac['arac_tipi_adi']); ?></p>
            <p><strong>Marka:</strong> <?php echo htmlspecialchars($arac['marka_adi']); ?></p>
            <p><strong>Model:</strong> <?php echo htmlspecialchars($arac['model_adi']); ?></p>
            <p><strong>Üretim Yılı:</strong> <?php echo htmlspecialchars($arac['uretim_yili']); ?></p>
            <p><strong>Firma:</strong> <?php echo htmlspecialchars($arac['firma_adi']); ?></p>
            <p><strong>Durum:</strong> <?php echo htmlspecialchars($arac['arac_durum_adi']); ?></p>
            <p><strong>Veri Giriş Tarihi:</strong> <?php echo htmlspecialchars($arac['veri_giris_tarihi']); ?></p>
        </div>
    </div>

    <!-- Muayene Geçmişi -->
    <div class="card mb-3">
        <div class="card-header bg-success text-white">Muayene Geçmişi</div>
        <div class="card-body">
            <ul>
                <?php foreach ($muayeneVerileri as $muayene): ?>
                    <li>
                        <strong>Tarih:</strong> <?php echo htmlspecialchars($muayene['muayene_tarihi']); ?><br>
                        <strong>Geçti mi:</strong> <?php echo $muayene['muayeneden_gecti_mi'] ? 'Evet' : 'Hayır'; ?><br>
                        <strong>Geçerlilik Tarihi:</strong> <?php echo htmlspecialchars($muayene['muayene_gecerlilik_tarihi'] ?: '-'); ?><br>
                        <strong>Veri Giriş Tarihi:</strong> <?php echo htmlspecialchars($muayene['veri_giris_tarihi']); ?>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($muayeneVerileri)): ?>
                    <li>Muayene kaydı bulunmamaktadır.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Kaza Kayıtları -->
    <div class="card mb-3">
        <div class="card-header bg-danger text-white">Kaza Kayıtları</div>
        <div class="card-body">
            <ul>
                <?php foreach ($kazaVerileri as $kaza): ?>
                    <li>
                        <strong>Tarih:</strong> <?php echo htmlspecialchars($kaza['is_kazasi_tarihi']); ?><br>
                        <strong>Tip:</strong> <?php echo htmlspecialchars($kaza['is_kazasi_tipi_adi']); ?><br>
                        <strong>Yaralanma Durumu:</strong> <?php echo htmlspecialchars($kaza['yaralanma_durum_adi']); ?><br>
                        <strong>Yaralanma Tipi:</strong> <?php echo htmlspecialchars($kaza['yaralanma_tipi_adi']); ?><br>
                        <strong>Operatör:</strong> <?php echo htmlspecialchars($kaza['ad_soyad'] ?: '-'); ?><br>
                        <strong>Açıklama:</strong> <?php echo htmlspecialchars($kaza['kaza_aciklamasi'] ?: '-'); ?><br>
                        <strong>Veri Giriş Tarihi:</strong> <?php echo htmlspecialchars($kaza['veri_giris_tarihi']); ?>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($kazaVerileri)): ?>
                    <li>Kaza kaydı bulunmamaktadır.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Operatör Atamaları -->
    <div class="card mb-3">
        <div class="card-header bg-info text-white">Operatör Atamaları</div>
        <div class="card-body">
            <ul>
                <?php foreach ($atamaVerileri as $atama): ?>
                    <li>
                        <strong>Operatör:</strong> <?php echo htmlspecialchars($atama['ad_soyad'] ?: '-'); ?><br>
                        <strong>Atama Tarihi:</strong> <?php echo htmlspecialchars($atama['atama_tarihi']); ?><br>
                        <strong>Görev Sonu:</strong> <?php echo htmlspecialchars($atama['gorev_sonu_tarihi'] ?: '-'); ?><br>
                        <strong>Veri Giriş Tarihi:</strong> <?php echo htmlspecialchars($atama['veri_giris_tarihi']); ?>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($atamaVerileri)): ?>
                    <li>Operatör atama kaydı bulunmamaktadır.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>