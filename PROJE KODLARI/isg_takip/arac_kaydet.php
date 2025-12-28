<?php
session_start();
require_once 'header.php';

// CSRF token kontrolü
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['hatalar'] = ["Geçersiz CSRF token."];
    header("Location: arac_duzenle.php?plaka_no=" . urlencode($_POST['eski_plaka']));
    exit;
}

// Hata raporlamasını etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kullanıcı ID'sini oturumdan al
$kul_id = isset($_SESSION['kul_id']) ? (int)$_SESSION['kul_id'] : 0;
if ($kul_id === 0) {
    $_SESSION['hatalar'] = ["Kullanıcı oturumu bulunamadı."];
    header("Location: arac_duzenle.php?plaka_no=" . urlencode($_POST['eski_plaka']));
    exit;
}

// Hata mesajları için dizi
$errors = [];


    $currentDateTimeString = date('Y-m-d\TH:i');

        // echo "Mevcut Zaman: " . $currentDateTime . "\n";

    $dateTime = new DateTime($currentDateTime);

    // 60 dakika (1 saat) eklemek için DateInterval nesnesi oluştur
    $interval = new DateInterval('PT61M'); // PT60M -> P (period) T (time) 60 M (minutes)

    // Saati ekle
    $dateTime->add($interval);

    // Yeni saati istediğiniz formatta biçimlendirin
    $newDateTime = $dateTime->format('Y-m-d\TH:i');
    $currentDateTimeString = $newDateTime;

        // // Sonucu ekrana yazdırın
        // echo "Mevcut Zaman: " . $currentDateTime . "\n";
        // echo "60 Dakika Sonraki Zaman: " . $newDateTime . "\n";
    
    $currentDateTime = new DateTime($currentDateTimeString);

    $minDateTime = '1950-01-01T00:00';

// Verileri al ve temizle
$eski_plaka = isset($_POST['eski_plaka']) ? trim(htmlspecialchars($_POST['eski_plaka'])) : '';
$plaka_no = isset($_POST['plaka_no']) ? trim(htmlspecialchars($_POST['plaka_no'])) : '';
$arac_tipi_id = isset($_POST['arac_tipi_id']) ? $_POST['arac_tipi_id'] : '';
$yeni_tip = isset($_POST['yeni_tip']) ? trim(htmlspecialchars($_POST['yeni_tip'])) : '';
$marka_id = isset($_POST['marka_id']) ? $_POST['marka_id'] : '';
$yeni_marka = isset($_POST['yeni_marka']) ? trim(htmlspecialchars($_POST['yeni_marka'])) : '';
$model_id = isset($_POST['model_id']) ? $_POST['model_id'] : '';
$yeni_model = isset($_POST['yeni_model']) ? trim(htmlspecialchars($_POST['yeni_model'])) : '';
$uretim_yili = isset($_POST['uretim_yili']) ? (int)$_POST['uretim_yili'] : 0;
$firma_id = isset($_POST['firma_id']) ? $_POST['firma_id'] : '';
$yeni_firma = isset($_POST['yeni_firma']) ? trim(htmlspecialchars($_POST['yeni_firma'])) : '';
$arac_durum_id = isset($_POST['arac_durum_id']) ? (int)$_POST['arac_durum_id'] : 0;
$muayene_tarihi = isset($_POST['muayene_tarihi']) ? trim($_POST['muayene_tarihi']) : '';
$muayene_sonucu = isset($_POST['muayene_sonucu']) ? trim($_POST['muayene_sonucu']) : '';

// Operatör atama bilgilerini al ve temizle
$tc_kimlik = isset($_POST['tc_kimlik']) ? trim(htmlspecialchars($_POST['tc_kimlik'])) : '';
$atama_tarihi = isset($_POST['atama_tarihi']) ? trim($_POST['atama_tarihi']) : '';
$gorev_sonu_tarihi = isset($_POST['gorev_sonu_tarihi']) ? trim($_POST['gorev_sonu_tarihi']) : null;



// Araç bilgisi doğrulama
if (empty($plaka_no)) {
    $errors[] = "Plaka No zorunludur.";
} else {
    // Plaka no benzersizlik kontrolü (eski plaka ile yeni plaka farklıysa)
    if ($plaka_no !== $eski_plaka) {
        $sorgu = $db->prepare("SELECT COUNT(*) FROM arac_bilgi WHERE plaka_no = ?");
        $sorgu->execute([$plaka_no]);
        if ($sorgu->fetchColumn() > 0) {
            $errors[] = "Bu plaka no zaten kayıtlı.";
        }
    }
}

if (empty($arac_tipi_id) || ($arac_tipi_id === 'diger' && empty($yeni_tip))) {
    $errors[] = "Araç Tipi zorunludur.";
}
if (empty($marka_id) || ($marka_id === 'diger' && empty($yeni_marka))) {
    $errors[] = "Marka zorunludur.";
}
if (empty($model_id) || ($model_id === 'diger' && empty($yeni_model))) {
    $errors[] = "Model zorunludur.";
}
if (empty($uretim_yili) || $uretim_yili < 1900 || $uretim_yili > date('Y')) {
    $errors[] = "Geçerli bir Üretim Yılı giriniz.";
}
if (empty($firma_id) || ($firma_id === 'diger' && empty($yeni_firma))) {
    $errors[] = "Firma zorunludur.";
}
if (empty($arac_durum_id)) {
    $errors[] = "Araç Durumu zorunludur.";
}
// Muayene bilgileri doğrulama
if (!empty($muayene_tarihi) || !empty($muayene_sonucu)) {
    if (empty($muayene_tarihi)) {
        $errors[] = "Muayene sonucu seçildiyse, muayene tarihi belirtilmelidir.";
    } elseif (empty($muayene_sonucu)) {
        $errors[] = "Muayene tarihi girildiyse, muayene sonucu (Evet/Hayır) seçilmelidir.";
    } else {
        // Muayene tarihi geçerli mi?
        $muayene_date = DateTime::createFromFormat('Y-m-d\TH:i', $muayene_tarihi);
        if (!$muayene_date || $muayene_date > $currentDateTime) {
            $errors[] = "Muayene tarihi geçerli değil veya gelecekte olamaz.";
        }
    }
}

// Operatör atama doğrulama (opsiyonel)
if (!empty($tc_kimlik) || !empty($atama_tarihi)) {
    if (empty($tc_kimlik)) {
        $errors[] = "Operatör atama için personel seçilmelidir.";
    }
    if (empty($atama_tarihi)) {
        $errors[] = "Operatör atama için atama tarihi belirtilmelidir.";
    } else {
        // Atama tarihi geçerli mi?
        $atama_date = DateTime::createFromFormat('Y-m-d\TH:i', $atama_tarihi);
        if (!$atama_date || $atama_date > $currentDateTime) {
            $errors[] = "Atama tarihi geçerli değil veya gelecekte olamaz.";
        }
    }
    // Görev sonu tarihi kontrolü
    if (!empty($gorev_sonu_tarihi)) {
        $gorev_sonu_date = DateTime::createFromFormat('Y-m-d\TH:i', $gorev_sonu_tarihi);
        if (!$gorev_sonu_date || $gorev_sonu_date < $currentDateTime) {
            $errors[] = "Görev sonu tarihi geçerli değil veya geçmişte olamaz.";
        }
    }
}

// Hata varsa kullanıcıya geri dön
if (!empty($errors)) {
    $_SESSION['hatalar'] = $errors;
    header("Location: arac_duzenle.php?plaka_no=" . urlencode($eski_plaka));
    exit;
}

try {
    $db->beginTransaction();

    // "Diğer" tablolarına ekleme yap ve ID'leri al
    if ($arac_tipi_id === 'diger') {
        $sorgu = $db->prepare("INSERT INTO hazir_arac_tipleri (arac_tipi_adi, muayene_gecerlilik_suresi_ay, kullanici_id) VALUES (?, ?, ?)");
        $sorgu->execute([$yeni_tip, 12, $kul_id]); // Varsayılan 12 ay
        $arac_tipi_id = $db->lastInsertId();
    }
    if ($marka_id === 'diger') {
        $sorgu = $db->prepare("INSERT INTO hazir_markalar (marka_adi, kullanici_id) VALUES (?, ?)");
        $sorgu->execute([$yeni_marka, $kul_id]);
        $marka_id = $db->lastInsertId();
    }
    if ($model_id === 'diger') {
        $sorgu = $db->prepare("INSERT INTO hazir_modeller (model_adi, marka_id, arac_tipi_id, kullanici_id) VALUES (?, ?, ?, ?)");
        $sorgu->execute([$yeni_model, $marka_id, $arac_tipi_id, $kul_id]);
        $model_id = $db->lastInsertId();
    }
    if ($firma_id === 'diger') {
        $sorgu = $db->prepare("INSERT INTO hazir_firmalar (firma_adi, kullanici_id) VALUES (?, ?)");
        $sorgu->execute([$yeni_firma, $kul_id]);
        $firma_id = $db->lastInsertId();
    }

    // Muayene sonucuna göre araç durumunu güncelle
    $aktif_durum = $db->query("SELECT arac_durum_id FROM hazir_arac_durumlari WHERE arac_durum_adi = 'Aktif'")->fetchColumn();
    $arizali_durum = $db->query("SELECT arac_durum_id FROM hazir_arac_durumlari WHERE arac_durum_adi = 'Arızalı'")->fetchColumn();
    if ($muayene_sonucu === 'Evet' && $aktif_durum) {
        $arac_durum_id = $aktif_durum;
    } elseif ($muayene_sonucu === 'Hayır' && $arizali_durum) {
        $arac_durum_id = $arizali_durum;
    }

    // Araç bilgilerini güncelle
    $sorgu = $db->prepare("
        UPDATE arac_bilgi 
        SET plaka_no = ?, arac_tipi_id = ?, marka_id = ?, model_id = ?, uretim_yili = ?, firma_id = ?, arac_durum_id = ?, kullanici_id = ?
        WHERE plaka_no = ?
    ");
    $sorgu->execute([$plaka_no, $arac_tipi_id, $marka_id, $model_id, $uretim_yili, $firma_id, $arac_durum_id, $kul_id, $eski_plaka]);

    // Araç ID'sini al
    $sorgu = $db->prepare("SELECT arac_id FROM arac_bilgi WHERE plaka_no = ?");
    $sorgu->execute([$plaka_no]);
    $arac_id = $sorgu->fetchColumn();
    if (!$arac_id) {
        throw new PDOException("Araç ID'si alınamadı.");
    }

    // Muayene bilgileri varsa güncelle veya ekle
    if (!empty($muayene_tarihi) && !empty($muayene_sonucu)) {
        $gecerlilik_suresi_ay = $db->prepare("SELECT muayene_gecerlilik_suresi_ay FROM hazir_arac_tipleri WHERE arac_tipi_id = ?");
        $gecerlilik_suresi_ay->execute([$arac_tipi_id]);
        $gecerlilik_suresi = $gecerlilik_suresi_ay->fetchColumn() ?: 12; // Varsayılan 12 ay

        // Muayene geçerlilik tarihini sadece "Evet" için hesapla
        $gecerlilik_tarihi = ($muayene_sonucu === 'Evet') 
            ? (new DateTime($muayene_tarihi))->modify("+$gecerlilik_suresi months")->format('Y-m-d H:i:s') 
            : null;

        // muayeneden_gecti_mi için TINYINT dönüşümü
        $muayeneden_gecti_mi = ($muayene_sonucu === 'Evet') ? 1 : 0;

        // Mevcut muayene kaydını kontrol et
        $sorgu = $db->prepare("SELECT muayene_id FROM arac_muayene WHERE arac_id = ?");
        $sorgu->execute([$arac_id]);
        $existing_muayene = $sorgu->fetchColumn();

        if ($existing_muayene) {
            // Mevcut kaydı güncelle
            $sorgu = $db->prepare("
                UPDATE arac_muayene 
                SET muayene_tarihi = ?, muayeneden_gecti_mi = ?, muayene_gecerlilik_tarihi = ?, kullanici_id = ?
                WHERE muayene_id = ?
            ");
            $sorgu->execute([$muayene_tarihi, $muayeneden_gecti_mi, $gecerlilik_tarihi, $kul_id, $existing_muayene]);
        } else {
            // Yeni kayıt ekle
            $sorgu = $db->prepare("
                INSERT INTO arac_muayene (arac_id, muayene_tarihi, muayeneden_gecti_mi, muayene_gecerlilik_tarihi, kullanici_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            $sorgu->execute([$arac_id, $muayene_tarihi, $muayeneden_gecti_mi, $gecerlilik_tarihi, $kul_id]);
        }
    }

    // Operatör atama işlemleri
    if (!empty($tc_kimlik) && !empty($atama_tarihi)) {
        // Mevcut aktif atama kaydını kontrol et
        $sorgu = $db->prepare("
            SELECT id FROM arac_operator_atama 
            WHERE arac_id = ? AND (gorev_sonu_tarihi IS NULL OR gorev_sonu_tarihi >= NOW())
        ");
        $sorgu->execute([$arac_id]);
        $existing_atama = $sorgu->fetchColumn();

        if ($existing_atama) {
            // Mevcut atamayı güncelle
            $sorgu = $db->prepare("
                UPDATE arac_operator_atama 
                SET tc_kimlik = ?, atama_tarihi = ?, gorev_sonu_tarihi = ?, kullanici_id = ?, veri_giris_tarihi = NOW()
                WHERE id = ?
            ");
            $sorgu->execute([$tc_kimlik, $atama_tarihi, $gorev_sonu_tarihi, $kul_id, $existing_atama]);
        } else {
            // Yeni atama kaydı ekle
            $sorgu = $db->prepare("
                INSERT INTO arac_operator_atama (arac_id, tc_kimlik, atama_tarihi, gorev_sonu_tarihi, kullanici_id, veri_giris_tarihi)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $sorgu->execute([$arac_id, $tc_kimlik, $atama_tarihi, $gorev_sonu_tarihi, $kul_id]);
        }
    } else {
        // Operatör atama bilgileri boşsa, mevcut aktif atamayı sonlandır
        $sorgu = $db->prepare("
            UPDATE arac_operator_atama 
            SET gorev_sonu_tarihi = NOW(), kullanici_id = ?, veri_giris_tarihi = NOW()
            WHERE arac_id = ? AND (gorev_sonu_tarihi IS NULL OR gorev_sonu_tarihi >= NOW())
        ");
        $sorgu->execute([$kul_id, $arac_id]);
    }

    $db->commit();

    // Yeni CSRF token üret
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    $_SESSION['basari'] = "Araç ve ilgili bilgiler başarıyla güncellendi.";
    header("Location: arac_duzenle.php?plaka_no=" . urlencode($plaka_no));
    exit;

} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['hatalar'] = ["Veritabanı hatası: " . $e->getMessage()];
    header("Location: arac_duzenle.php?plaka_no=" . urlencode($eski_plaka));
    exit;
}
?>