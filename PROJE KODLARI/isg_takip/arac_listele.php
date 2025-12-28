<?php
require_once 'header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Araç Listesi</h4>
                </div>
                <div class="card-body">
                    <button id="excel-indir" class="btn btn-primary mb-3">Tabloyu Excel Olarak İndir</button>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered text-center align-middle" id="tablo">
                            <thead class="table-dark">
                                <tr>
                                    <th>Plaka No</th>
                                    <th class="only-tablet">Araç Tipi</th>
                                    <th class="only-desktop">Marka</th>
                                    <th class="only-desktop">Model</th>
                                    <th class="only-tablet">Üretim Yılı</th>
                                    <th class="only-desktop">Firma</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sorgu = $db->prepare("
                                    SELECT ab.plaka_no, hat.arac_tipi_adi, hm.marka_adi, hmo.model_adi, 
                                           ab.uretim_yili, hf.firma_adi
                                    FROM arac_bilgi ab
                                    INNER JOIN hazir_arac_tipleri hat ON ab.arac_tipi_id = hat.arac_tipi_id
                                    INNER JOIN hazir_markalar hm ON ab.marka_id = hm.marka_id
                                    INNER JOIN hazir_modeller hmo ON ab.model_id = hmo.model_id
                                    INNER JOIN hazir_firmalar hf ON ab.firma_id = hf.firma_id
                                    ORDER BY ab.plaka_no ASC
                                ");
                                $sorgu->execute();
                                $araclar = $sorgu->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($araclar as $arac) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($arac['plaka_no']) ?></td>
                                        <td class="only-tablet"><?= htmlspecialchars($arac['arac_tipi_adi']) ?></td>
                                        <td class="only-desktop"><?= htmlspecialchars($arac['marka_adi']) ?></td>
                                        <td class="only-desktop"><?= htmlspecialchars($arac['model_adi']) ?></td>
                                        <td class="only-tablet"><?= htmlspecialchars($arac['uretim_yili']) ?></td>
                                        <td class="only-desktop"><?= htmlspecialchars($arac['firma_adi']) ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm d-flex flex-wrap justify-content-center gap-1">
                                                <a href="arac_duzenle.php?plaka_no=<?= urlencode($arac['plaka_no']) ?>" class="btn btn-warning">Düzenle</a>
                                                <a href="arac_sil.php?sil=sil&plaka_no=<?= urlencode($arac['plaka_no']) ?>" class="btn btn-danger" onclick="return confirm('Bu aracı silmek istediğinize emin misiniz?');">Sil</a>
                                                <a href="arac_detay.php?plaka_no=<?= urlencode($arac['plaka_no']) ?>" class="btn btn-info text-white">Görüntüle</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> <!-- table-responsive -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kolon Görünürlüğü Medya Sorguları -->
<style>
    /* Hepsi varsayılan olarak görünür */
    .only-desktop,
    .only-tablet {
        display: table-cell;
    }

    /* Tablet ve altı (≤ 991px): desktop kolonlar gizlenir */
    @media (max-width: 991.98px) {
        .only-desktop {
            display: none !important;
        }
    }

    /* Mobil (≤ 767px): tablet kolonlar da gizlenir */
    @media (max-width: 767.98px) {
        .only-tablet {
            display: none !important;
        }
    }

    /* Mobilde yazı boyutları küçültülür */
    @media (max-width: 576px) {
        td, th {
            font-size: 0.75rem;
        }
    }
</style>

<?php require_once 'footer.php'; ?>

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
            XLSX.utils.book_append_sheet(wb, ws, 'Araç Listesi');
            
            // Excel dosyasını indir
            XLSX.writeFile(wb, 'arac_listesi.xlsx');
        });
    </script>

<link href="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.css" rel="stylesheet" integrity="sha384-fTqd416qRc9kwY299KdgUPsjOvS5bwkeE6jlibx2m7eL3xKheqGyU48QCFgZAyN4" crossorigin="anonymous">
<script src="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.js" integrity="sha384-uAn6fsp1rIJ6afAYV0S5it5ao101zH2fViB74y5tPG8LR2FTMg+HXIWRNxvZrniN" crossorigin="anonymous"></script>

