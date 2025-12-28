<?php require_once 'header.php'; ?>

<div class="container mt-5">
    <h3 class="text-center text-danger font-weight-bold">3 ve Daha Fazla Uyarısı Olan Personeller</h3>
    <hr>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <div class="card">
        <div class="card-body">
            <?php
            $sorgu = $db->query("
                SELECT pkb.tc_kimlik, pkb.ad_soyad, COUNT(*) AS uyari_sayisi
                FROM personel_uyarilar pu
                JOIN personel_kisisel_bilgi pkb ON pu.tc_kimlik = pkb.tc_kimlik
                GROUP BY pu.tc_kimlik
                HAVING uyari_sayisi >= 3
                ORDER BY uyari_sayisi DESC
            ");

            if ($sorgu->rowCount() > 0):
            ?>
            <table id="tablo" class="table table-bordered table-hover">
                <button id="excel-indir" class="btn btn-dark mb-3">Tabloyu Excel Olarak İndir</button>
                <thead class="thead-dark">
                    <tr>
                        <th>TC Kimlik</th>
                        <th>Ad Soyad</th>
                        <th>Uyarı Sayısı</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sorgu as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['tc_kimlik']) ?></td>
                            <td><?= htmlspecialchars($row['ad_soyad']) ?></td>
                            <td class="text-danger font-weight-bold"><?= $row['uyari_sayisi'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p class="text-center">3 veya daha fazla uyarısı olan personel bulunamadı.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>

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
            XLSX.utils.book_append_sheet(wb, ws, 'Personel Uyarı Listesi');
            
            // Excel dosyasını indir
            XLSX.writeFile(wb, 'personel_uyari_listesi.xlsx');
        });

    $(document).ready(function () {
        $('#tablo').DataTable({
            responsive: true,
            autoWidth: false,
			lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
            language: dildosyasi
        });
    });

    </script>


