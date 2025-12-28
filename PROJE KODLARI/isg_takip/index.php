<?php
require_once 'header.php';

$kullanici_adi = $_SESSION['kul_isim'] ?? 'Sayın Yetkili';
?>
<?php
try {
    $toplam_personel = $db->query("SELECT COUNT(*) FROM personel_kisisel_bilgi")->fetchColumn();
    $arac_sayisi = $db->query("SELECT COUNT(*) FROM arac_bilgi")->fetchColumn();
    $saglik_tetkik = $db->query("SELECT COUNT(*) FROM personel_saglik_tetkikleri")->fetchColumn();
    $is_kazalari = $db->query("SELECT COUNT(*) FROM personel_is_kazalari")->fetchColumn();
    $aracli_kazalar = $db->query("SELECT COUNT(*) FROM aracli_kazalar")->fetchColumn();
    $duyurular = $db->query("SELECT COUNT(*) FROM duyurular")->fetchColumn();
    $aktif_araclar = $db->query("SELECT COUNT(*) FROM arac_bilgi WHERE arac_durum_id = 1")->fetchColumn();
    $bakimdaki_araclar = $db->query("SELECT COUNT(*) FROM arac_bilgi WHERE arac_durum_id = 2")->fetchColumn();
    $arizali_araclar = $db->query("SELECT COUNT(*) FROM arac_bilgi WHERE arac_durum_id = 3")->fetchColumn();
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>ISG Takip - Ana Sayfa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous">
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e6e9f0 0%, #eef1f5 100%);
            font-family: 'Poppins', sans-serif;
            color: #2c3e50;
        }
        .container {
            max-width: 1400px;
            padding: 20px;
        }
        .welcome-header {
            background: #ffffff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 2rem;
        }
        .welcome-header h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .welcome-header p {
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 0;
        }
        .card-counter {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-counter:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .card-counter i {
            font-size: 2rem;
            color: #fff;
            background: linear-gradient(45deg, #007bff, #00d4ff);
            padding: 12px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .card-counter .count-numbers {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2c3e50;
        }
        .card-counter .count-name {
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        .card-counter.primary i { background: linear-gradient(45deg, #007bff, #00d4ff); }
        .card-counter.success i { background: linear-gradient(45deg, #28a745, #34c759); }
        .card-counter.warning i { background: linear-gradient(45deg, #ffc107, #ffca2c); }
        .card-counter.danger i { background: linear-gradient(45deg, #dc3545, #ff4560); }
        .card-counter.info i { background: linear-gradient(45deg, #17a2b8, #1abc9c); }
        .quick-links .btn {
            border-radius: 10px;
            padding: 12px;
            font-size: 1rem;
            font-weight: 500;
            transition: color 0.3s ease, background-color 0.3s ease, transform 0.3s ease;
            border: 2px solid;
            text-align: center;
        }
        .quick-links .btn:hover {
            color: #fff;
            transform: scale(1.03);
        }
        .quick-links .btn-outline-primary:hover { background-color: #007bff; }
        .quick-links .btn-outline-success:hover { background-color: #28a745; }
        .quick-links .btn-outline-danger:hover { background-color: #dc3545; }
        .quick-links .btn-outline-warning:hover { background-color: #ffc107; color: #000; }
        .quick-links .btn-outline-info:hover { background-color: #17a2b8; }
        .quick-links .btn-outline-dark:hover { background-color: #343a40; }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            border-radius: 12px 12px 0 0;
            padding: 12px 15px;
        }
        .table {
            background: #ffffff;
            border-radius: 8px;
            margin-bottom: 0;
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 10px;
        }
        .table thead {
            background: #f8f9fa;
        }
        .table a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        .table a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .card-counter, .quick-links .btn {
                margin: 10px 0;
            }
            .welcome-header h1 {
                font-size: 1.5rem;
            }
            .welcome-header p {
                font-size: 1rem;
            }
            .table td, .table th {
                font-size: 0.85rem;
            }
        }
        @media (max-width: 576px) {
            .card-counter, .quick-links .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="welcome-header">
        <h1>👷 Hoş Geldiniz, <?= htmlspecialchars($kullanici_adi) ?>! 👷</h1>
        <p>İş Sağlığı ve Güvenliği Takip Sistemine hoş geldiniz.</p>
    </div>

    <!-- İstatistik Kartları (3’lü 2 sıra) -->
    <div class="row text-center">
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card-counter primary">
                <i class="fas fa-users"></i>
                <div class="count-numbers"><?= $toplam_personel ?></div>
                <div class="count-name">Toplam Personel</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card-counter success">
                <i class="fas fa-car"></i>
                <div class="count-numbers"><?= $arac_sayisi ?></div>
                <div class="count-name">Araç Kayıtları</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card-counter warning">
                <i class="fas fa-file-medical-alt"></i>
                <div class="count-numbers"><?= $saglik_tetkik ?></div>
                <div class="count-name">Sağlık Tetkikleri</div>
            </div>
        </div>
    </div>
    <div class="row text-center">
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card-counter danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="count-numbers"><?= $is_kazalari + $aracli_kazalar ?></div>
                <div class="count-name">Toplam İş Kazaları</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card-counter info">
                <i class="fas fa-bullhorn"></i>
                <div class="count-numbers"><?= $duyurular ?></div>
                <div class="count-name">Duyurular</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card-counter info">
                <i class="fas fa-tools"></i>
                <div class="count-numbers"><?= $arizali_araclar ?></div>
                <div class="count-name">Arızalı Araçlar</div>
            </div>
        </div>
    </div>

    <!-- Hızlı Erişim Linkleri (3’lü 2 sıra) -->
    <div class="row quick-links mt-4">
        <div class="col-md-4 col-sm-6 col-12 mb-3">
            <a href="personel_listele.php" class="btn btn-outline-primary btn-lg w-100">
                <i class="fas fa-id-card me-2"></i> Personel Listesi
            </a>
        </div>
        <div class="col-md-4 col-sm-6 col-12 mb-3">
            <a href="arac_listele.php" class="btn btn-outline-success btn-lg w-100">
                <i class="fas fa-truck me-2"></i> Araçlar
            </a>
        </div>
        <div class="col-md-4 col-sm-6 col-12 mb-3">
            <a href="iskazalari_analizi.php" class="btn btn-outline-danger btn-lg w-100">
                <i class="fas fa-exclamation-circle me-2"></i> İş Kazaları
            </a>
        </div>
    </div>
    <div class="row quick-links">
        <div class="col-md-4 col-sm-6 col-12 mb-3">
            <a href="personel_saglik.php" class="btn btn-outline-warning btn-lg w-100">
                <i class="fas fa-heartbeat me-2"></i> Sağlık Tetkikleri
            </a>
        </div>
        <div class="col-md-4 col-sm-6 col-12 mb-3">
            <a href="duyuru_ekle.php" class="btn btn-outline-info btn-lg w-100">
                <i class="fas fa-bullhorn me-2"></i> Duyurular
            </a>
        </div>
        <div class="col-md-4 col-sm-6 col-12 mb-3">
            <a href="profil.php" class="btn btn-outline-dark btn-lg w-100">
                <i class="fas fa-user-cog me-2"></i> Profil Ayarları
            </a>
        </div>
    </div>

    <!-- Duyurular Tablosu (Tam Genişlik) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i> Son Duyurular</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Başlık</th>
                                    <th>Tarih</th>
                                    <th>İçerik</th>
                                    <th>Detay</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $db->query("SELECT id, duyuru_basligi, duyuru_icerigi, duyuru_tarihi 
                                                        FROM duyurular 
                                                        ORDER BY duyuru_tarihi DESC 
                                                        LIMIT 5");
                                    if ($stmt->rowCount() === 0) {
                                        echo "<tr><td colspan='4' class='text-center'>Duyuru bulunmamaktadır.</td></tr>";
                                    } else {
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>
                                                    <td>" . htmlspecialchars($row['duyuru_basligi']) . "</td>
                                                    <td>" . date('d.m.Y', strtotime($row['duyuru_tarihi'])) . "</td>
                                                    <td>" . htmlspecialchars(substr($row['duyuru_icerigi'], 0, 50)) . "...</td>
                                                    <td><a href='duyuru_detay.php?id={$row['id']}&return=index.php' class='btn btn-sm btn-outline-primary'>Detay</a></td>
                                                  </tr>";
                                        }
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='4' class='text-center'>Hata: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php require_once 'footer.php'; ?>