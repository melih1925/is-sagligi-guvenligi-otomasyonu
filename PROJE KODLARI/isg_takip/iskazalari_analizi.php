<?php
require_once 'header.php';

// Hata kontrolü için PDO ayarları
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Kartlar için veri çekme
    // Aylık kaza sayısı
    $aylikSorgu = $db->prepare("
        SELECT COUNT(*) AS kaza_sayisi
        FROM personel_is_kazalari
        WHERE YEAR(is_kazasi_tarihi) = YEAR(CURDATE())
        AND MONTH(is_kazasi_tarihi) = MONTH(CURDATE())
    ");
    $aylikSorgu->execute();
    $aylikKaza = $aylikSorgu->fetch(PDO::FETCH_ASSOC);
    $aylikKazaSayisi = $aylikKaza['kaza_sayisi'];

    // Yıllık kaza sayısı
    $yillikSorgu = $db->prepare("
        SELECT COUNT(*) AS kaza_sayisi
        FROM personel_is_kazalari
        WHERE YEAR(is_kazasi_tarihi) = YEAR(CURDATE())
    ");
    $yillikSorgu->execute();
    $yillikKaza = $yillikSorgu->fetch(PDO::FETCH_ASSOC);
    $yillikKazaSayisi = $yillikKaza['kaza_sayisi'];

    // Toplam kaza sayısı
    $toplamSorgu = $db->prepare("
        SELECT COUNT(*) AS kaza_sayisi
        FROM personel_is_kazalari
    ");
    $toplamSorgu->execute();
    $toplamKaza = $toplamSorgu->fetch(PDO::FETCH_ASSOC);
    $toplamKazaSayisi = $toplamKaza['kaza_sayisi'];

    // Son 14 gündeki kaza sayısı
    $son14GunSorgu = $db->prepare("
        SELECT COUNT(*) AS kaza_sayisi
        FROM personel_is_kazalari
        WHERE is_kazasi_tarihi >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
    ");
    $son14GunSorgu->execute();
    $son14GunKaza = $son14GunSorgu->fetch(PDO::FETCH_ASSOC);
    $son14GunKazaSayisi = $son14GunKaza['kaza_sayisi'];

    // Aylık grafik verileri (son 12 ay)
    $aylikGrafikSorgu = $db->query("
        SELECT DATE_FORMAT(is_kazasi_tarihi, '%Y-%m') AS ay, COUNT(*) AS kaza_sayisi
        FROM personel_is_kazalari
        WHERE is_kazasi_tarihi >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(is_kazasi_tarihi, '%Y-%m')
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

    // Yıllık grafik verileri (son 10 yıl)
    $yillikGrafikSorgu = $db->query("
        SELECT YEAR(is_kazasi_tarihi) AS yil, COUNT(*) AS kaza_sayisi
        FROM personel_is_kazalari
        WHERE is_kazasi_tarihi >= DATE_SUB(CURDATE(), INTERVAL 10 YEAR)
        GROUP BY YEAR(is_kazasi_tarihi)
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

    // Kaza türleri grafik verileri
    $turSorgu = $db->query("
        SELECT hik.is_kazasi_tipi_adi, COUNT(pik.id) AS kaza_sayisi
        FROM personel_is_kazalari pik
        INNER JOIN hazir_is_kazalari hik ON pik.is_kazasi_tip_id = hik.is_kazasi_tip_id
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

    // Kaza tablosu için veri çekme
    $kazaSorgu = $db->prepare("
        SELECT pik.id, pik.tc_kimlik, pkb.ad_soyad, hik.is_kazasi_tipi_adi, hyd.yaralanma_durum_adi, hyt.yaralanma_tipi_adi, pik.kaza_nedeni, pik.is_kazasi_tarihi
        FROM personel_is_kazalari pik
        INNER JOIN personel_kisisel_bilgi pkb ON pik.tc_kimlik = pkb.tc_kimlik
        INNER JOIN hazir_is_kazalari hik ON pik.is_kazasi_tip_id = hik.is_kazasi_tip_id
        INNER JOIN hazir_yaralanma_durumlar hyd ON pik.yaralanma_durumu_id = hyd.yaralanma_durum_id
        INNER JOIN hazir_yaralanma_tipler hyt ON pik.yaralanma_tip_id = hyt.yaralanma_tip_id
        ORDER BY pik.is_kazasi_tarihi DESC
    ");
    $kazaSorgu->execute();
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
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">İş Kazası Analizi</h1>
    </div>

    <!-- Kartlar -->
    <div class="row mb-4">
        <!-- Son 14 Gündeki Kazalar -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Son 14 Gündeki Kazalar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $son14GunKazaSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aylık Kaza Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Aylık Kaza Sayısı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $aylikKazaSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yıllık Kaza Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Yıllık Kaza Sayısı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $yillikKazaSayisi; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toplam Kaza Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Toplam Kaza Sayısı</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $toplamKazaSayisi; ?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: <?php echo min(($toplamKazaSayisi / 100) * 100, 100); ?>%"
                                            aria-valuenow="<?php echo $toplamKazaSayisi; ?>"
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
        <!-- Aylık Kaza Grafiği -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <button id="aylikBtn" class="btn btn-primary btn-sm">Aylık Kaza Sayısı</button>
                        <button id="yillikBtn" class="btn btn-secondary btn-sm">Yıllık Kaza Sayısı</button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($aylar) && empty($yillar)) { ?>
                        <div class="alert alert-info">Kaza verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-area" style="height: 400px;">
                            <canvas id="kazalarAreaChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Kaza Türleri Grafiği -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Kaza Türleri Dağılımı</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($turAdlari)) { ?>
                        <div class="alert alert-info">Kaza türü verisi bulunmamaktadır.</div>
                    <?php } else { ?>
                        <div class="chart-pie pt-4 pb-2" style="height: 400px;">
                            <canvas id="kazalarPieChart"></canvas>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Kazalar Tablosu -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tüm İş Kazaları</h4>
                </div>
                <div class="card-body">
                    <button id="excel-indir" class="btn btn-primary mb-3">Tabloyu Excel Olarak İndir</button>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered text-center align-middle" id="kazalarTablo">
                            <thead class="table-dark">
                                <tr>
                                    <th>TC Kimlik</th>
                                    <th class="only-tablet">Ad Soyad</th>
                                    <th class="only-desktop">Kaza Türü</th>
                                    <th class="only-tablet">Yaralanma Durumu</th>
                                    <th class="only-desktop">Yaralanma Tipi</th>
                                    <th class="only-tablet">Kaza Nedeni</th>
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
                                        <td class="only-desktop"><?php echo htmlspecialchars($kaza['yaralanma_tipi_adi']); ?></td>
                                        <td class="only-tablet"><?php echo htmlspecialchars($kaza['kaza_nedeni']); ?></td>
                                        <td class="only-desktop"><?php echo date("d.m.Y H:i", strtotime($kaza['is_kazasi_tarihi'])); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm d-flex flex-wrap justify-content-center gap-1">
                                                <a href="personel_detay.php?tc_kimlik=<?php echo htmlspecialchars($kaza['tc_kimlik']); ?>" class="btn btn-info text-white">Görüntüle</a>
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
.chart-area, .chart-pie {
    position: relative;
    width: 100%;
    height: 400px !important;
}
#kazalarPieChartLegend {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
}
#kazalarPieChartLegend span {
    flex: 0 0 50%;
    max-width: 50%;
    box-sizing: border-box;
    padding: 5px;
}
@media (max-width: 767.98px) {
    #kazalarPieChartLegend span {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
.only-desktop,
.only-tablet {
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
    const ctxMonthly = document.getElementById('kazalarAreaChart');
    const ctxPie = document.getElementById('kazalarPieChart');

    if (!ctxMonthly || !ctxPie) {
        console.error('Canvas elemanları bulunamadı!');
        return;
    }

    // Aylık ve Yıllık Veri Setleri
    const aylikData = {
        labels: <?php echo json_encode($aylar); ?>,
        datasets: [{
            label: 'Aylık Kaza Sayısı',
            data: <?php echo json_encode($aylikKazaSayilari); ?>,
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
            label: 'Yıllık Kaza Sayısı',
            data: <?php echo json_encode($yillikKazaSayilari); ?>,
            fill: true,
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderColor: '#4e73df',
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 8
        }]
    };

    // Grafik Konfigürasyonu
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
                        text: 'Kaza Sayısı',
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

    // Kaza Türleri Grafiği
    const kazaTypesData = {
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
        data: kazaTypesData,
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

    // DataTable ile tabloyu başlatma
    $('#kazalarTablo').DataTable({
        responsive: true,
        autoWidth: false,
        language: dildosyasi,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
        order: [[6, 'desc']] // Varsayılan olarak kaza tarihine göre azalan sıralama
    });
});
</script>

<script>
    document.getElementById('excel-indir').addEventListener('click', function() {
        // Tabloyu seç
        var table = document.getElementById('kazalarTablo');
        
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
        XLSX.utils.book_append_sheet(wb, ws, 'İş Kazaları Listesi');
        
        // Excel dosyasını indir
        XLSX.writeFile(wb, 'is_kazalari_listesi.xlsx');
    });
</script>

<?php require_once 'footer.php'; ?>