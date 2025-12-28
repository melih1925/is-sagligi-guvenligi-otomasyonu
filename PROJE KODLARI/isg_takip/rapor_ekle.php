<?php
session_start();
require_once 'config.php'; // Veritabanı bağlantısı için
require_once 'header.php';

// Hata raporlamasını etkinleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Oturum kontrolü
if (!isset($_SESSION['kullanici_id']) && !isset($_SESSION['kul_id']) && !isset($_SESSION['user_id']) && !isset($_SESSION['id'])) {
    $_SESSION['errors'] = ["Oturum bulunamadı. Lütfen tekrar oturum açın."];
    error_log("Oturum hatası: Hiçbir kullanıcı ID anahtarı tanımlı değil. SESSION: " . print_r($_SESSION, true));
    header("Location: giris.php");
    exit;
}

// Kullanıcı ID'sini al
$kullanici_id = null;
if (isset($_SESSION['kullanici_id'])) {
    $kullanici_id = (int)$_SESSION['kullanici_id'];
} elseif (isset($_SESSION['kul_id'])) {
    $kullanici_id = (int)$_SESSION['kul_id'];
} elseif (isset($_SESSION['user_id'])) {
    $kullanici_id = (int)$_SESSION['user_id'];
} elseif (isset($_SESSION['id'])) {
    $kullanici_id = (int)$_SESSION['id'];
}

if ($kullanici_id <= 0) {
    $_SESSION['errors'] = ["Geçersiz kullanıcı ID'si. Lütfen tekrar oturum açın."];
    error_log("Geçersiz kullanıcı ID'si: $kullanici_id");
    header("Location: giris.php");
    exit;
}

// Kullanıcı ID'sinin veritabanında geçerli olduğunu kontrol et
try {
    $sorgu = $db->prepare("SELECT COUNT(*) FROM kullanicilar WHERE kul_id = ?");
    $sorgu->execute([$kullanici_id]);
    if ($sorgu->fetchColumn() == 0) {
        $_SESSION['errors'] = ["Veritabanında kullanıcı bulunamadı. Lütfen tekrar oturum açın."];
        error_log("Veritabanında kullanıcı bulunamadı: kul_id = $kullanici_id");
        header("Location: giris.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Veritabanı hatası (kullanıcı kontrolü): " . $e->getMessage()];
    error_log("Veritabanı hatası (kullanıcı kontrolü): " . $e->getMessage());
    header("Location: giris.php");
    exit;
}

// CSRF token oluştur
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Mevcut tarih ve saat için datetime-local kısıtlamaları
$currentDateTime = (new DateTime())->modify('+60 minutes')->format('Y-m-d\TH:i');
$minDateTime = '1950-01-01T00:00';

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['errors'] = ["Geçersiz CSRF token."];
    } else {
        $baslik = trim($_POST['baslik'] ?? '');
        $icerik = trim($_POST['icerik'] ?? '');
        $durum_id = $_POST['durum_id'] ?? '';
        $rapor_tarihi = trim($_POST['rapor_tarihi'] ?? '');

        // Doğrulama
        $errors = [];
        if (empty($baslik)) {
            $errors[] = "Rapor başlığı zorunludur.";
        }
        if (empty($icerik)) {
            $errors[] = "Rapor içeriği zorunludur.";
        }
        if (empty($durum_id)) {
            $errors[] = "Rapor durumu seçilmelidir.";
        }
        if (empty($rapor_tarihi)) {
            $errors[] = "Rapor tarihi zorunludur.";
        } else {
            $raporDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $rapor_tarihi);
            $now = (new DateTime())->modify('+61 minutes');
            if (!$raporDateTime) {
                $errors[] = "Geçersiz rapor tarihi formatı.";
            } elseif ($raporDateTime > $now) {
                $errors[] = "Rapor tarihi bugünden sonrası olamaz.";
            }
        }

        // Durum kontrolü
        if ($durum_id) {
            try {
                $sorgu = $db->prepare("SELECT COUNT(*) FROM hazir_rapor_durumlari WHERE id = ?");
                $sorgu->execute([$durum_id]);
                if ($sorgu->fetchColumn() == 0) {
                    $errors[] = "Seçilen rapor durumu geçersiz.";
                }
            } catch (PDOException $e) {
                $errors[] = "Veritabanı hatası (durum kontrolü): " . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        } else {
            // Rapor ekleme
            try {
                $stmt = $db->prepare("
                    INSERT INTO raporlar (rapor_basligi, rapor_icerigi, rapor_durumu, rapor_tarihi, kullanici_id, veri_giris_tarihi)
                    VALUES (?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$baslik, $icerik, $durum_id, $raporDateTime->format('Y-m-d H:i:s'), $kullanici_id]);

                $_SESSION['success'] = "Rapor başarıyla eklendi!";
                header("Location: rapor_ekle.php");
                exit;
            } catch (PDOException $e) {
                $_SESSION['errors'] = ["Veritabanı hatası (ekleme): " . $e->getMessage()];
                error_log("Veritabanı hatası (rapor ekleme): " . $e->getMessage());
            }
        }
    }
}

// Rapor durumlarını çek
try {
    $sorgu = $db->prepare("SELECT id, rapor_durum_adi FROM hazir_rapor_durumlari ORDER BY rapor_durum_adi ASC");
    $sorgu->execute();
    $durumlar = $sorgu->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Veritabanı hatası (durumlar): " . $e->getMessage()];
    error_log("Veritabanı hatası (durumlar): " . $e->getMessage());
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<div class="container-fluid mt-4">

    <!-- Hata ve başarı mesajları -->
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success']); ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Form Section -->
    <div class="mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Rapor Ekle</h4>
            </div>
            <div class="card-body">
                <form method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <!-- Rapor Başlığı -->
                    <div class="form-group mb-3">
                        <label for="baslik" class="form-label">Rapor Başlığı</label>
                        <input type="text" name="baslik" id="baslik" class="form-control" 
                               value="<?php echo isset($_POST['baslik']) ? htmlspecialchars($_POST['baslik']) : ''; ?>" required>
                    </div>

                    <!-- Rapor İçeriği -->
                    <div class="form-group mb-3">
                        <label for="icerik" class="form-label">Rapor İçeriği</label>
                        <textarea name="icerik" id="icerik" class="form-control textarea-top" rows="5" required><?php echo isset($_POST['icerik']) ? htmlspecialchars(trim($_POST['icerik'])) : ''; ?></textarea>
                    </div>

                    <!-- Rapor Durumu -->
                    <div class="form-group mb-3">
                        <label for="durum_id" class="form-label">Rapor Durumu</label>
                        <select name="durum_id" id="durum_id" class="form-control select2" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($durumlar as $durum): ?>
                                <option value="<?php echo $durum['id']; ?>" 
                                        <?php echo (isset($_POST['durum_id']) && $_POST['durum_id'] == $durum['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($durum['rapor_durum_adi']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Rapor Tarihi -->
                    <div class="form-group mb-3">
                        <label for="rapor_tarihi" class="form-label">Rapor Tarihi</label>
                        <input type="datetime-local" name="rapor_tarihi" id="rapor_tarihi" class="form-control"
                               min="<?php echo $minDateTime; ?>" max="<?php echo $currentDateTime; ?>" value="<?php echo $currentDateTime; ?>"
                                required>
                        <div class="invalid-feedback">Geçerli bir rapor tarihi giriniz (gelecekte olamaz).</div>
                    </div>

                    <button type="submit" class="btn btn-primary">Rapor Ekle</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Mevcut Raporlar</h4>
            </div>
            <div class="card-body">
                <button id="excel-indir" class="btn btn-primary mb-3">Tabloyu Excel Olarak İndir</button>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered text-center align-middle" id="tablo">
                        <thead class="table-dark">
                            <tr>
                                <th class="priority-1">Başlık</th>
                                <th class="priority-2">Durum</th>
                                <th class="priority-3">Rapor Tarihi</th>
                                <th class="priority-4">Oluşturan Kullanıcı</th>
                                <th class="priority-1">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $sorgu = $db->prepare("
                                    SELECT r.id, r.rapor_basligi, hrd.rapor_durum_adi, r.rapor_tarihi, k.kul_isim
                                    FROM raporlar r
                                    INNER JOIN hazir_rapor_durumlari hrd ON r.rapor_durumu = hrd.id
                                    INNER JOIN kullanicilar k ON r.kullanici_id = k.kul_id
                                    ORDER BY r.rapor_tarihi DESC
                                ");
                                $sorgu->execute();
                                $raporlar = $sorgu->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($raporlar as $rapor): 
                                    // Durum bazlı arka plan sınıfı belirle
                                    $row_class = '';
                                    if ($rapor['rapor_durum_adi'] === 'Sorun çözüldü') {
                                        $row_class = 'bg-success';
                                    } elseif ($rapor['rapor_durum_adi'] === 'Sorun devam ediyor') {
                                        $row_class = 'bg-danger';
                                    } else {
                                        $row_class = 'bg-white';
                                    }
                                ?>
                                    <tr class="<?php echo htmlspecialchars($row_class); ?>">
                                        <td class="priority-1 text-truncate" title="<?php echo htmlspecialchars($rapor['rapor_basligi']); ?>">
                                            <?php echo htmlspecialchars($rapor['rapor_basligi']); ?>
                                        </td>
                                        <td class="priority-2 text-truncate" title="<?php echo htmlspecialchars($rapor['rapor_durum_adi']); ?>">
                                            <?php echo htmlspecialchars($rapor['rapor_durum_adi']); ?>
                                        </td>
                                        <td class="priority-3 text-truncate" title="<?php echo htmlspecialchars($rapor['rapor_tarihi']); ?>">
                                            <?php echo date('d.m.Y H:i', strtotime($rapor['rapor_tarihi'])); ?>
                                        </td>
                                        <td class="priority-4 text-truncate" title="<?php echo htmlspecialchars($rapor['kul_isim']); ?>">
                                            <?php echo htmlspecialchars($rapor['kul_isim']); ?>
                                        </td>
                                        <td class="priority-1">
                                            <div class="btn-group btn-group-sm d-flex justify-content-center gap-1">
                                                <a href="rapor_detay.php?id=<?php echo urlencode($rapor['id']); ?>" class="btn btn-info btn-icon" title="Detay"><i class="fas fa-search"></i></a>
                                                <a href="rapor_duzenle.php?id=<?php echo urlencode($rapor['id']); ?>" class="btn btn-warning btn-icon" title="Düzenle"><i class="fas fa-edit"></i></a>
                                                <form action="rapor_sil.php" method="POST" style="display: inline;" onsubmit="return confirm('Bu raporu silmek istediğinize emin misiniz?');">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($rapor['id']); ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                    <button type="submit" class="btn btn-danger btn-icon" title="Sil"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='5' class='text-danger'>Veritabanı hatası: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stil Dosyası -->
<style>
    /* Tablo stilleri */
    .table {
        font-size: 0.9rem;
        width: 100%;
        margin-bottom: 0;
    }

    .table th, .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        transition: all 0.2s ease;
    }

    /* Uzun metinleri kırpma */
    .text-truncate {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Buton stilleri */
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        line-height: 1.5;
        min-width: 44px;
        transition: background-color 0.2s ease;
    }

    .btn-icon i {
        font-size: 0.9rem;
    }

    /* Öncelik bazlı sütun görünürlüğü */
    .priority-1 { display: table-cell !important; }
    .priority-2, .priority-3, .priority-4 { display: table-cell; }

    /* Durum bazlı arka plan renkleri */
    .bg-success {
        background-color: #d4edda !important; /* Yeşil (Sorun çözüldü) */
    }
    .bg-danger {
        background-color: #f8d7da !important; /* Kırmızı (Sorun devam ediyor) */
    }
    .bg-white {
        background-color: #ffffff !important; /* Beyaz (Diğer durumlar) */
    }

    /* Büyük ekranlar (≥1200px) */
    @media (min-width: 1200px) {
        .table { font-size: 1rem; }
        .text-truncate { max-width: 200px; }
    }

    /* Büyük ekranlar (≥992px, <1200px) */
    @media (max-width: 1199.98px) {
        .priority-4 { display: none; } /* Kullanıcı Adı gizle */
    }

    /* Tabletler (≥768px, <992px) */
    @media (max-width: 991.98px) {
        .priority-3 { display: none; } /* Rapor Tarihi gizle */
        .text-truncate { max-width: 120px; }
        .table { font-size: 0.85rem; }
    }

    /* Küçük tabletler/mobil (≥576px, <768px) */
    @media (max-width: 767.98px) {
        .priority-2 { display: none; } /* Durum gizle */
        .table th, .table td { padding: 0.5rem 0.3rem; }
        .btn-group-sm .btn { font-size: 0.75rem; }
    }

    /* Mobil (<576px) */
    @media (max-width: 575.98px) {
        .table { font-size: 0.8rem; }
        .text-truncate { max-width: 80px; }
        .btn-group-sm .btn { padding: 0.2rem 0.4rem; }
    }

    /* Form stilleri */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        font-size: 1.0rem;
        padding: 0.5rem 0.75rem;
    }

    .textarea-top {
        vertical-align: top;
        line-height: 1.5;
        padding: 0.5rem 0.75rem;
        resize: vertical;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
    }

    .is-invalid ~ .invalid-feedback {
        display: block;
    }

    /* Mobil form düzenlemeleri */
    @media (max-width: 575.98px) {
        .form-label { font-size: 0.85rem; }
        .form-control { font-size: 0.85rem; padding: 0.4rem 0.6rem; }
        .card-body { padding: 1rem; }
        .btn { font-size: 0.85rem; padding: 0.4rem 0.8rem; }
        .textarea-top { font-size: 0.85rem; padding: 0.4rem 0.6rem; }
    }

    /* Kart boşlukları */
    .card {
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
    }

    .card-header {
        padding: 1rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Hover efektleri */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>

<?php require_once 'footer.php'; ?>

<!-- Bağımlılıklar -->
<link href="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.css" rel="stylesheet" integrity="sha384-fTqd416qRc9kwY299KdgUPsjOvS5bwkeE6jlibx2m7eL3xKheqGyU48QCFgZAyN4" crossorigin="anonymous">
<link href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.js" integrity="sha384-uAn6fsp1rIJ6afAYV0S5it5ao101zH2fViB74y5tPG8LR2FTMg+HXIWRNxvZrniN" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    // Select2'yi başlat
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Seçiniz',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Sonuç bulunamadı"; },
            searching: function() { return "Aranıyor..."; }
        }
    });

    // DataTable başlat
    $('#tablo').DataTable({
        responsive: true,
            autoWidth: false,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
            language: dildosyasi,
        columnDefs: [
            { responsivePriority: 1, targets: [0, 4] }, // Başlık, İşlemler
            { responsivePriority: 2, targets: [1] },    // Durum
            { responsivePriority: 3, targets: [2] },    // Rapor Tarihi
            { responsivePriority: 4, targets: [3] }     // Kullanıcı Adı
        ]
    });

    // Textarea yüklendiğinde veya odaklandığında en üste kaydır
    $('#icerik').each(function () {
        this.scrollTop = 0; // Kaydırmayı en üste ayarla
        this.setSelectionRange(0, 0); // İmleci başa al
    }).on('focus', function () {
        this.scrollTop = 0; // Odaklandığında en üste kaydır
        this.setSelectionRange(0, 0); // İmleci başa al
    });

    // Rapor tarihi inputu için doğrulama
    const raporTarihi = $('#rapor_tarihi');
    const form = $('form');

    // Tarih değiştiğinde kontrol
    raporTarihi.on('change', function () {
        const selectedDate = new Date(this.value);
        const now = new Date(Date.now() + 61 * 60 * 1000); // 61 dakika ileri
        if (this.value && selectedDate > now) {
            this.classList.add('is-invalid');
            $(this).next('.invalid-feedback').text('Rapor tarihi bugünden sonrası olamaz.');
        } else if (!this.value) {
            this.classList.add('is-invalid');
            $(this).next('.invalid-feedback').text('Rapor tarihi zorunludur.');
        } else {
            this.classList.remove('is-invalid');
            $(this).next('.invalid-feedback').text('');
        }
    });

    // Form gönderiminde kontrol
    form.on('submit', function (e) {
        const selectedDate = new Date(raporTarihi.val());
        const now = new Date(Date.now() + 61 * 60 * 1000); // 61 dakika ileri
        if (!raporTarihi.val()) {
            e.preventDefault();
            raporTarihi.addClass('is-invalid');
            raporTarihi.next('.invalid-feedback').text('Rapor tarihi zorunludur.');
        } else if (selectedDate > now) {
            e.preventDefault();
            raporTarihi.addClass('is-invalid');
            raporTarihi.next('.invalid-feedback').text('Rapor tarihi bugünden sonrası olamaz.');
        }
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
        XLSX.utils.book_append_sheet(wb, ws, 'Rapor Listesi');
        
        // Excel dosyasını indir
        XLSX.writeFile(wb, 'rapor_listesi.xlsx');
    });
</script>

