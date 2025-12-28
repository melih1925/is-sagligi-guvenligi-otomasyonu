<?php
session_start();
require_once 'header.php';

// CSRF token üret
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Plaka no kontrolü
if (!isset($_GET['plaka_no']) || empty(trim($_GET['plaka_no']))) {
    header("Location: arac_listele.php");
    exit;
}

$plaka = trim($_GET['plaka_no']);

// Araç ve muayene bilgilerini çek
$sorgu = $db->prepare("
    SELECT ab.*, tipi.arac_tipi_adi, tipi.muayene_gecerlilik_suresi_ay, marka.marka_adi, model.model_adi, firma.firma_adi, durum.arac_durum_adi, 
           am.muayene_tarihi, am.muayeneden_gecti_mi, am.muayene_gecerlilik_tarihi
    FROM arac_bilgi ab
    LEFT JOIN hazir_arac_tipleri tipi ON ab.arac_tipi_id = tipi.arac_tipi_id
    LEFT JOIN hazir_markalar marka ON ab.marka_id = marka.marka_id
    LEFT JOIN hazir_modeller model ON ab.model_id = model.model_id
    LEFT JOIN hazir_firmalar firma ON ab.firma_id = firma.firma_id
    LEFT JOIN hazir_arac_durumlari durum ON ab.arac_durum_id = durum.arac_durum_id
    LEFT JOIN arac_muayene am ON ab.arac_id = am.arac_id
    WHERE ab.plaka_no = ?
");
$sorgu->execute([$plaka]);
$arac = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$arac) {
    header("Location: arac_listele.php");
    exit;
}

// Veritabanından tüm araç tiplerini, markaları, modelleri, firmaları, durumları ve personelleri çek
try {
    $arac_tipleri_sorgu = $db->prepare("SELECT arac_tipi_id, arac_tipi_adi, muayene_gecerlilik_suresi_ay FROM hazir_arac_tipleri ORDER BY arac_tipi_adi");
    $arac_tipleri_sorgu->execute();
    $arac_tipleri = $arac_tipleri_sorgu->fetchAll(PDO::FETCH_ASSOC);

    $markalar_sorgu = $db->prepare("SELECT marka_id, marka_adi FROM hazir_markalar ORDER BY marka_adi");
    $markalar_sorgu->execute();
    $tum_markalar = $markalar_sorgu->fetchAll(PDO::FETCH_ASSOC);

    $modeller_sorgu = $db->prepare("SELECT model_id, model_adi, marka_id, arac_tipi_id FROM hazir_modeller ORDER BY model_adi");
    $modeller_sorgu->execute();
    $tum_modeller = $modeller_sorgu->fetchAll(PDO::FETCH_ASSOC);

    $firmalar_sorgu = $db->prepare("SELECT firma_id, firma_adi FROM hazir_firmalar ORDER BY firma_adi");
    $firmalar_sorgu->execute();
    $firmalar = $firmalar_sorgu->fetchAll(PDO::FETCH_ASSOC);

    $durumlar_sorgu = $db->prepare("SELECT arac_durum_id, arac_durum_adi FROM hazir_arac_durumlari ORDER BY arac_durum_adi");
    $durumlar_sorgu->execute();
    $arac_durumlari = $durumlar_sorgu->fetchAll(PDO::FETCH_ASSOC);

    // Personel bilgilerini çek
    $personeller_sorgu = $db->prepare("SELECT tc_kimlik, ad_soyad FROM personel_kisisel_bilgi ORDER BY ad_soyad ASC");
    $personeller_sorgu->execute();
    $personeller = $personeller_sorgu->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['hatalar'] = ["Veritabanı hatası: " . $e->getMessage()];
}


    // Mevcut zamana 61 dakika ekle ve doğrudan istediğiniz formatta al
    $currentDateTime = (new DateTime())->modify('+61 minutes')->format('Y-m-d\TH:i');

    $minDateTime = '1950-01-01T00:00';

    $maxDateTime = (new DateTime())->modify('+2 years')->format('Y-m-d\TH:i');


    // Araç, muayene ve operatör atama bilgilerini çek
    $sorgu = $db->prepare("
        SELECT ab.*, 
            tipi.arac_tipi_adi, tipi.muayene_gecerlilik_suresi_ay, 
            marka.marka_adi, 
            model.model_adi, 
            firma.firma_adi, 
            durum.arac_durum_adi, 
            am.muayene_tarihi, am.muayeneden_gecti_mi, am.muayene_gecerlilik_tarihi,
            ao.tc_kimlik, ao.atama_tarihi, ao.gorev_sonu_tarihi
        FROM arac_bilgi ab
        LEFT JOIN hazir_arac_tipleri tipi ON ab.arac_tipi_id = tipi.arac_tipi_id
        LEFT JOIN hazir_markalar marka ON ab.marka_id = marka.marka_id
        LEFT JOIN hazir_modeller model ON ab.model_id = model.model_id
        LEFT JOIN hazir_firmalar firma ON ab.firma_id = firma.firma_id
        LEFT JOIN hazir_arac_durumlari durum ON ab.arac_durum_id = durum.arac_durum_id
        LEFT JOIN arac_muayene am ON ab.arac_id = am.arac_id
        LEFT JOIN arac_operator_atama ao ON ab.arac_id = ao.arac_id
            AND (ao.gorev_sonu_tarihi IS NULL OR ao.gorev_sonu_tarihi >= NOW())
            AND ao.atama_tarihi = (
                SELECT MAX(ao2.atama_tarihi)
                FROM arac_operator_atama ao2
                WHERE ao2.arac_id = ab.arac_id
                AND (ao2.gorev_sonu_tarihi IS NULL OR ao2.gorev_sonu_tarihi >= NOW())
            )
        WHERE ab.plaka_no = ?
    ");
    $sorgu->execute([$plaka]);
    $arac = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$arac) {
        header("Location: arac_listele.php");
        exit;
    }
?>

<style>
    .form-group label { font-weight: bold; color: #495057; }
    h6 { font-size: 1.25rem; text-decoration: underline; }
    .card-body { padding: 1.5rem; }
    .form-control { margin-bottom: 1rem; }
    .alert { margin-bottom: 1rem; }
    .yukleniyor { display: none; margin-left: 10px; }
    .form-note { font-style: italic; color: #6c757d; margin-top: -0.5rem; margin-bottom: 1rem; }
    .invalid-feedback { display: none; color: #dc3545; font-size: 0.875em; }
    .is-invalid ~ .invalid-feedback { display: block; }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Araç Düzenle</h1>

    <!-- Hata veya başarı mesajları -->
    <?php if (isset($_SESSION['hatalar']) && !empty($_SESSION['hatalar'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['hatalar'] as $hata): ?>
                <p><?php echo htmlspecialchars($hata); ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['hatalar']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['basari'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['basari']); ?>
            <?php unset($_SESSION['basari']); ?>
        </div>
    <?php endif; ?>

    <form action="arac_kaydet.php" method="POST" id="aracEkleForm" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="eski_plaka" value="<?php echo htmlspecialchars($arac['plaka_no']); ?>">

        <!-- Araç Bilgileri -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Araç Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="plaka_no">Plaka No</label>
                    <input type="text" class="form-control" id="plaka_no" name="plaka_no" maxlength="11" value="<?php echo htmlspecialchars($arac['plaka_no']); ?>" required>
                    <div class="invalid-feedback">Plaka No zorunludur ve 11 karakter olmalıdır.</div>
                </div>
                <div class="form-group">
                    <label for="arac_tipi_id">Araç Tipi</label>
                    <select class="form-control" id="arac_tipi_id" name="arac_tipi_id" onchange="digerKontrol(this, 'yeni_tip'); filterMarkalar(this.value);" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($arac_tipleri as $tip): ?>
                            <option value="<?php echo $tip['arac_tipi_id']; ?>" <?php echo $tip['arac_tipi_id'] == $arac['arac_tipi_id'] ? 'selected' : ''; ?> 
                                    data-gecerlilik-suresi="<?php echo $tip['muayene_gecerlilik_suresi_ay']; ?>">
                                <?php echo htmlspecialchars($tip['arac_tipi_adi']); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="diger">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="yeni_tip" name="yeni_tip" style="display:none;" placeholder="Yeni araç tipi">
                </div>
                <div class="form-group">
                    <label for="marka_id">Marka</label>
                    <select class="form-control" id="marka_id" name="marka_id" onchange="digerKontrol(this, 'yeni_marka'); filterModeller(document.getElementById('arac_tipi_id').value, this.value);" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($tum_markalar as $marka): ?>
                            <option value="<?php echo $marka['marka_id']; ?>" <?php echo $marka['marka_id'] == $arac['marka_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($marka['marka_adi']); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="diger">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="yeni_marka" name="yeni_marka" style="display:none;" placeholder="Yeni marka">
                </div>
                <div class="form-group">
                    <label for="model_id">Model</label>
                    <select class="form-control" id="model_id" name="model_id" onchange="digerKontrol(this, 'yeni_model')" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($tum_modeller as $model): ?>
                            <option value="<?php echo $model['model_id']; ?>" <?php echo $model['model_id'] == $arac['model_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($model['model_adi']); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="diger">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="yeni_model" name="yeni_model" style="display:none;" placeholder="Yeni model">
                </div>
                <div class="form-group">
                    <label for="uretim_yili">Üretim Yılı</label>
                    <input type="number" class="form-control" id="uretim_yili" name="uretim_yili" min="1970" max="<?php echo date('Y'); ?>" 
                           value="<?php echo htmlspecialchars($arac['uretim_yili']); ?>" required>
                    <div class="invalid-feedback">Geçerli bir üretim yılı giriniz.</div>
                </div>
                <div class="form-group">
                    <label for="firma_id">Firma</label>
                    <select class="form-control" id="firma_id" name="firma_id" onchange="digerKontrol(this, 'yeni_firma')" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($firmalar as $firma): ?>
                            <option value="<?php echo $firma['firma_id']; ?>" <?php echo $firma['firma_id'] == $arac['firma_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($firma['firma_adi']); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="diger">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="yeni_firma" name="yeni_firma" style="display:none;" placeholder="Yeni firma">
                </div>
                <div class="form-group">
                    <label for="arac_durum_id">Araç Durumu</label>
                    <select class="form-control" id="arac_durum_id" name="arac_durum_id" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($arac_durumlari as $durum): ?>
                            <option value="<?php echo $durum['arac_durum_id']; ?>" <?php echo $durum['arac_durum_id'] == $arac['arac_durum_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($durum['arac_durum_adi']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Muayene Bilgileri -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Muayene Bilgileri</h6>
            </div>
            <div class="card-body">
                <p class="form-note">Muayene bilgileri isteğe bağlıdır. Gerekirse boş bırakabilirsiniz.</p>
                <div class="form-group">
                    <label for="muayene_tarihi">Muayene Tarihi</label>
                    <input type="datetime-local" class="form-control" id="muayene_tarihi" name="muayene_tarihi"
                           min="<?php echo $minDateTime; ?>" max="<?php echo $currentDateTime; ?>"
                           value="<?php echo $arac['muayene_tarihi'] ? date('Y-m-d\TH:i', strtotime($arac['muayene_tarihi'])) : ''; ?>">
                    <div class="invalid-feedback">Geçerli bir muayene tarihi giriniz.</div>
                </div>
                <div class="form-group">
                    <label for="muayene_sonucu">Muayeneden Geçti mi?</label>
                    <select class="form-control" id="muayene_sonucu" name="muayene_sonucu">
                        <option value="">Seçiniz</option>
                        <option value="Evet" <?php echo $arac['muayeneden_gecti_mi'] == 1 ? 'selected' : ''; ?>>Evet</option>
                        <option value="Hayır" <?php echo $arac['muayeneden_gecti_mi'] == 0 ? 'selected' : ''; ?>>Hayır</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Araç Operatör Atama -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Araç Operatör Atama (Opsiyonel)</h6>
            </div>
            <div class="card-body">
                <p class="form-note">Operatör atama bilgileri isteğe bağlıdır. Gerekirse boş bırakabilirsiniz.</p>
                <div class="form-group">
                    <label for="tc_kimlik">Personel</label>
                    <select class="form-control" id="tc_kimlik" name="tc_kimlik">
                        <option value="">Seçiniz</option>
                        <?php foreach ($personeller as $personel): ?>
                            <option value="<?php echo htmlspecialchars($personel['tc_kimlik']); ?>" 
                                    <?php echo $personel['tc_kimlik'] == $arac['tc_kimlik'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($personel['ad_soyad']) . ' (' . htmlspecialchars($personel['tc_kimlik']) . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="atama_tarihi">Atama Tarihi</label>
                    <input type="datetime-local" class="form-control" id="atama_tarihi" name="atama_tarihi"
                        min="<?php echo $minDateTime; ?>" max="<?php echo $currentDateTime; ?>"
                        value="<?php echo $arac['atama_tarihi'] ? date('Y-m-d\TH:i', strtotime($arac['atama_tarihi'])) : ''; ?>">
                    <div class="invalid-feedback">Geçerli bir atama tarihi giriniz.</div>
                </div>
                <div class="form-group">
                    <label for="gorev_sonu_tarihi">Görev Sonu Tarihi (Opsiyonel)</label>
                    <input type="datetime-local" class="form-control" id="gorev_sonu_tarihi" name="gorev_sonu_tarihi"
                        min="<?php echo $currentDateTime; ?>" max="<?php echo $maxDateTime; ?>"
                        value="<?php echo $arac['gorev_sonu_tarihi'] ? date('Y-m-d\TH:i', strtotime($arac['gorev_sonu_tarihi'])) : ''; ?>">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-block" id="kaydetButon">Bilgileri Güncelle</button>
        <span class="yukleniyor spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    </form>
</div>

<script>
// Veritabanından çekilen verileri JavaScript'e aktar
const markalar = <?php echo json_encode($tum_markalar); ?>;
const modeller = <?php echo json_encode($tum_modeller); ?>;
const aracTipleri = <?php echo json_encode($arac_tipleri); ?>;
const aracDurumlari = <?php echo json_encode($arac_durumlari); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const aracTipiSelect = document.getElementById('arac_tipi_id');
    const markaSelect = document.getElementById('marka_id');
    const modelSelect = document.getElementById('model_id');
    const muayeneSonucuSelect = document.getElementById('muayene_sonucu');
    const aracDurumSelect = document.getElementById('arac_durum_id');
    const tcKimlikSelect = document.getElementById('tc_kimlik');
    const atamaTarihiInput = document.getElementById('atama_tarihi');

    // "Diğer" seçeneği için giriş alanını göster/gizle
    function digerKontrol(secim, alanId) {
        const alan = document.getElementById(alanId);
        alan.style.display = secim.value === 'diger' ? 'block' : 'none';
        alan.required = secim.value === 'diger';
    }

    // Araç tipi kontrolü
    aracTipiSelect.addEventListener('change', function() {
        digerKontrol(this, 'yeni_tip');
        filterMarkalar(this.value);
        filterModeller(this.value, markaSelect.value);
    });

    // Marka kontrolü
    markaSelect.addEventListener('change', function() {
        digerKontrol(this, 'yeni_marka');
        filterModeller(aracTipiSelect.value, this.value);
        if (aracTipiSelect.value === '') {
            filterAracTipleri(this.value);
        }
    });

    // Model kontrolü
    modelSelect.addEventListener('change', function() {
        digerKontrol(this, 'yeni_model');
    });

    // Firma kontrolü
    document.getElementById('firma_id').addEventListener('change', function() {
        digerKontrol(this, 'yeni_firma');
    });

    // Muayene sonucu değiştiğinde araç durumunu güncelle
    muayeneSonucuSelect.addEventListener('change', function() {
        const aktifDurum = aracDurumlari.find(durum => durum.arac_durum_adi === 'Aktif');
        const arizaliDurum = aracDurumlari.find(durum => durum.arac_durum_adi === 'Arızalı');
        
        if (this.value === 'Evet' && aktifDurum) {
            aracDurumSelect.value = aktifDurum.arac_durum_id;
        } else if (this.value === 'Hayır' && arizaliDurum) {
            aracDurumSelect.value = arizaliDurum.arac_durum_id;
        } else if (this.value === '') {
            aracDurumSelect.value = ''; // Kullanıcı istediği durumu seçebilir
        }
    });

    // Operatör atama formu doğrulama
    document.getElementById('aracEkleForm').addEventListener('submit', function(e) {
        const submitButton = document.getElementById('kaydetButon');
        const form = this;

        // Operatör atama alanlarının doğruluğunu kontrol et
        let operatorValid = true;
        if (tcKimlikSelect.value || atamaTarihiInput.value) {
            if (!tcKimlikSelect.value) {
                tcKimlikSelect.classList.add('is-invalid');
                operatorValid = false;
            } else {
                tcKimlikSelect.classList.remove('is-invalid');
            }
            if (!atamaTarihiInput.value) {
                atamaTarihiInput.classList.add('is-invalid');
                operatorValid = false;
            } else {
                atamaTarihiInput.classList.remove('is-invalid');
            }
        }

        if (form.checkValidity() && operatorValid) {
            submitButton.disabled = true;
            document.querySelector('.yukleniyor').style.display = 'inline-block';
        } else {
            e.preventDefault();
            form.classList.add('was-validated');
        }
    });

    // Markaları filtreleme
    function filterMarkalar(arac_tipi_id) {
        markaSelect.innerHTML = '<option value="">Seçiniz</option>';

        if (arac_tipi_id === 'diger' || arac_tipi_id === '') {
            markalar.forEach(marka => {
                const selected = marka.marka_id == '<?php echo $arac['marka_id']; ?>' ? 'selected' : '';
                markaSelect.innerHTML += `<option value="${marka.marka_id}" ${selected}>${marka.marka_adi}</option>`;
            });
            markaSelect.innerHTML += '<option value="diger">Diğer</option>';
            return;
        }

        const filteredMarkalar = markalar.filter(marka => {
            return modeller.some(model => model.marka_id == marka.marka_id && model.arac_tipi_id == arac_tipi_id);
        });

        if (filteredMarkalar.length === 0) {
            markaSelect.innerHTML += '<option value="diger">Diğer</option>';
        } else {
            filteredMarkalar.forEach(marka => {
                const selected = marka.marka_id == '<?php echo $arac['marka_id']; ?>' ? 'selected' : '';
                markaSelect.innerHTML += `<option value="${marka.marka_id}" ${selected}>${marka.marka_adi}</option>`;
            });
            markaSelect.innerHTML += '<option value="diger">Diğer</option>';
        }
    }

    // Modelleri filtreleme
    function filterModeller(arac_tipi_id, marka_id) {
        modelSelect.innerHTML = '<option value="">Seçiniz</option>';

        if (marka_id === 'diger' || marka_id === '' || arac_tipi_id === 'diger' || arac_tipi_id === '') {
            modeller.forEach(model => {
                const selected = model.model_id == '<?php echo $arac['model_id']; ?>' ? 'selected' : '';
                modelSelect.innerHTML += `<option value="${model.model_id}" ${selected}>${model.model_adi}</option>`;
            });
            modelSelect.innerHTML += '<option value="diger">Diğer</option>';
            return;
        }

        const filteredModeller = modeller.filter(model => model.marka_id == marka_id && model.arac_tipi_id == arac_tipi_id);

        if (filteredModeller.length === 0) {
            modelSelect.innerHTML += '<option value="diger">Diğer</option>';
        } else {
            filteredModeller.forEach(model => {
                const selected = model.model_id == '<?php echo $arac['model_id']; ?>' ? 'selected' : '';
                modelSelect.innerHTML += `<option value="${model.model_id}" ${selected}>${model.model_adi}</option>`;
            });
            modelSelect.innerHTML += '<option value="diger">Diğer</option>';
        }
    }

    // Araç tiplerini filtreleme (marka önce seçildiğinde)
    function filterAracTipleri(marka_id) {
        aracTipiSelect.innerHTML = '<option value="">Seçiniz</option>';

        if (marka_id === 'diger' || marka_id === '') {
            aracTipleri.forEach(tip => {
                const selected = tip.arac_tipi_id == '<?php echo $arac['arac_tipi_id']; ?>' ? 'selected' : '';
                aracTipiSelect.innerHTML += `<option value="${tip.arac_tipi_id}" ${selected}>${tip.arac_tipi_adi}</option>`;
            });
            aracTipiSelect.innerHTML += '<option value="diger">Diğer</option>';
            return;
        }

        const relatedTipIds = modeller
            .filter(model => model.marka_id == marka_id)
            .map(model => model.arac_tipi_id);

        const uniqueTipIds = [...new Set(relatedTipIds)];

        const filteredTipler = aracTipleri.filter(tip => uniqueTipIds.includes(parseInt(tip.arac_tipi_id)));

        filteredTipler.forEach(tip => {
            const selected = tip.arac_tipi_id == '<?php echo $arac['arac_tipi_id']; ?>' ? 'selected' : '';
            aracTipiSelect.innerHTML += `<option value="${tip.arac_tipi_id}" ${selected}>${tip.arac_tipi_adi}</option>`;
        });

        aracTipiSelect.innerHTML += '<option value="diger">Diğer</option>';
    }

    // İlk yüklemede markaları ve modelleri filtrele
    filterMarkalar('<?php echo $arac['arac_tipi_id']; ?>');
    filterModeller('<?php echo $arac['arac_tipi_id']; ?>', '<?php echo $arac['marka_id']; ?>');
});
</script>

<?php require_once 'footer.php'; ?>