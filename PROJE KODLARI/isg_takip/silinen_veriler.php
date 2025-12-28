<?php require_once 'header.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<div class="container-fluid mt-4">
	<div class="row">
		<div class="col-12">
			<div class="card shadow">
				<div class="card-header bg-dark text-white">
					<h4 class="mb-0">Silinen Veriler</h4>
				</div>
				<div class="card-body">
					<button id="excel-indir" class="btn btn-dark mb-3">Tabloyu Excel Olarak İndir</button>
					<div class="table-responsive">
						<table class="table table-hover table-bordered text-center align-middle" id="tablo">
							<thead class="table-dark">
								<tr>
									<th>Tablo İçeriği</th>
									<th class="only-tablet">Silinme Tarihi</th>
									<th class="only-desktop">Silen Kullanıcı</th>
									<th>Kimlik</th>
									<th class="only-tablet">İşlem Özeti</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$tablo_adlari = [
									"silinen_personel_kisisel_bilgi" => "Personel Kışisel Bilgisi",
									"silinen_personel_sirket_bilgi" => "Personel Şirket Bilgisi",
									"silinen_personel_saglik_tetkikleri" => "Personel Sağlık Tetkikleri",
									"silinen_personel_uyarilar" => "Personel Uyarısı",
									"silinen_personel_belgeler" => "Personel Belgeleri",
									"silinen_personel_ehliyetler" => "Personel Ehliyetleri",
									"silinen_personel_is_kazalari" => "Personel İŞ Kazaları",
									"silinen_personel_gerekli_belge" => "Gerekli Belgeler",
									"silinen_arac_bilgi" => "Araç Bilgisi",
									"silinen_arac_muayene" => "Araç Muayenesi",
									"silinen_arac_operator_atama" => "Araç Operatör Atamaları",
									"silinen_aracli_kazalar" => "Araçlı Kazalar",
									"silinen_hazir_arac_durumlari" => "Araç Durumları",
									"silinen_hazir_arac_tipleri" => "Araç Tipleri",
									"silinen_hazir_belgeler" => "Hazır Belgeler",
									"silinen_hazir_ehliyetler" => "Hazır Ehliyetler",
									"silinen_hazir_firmalar" => "Firmalar",
									"silinen_hazir_is_kazalari" => "Hazır İŞ Kazaları",
									"silinen_hazir_markalar" => "Markalar",
									"silinen_hazir_meslekler" => "Meslekler",
									"silinen_hazir_modeller" => "Modeller",
									"silinen_hazir_uyarilar" => "Hazır Uyarılar",
									"silinen_hazir_yaralanma_durumlar" => "Yaralanma Durumları",
									"silinen_hazir_yaralanma_tipler" => "Yaralanma Tipleri"
								];

								foreach ($tablo_adlari as $tablo => $gorunen_ad):
									$sorgu = $db->prepare("SELECT * FROM $tablo ORDER BY silinme_tarihi DESC");
									$sorgu->execute();
									$veriler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

									foreach ($veriler as $index => $satir):
										$silen_kullanici_adi = "";
										if (!empty($satir['silen_kullanici_id'])) {
											$kulSor = $db->prepare("SELECT kul_isim FROM kullanicilar WHERE kul_id = ? LIMIT 1");
											$kulSor->execute([$satir['silen_kullanici_id']]);
											$kullanici = $kulSor->fetch(PDO::FETCH_ASSOC);
											$silen_kullanici_adi = $kullanici['kul_isim'] ?? 'Bilinmiyor';
										} else {
											$silen_kullanici_adi = 'Bilinmiyor';
										}

										$kimlik = $satir['tc_kimlik'] ?? ($satir['plaka_no'] ?? 'Belirsiz');
										?>
										<tr>
											<td><?= $gorunen_ad ?></td>
											<td class="only-tablet"><?= date("d.m.Y H:i", strtotime($satir['silinme_tarihi'])) ?></td>
											<td class="only-desktop"><?= htmlspecialchars($silen_kullanici_adi) ?></td>
											<td><?= htmlspecialchars($kimlik) ?></td>
											<td class="only-tablet">
												<a href="silinen_detay.php?tablo=<?= urlencode($tablo) ?>&id=<?= urlencode($satir['id']) ?>" class="btn btn-sm btn-primary">Görüntüle</a>
											</td>
										</tr>
									<?php endforeach;
								endforeach; ?>
							</tbody>
						</table>
					</div> <!-- table-responsive -->
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once 'footer.php'; ?>

<!-- Responsive kolon görünürlüğü -->
<style>
	.only-desktop, .only-tablet { display: table-cell; }

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

<!-- DataTables Türkçe ve responsive -->
<link href="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.js"></script>
<script>
$(document).ready(function () {
	$('#tablo').DataTable({
		responsive: true,
		autoWidth: false,
		language: {
			url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/tr.json"
		},
		order: [[1, 'desc']]
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
        XLSX.utils.book_append_sheet(wb, ws, 'Silinen Veriler Listesi');
        
        // Excel dosyasını indir
        XLSX.writeFile(wb, 'silinen_veriler_listesi.xlsx');
    });
</script>
