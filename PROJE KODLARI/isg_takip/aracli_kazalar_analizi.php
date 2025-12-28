<?php
require_once 'header.php';

// Enable PDO error handling
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Fetch data for cards
    $aylikSorgu = $db->query("SELECT COUNT(*) AS kaza_sayisi FROM aracli_kazalar WHERE YEAR(is_kazasi_tarihi) = YEAR(CURDATE()) AND MONTH(is_kazasi_tarihi) = MONTH(CURDATE())");
    $aylikKazaSayisi = $aylikSorgu->fetch(PDO::FETCH_ASSOC)['kaza_sayisi'];

    $yillikSorgu = $db->query("SELECT COUNT(*) AS kaza_sayisi FROM aracli_kazalar WHERE YEAR(is_kazasi_tarihi) = YEAR(CURDATE())");
    $yillikKazaSayisi = $yillikSorgu->fetch(PDO::FETCH_ASSOC)['kaza_sayisi'];

    $toplamSorgu = $db->query("SELECT COUNT(*) AS kaza_sayisi FROM aracli_kazalar");
    $toplamKazaSayisi = $toplamSorgu->fetch(PDO::FETCH_ASSOC)['kaza_sayisi'];

    $son14GunSorgu = $db->query("SELECT COUNT(*) AS kaza_sayisi FROM aracli_kazalar WHERE is_kazasi_tarihi >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)");
    $son14GunKazaSayisi = $son14GunSorgu->fetch(PDO::FETCH_ASSOC)['kaza_sayisi'];

    // Monthly chart data (last 12 months)
    $aylikGrafikSorgu = $db->query("
        SELECT DATE_FORMAT(is_kazasi_tarihi, '%Y-%m') AS ay, COUNT(*) AS kaza_sayisi
        FROM aracli_kazalar
        WHERE is_kazasi_tarihi >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY ay
        ORDER BY ay
    ");
    $aylikKazalar = $aylikGrafikSorgu->fetchAll(PDO::FETCH_ASSOC);

    $aylar = [];
    $aylikKazaSayilari = [];
    $currentDate = new DateTime();
    for ($i = 11; $i >= 0; $i--) {
        $date = (clone $currentDate)->modify("-$i months");
        $aylar[] = $date->format('Y-m');
        $aylikKazaSayilari[$date->format('Y-m')] = 0;
    }
    foreach ($aylikKazalar as $aylik) {
        $aylikKazaSayilari[$aylik['ay']] = (int)$aylik['kaza_sayisi'];
    }
    $aylikKazaSayilari = array_values($aylikKazaSayilari);

    // Yearly chart data (last 10 years)
    $yillikGrafikSorgu = $db->query("
        SELECT YEAR(is_kazasi_tarihi) AS yil, COUNT(*) AS kaza_sayisi
        FROM aracli_kazalar
        WHERE is_kazasi_tarihi >= DATE_SUB(CURDATE(), INTERVAL 10 YEAR)
        GROUP BY yil
        ORDER BY yil
    ");
    $yillikKazalar = $yillikGrafikSorgu->fetchAll(PDO::FETCH_ASSOC);

    $yillar = [];
    $yillikKazaSayilari = [];
    for ($i = 9; $i >= 0; $i--) {
        $yil = (int)date('Y') - $i;
        $yillar[] = (string)$yil;
        $yillikKazaSayilari[$yil] = 0;
    }
    foreach ($yillikKazalar as $yillik) {
        $yillikKazaSayilari[$yillik['yil']] = (int)$yillik['kaza_sayisi'];
    }
    $yillikKazaSayilari = array_values($yillikKazaSayilari);

    // Accident types chart data
    $turSorgu = $db->query("
        SELECT hik.is_kazasi_tipi_adi, COUNT(ak.id) AS kaza_sayisi
        FROM aracli_kazalar ak
        INNER JOIN hazir_is_kazalari hik ON ak.is_kazasi_tip_id = hik.is_kazasi_tip_id
        GROUP BY hik.is_kazasi_tipi_adi
    ");
    $kazaTurleri = $turSorgu->fetchAll(PDO::FETCH_ASSOC);

    $turAdlari = [];
    $turSayilari = [];
    $renkler = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'];
    foreach ($kazaTurleri as $tur) {
        $turAdlari[] = $tur['is_kazasi_tipi_adi'];
        $turSayilari[] = (int)$tur['kaza_sayisi'];
    }
    if (count($turAdlari) > count($renkler)) {
        $renkler = array_merge($renkler, array_map(function($i) {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }, range(0, count($turAdlari) - count($renkler) - 1)));
    }

    // Injury status chart data
    $yaralanmaDurumuSorgu = $db->query("
        SELECT hyd.yaralanma_durum_adi, COUNT(ak.id) AS kaza_sayisi
        FROM aracli_kazalar ak
        INNER JOIN hazir_yaralanma_durumlar hyd ON ak.yaralanma_durumu_id = hyd.yaralanma_durum_id
        GROUP BY hyd.yaralanma_durum_adi
    ");
    $yaralanmaDurumlari = $yaralanmaDurumuSorgu->fetchAll(PDO::FETCH_ASSOC);

    $yaralanmaDurumAdlari = [];
    $yaralanmaDurumSayilari = [];
    foreach ($yaralanmaDurumlari as $durum) {
        $yaralanmaDurumAdlari[] = $durum['yaralanma_durum_adi'];
        $yaralanmaDurumSayilari[] = (int)$durum['kaza_sayisi'];
    }

    // Age group chart data
    $yasGrubuSorgu = $db->query("
        SELECT 
            CASE 
                WHEN FLOOR((YEAR(CURDATE()) - YEAR(pkb.dogum_tarihi))) BETWEEN 18 AND 25 THEN '18-25'
                WHEN FLOOR((YEAR(CURDATE()) - YEAR(pkb.dogum_tarihi))) BETWEEN 26 AND 35 THEN '26-35'
                WHEN FLOOR((YEAR(CURDATE()) - YEAR(pkb.dogum_tarihi))) BETWEEN 36 AND 45 THEN '36-45'
                WHEN FLOOR((YEAR(CURDATE()) - YEAR(pkb.dogum_tarihi))) BETWEEN 46 AND 55 THEN '46-55'
                ELSE '56+' 
            END AS yas_grubu,
            COUNT(ak.id) AS kaza_sayisi
        FROM personel_kisisel_bilgi pkb
        LEFT JOIN aracli_kazalar ak ON pkb.tc_kimlik = ak.tc_kimlik
        GROUP BY yas_grubu
        ORDER BY yas_grubu
    ");
    $yasGruplari = $yasGrubuSorgu->fetchAll(PDO::FETCH_ASSOC);

    $yasGrubuAdlari = [];
    $yasGrubuSayilari = [];
    foreach ($yasGruplari as $grup) {
        $yasGrubuAdlari[] = $grup['yas_grubu'];
        $yasGrubuSayilari[] = (int)$grup['kaza_sayisi'];
    }

    // Fetch accident table data
    $kazaSorgu = $db->query("
        SELECT ak.id, pkb.tc_kimlik, pkb.ad_soyad, hik.is_kazasi_tipi_adi, hyd.yaralanma_durum_adi, ak.kaza_aciklamasi, ak.is_kazasi_tarihi
        FROM aracli_kazalar ak
        INNER JOIN personel_kisisel_bilgi pkb ON ak.tc_kimlik = pkb.tc_kimlik
        INNER JOIN hazir_is_kazalari hik ON ak.is_kazasi_tip_id = hik.is_kazasi_tip_id
        INNER JOIN hazir_yaralanma_durumlar hyd ON ak.yaralanma_durumu_id = hyd.yaralanma_durum_id
        ORDER BY ak.is_kazasi_tarihi DESC
    ");
    $kazalar = $kazaSorgu->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Veritabanı hatası: ' . htmlspecialchars($e->getMessage()) . '</div>';
    require_once 'footer.php';
    exit;
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<div class="container-fluid mt-4">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Araç Kazaları Analizi</h1>


    <!-- Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Son 14 Gün</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $son14GunKazaSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Aylık Kaza</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $aylikKazaSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Yıllık Kaza</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $yillikKazaSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Toplam Kaza</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $toplamKazaSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <!-- Monthly/Yearly Accident Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4" style="height: 400px;">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Kaza Trendleri</h6>
                    <div>
                        <button id="aylikBtn" class="btn btn-primary btn-sm">Aylık</button>
                        <button id="yillikBtn" class="btn btn-secondary btn-sm">Yıllık</button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($aylar) && empty($yillar)) { ?>
                        <div class="alert alert-info">Kaza verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-area" style="height: 300px;">
                            <canvas id="kazalarAreaChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Accident Types Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4" style="height: 400px;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kaza Türleri</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($turAdlari)) { ?>
                        <div class="alert alert-info">Kaza türü verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-pie" style="height: 300px;">
                            <canvas id="kazalarPieChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Injury Status Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4" style="height: 400px;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Yaralanma Durumu</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($yaralanmaDurumAdlari)) { ?>
                        <div class="alert alert-info">Yaralanma durumu verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-bar" style="height: 300px;">
                            <canvas id="yaralanmaDurumuBarChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Age Group Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4" style="height: 400px;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Yaş Grupları</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($yasGrubuAdlari)) { ?>
                        <div class="alert alert-info">Yaş grubu verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-bar" style="height: 300px;">
                            <canvas id="yasGrubuBarChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Accident Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tüm Araç Kazaları</h4>
                </div>
                <div class="card-body">
                    <button id="excel-indir" class="btn btn-primary mb-3">Excel İndir</button>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered text-center align-middle" id="kazalarTablo">
                            <thead class="table-dark">
                                <tr>
                                    <th>TC Kimlik</th>
                                    <th class="only-tablet">Ad Soyad</th>
                                    <th class="only-desktop">Kaza Türü</th>
                                    <th class="only-tablet">Yaralanma Durumu</th>
                                    <th class="only-tablet">Kaza Açıklaması</th>
                                    <th class="only-desktop">Kaza Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kazalar as $kaza) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($kaza['tc_kimlik']); ?></td>
                                        <td class="only-tablet"><?php echo htmlspecialchars($kaza['ad_soyad']); ?></td>
                                        <td class="only-desktop"><?php echo htmlspecialchars($kaza['is_kazasi_tipi_adi']); ?></td>
                                        <td class="only-tablet"><?php echo htmlspecialchars($kaza['yaralanma_durum_adi']); ?></td>
                                        <td class="only-tablet"><?php echo htmlspecialchars($kaza['kaza_aciklamasi']); ?></td>
                                        <td class="only-desktop"><?php echo date("d.m.Y H:i", strtotime($kaza['is_kazasi_tarihi'])); ?></td>
                                        <td>
                                            <a href="kaza_detay.php?id=<?php echo htmlspecialchars($kaza['id']); ?>" class="btn btn-info btn-sm text-white">Görüntüle</a>
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

<!-- Styles -->
<style>
.chart-area, .chart-pie, .chart-bar {
    position: relative;
    width: 100%;
    height: 300px !important;
}
.only-desktop, .only-tablet {
    display: table-cell;
}
@media (max-width: 991.98px) {
    .only-desktop { display: none !important; }
}
@media (max-width: 767.98px) {
    .only-tablet { display: none !important; }
}
@media (max-width: 576px) {
    td, th { font-size: 0.75rem; }
}
</style>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function () {
    const ctxMonthly = document.getElementById('kazalarAreaChart');
    const ctxPie = document.getElementById('kazalarPieChart');
    const ctxYaralanmaDurumu = document.getElementById('yaralanmaDurumuBarChart');
    const ctxYasGrubu = document.getElementById('yasGrubuBarChart');

    if (!ctxMonthly || !ctxPie || !ctxYaralanmaDurumu || !ctxYasGrubu) {
        console.error('Canvas elemanları bulunamadı!');
        return;
    }

    // Monthly and Yearly Data
    const aylikData = {
        labels: <?php echo json_encode($aylar); ?>,
        datasets: [{
            label: 'Aylık Kaza Sayısı',
            data: <?php echo json_encode($aylikKazaSayilari); ?>,
            fill: true,
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderColor: '#4e73df',
            tension: 0.4
        }]
    };

    const yillikData = {
        labels: <?php echo json_encode($yillar); ?>,
        datasets: [{
            label: 'Yıllık Kaza Sayısı',
            data: <?php echo json_encode($yillikKazaSayilari); ?>,
            fill: true,
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderColor: '#4e73df',
            tension: 0.4
        }]
    };

    // Monthly/Yearly Chart
    const areaChart = new Chart(ctxMonthly.getContext('2d'), {
        type: 'line',
        data: aylikData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Kaza Sayısı' } },
                x: { title: { display: true, text: 'Ay' } }
            },
            plugins: { legend: { display: true, position: 'top' } }
        }
    });

    // Toggle Monthly/Yearly
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

    // Accident Types Chart
    new Chart(ctxPie.getContext('2d'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($turAdlari); ?>,
            datasets: [{
                data: <?php echo json_encode($turSayilari); ?>,
                backgroundColor: <?php echo json_encode($renkler); ?>,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        generateLabels: function(chart) {
                            return chart.data.labels.map((label, i) => ({
                                text: `${label}: ${chart.data.datasets[0].data[i]}`,
                                fillStyle: chart.data.datasets[0].backgroundColor[i],
                                strokeStyle: chart.data.datasets[0].backgroundColor[i],
                                lineWidth: 1,
                                index: i
                            }));
                        }
                    }
                }
            }
        }
    });

    // Injury Status Chart
    new Chart(ctxYaralanmaDurumu.getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($yaralanmaDurumAdlari); ?>,
            datasets: [{
                label: 'Kaza Sayısı',
                data: <?php echo json_encode($yaralanmaDurumSayilari); ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.5)',
                borderColor: '#4e73df',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Kaza Sayısı' } },
                x: { title: { display: true, text: 'Yaralanma Durumu' } }
            },
            plugins: { legend: { display: true, position: 'top' } }
        }
    });

    // Age Group Chart
    new Chart(ctxYasGrubu.getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($yasGrubuAdlari); ?>,
            datasets: [{
                label: 'Kaza Sayısı',
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
                y: { beginAtZero: true, title: { display: true, text: 'Kaza Sayısı' } },
                x: { title: { display: true, text: 'Yaş Grubu' } }
            },
            plugins: { legend: { display: true, position: 'top' } }
        }
    });

    // Initialize DataTable
    $('#kazalarTablo').DataTable({
        responsive: true,
        autoWidth: false,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tümü"]],
        language: dildosyasi,
        order: [[5, 'desc']]
    });

    // Excel Export
    document.getElementById('excel-indir').addEventListener('click', function() {
        const table = document.getElementById('kazalarTablo');
        const ws = XLSX.utils.table_to_sheet(table);
        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let C = range.s.c; C <= range.e.c; ++C) {
            const cell = ws[XLSX.utils.encode_cell({ r: 0, c: C })];
            if (cell && cell.v === 'İşlemler') {
                for (let R = range.s.r; R <= range.e.r; ++R) {
                    delete ws[XLSX.utils.encode_cell({ r: R, c: C })];
                }
                break;
            }
        }
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Araçlı Kazalar');
        XLSX.writeFile(wb, 'aracli_kazalar_listesi.xlsx');
    });
});
</script>

<?php require_once 'footer.php'; ?>