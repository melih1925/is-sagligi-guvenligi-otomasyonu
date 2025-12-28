<?php
require_once 'config.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    header("Location: aracli_kazalar_ekle.php");
    exit;
}

$accident_id = (int)$_GET['id'];

// Kaza kaydını çek
$accident_query = $db->prepare("
    SELECT ak.*, ab.plaka_no, pkb.ad_soyad, pkb.tc_kimlik
    FROM aracli_kazalar ak
    INNER JOIN arac_bilgi ab ON ak.arac_id = ab.arac_id
    INNER JOIN personel_kisisel_bilgi pkb ON ak.tc_kimlik = pkb.tc_kimlik
    WHERE ak.id = ?
");
$accident_query->execute([$accident_id]);
$accident = $accident_query->fetch(PDO::FETCH_ASSOC);

if (!$accident) {
    header("Location: aracli_kazalar_ekle.php");
    exit;
}

// Hazır veri listelerini çek
$accident_types = $db->query("SELECT * FROM hazir_is_kazalari")->fetchAll(PDO::FETCH_ASSOC);
$injury_statuses = $db->query("SELECT * FROM hazir_yaralanma_durumlar")->fetchAll(PDO::FETCH_ASSOC);
$injury_types = $db->query("SELECT * FROM hazir_yaralanma_tipler")->fetchAll(PDO::FETCH_ASSOC);
$personnel = $db->query("SELECT tc_kimlik, ad_soyad FROM personel_kisisel_bilgi ORDER BY ad_soyad")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4 mb-4">
    <h2 class="text-center text-danger font-weight-bold">Araçlı İş Kazası Düzenleme</h2>
    <hr>
</div>

<div class="container mt-5">
    <div class="card mb-4">
        <div class="card-header"><strong>Kaza Kaydını Düzenle</strong></div>
        <div class="card-body">
            <form action="aracli_kazalar_duzenle_sql.php" method="POST">
                <input type="hidden" name="id" value="<?= $accident['id'] ?>">
                <input type="hidden" name="arac_id" value="<?= $accident['arac_id'] ?>">
                <div class="form-group">
                    <label for="plaka_no">Plaka No</label>
                    <input type="text" id="plaka_no" class="form-control" value="<?= htmlspecialchars($accident['plaka_no']) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="tc_kimlik">Operatör (TC Kimlik)</label>
                    <select name="tc_kimlik" id="tc_kimlik" class="form-control" required>
                        <option value="<?= htmlspecialchars($accident['tc_kimlik']) ?>" selected>
                            <?= htmlspecialchars($accident['ad_soyad']) ?> (TC: <?= htmlspecialchars($accident['tc_kimlik']) ?>)
                        </option>
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
                            <option value="<?= $type['is_kazasi_tip_id'] ?>" <?= $accident['is_kazasi_tip_id'] == $type['is_kazasi_tip_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['is_kazasi_tipi_adi']) ?>
                            </option>
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
                            <option value="<?= $status['yaralanma_durum_id'] ?>" <?= $accident['yaralanma_durumu_id'] == $status['yaralanma_durum_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status['yaralanma_durum_adi']) ?>
                            </option>
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
                            <option value="<?= $type['yaralanma_tip_id'] ?>" <?= $accident['yaralanma_tip_id'] == $type['yaralanma_tip_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['yaralanma_tipi_adi']) ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="diger">Diğer</option>
                    </select>
                    <input type="text" name="yaralanma_tip_diger" id="yaralanma_tip_diger" class="form-control mt-2" placeholder="Diğer yaralanma tipini belirtin" style="display: none;">
                </div>
                <div class="form-group">
                    <label for="kaza_aciklamasi">Kaza Açıklaması</label>
                    <textarea name="kaza_aciklamasi" id="kaza_aciklamasi" class="form-control" rows="4" required><?= htmlspecialchars($accident['kaza_aciklamasi']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="is_kazasi_tarihi">Kaza Tarihi</label>
                    <input type="datetime-local" name="is_kazasi_tarihi" id="is_kazasi_tarihi" class="form-control" value="<?= str_replace(' ', 'T', $accident['is_kazasi_tarihi']) ?>" required>
                </div>
                <button type="submit" name="kaza_duzenle" class="btn btn-warning btn-block">Kaydı Güncelle</button>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Tarih input'u için sınırlar
    function setDateConstraints(input) {
        const now = new Date();
        now.setMinutes(now.getMinutes() + 180);
        const maxDate = now.toISOString().slice(0, 16);
        const minDate = new Date(now.getFullYear() - 50, now.getMonth(), now.getDay()).toISOString().slice(0, 16);
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
        const dateInput = document.getElementById('is_kazasi_tarihi');
        if (dateInput) setDateConstraints(dateInput);

        toggleOtherInput('is_kazasi_tip_id', 'is_kazasi_diger');
        toggleOtherInput('yaralanma_durumu_id', 'yaralanma_durumu_diger');
        toggleOtherInput('yaralanma_tip_id', 'yaralanma_tip_diger');
    });
</script>

<?php require_once 'footer.php'; ?>