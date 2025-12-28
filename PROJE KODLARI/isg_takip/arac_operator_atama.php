<?php
require_once 'header.php';


    $currentDateTime = (new DateTime())->modify('+61 minutes')->format('Y-m-d\TH:i');

    $minDateTime = '1970-01-01T00:00';

    // 2 yıl sonrası (max değeri)
    $maxDateTime = (new DateTime())->modify('+2 years')->format('Y-m-d\TH:i');



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plaka_no = $_POST['plaka_no'] ?? '';
    $tc_kimlik = $_POST['tc_kimlik'] ?? '';
    $atama_tarihi = $_POST['atama_tarihi'] ?? '';
    $gorev_sonu_tarihi = $_POST['gorev_sonu_tarihi'] ?? null;
    $kullanici_id = 1; // Assuming logged-in user ID; adjust based on your auth system

    if ($plaka_no && $tc_kimlik && $atama_tarihi) {
        try {
            // Get arac_id from plaka_no
            $sorgu = $db->prepare("SELECT arac_id FROM arac_bilgi WHERE plaka_no = ?");
            $sorgu->execute([$plaka_no]);
            $arac = $sorgu->fetch(PDO::FETCH_ASSOC);
            
            if ($arac) {
                $arac_id = $arac['arac_id'];
                $stmt = $db->prepare("
                    INSERT INTO arac_operator_atama (arac_id, tc_kimlik, atama_tarihi, gorev_sonu_tarihi, kullanici_id, veri_giris_tarihi)
                    VALUES (?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$arac_id, $tc_kimlik, $atama_tarihi, $gorev_sonu_tarihi, $kullanici_id]);
                echo '<div class="alert alert-success">Operatör ataması başarıyla eklendi!</div>';
            } else {
                echo '<div class="alert alert-danger">Geçersiz plaka numarası!</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Hata: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        echo '<div class="alert alert-warning">Lütfen tüm zorunlu alanları doldurun!</div>';
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
                <h4 class="mb-0">Araç Şoför Atama</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <!-- Araç Plaka -->
                    <div class="form-group mb-3">
                        <label for="plaka_no" class="form-label">Araç Plaka No</label>
                        <select name="plaka_no" id="plaka_no" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <?php
                            $sorgu = $db->prepare("SELECT plaka_no FROM arac_bilgi ORDER BY plaka_no ASC");
                            $sorgu->execute();
                            $plakalar = $sorgu->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($plakalar as $plaka) {
                                echo '<option value="' . htmlspecialchars($plaka['plaka_no']) . '">' . htmlspecialchars($plaka['plaka_no']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Personel -->
                    <div class="form-group mb-3">
                        <label for="tc_kimlik" class="form-label">Personel</label>
                        <select name="tc_kimlik" id="tc_kimlik" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <?php
                            $sorgu = $db->prepare("SELECT tc_kimlik, ad_soyad FROM personel_kisisel_bilgi ORDER BY ad_soyad ASC");
                            $sorgu->execute();
                            $personeller = $sorgu->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($personeller as $personel) {
                                echo '<option value="' . htmlspecialchars($personel['tc_kimlik']) . '">' . htmlspecialchars($personel['ad_soyad']) . ' (' . htmlspecialchars($personel['tc_kimlik']) . ')</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Atama Tarihi -->
                    <div class="form-group mb-3">
                        <label for="atama_tarihi" class="form-label">Atama Tarihi</label>
                        <input type="datetime-local" name="atama_tarihi" id="atama_tarihi" class="form-control"
                               min="<?php echo $minDateTime; ?>" max="<?php echo $currentDateTime; ?>" required>
                    </div>

                    <!-- Görev Sonu Tarihi (Opsiyonel) -->
                    <div class="form-group mb-3">
                        <label for="gorev_sonu_tarihi" class="form-label">Görev Sonu Tarihi (Opsiyonel)</label>
                        <input type="datetime-local" name="gorev_sonu_tarihi" id="gorev_sonu_tarihi" class="form-control"
                               min="<?php echo $currentDateTime; ?>" max="<?php echo $maxDateTime; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Atama Yap</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Mevcut Atamalar</h4>
            </div>
            <div class="card-body">
                <button id="excel-indir" class="btn btn-primary mb-3">Tabloyu Excel Olarak İndir</button>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered text-center align-middle" id="tablo">
                        <thead class="table-dark">
                            <tr>
                                <th class="priority-1">Plaka No</th>
                                <th class="priority-2">Personel Adı</th>
                                <th class="priority-4">TC Kimlik</th>
                                <th class="priority-3">Atama Tarihi</th>
                                <th class="priority-5">Görev Sonu</th>
                                <th class="priority-1">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sorgu = $db->prepare("
                                SELECT ao.id, ab.plaka_no, pk.ad_soyad, ao.tc_kimlik, ao.atama_tarihi, ao.gorev_sonu_tarihi
                                FROM arac_operator_atama ao
                                INNER JOIN arac_bilgi ab ON ao.arac_id = ab.arac_id
                                INNER JOIN personel_kisisel_bilgi pk ON ao.tc_kimlik = pk.tc_kimlik
                                ORDER BY ao.atama_tarihi DESC
                            ");
                            $sorgu->execute();
                            $atamalar = $sorgu->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($atamalar as $atama) { ?>
                                <tr>
                                    <td class="priority-1 text-truncate" title="<?= htmlspecialchars($atama['plaka_no']) ?>"><?= htmlspecialchars($atama['plaka_no']) ?></td>
                                    <td class="priority-2 text-truncate" title="<?= htmlspecialchars($atama['ad_soyad']) ?>"><?= htmlspecialchars($atama['ad_soyad']) ?></td>
                                    <td class="priority-4 text-truncate" title="<?= htmlspecialchars($atama['tc_kimlik']) ?>"><?= htmlspecialchars($atama['tc_kimlik']) ?></td>
                                    <td class="priority-3 text-truncate" title="<?= htmlspecialchars($atama['atama_tarihi']) ?>"><?= htmlspecialchars($atama['atama_tarihi']) ?></td>
                                    <td class="priority-5 text-truncate" title="<?= $atama['gorev_sonu_tarihi'] ? htmlspecialchars($atama['gorev_sonu_tarihi']) : '-' ?>"><?= $atama['gorev_sonu_tarihi'] ? htmlspecialchars($atama['gorev_sonu_tarihi']) : '-' ?></td>
                                    <td class="priority-1">
                                        <div class="btn-group btn-group-sm d-flex justify-content-center gap-1">
                                            <a href="arac_operator_duzenle.php?id=<?= urlencode($atama['id']) ?>" class="btn btn-warning btn-icon" title="Düzenle"><i class="fas fa-edit"></i></a>
                                            <a href="arac_operator_sil.php?sil=sil&id=<?= urlencode($atama['id']) ?>" class="btn btn-danger btn-icon" title="Sil" onclick="return confirm('Bu atamayı silmek istediğinize emin misiniz?');"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Responsive Styles -->
<style>
    /* Base table styles */
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

    /* Truncate long text with ellipsis */
    .text-truncate {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Button styles */
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        line-height: 1.5;
        min-width: 44px; /* WCAG touch target */
        transition: background-color 0.2s ease;
    }

    .btn-icon i {
        font-size: 0.9rem;
    }

    /* Priority-based column visibility */
    .priority-1 { display: table-cell !important; }
    .priority-2, .priority-3, .priority-4, .priority-5 { display: table-cell; }

    /* Extra large screens (≥1200px) */
    @media (min-width: 1200px) {
        .table { font-size: 1rem; }
        .text-truncate { max-width: 200px; }
    }

    /* Large screens (≥992px, <1200px) */
    @media (max-width: 1199.98px) {
        .priority-5 { display: none; } /* Hide Görev Sonu */
    }

    /* Tablets (≥768px, <992px) */
    @media (max-width: 991.98px) {
        .priority-4 { display: none; } /* Hide TC Kimlik */
        .text-truncate { max-width: 120px; }
        .table { font-size: 0.85rem; }
    }

    /* Small tablets/mobile (≥576px, <768px) */
    @media (max-width: 767.98px) {
        .priority-3 { display: none; } /* Hide Atama Tarihi */
        .table th, .table td { padding: 0.5rem 0.3rem; }
        .btn-group-sm .btn { font-size: 0.75rem; }
    }

    /* Mobile (<576px) */
    @media (max-width: 575.98px) {
        .priority-2 { display: none; } /* Hide Personel Adı */
        .table { font-size: 0.8rem; }
        .text-truncate { max-width: 80px; }
        .btn-group-sm .btn { padding: 0.2rem 0.4rem; }
    }

    /* Form styles */
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

    /* Mobile form adjustments */
    @media (max-width: 575.98px) {
        .form-label { font-size: 0.85rem; }
        .form-control { font-size: 0.85rem; padding: 0.4rem 0.6rem; }
        .card-body { padding: 1rem; }
        .btn { font-size: 0.85rem; padding: 0.4rem 0.8rem; }
    }

    /* Card spacing */
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

    /* Hover effects */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>

<?php require_once 'footer.php'; ?>

<!-- DataTable with Responsive Extension -->
<script>
    $(document).ready(function () {
        $('#tablo').DataTable({
            responsive: true,
            autoWidth: false,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
            language: dildosyasi,
            columnDefs: [
                { responsivePriority: 1, targets: [0, 5] }, // Plaka No, İşlemler
                { responsivePriority: 2, targets: [1] },    // Personel Adı
                { responsivePriority: 3, targets: [3] },    // Atama Tarihi
                { responsivePriority: 4, targets: [2] },    // TC Kimlik
                { responsivePriority: 5, targets: [4] }     // Görev Sonu
            ]
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
        XLSX.utils.book_append_sheet(wb, ws, 'Araç Operatörleri Listesi');
        
        // Excel dosyasını indir
        XLSX.writeFile(wb, 'arac_operator_listesi.xlsx');
    });
</script>


<!-- Dependencies -->
<link href="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.css" rel="stylesheet" integrity="sha384-fTqd416qRc9kwY299KdgUPsjOvS5bwkeE6jlibx2m7eL3xKheqGyU48QCFgZAyN4" crossorigin="anonymous">
<link href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap4.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/bs4/dt-2.2.2/datatables.min.js" integrity="sha384-uAn6fsp1rIJ6afAYV0S5it5ao101zH2fViB74y5tPG8LR2FTMg+HXIWRNxvZrniN" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

