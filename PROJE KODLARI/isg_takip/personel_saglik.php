<?php require_once 'header.php'; ?>

<div class="container-fluid mt-4">
	<div class="row">
		<div class="col-12">
			<div class="card shadow">
				<div class="card-header bg-danger text-white">
					<h4 class="mb-0">Sağlık Muayenesi Yaklaşan Personeller</h4>
				</div>

									<!-- SheetJS Kütüphanesini Ekleyin -->
				<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


				<div class="card-body">
					<button id="excel-indir" class="btn btn-danger mb-3">Tabloyu Excel Olarak İndir</button>
					<div class="table-responsive">
						<table class="table table-striped table-hover table-bordered text-center align-middle" id="tablo">
							<thead class="table-dark">
								<tr>
									<th>TC Kimlik</th>
									<th class="only-tablet">Ad Soyad</th>
									<th class="only-desktop">Telefon</th>
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
										pkb.tc_kimlik,
										pkb.ad_soyad,
										pkb.telefon,
										f.firma_adi,
										pst.tarih AS muayene_tarihi,
										pst.gecerlilik_tarihi,
										DATEDIFF(pst.gecerlilik_tarihi, CURDATE()) AS kalan_gun
									FROM personel_saglik_tetkikleri pst
									INNER JOIN personel_kisisel_bilgi pkb ON pst.tc_kimlik = pkb.tc_kimlik
									INNER JOIN personel_sirket_bilgi psb ON pkb.tc_kimlik = psb.tc_kimlik
									INNER JOIN hazir_firmalar f ON psb.firma_id = f.firma_id
									WHERE DATEDIFF(pst.gecerlilik_tarihi, CURDATE()) <= 30
									ORDER BY kalan_gun ASC
								");
								$sorgu->execute();
								$veriler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

								if (count($veriler) > 0) {
									foreach ($veriler as $satir) { 
										$satir_renk = ($satir['kalan_gun'] <= 0) ? 'bg-danger text-white' : '';
								?>
										<tr class="<?= $satir_renk ?>">
											<td><?= $satir['tc_kimlik'] ?></td>
											<td class="only-tablet"><?= $satir['ad_soyad'] ?></td>
											<td class="only-desktop"><?= $satir['telefon'] ?></td>
											<td class="only-desktop"><?= $satir['firma_adi'] ?></td>
											<td class="only-tablet"><?= date("d.m.Y", strtotime($satir['muayene_tarihi'])) ?></td>
											<td class="only-tablet"><?= date("d.m.Y", strtotime($satir['gecerlilik_tarihi'])) ?></td>
											<td><?= $satir['kalan_gun'] ?> gün</td>
											<td>
												<div class="btn-group btn-group-sm d-flex flex-wrap justify-content-center gap-1">
													<a href="personel_duzenle.php?tc_kimlik=<?= $satir['tc_kimlik'] ?>" class="btn btn-warning">Düzenle</a>
													<a href="personel_detay.php?tc_kimlik=<?= $satir['tc_kimlik'] ?>" class="btn btn-info text-white">Görüntüle</a>
												</div>
											</td>
										</tr>
									<?php }
								} else { ?>
									<tr>
										<td colspan="8" class="text-center text-success fw-bold">
											Görüntülenecek kayıt bulunamadı. Bütün muayeneler yapılmış durumda.
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
        XLSX.utils.book_append_sheet(wb, ws, 'Personel Sağlık Muayene Listesi');
        
        // Excel dosyasını indir
        XLSX.writeFile(wb, 'personel_saglik_muayene_listesi.xlsx');
    });
</script>


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

<link href="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.js"></script>
