<?php
session_start();
require_once 'header.php';

// Başarı mesajı için yönlendirme kontrolü
$show_success = isset($_SESSION['from_sql']) && $_SESSION['from_sql'] === true;
unset($_SESSION['from_sql']);

// Veritabanından tüm araç tiplerini, markaları, modelleri, firmaları ve durumları çek
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
    $_SESSION['errors'] = ["Veritabanı hatası: " . $e->getMessage()];
}



            // CSRF token üret
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            // Current date and time for datetime-local restrictions
            $currentDateTime = date('Y-m-d\TH:i');

            $dateTime = new DateTime($currentDateTime);

            // 60 dakika (1 saat) eklemek için DateInterval nesnesi oluştur
            $interval = new DateInterval('PT61M'); // PT61M -> P (period) T (time) 61 M (minutes)

            // Saati ekle
            $dateTime->add($interval);

            // Yeni saati istediğiniz formatta biçimlendirin
            $newDateTime = $dateTime->format('Y-m-d\TH:i');
            $currentDateTime = $newDateTime;
            $maxDateTime = (new DateTime())->modify('+2 years')->format('Y-m-d\TH:i');
            $minDateTime = '1970-01-01T00:00';
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
    <h1 class="h3 mb-4 text-gray-800">Araç Ekle</h1>

    <!-- Hata veya başarı mesajları -->
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>
    <?php if ($show_success && isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success']); ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form action="arac_ekle_sql.php" method="POST" id="aracEkleForm" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Araç Bilgileri -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Araç Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="plaka_no">Plaka No</label>
                    <input type="text" class="form-control text-uppercase" id="plaka_no" name="plaka_no" maxlength="11" required>
                    <div class="invalid-feedback">Geçerli bir plaka giriniz. Örn: 34 ABC 1234</div>
                </div>
                <div class="form-group">
                    <label for="arac_tipi_id">Araç Tipi</label>
                    <select class="form-control" id="arac_tipi_id" name="arac_tipi_id" onchange="digerKontrol(this, 'yeni_tip'); filterMarkalar(this.value);" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($arac_tipleri as $tip): ?>
                            <option value="<?php echo $tip['arac_tipi_id']; ?>" data-gecerlilik-suresi="<?php echo $tip['muayene_gecerlilik_suresi_ay']; ?>">
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
                            <option value="<?php echo $marka['marka_id']; ?>"><?php echo htmlspecialchars($marka['marka_adi']); ?></option>
                        <?php endforeach; ?>
                        <option value="diger">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="yeni_marka" name="yeni_marka" style="display:none;" placeholder="Yeni marka">
                </div>
                <div class="form-group">
                    <label for="model_id">Model</label>
                    <select class="form-control" id="model_id" name="model_id" onchange="digerKontrol(this, 'yeni_model')" required>
                        <option value="">Seçiniz</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="yeni_model" name="yeni_model" style="display:none;" placeholder="Yeni model">
                </div>
                <div class="form-group">
                    <label for="uretim_yili">Üretim Yılı</label>
                    <input type="number" class="form-control" id="uretim_yili" name="uretim_yili" min="1970" max="<?php echo date('Y'); ?>" required>
                    <div class="invalid-feedback">Geçerli bir üretim yılı giriniz.</div>
                </div>
                <div class="form-group">
                    <label for="firma_id">Firma</label>
                    <select class="form-control" id="firma_id" name="firma_id" onchange="digerKontrol(this, 'yeni_firma')" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($firmalar as $firma): ?>
                            <option value="<?php echo $firma['firma_id']; ?>"><?php echo htmlspecialchars($firma['firma_adi']); ?></option>
                        <?php endforeach; ?>
                        <option value="diger">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="yeni_firma" name="yeni_firma" style="display:none;" placeholder="Yeni firma">
                </div>
                <div class="form-group">
                    <label for="arac_durum_id">Araç Durumu</label>
                    <select class="form-control" id="arac_durum_id" name="arac_durum_id" onchange="toggleMuayeneRequired(this)" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($arac_durumlari as $durum): ?>
                            <option value="<?php echo $durum['arac_durum_id']; ?>" <?php echo $durum['arac_durum_adi'] === 'Aktif' ? 'selected' : ''; ?>>
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
                <p class="form-note">Muayene bilgileri isteğe bağlıdır. Ancak araç durumu "Aktif" seçildiğinde muayene tarihi zorunludur.</p>
                <div class="form-group">
                    <label for="muayene_tarihi">Muayene Tarihi</label>
                    <input type="datetime-local" class="form-control" id="muayene_tarihi" name="muayene_tarihi"
                           min="<?php echo $minDateTime; ?>" max="<?php echo $currentDateTime; ?>">
                    <div class="invalid-feedback">Geçerli bir muayene tarihi giriniz.</div>
                </div>
                <div class="form-group">
                    <label for="muayene_sonucu">Muayeneden Geçti mi?</label>
                    <select class="form-control" id="muayene_sonucu" name="muayene_sonucu">
                        <option value="">Seçiniz</option>
                        <option value="Evet">Evet</option>
                        <option value="Hayır">Hayır</option>
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
                            <option value="<?php echo htmlspecialchars($personel['tc_kimlik']); ?>">
                                <?php echo htmlspecialchars($personel['ad_soyad']) . ' (' . htmlspecialchars($personel['tc_kimlik']) . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="atama_tarihi">Atama Tarihi</label>
                    <input type="datetime-local" class="form-control" id="atama_tarihi" name="atama_tarihi"
                           min="<?php echo $minDateTime; ?>" max="<?php echo $currentDateTime; ?>">
                    <div class="invalid-feedback">Geçerli bir atama tarihi giriniz.</div>
                </div>
                <div class="form-group">
                    <label for="gorev_sonu_tarihi">Görev Sonu Tarihi</label>
                    <input type="datetime-local" class="form-control" id="gorev_sonu_tarihi" name="gorev_sonu_tarihi"
                           min="<?php echo $currentDateTime; ?>" max="<?php echo $maxDateTime; ?>">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-block" id="kaydetButon">Kaydet
            <span class="yukleniyor spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>
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
    const muayeneTarihiInput = document.getElementById('muayene_tarihi');
    const tcKimlikSelect = document.getElementById('tc_kimlik');
    const atamaTarihiInput = document.getElementById('atama_tarihi');

    // "Diğer" seçeneği için giriş alanını göster/gizle
    function digerKontrol(secim, alanId) {
        const alan = document.getElementById(alanId);
        alan.style.display = secim.value === 'diger' ? 'block' : 'none';
        alan.required = secim.value === 'diger';
    }

    // Araç durumu değiştiğinde muayene tarihini zorunlu yap
    function toggleMuayeneRequired(select) {
        const aktifDurum = aracDurumlari.find(durum => durum.arac_durum_adi === 'Aktif');
        muayeneTarihiInput.required = select.value == aktifDurum?.arac_durum_id;
        muayeneTarihiInput.setAttribute('aria-required', muayeneTarihiInput.required);
        if (muayeneTarihiInput.required) {
            muayeneTarihiInput.classList.add('is-required');
        } else {
            muayeneTarihiInput.classList.remove('is-required');
        }
    }

    // Araç tipi kontrolü
    aracTipiSelect.addEventListener('change', function() {
        digerKontrol(this, 'yeni_tip');
        filterMarkalar(this.value);
        modelSelect.innerHTML = '<option value="">Seçiniz</option>';
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
            toggleMuayeneRequired(aracDurumSelect);
        } else if (this.value === 'Hayır' && arizaliDurum) {
            aracDurumSelect.value = arizaliDurum.arac_durum_id;
            toggleMuayeneRequired(aracDurumSelect);
        } else if (this.value === '') {
            aracDurumSelect.value = '';
            toggleMuayeneRequired(aracDurumSelect);
        }
    });

    // Araç durumu değiştiğinde muayene tarihini kontrol et
    aracDurumSelect.addEventListener('change', function() {
        toggleMuayeneRequired(this);
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

        // Muayene tarihi doğrulama
        const aktifDurum = aracDurumlari.find(durum => durum.arac_durum_adi === 'Aktif');
        let muayeneValid = true;
        if (aracDurumSelect.value == aktifDurum?.arac_durum_id && !muayeneTarihiInput.value) {
            muayeneTarihiInput.classList.add('is-invalid');
            muayeneValid = false;
        } else {
            muayeneTarihiInput.classList.remove('is-invalid');
        }

        if (form.checkValidity() && operatorValid && muayeneValid) {
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
        modelSelect.innerHTML = '<option value="">Seçiniz</option>';

        if (arac_tipi_id === 'diger' || arac_tipi_id === '') {
            markalar.forEach(marka => {
                markaSelect.innerHTML += `<option value="${marka.marka_id}">${marka.marka_adi}</option>`;
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
                markaSelect.innerHTML += `<option value="${marka.marka_id}">${marka.marka_adi}</option>`;
            });
            markaSelect.innerHTML += '<option value="diger">Diğer</option>';
        }
    }

    // Modelleri filtreleme
    function filterModeller(arac_tipi_id, marka_id) {
        modelSelect.innerHTML = '<option value="">Seçiniz</option>';

        if (marka_id === 'diger' || marka_id === '' || arac_tipi_id === 'diger' || arac_tipi_id === '') {
            modelSelect.innerHTML += '<option value="diger">Diğer</option>';
            return;
        }

        const filteredModeller = modeller.filter(model => model.marka_id == marka_id && model.arac_tipi_id == arac_tipi_id);

        if (filteredModeller.length === 0) {
            modelSelect.innerHTML += '<option value="diger">Diğer</option>';
        } else {
            filteredModeller.forEach(model => {
                modelSelect.innerHTML += `<option value="${model.model_id}">${model.model_adi}</option>`;
            });
            modelSelect.innerHTML += '<option value="diger">Diğer</option>';
        }
    }

    // Araç tiplerini filtreleme (marka önce seçildiğinde)
    function filterAracTipleri(marka_id) {
        aracTipiSelect.innerHTML = '<option value="">Seçiniz</option>';

        if (marka_id === 'diger' || marka_id === '') {
            aracTipleri.forEach(tip => {
                aracTipiSelect.innerHTML += `<option value="${tip.arac_tipi_id}">${tip.arac_tipi_adi}</option>`;
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
            aracTipiSelect.innerHTML += `<option value="${tip.arac_tipi_id}">${tip.arac_tipi_adi}</option>`;
        });

        aracTipiSelect.innerHTML += '<option value="diger">Diğer</option>';
    }

    // İlk yüklemede muayene tarihini kontrol et
    toggleMuayeneRequired(aracDurumSelect);
});

// Plaka No girilirken bütün harfsel karakterler otomatik olarak büyük yazılır.
document.getElementById('plaka_no').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>

<?php require_once 'footer.php'; ?>