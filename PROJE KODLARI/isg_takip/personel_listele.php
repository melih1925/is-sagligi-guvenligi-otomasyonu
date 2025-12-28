<?php require_once 'header.php'; ?>
<?php 
// Başarı mesajı varsa göster
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">'.htmlspecialchars($_SESSION['success']).'</div>';
    unset($_SESSION['success']);
}

// Hata mesajları varsa göster
if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>';
    }
    unset($_SESSION['errors']);
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<div class="container-fluid mt-4">
	<div class="row">
		<div class="col-12">
			<div class="card shadow">
				<div class="card-header bg-primary text-white">
					<h4 class="mb-0">Personel Listesi</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<button id="excel-indir" class="btn btn-primary mb-3">Tabloyu Excel Olarak İndir</button>
						<table class="table table-striped table-hover table-bordered text-center align-middle" id="tablo">
							<thead class="table-dark">
								<tr>
									<th>TC Kimlik</th>
									<th class="only-tablet">Ad Soyad</th>
									<th class="only-desktop">Telefon</th>
									<th class="only-desktop">Firma</th>
									<th class="only-tablet">Meslek</th>
									<th class="only-desktop">İşe Giriş</th>
									<th>İşlemler</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sorgu = $db->prepare("
									SELECT pkb.tc_kimlik, pkb.ad_soyad, pkb.telefon, 
										   f.firma_adi, m.meslek_adi, psb.ise_giris_tarihi
									FROM personel_kisisel_bilgi pkb
									INNER JOIN personel_sirket_bilgi psb ON pkb.tc_kimlik = psb.tc_kimlik
									INNER JOIN hazir_firmalar f ON psb.firma_id = f.firma_id
									INNER JOIN hazir_meslekler m ON psb.meslek_id = m.meslek_id
									ORDER BY pkb.ad_soyad ASC
								");
								$sorgu->execute();
								$personeller = $sorgu->fetchAll(PDO::FETCH_ASSOC);

								foreach ($personeller as $personel) { ?>
									<tr>
										<td><?= $personel['tc_kimlik'] ?></td>
										<td class="only-tablet"><?= $personel['ad_soyad'] ?></td>
										<td class="only-desktop"><?= $personel['telefon'] ?></td>
										<td class="only-desktop"><?= $personel['firma_adi'] ?></td>
										<td class="only-tablet"><?= $personel['meslek_adi'] ?></td>
										<td class="only-desktop"><?= date("d.m.Y", strtotime($personel['ise_giris_tarihi'])) ?></td>
										<td>
											<div class="btn-group btn-group-sm d-flex flex-wrap justify-content-center gap-1">
												<a href="personel_duzenle.php?tc_kimlik=<?= $personel['tc_kimlik'] ?>" class="btn btn-warning">Düzenle</a>
												<a href="personel_sil.php?sil=sil&tc_kimlik=<?= $personel['tc_kimlik'] ?>" class="btn btn-danger" onclick="return confirm('Bu personelin tüm bilgilerini silmek istediğinize emin misiniz?');">Sil</a>
												<a href="personel_detay.php?tc_kimlik=<?= $personel['tc_kimlik'] ?>" class="btn btn-info text-white">Görüntüle</a>
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
			XLSX.utils.book_append_sheet(wb, ws, 'Personel Listesi');
			
			// Excel dosyasını indir
			XLSX.writeFile(wb, 'personel_listesi.xlsx');
		});
	</script>

<?php require_once 'footer.php'; ?>


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

<!-- DataTable Türkçe -->
<!--Data Table ile yapılldı.--->
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


<link href="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.css" rel="stylesheet" integrity="sha384-fTqd416qRc9kwY299KdgUPsjOvS5bwkeE6jlibx2m7eL3xKheqGyU48QCFgZAyN4" crossorigin="anonymous">
 
<script src="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.js" integrity="sha384-uAn6fsp1rIJ6afAYV0S5it5ao101zH2fViB74y5tPG8LR2FTMg+HXIWRNxvZrniN" crossorigin="anonymous"></script>