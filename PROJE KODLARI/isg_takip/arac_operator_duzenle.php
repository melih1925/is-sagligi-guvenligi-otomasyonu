<?php
session_start();
require_once 'header.php';

// CSRF token oluştur
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Atama ID'sini al
$atama_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kullanıcı ID'sini oturumdan al
$kul_id = isset($_SESSION['kul_id']) ? (int)$_SESSION['kul_id'] : 0;

// Atama bilgilerini çek
try {
    $sorgu = $db->prepare("
        SELECT ao.arac_id, ao.tc_kimlik, ao.atama_tarihi, ao.gorev_sonu_tarihi, ab.plaka_no, pkb.ad_soyad
        FROM arac_operator_atama ao
        INNER JOIN arac_bilgi ab ON ao.arac_id = ab.arac_id
        INNER JOIN personel_kisisel_bilgi pkb ON ao.tc_kimlik = pkb.tc_kimlik
        WHERE ao.id = ?
    ");
    $sorgu->execute([$atama_id]);
    $atama = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$atama) {
        $_SESSION['errors'] = ["Atama kaydı bulunamadı."];
        header("Location: arac_operator_atama.php");
        exit;
    }

    // Araçlar ve personeller
    $araclar_sorgu = $db->prepare("SELECT arac_id, plaka_no FROM arac_bilgi");
    $araclar_sorgu->execute();
    $araclar = $araclar_sorgu->fetchAll(PDO::FETCH_ASSOC);

    $personeller_sorgu = $db->prepare("SELECT tc_kimlik, ad_soyad FROM personel_kisisel_bilgi");
    $personeller_sorgu->execute();
    $personeller = $personeller_sorgu->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Veritabanı hatası: " . $e->getMessage()];
    header("Location: arac_operator_atama.php");
    exit;
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['errors'] = ["Geçersiz CSRF token."];
        header("Location: arac_operator_duzenle.php?id=$atama_id");
        exit;
    }

    // Verileri al
    $arac_id = isset($_POST['arac_id']) ? (int)$_POST['arac_id'] : 0;
    $tc_kimlik = isset($_POST['tc_kimlik']) ? trim(htmlspecialchars($_POST['tc_kimlik'])) : '';
    $atama_tarihi = isset($_POST['atama_tarihi']) ? trim($_POST['atama_tarihi']) : '';
    $gorev_sonu_tarihi = isset($_POST['gorev_sonu_tarihi']) && !empty($_POST['gorev_sonu_tarihi']) ? trim($_POST['gorev_sonu_tarihi']) : null;


    $currentDateTime = (new DateTime())->modify('+181 minutes');

    $currentDateTimeString = $currentDateTime->format('Y-m-d\TH:i');

    // Doğrulama
    $errors = [];
    if ($arac_id <= 0) {
        $errors[] = "Geçerli bir araç seçiniz.";
    }
    if (empty($tc_kimlik) || !preg_match('/^[0-9]{11}$/', $tc_kimlik)) {
        $errors[] = "Geçerli bir TC Kimlik No giriniz.";
    }
    if (empty($atama_tarihi)) {
        $errors[] = "Atama tarihi zorunludur.";
    } else {
        $atamaDateTime = new DateTime($atama_tarihi);

        if ($atamaDateTime > $currentDateTime) {
            $errors[] = "Atama tarihi bugünden sonrası olamaz. Şuan ki zaman: " . $currentDateTimeString;
        }
    }
    if ($gorev_sonu_tarihi) {
        $gorevSonuDateTime = new DateTime($gorev_sonu_tarihi);
        if ($gorevSonuDateTime < $currentDateTime) {
            $errors[] = "Görev sonu tarihi, atama tarihinden önce olamaz.";
        }
    }

    // Araç ve personel kontrolü
    try {
        $sorgu = $db->prepare("SELECT COUNT(*) FROM arac_bilgi WHERE arac_id = ?");
        $sorgu->execute([$arac_id]);
        if ($sorgu->fetchColumn() == 0) {
            $errors[] = "Seçilen araç bulunamadı.";
        }

        $sorgu = $db->prepare("SELECT COUNT(*) FROM personel_kisisel_bilgi WHERE tc_kimlik = ?");
        $sorgu->execute([$tc_kimlik]);
        if ($sorgu->fetchColumn() == 0) {
            $errors[] = "Seçilen personel bulunamadı.";
        }
    } catch (PDOException $e) {
        $errors[] = "Veritabanı hatası: " . $e->getMessage();
    }

    // Hata varsa
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: arac_operator_duzenle.php?id=$atama_id");
        exit;
    }

    // Güncelleme
    try {
        $guncelle_sorgu = $db->prepare("
            UPDATE arac_operator_atama 
            SET arac_id = ?, tc_kimlik = ?, atama_tarihi = ?, gorev_sonu_tarihi = ?, kullanici_id = ?
            WHERE id = ?
        ");
        $guncelle_sorgu->execute([$arac_id, $tc_kimlik, $atama_tarihi, $gorev_sonu_tarihi, $kul_id, $atama_id]);

        $_SESSION['success'] = "Atama başarıyla güncellendi.";
        $_SESSION['from_sql'] = true;
        header("Location: arac_operator_atama.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['errors'] = ["Veritabanı hatası: " . $e->getMessage()];
        header("Location: arac_operator_duzenle.php?id=$atama_id");
        exit;
    }
}
?>

<style>
    .form-group label {
        font-weight: bold;
        color: #495057;
    }
    h6 {
        font-size: 20px;
        text-decoration: underline 1px solid #000;
    }
    .loading-spinner {
        display: none;
        margin-left: 10px;
    }
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
    }
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px) !important;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: 100%;
        top: 0;
        right: 0.75rem;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />


<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Operatör Atama Düzenle</h1>

    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <form action="arac_operator_duzenle.php?id=<?php echo $atama_id; ?>" method="POST" id="operatorDuzenleForm" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Atama Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="arac_id">Araç</label>
                    <select class="form-control select2" id="arac_id" name="arac_id" required>
                        <option value="">Araç Seçiniz</option>
                        <?php foreach ($araclar as $arac): ?>
                            <option value="<?php echo $arac['arac_id']; ?>" <?php echo $arac['arac_id'] == $atama['arac_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($arac['plaka_no']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Araç seçimi zorunludur.</div>
                </div>
                <div class="form-group">
                    <label for="tc_kimlik">Personel</label>
                    <select class="form-control select2" id="tc_kimlik" name="tc_kimlik" required>
                        <option value="">Personel Seçiniz</option>
                        <?php foreach ($personeller as $personel): ?>
                            <option value="<?php echo $personel['tc_kimlik']; ?>" <?php echo $personel['tc_kimlik'] == $atama['tc_kimlik'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($personel['ad_soyad'] . ' (' . $personel['tc_kimlik'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Personel seçimi zorunludur.</div>
                </div>
                <div class="form-group">
                    <label for="atama_tarihi">Atama Tarihi</label>
                    <input type="datetime-local" class="form-control" id="atama_tarihi" name="atama_tarihi" required
                        max="<?php echo date('Y-m-d\TH:i'); ?>"
                        value="<?php echo $atama['atama_tarihi'] ? date('Y-m-d\TH:i', strtotime($atama['atama_tarihi'])) : ''; ?>">
                    <div class="invalid-feedback">Geçerli bir atama tarihi giriniz (gelecekte olamaz).</div>
                </div>
                <div class="form-group">
                    <label for="gorev_sonu_tarihi">Görev Sonu Tarihi (İsteğe Bağlı)</label>
                    <input type="datetime-local" class="form-control" id="gorev_sonu_tarihi" name="gorev_sonu_tarihi"
                        min="<?php echo date('Y-m-d\TH:i'); ?>"
                        value="<?php echo $atama['gorev_sonu_tarihi'] && strtotime($atama['gorev_sonu_tarihi']) >= time() ? date('Y-m-d\TH:i', strtotime($atama['gorev_sonu_tarihi'])) : ''; ?>">
                    <div class="invalid-feedback">Geçerli bir görev sonu tarihi giriniz (bugünden önce olamaz).</div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" id="submitButton">Güncelle</button>
        <span class="loading-spinner spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('operatorDuzenleForm');
    const submitButton = document.getElementById('submitButton');
    const aracSelect = document.getElementById('arac_id');
    const personelSelect = document.getElementById('tc_kimlik');
    const atamaTarihi = document.getElementById('atama_tarihi');
    const gorevSonuTarihi = document.getElementById('gorev_sonu_tarihi');

    // Select2'yi başlat
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: $(this).find('option:first').text(),
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Sonuç bulunamadı"; },
            searching: function() { return "Aranıyor..."; }
        }
    });

    $(aracSelect).on('select2:open', function() {
        if ($(this).val() === '') {
            $(this).find('option:first').prop('disabled', true);
        }
    });
    $(personelSelect).on('select2:open', function() {
        if ($(this).val() === '') {
            $(this).find('option:first').prop('disabled', true);
        }
    });

    // Form gönderiminde doğrulama ve spinner
    form.addEventListener('submit', function(e) {
        let isValid = form.checkValidity() && aracSelect.value !== '' && personelSelect.value !== '' && atamaTarihi.value !== '';
        if (isValid) {
            const atamaDate = new Date(atamaTarihi.value);
            const now = new Date();
            if (atamaDate > now) {
                e.preventDefault();
                atamaTarihi.classList.add('is-invalid');
                atamaTarihi.nextElementSibling.textContent = 'Atama tarihi bugünden sonrası olamaz.';
                return;
            }
            if (gorevSonuTarihi.value) {
                const gorevSonuDate = new Date(gorevSonuTarihi.value);
                if (gorevSonuDate < now) {
                    e.preventDefault();
                    gorevSonuTarihi.classList.add('is-invalid');
                    gorevSonuTarihi.nextElementSibling.textContent = 'Görev sonu tarihi bugünden önce olamaz.';
                    return;
                }
                if (gorevSonuDate < atamaDate) {
                    e.preventDefault();
                    gorevSonuTarihi.classList.add('is-invalid');
                    gorevSonuTarihi.nextElementSibling.textContent = 'Görev sonu tarihi, atama tarihinden önce olamaz.';
                    return;
                }
            }
            submitButton.disabled = true;
            document.querySelector('.loading-spinner').style.display = 'inline-block';
        } else {
            e.preventDefault();
            form.classList.add('was-validated');
            if (aracSelect.value === '') $(aracSelect).addClass('is-invalid');
            if (personelSelect.value === '') $(personelSelect).addClass('is-invalid');
            if (atamaTarihi.value === '') atamaTarihi.classList.add('is-invalid');
        }
    });

    $(aracSelect).on('change', function() {
        $(this).removeClass('is-invalid');
    });
    $(personelSelect).on('change', function() {
        $(this).removeClass('is-invalid');
    });

    atamaTarihi.addEventListener('change', function() {
        this.classList.remove('is-invalid');
        this.nextElementSibling.textContent = '';
        const atamaDate = new Date(this.value);
        const now = new Date();
        if (this.value && atamaDate > now) {
            this.classList.add('is-invalid');
            this.nextElementSibling.textContent = 'Atama tarihi bugünden sonrası olamaz.';
        }
        if (gorevSonuTarihi.value) {
            const gorevSonuDate = new Date(gorevSonuTarihi.value);
            if (gorevSonuDate < atamaDate) {
                gorevSonuTarihi.classList.add('is-invalid');
                gorevSonuTarihi.nextElementSibling.textContent = 'Görev sonu tarihi, atama tarihinden önce olamaz.';
            } else {
                gorevSonuTarihi.classList.remove('is-invalid');
                gorevSonuTarihi.nextElementSibling.textContent = '';
            }
        }
    });

    gorevSonuTarihi.addEventListener('change', function() {
        this.classList.remove('is-invalid');
        this.nextElementSibling.textContent = '';
        if (this.value) {
            const gorevSonuDate = new Date(this.value);
            const now = new Date();
            const atamaDate = atamaTarihi.value ? new Date(atamaTarihi.value) : now;
            if (gorevSonuDate < now) {
                this.classList.add('is-invalid');
                this.nextElementSibling.textContent = 'Görev sonu tarihi bugünden önce olamaz.';
            } else if (gorevSonuDate < atamaDate) {
                this.classList.add('is-invalid');
                this.nextElementSibling.textContent = 'Görev sonu tarihi, atama tarihinden önce olamaz.';
            }
        }
    });
});
</script>

<?php require_once 'footer.php'; ?>