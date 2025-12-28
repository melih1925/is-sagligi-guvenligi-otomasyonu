<?php
session_start();
require_once 'header.php';

// Hata raporlamasını etkinleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Oturum kontrolü ve hata ayıklama
if (!isset($_SESSION['kullanici_id']) && !isset($_SESSION['kul_id']) && !isset($_SESSION['user_id']) && !isset($_SESSION['id'])) {
    // Oturum verisi yoksa, hata mesajı ve yönlendirme
    $_SESSION['errors'] = ["Oturum bulunamadı. Lütfen tekrar oturum açın."];
    // Hata ayıklama için oturum verilerini logla
    error_log("Oturum hatası: Hiçbir kullanıcı ID anahtarı tanımlı değil. SESSION: " . print_r($_SESSION, true));
    header("Location: giris.php");
    exit;
}

// Kullanıcı ID'sini al (farklı anahtarları kontrol et)
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
        $duyuru_tarihi = trim($_POST['duyuru_tarihi'] ?? '');

        // Doğrulama
        $errors = [];
        if (empty($baslik)) {
            $errors[] = "Duyuru başlığı zorunludur.";
        }
        if (empty($icerik)) {
            $errors[] = "Duy GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY '';
            FLUSH PRIVILEGES;uru içeriği zorunludur.";
        }
        if (empty($duyuru_tarihi)) {
            $errors[] = "Duyuru tarihi zorunludur.";
        } else {
            $duyuruDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $duyuru_tarihi);
            $now = (new DateTime())->modify('+61 minutes');
            if (!$duyuruDateTime) {
                $errors[] = "Geçersiz duyuru tarihi formatı.";
            } elseif ($duyuruDateTime > $now) {
                $errors[] = "Duyuru tarihi bugünden sonrası olamaz.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        } else {
            // Duyuru ekleme
            try {
                $stmt = $db->prepare("
                    INSERT INTO duyurular (duyuru_basligi, duyuru_icerigi, duyuru_tarihi, kullanici_id, veri_giris_tarihi)
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$baslik, $icerik, $duyuruDateTime->format('Y-m-d H:i:s'), $kullanici_id]);

                $_SESSION['success'] = "Duyuru başarıyla eklendi!";
                header("Location: duyuru_ekle.php");
                exit;
            } catch (PDOException $e) {
                $_SESSION['errors'] = ["Veritabanı hatası (ekleme): " . $e->getMessage()];
                error_log("Veritabanı hatası (duyuru ekleme): " . $e->getMessage());
            }
        }
    }
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
                <h4 class="mb-0">Duyuru Ekle</h4>
            </div>
            <div class="card-body">
                <form method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <!-- Duyuru Başlığı -->
                    <div class="form-group mb-3">
                        <label for="baslik" class="form-label">Duyuru Başlığı</label>
                        <input type="text" name="baslik" id="baslik" class="form-control" value="<?php echo isset($_POST['baslik']) ? htmlspecialchars($_POST['baslik']) : ''; ?>" required>
                    </div>

                    <!-- Duyuru İçeriği -->
                    <div class="form-group mb-3">
                        <label for="icerik" class="form-label">Duyuru İçeriği</label>
                        <textarea name="icerik" id="icerik" class="form-control" rows="5" required><?php echo isset($_POST['icerik']) ? htmlspecialchars($_POST['icerik']) : ''; ?></textarea>
                    </div>

                    <!-- Duyuru Tarihi -->
                    <div class="form-group mb-3">
                        <label for="duyuru_tarihi" class="form-label">Duyuru Tarihi</label>
                        <input type="datetime-local" name="duyuru_tarihi" id="duyuru_tarihi" class="form-control"
                               min="<?php echo $minDateTime; ?>" max="<?php echo $currentDateTime; ?>" value="<?php echo $currentDateTime; ?>"
                                required>
                        <div class="invalid-feedback">Geçerli bir duyuru tarihi giriniz (gelecekte olamaz).</div>
                    </div>

                    <button type="submit" class="btn btn-primary">Duyuru Ekle</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Mevcut Duyurular</h4>
            </div>
            <div class="card-body">
                <button id="excel-indir" class="btn btn-primary mb-3">Tabloyu Excel Olarak İndir</button>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered text-center align-middle" id="tablo">
                        <thead class="table-dark">
                            <tr>
                                <th class="priority-1">Başlık</th>
                                <th class="priority-2">Duyuru Tarihi</th>
                                <th class="priority-3">Oluşturan Kullanıcı</th>
                                <th class="priority-1">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $sorgu = $db->prepare("
                                    SELECT d.id, d.duyuru_basligi, d.duyuru_tarihi, k.kul_isim
                                    FROM duyurular d
                                    INNER JOIN kullanicilar k ON d.kullanici_id = k.kul_id
                                    ORDER BY d.duyuru_tarihi DESC
                                ");
                                $sorgu->execute();
                                $duyurular = $sorgu->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($duyurular as $duyuru): ?>
                                    <tr>
                                        <td class="priority-1 text-truncate" title="<?php echo htmlspecialchars($duyuru['duyuru_basligi']); ?>">
                                            <?php echo htmlspecialchars($duyuru['duyuru_basligi']); ?>
                                        </td>
                                        <td class="priority-2 text-truncate" title="<?php echo htmlspecialchars($duyuru['duyuru_tarihi']); ?>">
                                            <?php echo date('d.m.Y H:i', strtotime($duyuru['duyuru_tarihi'])); ?>
                                        </td>
                                        <td class="priority-3 text-truncate" title="<?php echo htmlspecialchars($duyuru['kul_isim']); ?>">
                                            <?php echo htmlspecialchars($duyuru['kul_isim']); ?>
                                        </td>
                                        <td class="priority-1">
                                            <div class="btn-group btn-group-sm d-flex justify-content-center gap-1">
                                                <a href="duyuru_detay.php?id=<?php echo urlencode($duyuru['id']); ?>" class="btn btn-info btn-icon" title="Detay"><i class="fas fa-search"></i></a>
                                                <a href="duyuru_duzenle.php?id=<?php echo urlencode($duyuru['id']); ?>" class="btn btn-warning btn-icon" title="Düzenle"><i class="fas fa-edit"></i></a>
                                                <form action="duyuru_sil.php" method="POST" style="display: inline;" onsubmit="return confirm('Bu duyuruyu silmek istediğinize emin misiniz?');">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($duyuru['id']); ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                    <button type="submit" class="btn btn-danger btn-icon" title="Sil"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='4' class='text-danger'>Veritabanı hatası: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
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
    .priority-2, .priority-3 { display: table-cell; }

    /* Büyük ekranlar (≥1200px) */
    @media (min-width: 1200px) {
        .table { font-size: 1rem; }
        .text-truncate { max-width: 200px; }
    }

    /* Büyük ekranlar (≥992px, <1200px) */
    @media (max-width: 1199.98px) {
        .priority-3 { display: none; } /* Kullanıcı Adı gizle */
    }

    /* Tabletler (≥768px, <992px) */
    @media (max-width: 991.98px) {
        .priority-2 { display: none; } /* Duyuru Tarihi gizle */
        .text-truncate { max-width: 120px; }
        .table { font-size: 0.85rem; }
    }

    /* Küçük tabletler/mobil (≥576px, <768px) */
    @media (max-width: 767.98px) {
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
    // DataTable başlat
    $('#tablo').DataTable({
        responsive: true,
            autoWidth: false,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
            language: dildosyasi,
        columnDefs: [
            { responsivePriority: 1, targets: [0, 3] }, // Başlık, İşlemler
            { responsivePriority: 2, targets: [1] },    // Duyuru Tarihi
            { responsivePriority: 3, targets: [2] }     // Kullanıcı Adı
        ]
    });

    // Duyuru tarihi inputu için doğrulama
    const duyuruTarihi = $('#duyuru_tarihi');
    const form = $('form');

    // Tarih değiştiğinde kontrol
    duyuruTarihi.on('change', function () {
        const selectedDate = new Date(this.value);
        const now = new Date();
        if (this.value && selectedDate > now) {
            this.classList.add('is-invalid');
            $(this).next('.invalid-feedback').text('Duyuru tarihi bugünden sonrası olamaz.');
        } else if (!this.value) {
            this.classList.add('is-invalid');
            $(this).next('.invalid-feedback').text('Duyuru tarihi zorunludur.');
        } else {
            this.classList.remove('is-invalid');
            $(this).next('.invalid-feedback').text('');
        }
    });

    // Form gönderiminde kontrol
    form.on('submit', function (e) {
        const selectedDate = new Date(duyuruTarihi.val());
        const now = new Date();
        if (!duyuruTarihi.val()) {
            e.preventDefault();
            duyuruTarihi.addClass('is-invalid');
            duyuruTarihi.next('.invalid-feedback').text('Duyuru tarihi zorunludur.');
        } else if (selectedDate > now) {
            e.preventDefault();
            duyuruTarihi.addClass('is-invalid');
            duyuruTarihi.next('.invalid-feedback').text('Duyuru tarihi bugünden sonrası olamaz.');
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
        XLSX.utils.book_append_sheet(wb, ws, 'Duyuru Listesi');
        
        // Excel dosyasını indir
        XLSX.writeFile(wb, 'duyuru_listesi.xlsx');
    });
</script>

<?php require_once 'footer.php'; ?>
