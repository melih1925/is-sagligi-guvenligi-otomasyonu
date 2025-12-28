<?php require_once 'header.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Muayenesi Yaklaşan Araçlar</h4>
                </div>
                <div class="card-body">
                    <button id="excel-indir" class="btn btn-danger mb-3">Tabloyu Excel Olarak İndir</button>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered text-center align-middle" id="tablo">
                            <thead class="table-dark">
                                <tr>
                                    <th>Plaka</th>
                                    <th class="only-tablet">Operatör Adı Soyadı</th>
                                    <th class="only-desktop">Telefon No</th>
                                    <th class="only-desktop">Firma</th>
                                    <th class="only-tablet">Muayene Tarihi</th>
                                    <th class="only-tablet">Geçerlilik Tarihi</th>
                                    <th>Kalan Gün</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sorgu = $db->prepare("
                                    SELECT 
                                        ab.arac_id,
                                        ab.plaka_no,
                                        f.firma_adi,
                                        am.muayene_tarihi,
                                        am.muayene_gecerlilik_tarihi AS gecerlilik_tarihi,
                                        DATEDIFF(am.muayene_gecerlilik_tarihi, CURDATE()) AS kalan_gun,
                                        (SELECT COUNT(*) FROM arac_operator_atama aoa2 WHERE aoa2.arac_id = ab.arac_id) - 1 AS diger_sayisi,
                                        COALESCE(
                                            (SELECT pkb.ad_soyad 
                                             FROM arac_operator_atama aoa3 
                                             INNER JOIN personel_kisisel_bilgi pkb ON aoa3.tc_kimlik = pkb.tc_kimlik 
                                             WHERE aoa3.arac_id = ab.arac_id 
                                             ORDER BY aoa3.atama_tarihi ASC LIMIT 1), 
                                            'Operatör Atanmamış'
                                        ) AS ilk_operator_adi,
                                        COALESCE(
                                            (SELECT pkb.telefon 
                                             FROM arac_operator_atama aoa3 
                                             INNER JOIN personel_kisisel_bilgi pkb ON aoa3.tc_kimlik = pkb.tc_kimlik 
                                             WHERE aoa3.arac_id = ab.arac_id 
                                             ORDER BY aoa3.atama_tarihi ASC LIMIT 1), 
                                            'Yok'
                                        ) AS ilk_operator_telefon
                                    FROM arac_muayene am
                                    INNER JOIN arac_bilgi ab ON am.arac_id = ab.arac_id
                                    LEFT JOIN hazir_firmalar f ON ab.firma_id = f.firma_id
                                    WHERE DATEDIFF(am.muayene_gecerlilik_tarihi, CURDATE()) <= 30
                                    AND am.muayene_gecerlilik_tarihi IS NOT NULL
                                    ORDER BY kalan_gun ASC
                                ");
                                try {
                                    $sorgu->execute();
                                    $veriler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
                                    if (empty($veriler)) {
                                        echo "<div class='alert alert-warning'>Sorgu çalıştı, ancak sonuç döndürmedi. Veritabanında uygun veri olmayabilir.</div>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<div class='alert alert-danger'>Sorgu hatası: " . $e->getMessage() . "</div>";
                                }

                                foreach ($veriler as $satir):
                                    $satir_renk = ($satir['kalan_gun'] <= 0) ? 'bg-danger text-white' : '';
                                    $operator_bilgi = htmlspecialchars($satir['ilk_operator_adi']);
                                    if ($satir['diger_sayisi'] > 0) {
                                        $operator_bilgi .= " + " . $satir['diger_sayisi'];
                                    }
                                ?>
                                    <tr class="<?= $satir_renk ?>">
                                        <td><?= htmlspecialchars($satir['plaka_no']) ?></td>
                                        <td class="only-tablet"><?= $operator_bilgi ?></td>
                                        <td class="only-desktop"><?= htmlspecialchars($satir['ilk_operator_telefon'] ?? 'Yok') ?></td>
                                        <td class="only-desktop"><?= htmlspecialchars($satir['firma_adi'] ?? 'Bilinmiyor') ?></td>
                                        <td class="only-tablet"><?= $satir['muayene_tarihi'] ? date("d.m.Y", strtotime($satir['muayene_tarihi'])) : 'Yok' ?></td>
                                        <td class="only-tablet"><?= $satir['gecerlilik_tarihi'] ? date("d.m.Y", strtotime($satir['gecerlilik_tarihi'])) : 'Yok' ?></td>
                                        <td><?= $satir['kalan_gun'] ?> gün</td>
                                        <td>
                                            <div class="btn-group btn-group-sm d-flex flex-wrap justify-content-center gap-1">
                                                <a href="arac_duzenle.php?plaka_no=<?= urlencode($satir['plaka_no']) ?>" class="btn btn-warning">Düzenle</a>
                                                <a href="arac_detay.php?plaka_no=<?= urlencode($satir['plaka_no']) ?>" class="btn btn-info text-white">Görüntüle</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php if (count($veriler) == 0): ?>
                            <div class="alert alert-success text-center fw-bold mt-3">
                                Görüntülenecek araç bulunamadı. Tüm araç muayeneleri güncel.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<!-- Kolon Görünürlüğü Medya Sorguları -->
<style>
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

<!-- DataTable Türkçe -->
<script>
    $(document).ready(function () {
        $('#tablo').DataTable({
            responsive: true,
            autoWidth: false,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
            language: dildosyasi
        });
    });
</script>

<script>
    document.getElementById('excel-indir').addEventListener('click', function() {
        // Tabloyu seç
        var table = document.getElementById('tablo');
        
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
        XLSX.utils.book_append_sheet(wb, ws, 'Araç Muayene Listesi');
        
        // Excel dosyasını indir
        XLSX.writeFile(wb, 'arac_muayene_listesi.xlsx');
    });
</script>

<!-- DataTables JS/CSS -->
<link href="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.js"></script>