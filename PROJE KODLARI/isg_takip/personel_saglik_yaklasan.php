<?php require_once 'header.php'; ?>

<div class="container-fluid mt-4">
	<div class="row">
		<div class="col-12">
			<div class="card shadow">
				<div class="card-header bg-danger text-white">
					<h4 class="mb-0">Sağlık Muayenesi Yaklaşan Personeller</h4>
				</div>
				<div class="card-body">
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
									WHERE DATEDIFF(pst.gecerlilik_tarihi, CURDATE()) <= 30 AND DATEDIFF(pst.gecerlilik_tarihi, CURDATE()) >= 0
									ORDER BY kalan_gun ASC
								");
								$sorgu->execute();
								$veriler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

								if (count($veriler) > 0) {
									foreach ($veriler as $satir) {
										// Kalan gün sayısına göre renk seçimi
										$kalanGunClass = ($satir['kalan_gun'] <= 0) ? 'bg-danger text-white' : '';
										?>
										<tr class="<?= $kalanGunClass ?>">
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
            language: dildosyasi
        });
    });
</script>

<link href="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.js"></script>

