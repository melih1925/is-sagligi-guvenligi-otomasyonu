<?php
require_once 'header.php';

// Hata kontrolü için PDO ayarları
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Kaza ID'sini al
$kaza_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Önce kaza tarihini al
    $sorgu_tarih = $db->prepare("SELECT is_kazasi_tarihi FROM aracli_kazalar WHERE id = ?");
    $sorgu_tarih->execute([$kaza_id]);
    $kaza_tarih = $sorgu_tarih->fetch(PDO::FETCH_ASSOC);

    if (!$kaza_tarih) {
        echo '<div class="alert alert-danger">Kaza bulunamadı!</div>';
        require_once 'footer.php';
        exit;
    }

    $is_kazasi_tarihi = $kaza_tarih['is_kazasi_tarihi'];

    // Ana sorgu
    $sorgu = $db->prepare("
        SELECT 
            ak.id, ak.tc_kimlik, pkb.ad_soyad, pkb.dogum_tarihi, pkb.cinsiyet, 
            hik.is_kazasi_tipi_adi, hyd.yaralanma_durum_adi, hyt.yaralanma_tipi_adi, 
            ak.kaza_aciklamasi, ak.is_kazasi_tarihi,
            ab.plaka_no, hat.arac_tipi_adi, hm_marka.marka_adi, hmo.model_adi, ab.uretim_yili,
            hf.firma_adi, had.arac_durum_adi,
            am.muayene_tarihi, am.muayeneden_gecti_mi, am.muayene_gecerlilik_tarihi,
            aoa.tc_kimlik AS operator_tc_kimlik, pkb_op.ad_soyad AS operator_ad_soyad,
            aoa.atama_tarihi, aoa.gorev_sonu_tarihi,
            psb.firma_id, hf_psb.firma_adi AS personel_firma_adi, psb.meslek_id, hm.meslek_adi,
            psb.ise_giris_tarihi, psb.toplam_deneyim_yili
        FROM aracli_kazalar ak
        INNER JOIN personel_kisisel_bilgi pkb ON ak.tc_kimlik = pkb.tc_kimlik
        INNER JOIN hazir_is_kazalari hik ON ak.is_kazasi_tip_id = hik.is_kazasi_tip_id
        INNER JOIN hazir_yaralanma_durumlar hyd ON ak.yaralanma_durumu_id = hyd.yaralanma_durum_id
        INNER JOIN hazir_yaralanma_tipler hyt ON ak.yaralanma_tip_id = hyt.yaralanma_tip_id
        INNER JOIN personel_sirket_bilgi psb ON ak.tc_kimlik = psb.tc_kimlik
        INNER JOIN hazir_firmalar hf_psb ON psb.firma_id = hf_psb.firma_id
        INNER JOIN hazir_meslekler hm ON psb.meslek_id = hm.meslek_id
        LEFT JOIN arac_bilgi ab ON ak.arac_id = ab.arac_id
        LEFT JOIN hazir_arac_tipleri hat ON ab.arac_tipi_id = hat.arac_tipi_id
        LEFT JOIN hazir_markalar hm_marka ON ab.marka_id = hm_marka.marka_id
        LEFT JOIN hazir_modeller hmo ON ab.model_id = hmo.model_id
        LEFT JOIN hazir_firmalar hf ON ab.firma_id = hf.firma_id
        LEFT JOIN hazir_arac_durumlari had ON ab.arac_durum_id = had.arac_durum_id
        LEFT JOIN (
            SELECT arac_id, muayene_tarihi, muayeneden_gecti_mi, muayene_gecerlilik_tarihi
            FROM arac_muayene am1
            WHERE am1.muayene_tarihi = (
                SELECT MAX(am2.muayene_tarihi)
                FROM arac_muayene am2
                WHERE am2.arac_id = am1.arac_id
                AND am2.muayene_tarihi <= ?
            )
        ) am ON ab.arac_id = am.arac_id
        LEFT JOIN arac_operator_atama aoa ON ak.arac_id = aoa.arac_id 
            AND aoa.atama_tarihi <= ? 
            AND (aoa.gorev_sonu_tarihi IS NULL OR aoa.gorev_sonu_tarihi >= ?)
        LEFT JOIN personel_kisisel_bilgi pkb_op ON aoa.tc_kimlik = pkb_op.tc_kimlik
        WHERE ak.id = ?
    ");
    $sorgu->execute([$is_kazasi_tarihi, $is_kazasi_tarihi, $is_kazasi_tarihi, $kaza_id]);
    $kaza = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$kaza) {
        echo '<div class="alert alert-danger">Kaza bulunamadı!</div>';
        require_once 'footer.php';
        exit;
    }

    // Yaş hesaplama
    $dogum_tarihi = new DateTime($kaza['dogum_tarihi']);
    $bugun = new DateTime();
    $yas = $bugun->diff($dogum_tarihi)->y;

    // Cinsiyet formatlama
    $cinsiyet = $kaza['cinsiyet'] == 1 ? 'Erkek' : 'Kadın';

    // Muayeneden geçti mi formatlama
    $muayeneden_gecti = $kaza['muayeneden_gecti_mi'] === null ? 'Bilinmiyor' : ($kaza['muayeneden_gecti_mi'] == 1 ? 'Evet' : 'Hayır');

} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Veritabanı hatası: ' . htmlspecialchars($e->getMessage()) . '</div>';
    require_once 'footer.php';
    exit;
}
?>

<div class="container-fluid mt-4">
    <!-- Genel Başlık -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">KAZA BİLGİLERİ</h1>
    </div>

    <!-- Kaza Detay Kutuları -->
    <div class="row mb-3" id="kazaDetayAlani">
        <!-- Personel Bilgileri -->
        <div class="col-12 mb-2">
            <div class="card shadow border-left-primary">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Personel Bilgileri</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 pr-2">
                            <p><strong>TC Kimlik:</strong> <?php echo htmlspecialchars($kaza['tc_kimlik']); ?></p>
                            <p><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($kaza['ad_soyad']); ?></p>
                            <p><strong>Yaş:</strong> <?php echo $yas; ?></p>
                            <p><strong>Cinsiyet:</strong> <?php echo $cinsiyet; ?></p>
                        </div>
                        <div class="col-6 pl-2">
                            <p><strong>Şirket:</strong> <?php echo htmlspecialchars($kaza['personel_firma_adi'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Meslek:</strong> <?php echo htmlspecialchars($kaza['meslek_adi'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>İşe Giriş:</strong> <?php echo $kaza['ise_giris_tarihi'] ? date("d.m.Y", strtotime($kaza['ise_giris_tarihi'])) : 'Bilinmiyor'; ?></p>
                            <p><strong>Deneyim:</strong> <?php echo htmlspecialchars($kaza['toplam_deneyim_yili'] ?? 'Bilinmiyor'); ?> yıl</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Karışan Araç Bilgileri -->
        <div class="col-12 mb-2">
            <div class="card shadow border-left-success">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Karışan Araç Bilgileri</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 pr-2">
                            <p><strong>Plaka:</strong> <?php echo htmlspecialchars($kaza['plaka_no'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Araç Tipi:</strong> <?php echo htmlspecialchars($kaza['arac_tipi_adi'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Marka:</strong> <?php echo htmlspecialchars($kaza['marka_adi'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Model:</strong> <?php echo htmlspecialchars($kaza['model_adi'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Üretim Yılı:</strong> <?php echo htmlspecialchars($kaza['uretim_yili'] ?? 'Bilinmiyor'); ?></p>
                        </div>
                        <div class="col-6 pl-2">
                            <p><strong>Firma:</strong> <?php echo htmlspecialchars($kaza['firma_adi'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Durum:</strong> <?php echo htmlspecialchars($kaza['arac_durum_adi'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Muayene Tarihi:</strong> <?php echo $kaza['muayene_tarihi'] ? date("d.m.Y", strtotime($kaza['muayene_tarihi'])) : 'Bilinmiyor'; ?></p>
                            <p><strong>Muayene Sonucu:</strong> <?php echo $muayeneden_gecti; ?></p>
                            <p><strong>Geçerlilik:</strong> <?php echo $kaza['muayene_gecerlilik_tarihi'] ? date("d.m.Y", strtotime($kaza['muayene_gecerlilik_tarihi'])) : 'Bilinmiyor'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Araç Operatör Bilgileri -->
        <div class="col-12 mb-2">
            <div class="card shadow border-left-info">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Araç Operatör Bilgileri</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 pr-2">
                            <p><strong>TC Kimlik:</strong> <?php echo htmlspecialchars($kaza['operator_tc_kimlik'] ?? 'Bilinmiyor'); ?></p>
                            <p><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($kaza['operator_ad_soyad'] ?? 'Bilinmiyor'); ?></p>
                        </div>
                        <div class="col-6 pl-2">
                            <p><strong>Atama Tarihi:</strong> <?php echo $kaza['atama_tarihi'] ? date("d.m.Y H:i", strtotime($kaza['atama_tarihi'])) : 'Bilinmiyor'; ?></p>
                            <p><strong>Görev Sonu:</strong> <?php echo $kaza['gorev_sonu_tarihi'] ? date("d.m.Y H:i", strtotime($kaza['gorev_sonu_tarihi'])) : 'Bilinmiyor'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kaza Bilgileri -->
        <div class="col-12 mb-2">
            <div class="card shadow border-left-warning">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">Kaza Bilgileri</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 pr-2">
                            <p><strong>Kaza Türü:</strong> <?php echo htmlspecialchars($kaza['is_kazasi_tipi_adi']); ?></p>
                            <p><strong>Yaralanma Durumu:</strong> <?php echo htmlspecialchars($kaza['yaralanma_durum_adi']); ?></p>
                        </div>
                        <div class="col-6 pl-2">
                            <p><strong>Yaralanma Tipi:</strong> <?php echo htmlspecialchars($kaza['yaralanma_tipi_adi']); ?></p>
                            <p><strong>Kaza Tarihi:</strong> <?php echo date("d.m.Y H:i", strtotime($kaza['is_kazasi_tarihi'])); ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 pl-2">
                            <p><strong>Açıklama:</strong> <?php echo htmlspecialchars($kaza['kaza_aciklamasi']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Geri Dön Butonu -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="aracli_kazalar_analizi.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Geri Dön</a>
        </div>
    </div>
</div>

<!-- Stil Ayarları -->
<style>
#kazaDetayAlani .card {
    border-radius: 6px;
    transition: transform 0.3s ease-in-out;
}
#kazaDetayAlani .card:hover {
    transform: translateY(-3px);
}
#kazaDetayAlani .card-header {
    padding: 0.4rem 0.8rem;
}
#kazaDetayAlani .card-body {
    background-color: #f8f9fa;
    padding: 1.2rem;
}
#kazaDetayAlani p {
    margin-bottom: 0.4rem;
    font-size: 0.9rem;
    line-height: 1.4;
    font-family: Arial, Helvetica, sans-serif; /* Türkçe karakter desteği */
}
#kazaDetayAlani strong {
    color: #4e73df;
}
#kazaDetayAlani .col-6 {
    padding-right: 0.5rem;
    padding-left: 0.5rem;
}
.border-left-primary {
    border-left: 0.15rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.15rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.15rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.15rem solid #f6c23e !important;
}
@media (max-width: 575.98px) {
    #kazaDetayAlani p {
        font-size: 0.8rem;
    }
    #kazaDetayAlani .card-header h6 {
        font-size: 0.9rem;
    }
    #kazaDetayAlani .card-body {
        padding: 0.8rem;
    }
    #kazaDetayAlani .col-6 {
        padding-right: 0.3rem;
        padding-left: 0.3rem;
    }
}
@media print {
    #kazaDetayAlani .card-body {
        padding: 1rem;
    }
    #kazaDetayAlani p {
        font-size: 0.85rem;
    }
}
</style>

<!-- JavaScript Kodları -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>

<?php require_once 'footer.php'; ?>