<?php
require_once 'header.php';

// Hata kontrolü için PDO ayarları
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Kartlar için veri çekme
    $aylikSorgu = $db->prepare("
        SELECT COUNT(*) AS uyari_sayisi
        FROM personel_uyarilar
        WHERE YEAR(uyari_tarihi) = YEAR(CURDATE())
        AND MONTH(uyari_tarihi) = MONTH(CURDATE())
    ");
    $aylikSorgu->execute();
    $aylikUyari = $aylikSorgu->fetch(PDO::FETCH_ASSOC);
    $aylikUyariSayisi = $aylikUyari['uyari_sayisi'];

    $yillikSorgu = $db->prepare("
        SELECT COUNT(*) AS uyari_sayisi
        FROM personel_uyarilar
        WHERE YEAR(uyari_tarihi) = YEAR(CURDATE())
    ");
    $yillikSorgu->execute();
    $yillikUyari = $yillikSorgu->fetch(PDO::FETCH_ASSOC);
    $yillikUyariSayisi = $yillikUyari['uyari_sayisi'];

    $toplamSorgu = $db->prepare("
        SELECT COUNT(*) AS uyari_sayisi
        FROM personel_uyarilar
    ");
    $toplamSorgu->execute();
    $toplamUyari = $toplamSorgu->fetch(PDO::FETCH_ASSOC);
    $toplamUyariSayisi = $toplamUyari['uyari_sayisi'];

    $son14GunSorgu = $db->prepare("
        SELECT COUNT(*) AS uyari_sayisi
        FROM personel_uyarilar
        WHERE uyari_tarihi >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
    ");
    $son14GunSorgu->execute();
    $son14GunUyari = $son14GunSorgu->fetch(PDO::FETCH_ASSOC);
    $son14GunUyariSayisi = $son14GunUyari['uyari_sayisi'];

    // Aylık grafik verileri (son 12 ay)
    $aylikGrafikSorgu = $db->query("
        SELECT DATE_FORMAT(uyari_tarihi, '%Y-%m') AS ay, COUNT(*) AS uyari_sayisi
        FROM personel_uyarilar
        WHERE uyari_tarihi >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(uyari_tarihi, '%Y-%m')
        ORDER BY ay
    ");
    $aylikUyarilar = $aylikGrafikSorgu->fetchAll(PDO::FETCH_ASSOC);

    $aylar = [];
    $aylikUyariSayilari = [];
    $currentDate = new DateTime();
    for ($i = 11; $i >= 0; $i--) {
        $date = (clone $currentDate)->modify("-$i months");
        $aylar[] = $date->format('Y-m');
        $aylikUyariSayilari[$date->format('Y-m')] = 0;
    }

    foreach ($aylikUyarilar as $aylik) {
        $aylikUyariSayilari[$aylik['ay']] = (int)$aylik['uyari_sayisi'];
    }
    $aylikUyariSayilari = array_values($aylikUyariSayilari);

    // Yıllık grafik verileri (son 10 yıl)
    $yillikGrafikSorgu = $db->query("
        SELECT YEAR(uyari_tarihi) AS yil, COUNT(*) AS uyari_sayisi
        FROM personel_uyarilar
        WHERE uyari_tarihi >= DATE_SUB(CURDATE(), INTERVAL 10 YEAR)
        GROUP BY YEAR(uyari_tarihi)
        ORDER BY yil
    ");
    $yillikUyarilar = $yillikGrafikSorgu->fetchAll(PDO::FETCH_ASSOC);

    $yillar = [];
    $yillikUyariSayilari = [];
    for ($i = 9; $i >= 0; $i--) {
        $yil = (int)date('Y') - $i;
        $yillar[] = (string)$yil;
        $yillikUyariSayilari[$yil] = 0;
    }

    foreach ($yillikUyarilar as $yillik) {
        $yillikUyariSayilari[$yillik['yil']] = (int)$yillik['uyari_sayisi'];
    }
    $yillikUyariSayilari = array_values($yillikUyariSayilari);

    // Uyarı türleri grafik verileri
    $turSorgu = $db->query("
        SELECT hu.uyari_tipi_adi, COUNT(pu.id) AS uyari_sayisi
        FROM personel_uyarilar pu
        INNER JOIN hazir_uyarilar hu ON pu.uyari_tipi_id = hu.uyari_tip_id
        GROUP BY hu.uyari_tipi_adi
    ");
    $uyariTurleri = $turSorgu->fetchAll(PDO::FETCH_ASSOC);

    $turAdlari = [];
    $turSayilari = [];
    $renkler = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'];
    foreach ($uyariTurleri as $tur) {
        $turAdlari[] = $tur['uyari_tipi_adi'];
        $turSayilari[] = (int)$tur['uyari_sayisi'];
    }
    if (count($turAdlari) > count($renkler)) {
        $renkler = array_merge($renkler, array_map(function($i) {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }, range(0, count($turAdlari) - count($renkler) - 1)));
    }

    // Yaş grubu analizi
    $yasGrubuSorgu = $db->query("
        SELECT 
            CASE 
                WHEN FLOOR((YEAR(CURDATE()) - YEAR(pkb.dogum_tarihi))) BETWEEN 18 AND 25 THEN '18-25'
                WHEN FLOOR((YEAR(CURDATE()) - YEAR(pkb.dogum_tarihi))) BETWEEN 26 AND 35 THEN '26-35'
                WHEN FLOOR((YEAR(CURDATE()) - YEAR(pkb.dogum_tarihi))) BETWEEN 36 AND 45 THEN '36-45'
                WHEN FLOOR((YEAR(CURDATE()) - YEAR(pkb.dogum_tarihi))) BETWEEN 46 AND 55 THEN '46-55'
                ELSE '56+' 
            END AS yas_grubu,
            COUNT(pu.id) AS uyari_sayisi
        FROM personel_kisisel_bilgi pkb
        LEFT JOIN personel_uyarilar pu ON pkb.tc_kimlik = pu.tc_kimlik
        GROUP BY yas_grubu
        ORDER BY yas_grubu
    ");
    $yasGruplari = $yasGrubuSorgu->fetchAll(PDO::FETCH_ASSOC);

    $yasGrubuAdlari = [];
    $yasGrubuSayilari = [];
    foreach ($yasGruplari as $grup) {
        $yasGrubuAdlari[] = $grup['yas_grubu'];
        $yasGrubuSayilari[] = (int)$grup['uyari_sayisi'];
    }

    // Cinsiyet bazlı uyarı analizi
    $cinsiyetSorgu = $db->query("
        SELECT 
            CASE pkb.cinsiyet 
                WHEN 1 THEN 'Erkek' 
                WHEN 0 THEN 'Kadın' 
            END AS cinsiyet,
            hu.uyari_tipi_adi,
            COUNT(pu.id) AS uyari_sayisi
        FROM personel_kisisel_bilgi pkb
        LEFT JOIN personel_uyarilar pu ON pkb.tc_kimlik = pu.tc_kimlik
        LEFT JOIN hazir_uyarilar hu ON pu.uyari_tipi_id = hu.uyari_tip_id
        WHERE pu.id IS NOT NULL
        GROUP BY pkb.cinsiyet, hu.uyari_tipi_adi
        ORDER BY pkb.cinsiyet, uyari_sayisi DESC
    ");
    $cinsiyetUyarilar = $cinsiyetSorgu->fetchAll(PDO::FETCH_ASSOC);

    $cinsiyetData = ['Erkek' => [], 'Kadın' => []];
    $uyariTurleriCinsiyet = [];
    foreach ($cinsiyetUyarilar as $uyari) {
        $cinsiyet = $uyari['cinsiyet'];
        $tur = $uyari['uyari_tipi_adi'];
        if (!in_array($tur, $uyariTurleriCinsiyet)) {
            $uyariTurleriCinsiyet[] = $tur;
        }
        $cinsiyetData[$cinsiyet][$tur] = (int)$uyari['uyari_sayisi'];
    }
    foreach ($cinsiyetData as &$data) {
        foreach ($uyariTurleriCinsiyet as $tur) {
            if (!isset($data[$tur])) {
                $data[$tur] = 0;
            }
        }
    }

    // Meslek bazlı uyarı analizi
    $meslekSorgu = $db->query("
        SELECT hm.meslek_adi, COUNT(pu.id) AS uyari_sayisi
        FROM personel_uyarilar pu
        INNER JOIN personel_sirket_bilgi psb ON pu.tc_kimlik = psb.tc_kimlik
        INNER JOIN hazir_meslekler hm ON psb.meslek_id = hm.meslek_id
        GROUP BY hm.meslek_adi
        ORDER BY uyari_sayisi DESC
    ");
    $meslekUyarilar = $meslekSorgu->fetchAll(PDO::FETCH_ASSOC);

    $meslekAdlari = [];
    $meslekSayilari = [];
    foreach ($meslekUyarilar as $meslek) {
        $meslekAdlari[] = $meslek['meslek_adi'];
        $meslekSayilari[] = (int)$meslek['uyari_sayisi'];
    }

    // Deneyim yılına göre uyarı analizi
    $deneyimSorgu = $db->query("
        SELECT 
            CASE 
                WHEN psb.toplam_deneyim_yili BETWEEN 0 AND 2 THEN '0-2 Yıl'
                WHEN psb.toplam_deneyim_yili BETWEEN 3 AND 5 THEN '3-5 Yıl'
                WHEN psb.toplam_deneyim_yili BETWEEN 6 AND 10 THEN '6-10 Yıl'
                ELSE '10+ Yıl'
            END AS deneyim_grubu,
            COUNT(pu.id) AS uyari_sayisi
        FROM personel_uyarilar pu
        INNER JOIN personel_sirket_bilgi psb ON pu.tc_kimlik = psb.tc_kimlik
        GROUP BY deneyim_grubu
        ORDER BY deneyim_grubu
    ");
    $deneyimGruplari = $deneyimSorgu->fetchAll(PDO::FETCH_ASSOC);

    $deneyimGrubuAdlari = [];
    $deneyimGrubuSayilari = [];
    foreach ($deneyimGruplari as $grup) {
        $deneyimGrubuAdlari[] = $grup['deneyim_grubu'];
        $deneyimGrubuSayilari[] = (int)$grup['uyari_sayisi'];
    }

    // Uyarılar tablosu için veri çekme
    $uyariSorgu = $db->prepare("
        SELECT pkb.tc_kimlik, pkb.ad_soyad, hu.uyari_tipi_adi, pu.uyari_nedeni, pu.uyari_tarihi
        FROM personel_uyarilar pu
        INNER JOIN personel_kisisel_bilgi pkb ON pu.tc_kimlik = pkb.tc_kimlik
        INNER JOIN hazir_uyarilar hu ON pu.uyari_tipi_id = hu.uyari_tip_id
        ORDER BY pu.uyari_tarihi DESC
    ");
    $uyariSorgu->execute();
    $uyarilar = $uyariSorgu->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Veritabanı hatası: ' . htmlspecialchars($e->getMessage()) . '</div>';
    require_once 'footer.php';
    exit;
}
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


<div class="container-fluid mt-4">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Uyarı Analizi</h1>
    </div>


    <!-- Kartlar -->
    <div class="row mb-4">
        <!-- Son 14 Gündeki Uyarılar -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Son 14 Gündeki Uyarılar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $son14GunUyariSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aylık Uyarı Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Aylık Uyarı Sayısı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $aylikUyariSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yıllık Uyarı Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Yıllık Uyarı Sayısı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $yillikUyariSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toplam Uyarı Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Toplam Uyarı Sayısı</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $toplamUyariSayisi; ?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: <?php echo min(($toplamUyariSayisi / 100) * 100, 100); ?>%"
                                            aria-valuenow="<?php echo $toplamUyariSayisi; ?>"
                                            aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafikler -->
    <div class="row mb-4">
        <!-- Aylık/Yıllık Uyarı Grafiği -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <button id="aylikBtn" class="btn btn-primary btn-sm">Aylık Uyarı Sayısı</button>
                        <button id="yillikBtn" class="btn btn-secondary btn-sm">Yıllık Uyarı Sayısı</button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($aylar) && empty($yillar)) { ?>
                        <div class="alert alert-info">Uyarı verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-area" style="height: 400px;">
                            <canvas id="uyarilarAreaChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Uyarı Türleri Grafiği -->
        <div class="col-xl-3 col-lg-5">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Uyarı Türleri Dağılımı</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($turAdlari)) { ?>
                        <div class="alert alert-info">Uyarı türü verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-pie pt-4 pb-2" style="height: 400px;">
                            <canvas id="uyarilarPieChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Yaş Grubu Grafiği -->
        <div class="col-xl-3 col-lg-5">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Yaş Gruplarına Göre Uyarı Dağılımı</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($yasGrubuAdlari)) { ?>
                        <div class="alert alert-info">Yaş grubu verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-bar" style="height: 400px;">
                            <canvas id="yasGrubuBarChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Cinsiyet Bazlı Uyarı Grafiği -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cinsiyete Göre Uyarı Türleri</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($uyariTurleriCinsiyet)) { ?>
                        <div class="alert alert-info">Cinsiyet bazlı uyarı verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-bar" style="height: 400px;">
                            <canvas id="cinsiyetBarChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Meslek Bazlı Uyarı Grafiği -->
        <div class="col-xl-3 col-lg-6">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mesleklere Göre Uyarı Dağılımı</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($meslekAdlari)) { ?>
                        <div class="alert alert-info">Meslek bazlı uyarı verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-pie pt-4 pb-2" style="height: 400px;">
                            <canvas id="meslekPieChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Deneyim Yılı Grafiği -->
        <div class="col-xl-3 col-lg-6">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Deneyim Yılına Göre Uyarı Dağılımı</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($deneyimGrubuAdlari)) { ?>
                        <div class="alert alert-info">Deneyim yılı verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-bar" style="height: 400px;">
                            <canvas id="deneyimBarChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Uyarılar Tablosu -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tüm Uyarılar</h4>
                </div>
                <div class="card-body">
                    <button id="excel-indir" class="btn btn-primary mb-3">Tabloyu Excel Olarak İndir</button>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered text-center align-middle" id="uyarilarTablo">
                            <thead class="table-dark">
                                <tr>
                                    <th>TC Kimlik</th>
                                    <th class="only-tablet">Ad Soyad</th>
                                    <th class="only-desktop">Uyarı Türü</th>
                                    <th class="only-tablet">Uyarı Nedeni</th>
                                    <th class="only-desktop">Uyarı Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($uyarilar as $uyari) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($uyari['tc_kimlik']); ?></td>
                                        <td class="only-tablet"><?php echo htmlspecialchars($uyari['ad_soyad']); ?></td>
                                        <td class="only-desktop"><?php echo htmlspecialchars($uyari['uyari_tipi_adi']); ?></td>
                                        <td class="only-tablet"><?php echo htmlspecialchars($uyari['uyari_nedeni']); ?></td>
                                        <td class="only-desktop"><?php echo date("d.m.Y H:i", strtotime($uyari['uyari_tarihi'])); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm d-flex flex-wrap justify-content-center gap-1">
                                                <a href="personel_detay.php?tc_kimlik=<?php echo htmlspecialchars($uyari['tc_kimlik']); ?>" class="btn btn-info text-white">Görüntüle</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stil Ayarları -->
<style>
.chart-area, .chart-pie, .chart-bar {
    position: relative;
    width: 100%;
    height: 400px !important;
}
#uyarilarPieChartLegend, #meslekPieChartLegend {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
}
#uyarilarPieChartLegend span, #meslekPieChartLegend span {
    flex: 0 0 50%;
    max-width: 50%;
    box-sizing: border-box;
    padding: 5px;
}
@media (max-width: 767.98px) {
    #uyarilarPieChartLegend span, #meslekPieChartLegend span {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
.only-desktop, .only-tablet {
    display: table-cell;
}
@media (max-width: 991.98px) {
    .only-desktop {
        display: none !important;
    }
}
@media (max-width: 767.98px) {
    .only-tablet {
        display: none !important;
    }
}
@media (max-width: 576px) {
    td, th {
        font-size: 0.75rem;
    }
}
</style>

<!-- JavaScript Kodları -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function () {
    const ctxMonthly = document.getElementById('uyarilarAreaChart');
    const ctxPie = document.getElementById('uyarilarPieChart');
    const ctxYasGrubu = document.getElementById('yasGrubuBarChart');
    const ctxCinsiyet = document.getElementById('cinsiyetBarChart');
    const ctxMeslek = document.getElementById('meslekPieChart');
    const ctxDeneyim = document.getElementById('deneyimBarChart');

    if (!ctxMonthly || !ctxPie || !ctxYasGrubu || !ctxCinsiyet || !ctxMeslek || !ctxDeneyim) {
        console.error('Canvas elemanları bulunamadı!');
        return;
    }

    // Aylık ve Yıllık Veri Setleri
    const aylikData = {
        labels: <?php echo json_encode($aylar); ?>,
        datasets: [{
            label: 'Aylık Uyarı Sayısı',
            data: <?php echo json_encode($aylikUyariSayilari); ?>,
            fill: true,
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderColor: '#4e73df',
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 8
        }]
    };

    const yillikData = {
        labels: <?php echo json_encode($yillar); ?>,
        datasets: [{
            label: 'Yıllık Uyarı Sayısı',
            data: <?php echo json_encode($yillikUyariSayilari); ?>,
            fill: true,
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderColor: '#4e73df',
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 8
        }]
    };

    // Aylık/Yıllık Grafik Konfigürasyonu
    const chartConfig = {
        type: 'line',
        data: aylikData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Uyarı Sayısı',
                        font: { size: 14 }
                    },
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        display: true,
                        drawBorder: true,
                        drawOnChartArea: true
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tarih',
                        font: { size: 14 }
                    },
                    grid: {
                        display: true,
                        drawBorder: true,
                        drawOnChartArea: true
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 12 }
                    }
                }
            }
        }
    };

    const areaChart = new Chart(ctxMonthly.getContext('2d'), chartConfig);

    // Buton İşlevselliği
    $('#aylikBtn').click(function() {
        areaChart.data = aylikData;
        areaChart.options.scales.x.title.text = 'Ay';
        areaChart.update();
        $(this).addClass('btn-primary').removeClass('btn-secondary');
        $('#yillikBtn').addClass('btn-secondary').removeClass('btn-primary');
    });

    $('#yillikBtn').click(function() {
        areaChart.data = yillikData;
        areaChart.options.scales.x.title.text = 'Yıl';
        areaChart.update();
        $(this).addClass('btn-primary').removeClass('btn-secondary');
        $('#aylikBtn').addClass('btn-secondary').removeClass('btn-primary');
    });

    // Uyarı Türleri Grafiği
    const warningTypesData = {
        labels: <?php echo json_encode($turAdlari); ?>,
        datasets: [{
            data: <?php echo json_encode($turSayilari); ?>,
            backgroundColor: <?php echo json_encode($renkler); ?>,
            borderColor: '#fff',
            borderWidth: 2
        }]
    };

    new Chart(ctxPie.getContext('2d'), {
        type: 'pie',
        data: warningTypesData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 12 },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const backgroundColor = data.datasets[0].backgroundColor[i];
                                    return {
                                        text: `${label}: ${value}`,
                                        fillStyle: backgroundColor,
                                        strokeStyle: backgroundColor,
                                        lineWidth: 1,
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });

    // Yaş Grubu Grafiği
    new Chart(ctxYasGrubu.getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($yasGrubuAdlari); ?>,
            datasets: [{
                label: 'Uyarı Sayısı',
                data: <?php echo json_encode($yasGrubuSayilari); ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.5)',
                borderColor: '#4e73df',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Uyarı Sayısı' }
                },
                x: {
                    title: { display: true, text: 'Yaş Grubu' }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' }
            }
        }
    });

    // Cinsiyet Bazlı Grafik
    new Chart(ctxCinsiyet.getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($uyariTurleriCinsiyet); ?>,
            datasets: [
                {
                    label: 'Erkek',
                    data: <?php echo json_encode(array_values($cinsiyetData['Erkek'])); ?>,
                    backgroundColor: 'rgba(78, 115, 223, 0.5)',
                    borderColor: '#4e73df',
                    borderWidth: 1
                },
                {
                    label: 'Kadın',
                    data: <?php echo json_encode(array_values($cinsiyetData['Kadın'])); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: '#ff6384',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Uyarı Sayısı' },
                    stacked: true
                },
                x: {
                    title: { display: true, text: 'Uyarı Türü' },
                    stacked: true
                }
            },
            plugins: {
                legend: { display: true, position: 'top' }
            }
        }
    });

    // Meslek Bazlı Grafik
    const meslekData = {
        labels: <?php echo json_encode($meslekAdlari); ?>,
        datasets: [{
            data: <?php echo json_encode($meslekSayilari); ?>,
            backgroundColor: <?php echo json_encode($renkler); ?>,
            borderColor: '#fff',
            borderWidth: 2
        }]
    };

    new Chart(ctxMeslek.getContext('2d'), {
        type: 'pie',
        data: meslekData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 12 },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const backgroundColor = data.datasets[0].backgroundColor[i];
                                    return {
                                        text: `${label}: ${value}`,
                                        fillStyle: backgroundColor,
                                        strokeStyle: backgroundColor,
                                        lineWidth: 1,
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });

    // Deneyim Yılı Grafiği
    new Chart(ctxDeneyim.getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($deneyimGrubuAdlari); ?>,
            datasets: [{
                label: 'Uyarı Sayısı',
                data: <?php echo json_encode($deneyimGrubuSayilari); ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.5)',
                borderColor: '#4e73df',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Uyarı Sayısı' }
                },
                x: {
                    title: { display: true, text: 'Deneyim Yılı' }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' }
            }
        }
    });

    // DataTable ile tabloyu başlatma
    $('#uyarilarTablo').DataTable({
        responsive: true,
        autoWidth: false,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
        language: dildosyasi,
        order: [[4, 'desc']],
    });
});
</script>

<script>
    document.getElementById('excel-indir').addEventListener('click', function() {
        // Tabloyu seç
        var table = document.getElementById('uyarilarTablo');
        
        // Tabloyu worksheet'e çevir
        var ws = XLSX.utils.table_to_sheet(table);
        
        // "İşlemler" kolonunu kaldır
        var range = XLSX.utils.decode_range(ws['!ref']);
        for (var C = range.s.c; C <= range.e.c; ++C) {
            var cell = ws[XLSX.utils.encode_cell({ r: 0, c: C })];
            if (cell && cell.v === 'İşlemler') {
                for (var R = range.s.r; R <= range.e.r; ++R) {
                    delete ws[XLSX.utils.encode_cell({ r: R, c: C })];
                }
                break;
            }
        }
        
        // Workbook oluştur
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Uyarı Analizleri Listesi');
        
        // Excel dosyasını indir
        XLSX.writeFile(wb, 'uyari_analizleri_listesi.xlsx');
    });
</script>

<?php require_once 'footer.php'; ?>