<?php
require_once 'header.php';

$vehicle = null;
$accidents = [];
$operator = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plaka_sorgula'])) {
    $plaka_no = trim($_POST['plaka_no']);

    // Araç bilgilerini ve ilk operatörü çek
    $vehicle_query = $db->prepare("
        SELECT ab.*, hat.arac_tipi_adi, hm.marka_adi, hmo.model_adi, hf.firma_adi,
               aot.tc_kimlik, pkb.ad_soyad
        FROM arac_bilgi ab
        INNER JOIN hazir_arac_tipleri hat ON ab.arac_tipi_id = hat.arac_tipi_id
        INNER JOIN hazir_markalar hm ON ab.marka_id = hm.marka_id
        INNER JOIN hazir_modeller hmo ON ab.model_id = hmo.model_id
        INNER JOIN hazir_firmalar hf ON ab.firma_id = hf.firma_id
        LEFT JOIN (
            SELECT a1.arac_id, a1.tc_kimlik, a1.atama_tarihi
            FROM arac_operator_atama a1
            WHERE a1.atama_tarihi = (
                SELECT MIN(a2.atama_tarihi)
                FROM arac_operator_atama a2
                WHERE a2.arac_id = a1.arac_id
            )
        ) aot ON ab.arac_id = aot.arac_id
        LEFT JOIN personel_kisisel_bilgi pkb ON aot.tc_kimlik = pkb.tc_kimlik
        WHERE ab.plaka_no = ?
    ");
    if (!$vehicle_query->execute([$plaka_no])) {
        echo '<div class="alert alert-danger text-center">Araç sorgusu başarısız: ' . implode(', ', $vehicle_query->errorInfo()) . '</div>';
        exit;
    }
    $vehicle = $vehicle_query->fetch(PDO::FETCH_ASSOC);

    $operator = null;
    if ($vehicle && !empty($vehicle['tc_kimlik']) && !empty($vehicle['ad_soyad'])) {
        $operator = [
            'tc_kimlik' => $vehicle['tc_kimlik'],
            'ad_soyad' => $vehicle['ad_soyad']
        ];
    }

    // Araçla ilgili kaza kayıtlarını çek
    if ($vehicle) {
        $accident_query = $db->prepare("
            SELECT ak.*, hik.is_kazasi_tipi_adi, hytd.yaralanma_tipi_adi, hydd.yaralanma_durum_adi, pkb.ad_soyad
            FROM aracli_kazalar ak
            LEFT JOIN hazir_is_kazalari hik ON ak.is_kazasi_tip_id = hik.is_kazasi_tip_id
            LEFT JOIN hazir_yaralanma_tipler hytd ON ak.yaralanma_tip_id = hytd.yaralanma_tip_id
            LEFT JOIN hazir_yaralanma_durumlar hydd ON ak.yaralanma_durumu_id = hydd.yaralanma_durum_id
            LEFT JOIN personel_kisisel_bilgi pkb ON ak.tc_kimlik = pkb.tc_kimlik
            WHERE ak.arac_id = ?
            ORDER BY ak.is_kazasi_tarihi DESC
        ");
        if (!$accident_query->execute([$vehicle['arac_id']])) {
            echo '<div class="alert alert-danger text-center">Kaza sorgusu başarısız: ' . implode(', ', $accident_query->errorInfo()) . '</div>';
            exit;
        }
        $accidents = $accident_query->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Hazır veri listelerini çek
$accident_types = $db->query("SELECT * FROM hazir_is_kazalari")->fetchAll(PDO::FETCH_ASSOC);
$injury_statuses = $db->query("SELECT * FROM hazir_yaralanma_durumlar")->fetchAll(PDO::FETCH_ASSOC);
$injury_types = $db->query("SELECT * FROM hazir_yaralanma_tipler")->fetchAll(PDO::FETCH_ASSOC);
$personnel = $db->query("SELECT tc_kimlik, ad_soyad FROM personel_kisisel_bilgi ORDER BY ad_soyad")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4 mb-4">
    <h2 class="text-center text-danger font-weight-bold"><b>Araçlı İş Kazası Kayıt Ekranı</b></h2>
    <hr>
</div>

<div class="container mt-5">
    <!-- Plaka Sorgulama -->
    <div class="card mb-4 mx-auto" style="max-width: 500px;">
        <div class="card-header text-center">
            <strong>Araç Sorgulama</strong>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="plaka_no" class="form-control mb-2 text-center" placeholder="Plaka No" maxlength="20" required>
                </div>
                <button type="submit" name="plaka_sorgula" class="btn btn-primary btn-block">Sorgula</button>
            </form>
        </div>
    </div>

    <?php if ($vehicle): ?>
        <!-- Araç Bilgileri -->
        <div class="card mb-4">
            <div class="card-header"><strong>Araç Bilgileri</strong></div>
            <div class="card-body">
                <p><strong>Plaka:</strong> <?= htmlspecialchars($vehicle['plaka_no']) ?></p>
                <p><strong>Araç Tipi:</strong> <?= htmlspecialchars($vehicle['arac_tipi_adi']) ?></p>
                <p><strong>Marka:</strong> <?= htmlspecialchars($vehicle['marka_adi']) ?></p>
                <p><strong>Model:</strong> <?= htmlspecialchars($vehicle['model_adi']) ?></p>
                <p><strong>Firma:</strong> <?= htmlspecialchars($vehicle['firma_adi']) ?></p>
                <?php if ($operator): ?>
                    <p><strong>Şoför:</strong> <?= htmlspecialchars($operator['ad_soyad']) ?> (TC: <?= htmlspecialchars($operator['tc_kimlik']) ?>)</p>
                <?php else: ?>
                    <p><strong>Şoför:</strong> Atanmış operatör bulunamadı.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Yeni Kaza Kaydı Ekle -->
        <div class="card mb-4">
            <div class="card-header"><strong>Yeni Araçlı İş Kazası Kaydı Ekle</strong></div>
            <div class="card-body">
                <form action="aracli_kazalar_ekle_sql.php" method="POST">
                    <input type="hidden" name="arac_id" value="<?= $vehicle['arac_id'] ?>">
                    <div class="form-group">
                        <label for="plaka_no">Plaka No</label>
                        <input type="text" id="plaka_no" class="form-control" value="<?= htmlspecialchars($vehicle['plaka_no']) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="tc_kimlik">Şoför (TC Kimlik)</label>
                        <select name="tc_kimlik" id="tc_kimlik" class="form-control" required>
                            <?php if ($operator): ?>
                                <option value="<?= htmlspecialchars($operator['tc_kimlik']) ?>" selected>
                                    <?= htmlspecialchars($operator['ad_soyad']) ?> (TC: <?= htmlspecialchars($operator['tc_kimlik']) ?>)
                                </option>
                            <?php endif; ?>
                            <option value="">Başka bir personel seç</option>
                            <?php foreach ($personnel as $person): ?>
                                <option value="<?= htmlspecialchars($person['tc_kimlik']) ?>">
                                    <?= htmlspecialchars($person['ad_soyad']) ?> (TC: <?= htmlspecialchars($person['tc_kimlik']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_kazasi_tip_id">İş Kazası Tipi</label>
                        <select name="is_kazasi_tip_id" id="is_kazasi_tip_id" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($accident_types as $type): ?>
                                <option value="<?= $type['is_kazasi_tip_id'] ?>"><?= htmlspecialchars($type['is_kazasi_tipi_adi']) ?></option>
                            <?php endforeach; ?>
                            <option value="diger">Diğer</option>
                        </select>
                        <input type="text" name="is_kazasi_diger" id="is_kazasi_diger" class="form-control mt-2" placeholder="Diğer kaza tipini belirtin" style="display: none;">
                    </div>
                    <div class="form-group">
                        <label for="yaralanma_durumu_id">Yaralanma Durumu</label>
                        <select name="yaralanma_durumu_id" id="yaralanma_durumu_id" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($injury_statuses as $status): ?>
                                <option value="<?= $status['yaralanma_durum_id'] ?>"><?= htmlspecialchars($status['yaralanma_durum_adi']) ?></option>
                            <?php endforeach; ?>
                            <option value="diger">Diğer</option>
                        </select>
                        <input type="text" name="yaralanma_durumu_diger" id="yaralanma_durumu_diger" class="form-control mt-2" placeholder="Diğer yaralanma durumunu belirtin" style="display: none;">
                    </div>
                    <div class="form-group">
                        <label for="yaralanma_tip_id">Yaralanma Tipi</label>
                        <select name="yaralanma_tip_id" id="yaralanma_tip_id" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($injury_types as $type): ?>
                                <option value="<?= $type['yaralanma_tip_id'] ?>"><?= htmlspecialchars($type['yaralanma_tipi_adi']) ?></option>
                            <?php endforeach; ?>
                            <option value="diger">Diğer</option>
                        </select>
                        <input type="text" name="yaralanma_tip_diger" id="yaralanma_tip_diger" class="form-control mt-2" placeholder="Diğer yaralanma tipini belirtin" style="display: none;">
                    </div>
                    <div class="form-group">
                        <label for="kaza_aciklamasi">Kaza Açıklaması</label>
                        <textarea name="kaza_aciklamasi" id="kaza_aciklamasi" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="is_kazasi_tarihi">Kaza Tarihi</label>
                        <input type="datetime-local" name="is_kazasi_tarihi" id="is_kazasi_tarihi" class="form-control" required>
                    </div>
                    <button type="submit" name="kaza_ekle" class="btn btn-danger btn-block">Kaza Kaydet</button>
                </form>
            </div>
        </div>

        <!-- Geçmiş Kaza Kayıtları -->
        <div class="card mb-4">
            <div class="card-header"><strong>Geçmiş Araçlı İş Kazası Kayıtları</strong></div>
            <div class="card-body">
                <?php if ($accidents): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center" id="kazalarTablosu">
                            <thead class="table-dark">
                                <tr>
                                    <th>Şoför</th>
                                    <th>Kaza Tipi</th>
                                    <th>Yaralanma Durumu</th>
                                    <th>Yaralanma Tipi</th>
                                    <th>Kaza Açıklaması</th>
                                    <th>Kaza Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($accidents as $accident): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            if (!empty($accident['ad_soyad'])) {
                                                echo htmlspecialchars($accident['ad_soyad']) . ' (TC: ' . htmlspecialchars($accident['tc_kimlik']) . ')';
                                            } else {
                                                echo 'Bilinmeyen Personel (TC: ' . htmlspecialchars($accident['tc_kimlik']) . ')';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($accident['is_kazasi_tipi_adi'] ?? 'Bilinmeyen') ?></td>
                                        <td><?= htmlspecialchars($accident['yaralanma_durum_adi'] ?? 'Bilinmeyen') ?></td>
                                        <td><?= htmlspecialchars($accident['yaralanma_tipi_adi'] ?? 'Bilinmeyen') ?></td>
                                        <td><?= htmlspecialchars($accident['kaza_aciklamasi']) ?></td>
                                        <td><?= htmlspecialchars($accident['is_kazasi_tarihi']) ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm d-flex flex-wrap justify-content-center gap-1">
                                                <a href="aracli_kazalar_duzenle.php?id=<?= $accident['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                                <a href="aracli_kazalar_sil.php?id=<?= $accident['id'] ?>" class="btn btn-danger" onclick="return confirm('Bu kaza kaydını silmek istediğinize emin misiniz?');"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>Bu araca ait iş kazası kaydı bulunamadı.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif (isset($_POST['plaka_sorgula'])): ?>
        <div class="alert alert-warning text-center">
            Bu plakaya ait araç bulunamadı.
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript -->
<script>
    // Tarih input'u için sınırlar
    function setDateConstraints(input) {
        const now = new Date();
        now.setMinutes(now.getMinutes() + 180);
        const maxDate = now.toISOString().slice(0, 16);
        const minDate = new Date(now.getFullYear() - 50, now.getMonth(), now.getDate()).toISOString().slice(0, 16);
        input.setAttribute('max', maxDate);
        input.setAttribute('min', minDate);
    }

    // Diğer seçenekleri için dinamik input gösterimi
    function toggleOtherInput(selectId, inputId) {
        const select = document.getElementById(selectId);
        const input = document.getElementById(inputId);
        select.addEventListener('change', () => {
            input.style.display = select.value === 'diger' ? 'block' : 'none';
            input.required = select.value === 'diger';
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Tarih input'una sınırlar
        const dateInput = document.getElementById('is_kazasi_tarihi');
        if (dateInput) setDateConstraints(dateInput);

        // Diğer seçenekleri için input'lar
        toggleOtherInput('is_kazasi_tip_id', 'is_kazasi_diger');
        toggleOtherInput('yaralanma_durumu_id', 'yaralanma_durumu_diger');
        toggleOtherInput('yaralanma_tip_id', 'yaralanma_tip_diger');

        // DataTable
        if (document.getElementById('kazalarTablosu')) {
            $('#kazalarTablosu').DataTable({
                responsive: true,
                autoWidth: false,
                language: dildosyasi
            });
        }
    });
</script>

<!-- DataTable Kütüphaneleri -->
<link href="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.css" rel="stylesheet" integrity="sha384-fTqd416qRc9kwY299KdgUPsjOvS5bwkeE6jlibx2m7eL3xKheqGyU48QCFgZAyN4" crossorigin="anonymous">
<script src="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.js" integrity="sha384-uAn6fsp1rIJ6afAYV0S5it5ao101zH2fViB74y5tPG8LR2FTMg+HXIWRNxvZrniN" crossorigin="anonymous"></script>

<?php require_once 'footer.php'; ?>