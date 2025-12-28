<?php
require_once 'header.php';

// Gelen GET parametreleri: tablo ve id
$tablo = $_GET['tablo'] ?? '';
$id = $_GET['id'] ?? '';

if (!$tablo || !$id) {
    echo "Eksik parametre.";
    exit;
}

// Güvenlik: tablo ismini izin verilenler listesinde kontrol et
$izinli_tablolar = [
    "silinen_personel_kisisel_bilgi",
    "silinen_personel_sirket_bilgi",
    "silinen_personel_saglik_tetkikleri",
    "silinen_personel_uyarilar",
    "silinen_personel_belgeler",
    "silinen_personel_ehliyetler",
    "silinen_personel_is_kazalari",
    "silinen_personel_gerekli_belge",
    "silinen_arac_bilgi",
    "silinen_arac_muayene",
    "silinen_arac_operator_atama",
    "silinen_aracli_kazalar",
    "silinen_hazir_arac_durumlari",
    "silinen_hazir_arac_tipleri",
    "silinen_hazir_belgeler",
    "silinen_hazir_ehliyetler",
    "silinen_hazir_firmalar",
    "silinen_hazir_is_kazalari",
    "silinen_hazir_markalar",
    "silinen_hazir_meslekler",
    "silinen_hazir_modeller",
    "silinen_hazir_uyarilar",
    "silinen_hazir_yaralanma_durumlar",
    "silinen_hazir_yaralanma_tipler"
];

if (!in_array($tablo, $izinli_tablolar)) {
    echo "Geçersiz tablo adı.";
    exit;
}

// Kayıt çek
try {
    $sql = "SELECT * FROM $tablo WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    $kayit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kayit) {
        echo "Kayıt bulunamadı.";
        exit;
    }
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
    exit;
}

// 0/1 alanlarını evet/hayır çevirme fonksiyonu
function evetHayir($deger) {
    if ($deger === null) return '';
    return ($deger == 1) ? 'Evet' : 'Hayır';
}

// Kullanıcı adı getirme fonksiyonu
function kullaniciAdiGetir($db, $kul_id) {
    if (!$kul_id) return 'Bilinmiyor';
    $stmt = $db->prepare("SELECT kul_isim FROM kullanicilar WHERE kul_id = :kul_id LIMIT 1");
    $stmt->execute(['kul_id' => $kul_id]);
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);
    return $kullanici ? $kullanici['kul_isim'] : 'Bilinmiyor';
}

?>

<div class="container mt-4">
    <h3><?= htmlspecialchars($tablo) ?> Detayları</h3>
    <table class="table table-bordered">
        <tbody>
            <?php foreach ($kayit as $alan => $deger): ?>
                <tr>
                    <th><?= htmlspecialchars($alan) ?></th>
                    <td>
                        <?php 
                        // 0/1 alanları için evet/hayır yazdır
                        if (in_array($alan, ['ise_giris_egitimi_var_mi', 'operatorluk_belgesi_var_mi', 'mesleki_yeterlilik_belgesi_var_mi', 'saglik_tetkikleri_oldu_mu'])) {
                            echo evetHayir($deger);
                        }
                        // Kullanıcı id alanları ise kullanıcı adı getir
                        elseif (in_array($alan, ['olusturan_kullanici_id', 'silen_kullanici_id'])) {
                            echo htmlspecialchars(kullaniciAdiGetir($db, $deger));
                        }
                        else {
                            echo nl2br(htmlspecialchars($deger));
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="silinen_veriler.php" class="btn btn-secondary">Geri</a>
</div>

<?php require_once 'footer.php'; ?>
