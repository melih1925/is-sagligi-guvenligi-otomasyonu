<?php
session_start();
require_once 'config.php';

oturum();

// CSRF token kontrolü
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['errors'] = ["Geçersiz CSRF token."];
    $_SESSION['edit_tc_kimlik'] = $_POST['tc_kimlik']; // Oturuma kaydet
    header("Location: personel_duzenle.php"); // tc_kimlik'siz yönlendir
    exit;
}

// Hata raporlamasını etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kullanıcı ID'sini oturumdan al
$kul_id = (int)$_SESSION['kul_id'];

// Formdan gelen verileri al ve temizle
$tc_kimlik = isset($_POST['tc_kimlik']) ? trim($_POST['tc_kimlik']) : '';
$ad_soyad = isset($_POST['ad_soyad']) ? trim(htmlspecialchars($_POST['ad_soyad'])) : '';
$dogum_tarihi = isset($_POST['dogum_tarihi']) ? $_POST['dogum_tarihi'] : '';
$telefon = isset($_POST['telefon']) ? trim($_POST['telefon']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$adres = isset($_POST['adres']) ? trim(htmlspecialchars($_POST['adres'])) : '';
$pp_dosya_yolu = isset($_FILES['pp_dosya_yolu']) ? $_FILES['pp_dosya_yolu'] : null;
$firma_id = isset($_POST['firma_id']) ? $_POST['firma_id'] : '';
$other_firma = isset($_POST['other_firma']) ? trim(htmlspecialchars($_POST['other_firma'])) : '';
$meslek_id = isset($_POST['meslek_id']) ? $_POST['meslek_id'] : '';
$other_meslek = isset($_POST['other_meslek']) ? trim(htmlspecialchars($_POST['other_meslek'])) : '';
$ise_giris_tarihi = isset($_POST['ise_giris_tarihi']) ? $_POST['ise_giris_tarihi'] : '';
$ise_giris_egitimi_var_mi = isset($_POST['ise_giris_egitimi_var_mi']) ? (int)$_POST['ise_giris_egitimi_var_mi'] : 0;
$operatorluk_belgesi_var_mi = isset($_POST['operatorluk_belgesi_var_mi']) ? (int)$_POST['operatorluk_belgesi_var_mi'] : 0;
$mesleki_yeterlilik_belgesi_var_mi = isset($_POST['mesleki_yeterlilik_belgesi_var_mi']) ? (int)$_POST['mesleki_yeterlilik_belgesi_var_mi'] : 0;
$saglik_tetkikleri_oldu_mu = isset($_POST['saglik_tetkikleri_oldu_mu']) ? (int)$_POST['saglik_tetkikleri_oldu_mu'] : 0;
$saglik_tetkik_tarihi = isset($_POST['saglik_tetkik_tarihi']) && !empty($_POST['saglik_tetkik_tarihi']) ? $_POST['saglik_tetkik_tarihi'] : null;

// Dinamik alanlar
$belge_kayit_id = isset($_POST['belge_kayit_id']) && is_array($_POST['belge_kayit_id']) ? array_map('trim', array_filter($_POST['belge_kayit_id'], 'is_string')) : [];
$belge_id = isset($_POST['belge_id']) && is_array($_POST['belge_id']) ? array_map('trim', array_filter($_POST['belge_id'], 'is_string')) : [];
$other_belge = isset($_POST['other_belge']) && is_array($_POST['other_belge']) ? array_map('htmlspecialchars', array_filter($_POST['other_belge'], 'is_string')) : [];
$belge_alinma_tarihi = isset($_POST['belge_alinma_tarihi']) && is_array($_POST['belge_alinma_tarihi']) ? array_map('trim', array_filter($_POST['belge_alinma_tarihi'], 'is_string')) : [];
$ehliyet_kayit_id = isset($_POST['ehliyet_kayit_id']) && is_array($_POST['ehliyet_kayit_id']) ? array_map('trim', array_filter($_POST['ehliyet_kayit_id'], 'is_string')) : [];
$ehliyet_var_mi = isset($_POST['ehliyet_var_mi']) ? (int)$_POST['ehliyet_var_mi'] : 0;
$ehliyet_id = isset($_POST['ehliyet_id']) && is_array($_POST['ehliyet_id']) ? array_map('trim', array_filter($_POST['ehliyet_id'], 'is_string')) : [];
$other_ehliyet = isset($_POST['other_ehliyet']) && is_array($_POST['other_ehliyet']) ? array_map('htmlspecialchars', array_filter($_POST['other_ehliyet'], 'is_string')) : [];
$ehliyet_alinma_tarihi = isset($_POST['ehliyet_alinma_tarihi']) && is_array($_POST['ehliyet_alinma_tarihi']) ? array_map('trim', array_filter($_POST['ehliyet_alinma_tarihi'], 'is_string')) : [];
$kaza_kayit_id = isset($_POST['kaza_kayit_id']) && is_array($_POST['kaza_kayit_id']) ? array_map('trim', array_filter($_POST['kaza_kayit_id'], 'is_string')) : [];
$is_kazasi_tip_id = isset($_POST['is_kazasi_tip_id']) && is_array($_POST['is_kazasi_tip_id']) ? array_map('trim', array_filter($_POST['is_kazasi_tip_id'], 'is_string')) : [];
$other_kaza = isset($_POST['other_kaza']) && is_array($_POST['other_kaza']) ? array_map('htmlspecialchars', array_filter($_POST['other_kaza'], 'is_string')) : [];
$yaralanma_durumu_id = isset($_POST['yaralanma_durumu_id']) && is_array($_POST['yaralanma_durumu_id']) ? array_map('trim', array_filter($_POST['yaralanma_durumu_id'], 'is_string')) : [];
$other_yaralanma_durum = isset($_POST['other_yaralanma_durum']) && is_array($_POST['other_yaralanma_durum']) ? array_map('htmlspecialchars', array_filter($_POST['other_yaralanma_durum'], 'is_string')) : [];
$yaralanma_tip_id = isset($_POST['yaralanma_tip_id']) && is_array($_POST['yaralanma_tip_id']) ? array_map('trim', array_filter($_POST['yaralanma_tip_id'], 'is_string')) : [];
$other_yaralanma_tip = isset($_POST['other_yaralanma_tip']) && is_array($_POST['other_yaralanma_tip']) ? array_map('htmlspecialchars', array_filter($_POST['other_yaralanma_tip'], 'is_string')) : [];
$kaza_nedeni = isset($_POST['kaza_nedeni']) && is_array($_POST['kaza_nedeni']) ? array_map('htmlspecialchars', array_filter($_POST['kaza_nedeni'], 'is_string')) : [];
$is_kazasi_tarihi = isset($_POST['is_kazasi_tarihi']) && is_array($_POST['is_kazasi_tarihi']) ? array_map('trim', array_filter($_POST['is_kazasi_tarihi'], 'is_string')) : [];
$uyari_kayit_id = isset($_POST['uyari_kayit_id']) && is_array($_POST['uyari_kayit_id']) ? array_map('trim', array_filter($_POST['uyari_kayit_id'], 'is_string')) : [];
$uyari_tipi_id = isset($_POST['uyari_tipi_id']) && is_array($_POST['uyari_tipi_id']) ? array_map('trim', array_filter($_POST['uyari_tipi_id'], 'is_string')) : [];
$other_uyari = isset($_POST['other_uyari']) && is_array($_POST['other_uyari']) ? array_map('htmlspecialchars', array_filter($_POST['other_uyari'], 'is_string')) : [];
$uyari_nedeni = isset($_POST['uyari_nedeni']) && is_array($_POST['uyari_nedeni']) ? array_map('htmlspecialchars', array_filter($_POST['uyari_nedeni'], 'is_string')) : [];
$uyari_tarihi = isset($_POST['uyari_tarihi']) && is_array($_POST['uyari_tarihi']) ? array_map('trim', array_filter($_POST['uyari_tarihi'], 'is_string')) : [];

// Hata mesajları için dizi
$errors = [];

// Maksimum tarih (şimdiki zaman + 3 saat)
$max_date = new DateTime('now', new DateTimeZone('Europe/Istanbul'));
$max_date->modify('+3 hours');

// Input validasyonu
if (!preg_match('/^\d{11}$/', $tc_kimlik)) {
    $errors[] = "T.C. Kimlik No 11 haneli ve sadece rakamlardan oluşmalıdır.";
}
if (empty($ad_soyad) || !preg_match('/^[A-Za-zÇçĞğİıÖöŞşÜü\s]+$/', $ad_soyad)) {
    $errors[] = "Ad Soyad sadece Türkçe karakterler ve boşluk içermelidir.";
}
if (empty($dogum_tarihi)) {
    $errors[] = "Doğum tarihi zorunludur.";
} else {
    $dogum_date = DateTime::createFromFormat('Y-m-d', $dogum_tarihi);
    if (!$dogum_date || $dogum_date > $max_date) {
        $errors[] = "Doğum tarihi geçerli olmalı ve gelecekte olamaz.";
    }
}
if (!preg_match('/^0[0-9]{10}$/', $telefon)) {
    $errors[] = "Telefon numarası '05xxxxxxxxx' formatında olmalıdır.";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Geçerli bir e-posta adresi giriniz.";
} else {
    $stmt = $db->prepare("SELECT tc_kimlik FROM personel_kisisel_bilgi WHERE email = ? AND tc_kimlik != ?");
    $stmt->execute([$email, $tc_kimlik]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $errors[] = "Bu e-posta adresi zaten başka bir personel tarafından kullanılıyor.";
    }
}
if (empty($firma_id)) {
    $errors[] = "Firma seçimi zorunludur.";
}
if ($firma_id === 'other' && empty($other_firma)) {
    $errors[] = "Yeni firma adı girilmelidir.";
}
if (empty($meslek_id)) {
    $errors[] = "Meslek seçimi zorunludur.";
}
if ($meslek_id === 'other' && empty($other_meslek)) {
    $errors[] = "Yeni meslek adı girilmelidir.";
}
if (empty($ise_giris_tarihi)) {
    $errors[] = "İşe giriş tarihi zorunludur.";
} else {
    $ise_giris_date = DateTime::createFromFormat('Y-m-d', $ise_giris_tarihi);
    $dogum_date = DateTime::createFromFormat('Y-m-d', $dogum_tarihi);
    if (!$ise_giris_date || $ise_giris_date > $max_date || ($dogum_date && $ise_giris_date < $dogum_date)) {
        $errors[] = "İşe giriş tarihi geçerli olmalı, gelecekte veya doğum tarihinden önce olamaz.";
    }
}
if ($saglik_tetkikleri_oldu_mu && empty($saglik_tetkik_tarihi)) {
    $errors[] = "Sağlık tetkikleri yapıldıysa, tarih belirtilmelidir.";
} elseif ($saglik_tetkikleri_oldu_mu && !empty($saglik_tetkik_tarihi)) {
    $saglik_tetkik_date = DateTime::createFromFormat('Y-m-d', $saglik_tetkik_tarihi);
    if (!$saglik_tetkik_date || $saglik_tetkik_date > $max_date) {
        $errors[] = "Sağlık tetkik tarihi geçerli olmalı ve gelecekte olamaz.";
    }
}

// Sertifika validasyonu ve tekrar kontrolü
$processed_belge_ids = []; // Tekrarlı belge kontrolü için
if (!empty($belge_id)) {
    foreach ($belge_id as $index => $bid) {
        if (!empty($bid)) {
            if ($bid !== 'other') {
                $stmt = $db->prepare("SELECT belge_id FROM hazir_belgeler WHERE belge_id = ?");
                $stmt->execute([$bid]);
                if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                    $errors[] = "Seçilen sertifika geçersiz.";
                }
            }
            if ($bid === 'other' && empty($other_belge[$index])) {
                $errors[] = "Yeni sertifika adı belirtilmelidir.";
            }
            if (empty($belge_alinma_tarihi[$index])) {
                $errors[] = "Sertifika alınma tarihi belirtilmelidir.";
            } else {
                $belge_alinma_date = DateTime::createFromFormat('Y-m-d', $belge_alinma_tarihi[$index]);
                if (!$belge_alinma_date || $belge_alinma_date > $max_date) {
                    $errors[] = "Sertifika alınma tarihi geçerli olmalı ve gelecekte olamaz.";
                }
            }
            if (!empty($belge_kayit_id[$index])) {
                $stmt = $db->prepare("SELECT id FROM personel_belgeler WHERE id = ? AND tc_kimlik = ?");
                $stmt->execute([$belge_kayit_id[$index], $tc_kimlik]);
                if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                    $errors[] = "Geçersiz sertifika kayıt ID'si.";
                }
            }

// Tekrarlı belge kontrolü
            $belge_key = ($bid === 'other' && !empty($other_belge[$index])) ? 'other_' . $other_belge[$index] : $bid;
            if (in_array($belge_key, $processed_belge_ids)) {
                $belge_adi = ($bid === 'other' && !empty($other_belge[$index])) ? htmlspecialchars($other_belge[$index]) : '';
                if ($bid !== 'other') {
                    $stmt = $db->prepare("SELECT belge_adi FROM hazir_belgeler WHERE belge_id = ?");
                    $stmt->execute([$bid]);
                    $belge_adi = $stmt->fetchColumn() ?: $bid; // Belge adı bulunamazsa $bid döner
                }
                $errors[] = "Aynı sertifika birden fazla eklenemez!";
            } else {
                $processed_belge_ids[] = $belge_key;
            }
        }
    }

    // Mevcut belgelerle çakışma kontrolü
    $stmt = $db->prepare("SELECT belge_id FROM personel_belgeler WHERE tc_kimlik = ?");
    $stmt->execute([$tc_kimlik]);
    $mevcut_belge_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($belge_id as $index => $bid) {
        if (!empty($bid) && $bid !== 'other' && empty($belge_kayit_id[$index]) && in_array($bid, $mevcut_belge_ids)) {
            $errors[] = "Bu personel zaten '" . $belge_adi . "' belgesine sahip.";
        }
    }
}

// Ehliyet validasyonu ve tekrar kontrolü
$processed_ehliyet_ids = []; // Tekrarlı ehliyet kontrolü
if ($ehliyet_var_mi && !empty($ehliyet_id)) {
    foreach ($ehliyet_id as $index => $eid) {
        if (!empty($eid)) {
            if ($eid !== 'other') {
                $stmt = $db->prepare("SELECT ehliyet_id FROM hazir_ehliyetler WHERE ehliyet_id = ?");
                $stmt->execute([$eid]);
                if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                    $errors[] = "Seçilen ehliyet sınıfı geçersiz.";
                }
            }
            if ($eid === 'other' && empty($other_ehliyet[$index])) {
                $errors[] = "Yeni ehliyet sınıfı belirtilmelidir.";
            }
            if (empty($ehliyet_alinma_tarihi[$index])) {
                $errors[] = "Ehliyet alınma tarihi belirtilmelidir.";
            } else {
                $ehliyet_alinma_date = DateTime::createFromFormat('Y-m-d', $ehliyet_alinma_tarihi[$index]);
                if (!$ehliyet_alinma_date || $ehliyet_alinma_date > $max_date) {
                    $errors[] = "Ehliyet alınma tarihi geçerli olmalı ve gelecekte olamaz.";
                }
            }
            if (!empty($ehliyet_kayit_id[$index])) {
                $stmt = $db->prepare("SELECT id FROM personel_ehliyetler WHERE id = ? AND tc_kimlik = ?");
                $stmt->execute([$ehliyet_kayit_id[$index], $tc_kimlik]);
                if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                    $errors[] = "Geçersiz ehliyet kayıt ID'si.";
                }
            }

// Tekrarlı ehliyet kontrolü
            $ehliyet_key = ($eid === 'other' && !empty($other_ehliyet[$index])) ? 'other_' . $other_ehliyet[$index] : $eid;
            if (in_array($ehliyet_key, $processed_ehliyet_ids)) {
                $ehliyet_adi = ($eid === 'other' && !empty($other_ehliyet[$index])) ? htmlspecialchars($other_ehliyet[$index]) : '';
                if ($eid !== 'other') {
                    $stmt = $db->prepare("SELECT ehliyet_adi FROM hazir_ehliyetler WHERE ehliyet_id = ?");
                    $stmt->execute([$eid]);
                    $ehliyet_adi = $stmt->fetchColumn() ?: $eid; // Ehliyet adı bulunamazsa $eid döner
                }
                $errors[] = "Aynı ehliyet birden fazla eklenemez!";
            } else {
                $processed_ehliyet_ids[] = $ehliyet_key;
            }
        }
    }

    // Mevcut ehliyetlerle çakışma kontrolü
    $stmt = $db->prepare("SELECT ehliyet_id FROM personel_ehliyetler WHERE tc_kimlik = ?");
    $stmt->execute([$tc_kimlik]);
    $mevcut_ehliyet_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($ehliyet_id as $index => $eid) {
        if (!empty($eid) && $eid !== 'other' && empty($ehliyet_kayit_id[$index]) && in_array($eid, $mevcut_ehliyet_ids)) {
            $errors[] = "Bu personel zaten '" . $ehliyet_adi . "' ehliyetine sahip.";
        }
    }
}

// İş kazası validasyonu
foreach ($is_kazasi_tip_id as $index => $kid) {
    if (!empty($kid)) {
        if ($kid !== 'other') {
            $stmt = $db->prepare("SELECT is_kazasi_tip_id FROM hazir_is_kazalari WHERE is_kazasi_tip_id = ?");
            $stmt->execute([$kid]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $errors[] = "Seçilen iş kazası tipi geçersiz.";
            }
        }
        if ($kid === 'other' && empty($other_kaza[$index])) {
            $errors[] = "Yeni iş kazası tipi belirtilmelidir.";
        }
        if (empty($yaralanma_durumu_id[$index])) {
            $errors[] = "Yaralanma durumu seçilmelidir.";
        }
        if ($yaralanma_durumu_id[$index] === 'other' && empty($other_yaralanma_durum[$index])) {
            $errors[] = "Yeni yaralanma durumu belirtilmelidir.";
        }
        if ($yaralanma_durumu_id[$index] !== 'other') {
            $stmt = $db->prepare("SELECT yaralanma_durum_id FROM hazir_yaralanma_durumlar WHERE yaralanma_durum_id = ?");
            $stmt->execute([$yaralanma_durumu_id[$index]]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $errors[] = "Seçilen yaralanma durumu geçersiz.";
            }
        }
        if (empty($yaralanma_tip_id[$index])) {
            $errors[] = "Yaralanma tipi seçilmelidir.";
        }
        if ($yaralanma_tip_id[$index] === 'other' && empty($other_yaralanma_tip[$index])) {
            $errors[] = "Yeni yaralanma tipi belirtilmelidir.";
        }
        if ($yaralanma_tip_id[$index] !== 'other') {
            $stmt = $db->prepare("SELECT yaralanma_tip_id FROM hazir_yaralanma_tipler WHERE yaralanma_tip_id = ?");
            $stmt->execute([$yaralanma_tip_id[$index]]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $errors[] = "Seçilen yaralanma tipi geçersiz.";
            }
        }
        if (empty($kaza_nedeni[$index])) {
            $errors[] = "Kaza nedeni belirtilmelidir.";
        }
        if (empty($is_kazasi_tarihi[$index])) {
            $errors[] = "İş kazası tarihi belirtilmelidir.";
        } else {
            $kaza_tarihi_date = DateTime::createFromFormat('Y-m-d\TH:i', $is_kazasi_tarihi[$index]);
            if (!$kaza_tarihi_date || $kaza_tarihi_date > $max_date) {
                $errors[] = "İş kazası tarihi geçerli olmalı ve gelecekte olamaz.";
            }
        }
        if (!empty($kaza_kayit_id[$index])) {
            $stmt = $db->prepare("SELECT id FROM personel_is_kazalari WHERE id = ? AND tc_kimlik = ?");
            $stmt->execute([$kaza_kayit_id[$index], $tc_kimlik]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $errors[] = "Geçersiz iş kazası kayıt ID'si.";
            }
        }
    }
}

// Uyarı validasyonu
foreach ($uyari_tipi_id as $index => $uid) {
    if (!empty($uid)) {
        if ($uid !== 'other') {
            $stmt = $db->prepare("SELECT uyari_tip_id FROM hazir_uyarilar WHERE uyari_tip_id = ?");
            $stmt->execute([$uid]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $errors[] = "Seçilen uyarı tipi geçersiz.";
            }
        }
        if ($uid === 'other' && empty($other_uyari[$index])) {
            $errors[] = "Yeni uyarı tipi belirtilmelidir.";
        }
        if (empty($uyari_nedeni[$index])) {
            $errors[] = "Uyarı nedeni belirtilmelidir.";
        }
        if (empty($uyari_tarihi[$index])) {
            $errors[] = "Uyarı tarihi belirtilmelidir.";
        } else {
            $uyari_tarihi_date = DateTime::createFromFormat('Y-m-d\TH:i', $uyari_tarihi[$index]);
            if (!$uyari_tarihi_date || $uyari_tarihi_date > $max_date) {
                $errors[] = "Uyarı tarihi geçerli olmalı ve gelecekte olamaz.";
            }
        }
        if (!empty($uyari_kayit_id[$index])) {
            $stmt = $db->prepare("SELECT id FROM personel_uyarilar WHERE id = ? AND tc_kimlik = ?");
            $stmt->execute([$uyari_kayit_id[$index], $tc_kimlik]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $errors[] = "Geçersiz uyarı kayıt ID'si.";
            }
        }
    }
}

try {
    // Veritabanında personel var mı kontrol et
    $stmt = $db->prepare("SELECT tc_kimlik FROM personel_kisisel_bilgi WHERE tc_kimlik = ?");
    $stmt->execute([$tc_kimlik]);
    if (!$stmt->fetch()) {
        $errors[] = "Bu T.C. Kimlik numarasına sahip personel bulunamadı.";
    }

    // Profil fotoğrafı yükleme
    $pp_dosya_adi = '';
    $current_pp = $db->prepare("SELECT pp_dosya_yolu FROM personel_kisisel_bilgi WHERE tc_kimlik = ?");
    $current_pp->execute([$tc_kimlik]);
    $current_pp_path = $current_pp->fetchColumn();

    if ($pp_dosya_yolu && $pp_dosya_yolu['error'] == UPLOAD_ERR_OK) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($pp_dosya_yolu['tmp_name']);
        $izin_verilen_türler = ['image/jpeg', 'image/png', 'image/gif'];
        $max_boyut = 5 * 1024 * 1024; // 5MB
        if (!in_array($mime, $izin_verilen_türler)) {
            $errors[] = "Profil fotoğrafı yalnızca JPG, PNG veya GIF formatında olabilir.";
        } elseif ($pp_dosya_yolu['size'] > $max_boyut) {
            $errors[] = "Profil fotoğrafı 5MB'dan büyük olamaz.";
        } else {
            $hedef_dizin = 'uploads/';
            $uzanti = pathinfo($pp_dosya_yolu['name'], PATHINFO_EXTENSION);
            $pp_dosya_adi = bin2hex(random_bytes(8)) . '.' . $uzanti;
            $hedef_yol = $hedef_dizin . $pp_dosya_adi;

            if (!move_uploaded_file($pp_dosya_yolu['tmp_name'], $hedef_yol)) {
                $errors[] = "Profil fotoğrafı yüklenirken bir hata oluştu.";
            }
        }
    } else {
        $pp_dosya_adi = $current_pp_path;
    }

        // Hata varsa yönlendir
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['edit_tc_kimlik'] = $tc_kimlik; // Oturuma kaydet
            header("Location: personel_duzenle.php"); // tc_kimlik'siz yönlendir
            exit;
        }

    // Transaction başlat
    $db->beginTransaction();

    // Kullanıcı ID’sini set et
    $db->exec("SET @silen_kullanici_id = $kul_id");
    $db->exec("SET @ekleyen_kullanici_id = $kul_id");

    // Firma ID'sini kontrol et ve gerekirse yeni firma ekle
    if ($firma_id === 'other' && !empty($other_firma)) {
        $stmt = $db->prepare("SELECT firma_id FROM hazir_firmalar WHERE firma_adi = ?");
        $stmt->execute([$other_firma]);
        $existing_firma = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing_firma) {
            $stmt = $db->prepare("INSERT INTO hazir_firmalar (firma_adi, kullanici_id) VALUES (?, ?)");
            $stmt->execute([$other_firma, $kul_id]);
            $firma_id = $db->lastInsertId();
        } else {
            $firma_id = $existing_firma['firma_id'];
        }
    }

    // Meslek ID'sini kontrol et ve gerekirse yeni meslek ekle
    if ($meslek_id === 'other' && !empty($other_meslek)) {
        $stmt = $db->prepare("SELECT meslek_id FROM hazir_meslekler WHERE meslek_adi = ?");
        $stmt->execute([$other_meslek]);
        $existing_meslek = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing_meslek) {
            $stmt = $db->prepare("INSERT INTO hazir_meslekler (meslek_adi, kullanici_id) VALUES (?, ?)");
            $stmt->execute([$other_meslek, $kul_id]);
            $meslek_id = $db->lastInsertId();
        } else {
            $meslek_id = $existing_meslek['meslek_id'];
        }
    }

    // Temel personel bilgilerini güncelle
    $stmt = $db->prepare("UPDATE personel_kisisel_bilgi SET ad_soyad = ?, dogum_tarihi = ?, telefon = ?, email = ?, adres = ?, pp_dosya_yolu = ? WHERE tc_kimlik = ?");
    $stmt->execute([$ad_soyad, $dogum_tarihi, $telefon, $email, $adres, $pp_dosya_adi, $tc_kimlik]);

    $stmt = $db->prepare("UPDATE personel_sirket_bilgi SET firma_id = ?, meslek_id = ?, ise_giris_tarihi = ? WHERE tc_kimlik = ?");
    $stmt->execute([$firma_id, $meslek_id, $ise_giris_tarihi, $tc_kimlik]);

    $stmt = $db->prepare("UPDATE personel_gerekli_belge SET ise_giris_egitimi_var_mi = ?, operatorluk_belgesi_var_mi = ?, mesleki_yeterlilik_belgesi_var_mi = ?, saglik_tetkikleri_oldu_mu = ? WHERE tc_kimlik = ?");
    $stmt->execute([$ise_giris_egitimi_var_mi, $operatorluk_belgesi_var_mi, $mesleki_yeterlilik_belgesi_var_mi, $saglik_tetkikleri_oldu_mu, $tc_kimlik]);

    $stmt = $db->prepare("UPDATE personel_saglik_tetkikleri SET saglik_tetkikleri_oldu_mu = ?, tarih = ? WHERE tc_kimlik = ?");
    $stmt->execute([$saglik_tetkikleri_oldu_mu, $saglik_tetkik_tarihi, $tc_kimlik]);

    // Sertifika işlemleri
    if (!empty($belge_id)) {
        $stmt = $db->prepare("SELECT id FROM personel_belgeler WHERE tc_kimlik = ?");
        $stmt->execute([$tc_kimlik]);
        $mevcut_belge_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $form_belge_ids = array_filter($belge_kayit_id, 'is_numeric');
        $silinecek_belge_ids = array_diff($mevcut_belge_ids, $form_belge_ids);

        if (!empty($silinecek_belge_ids)) {
            $placeholders = implode(',', array_fill(0, count($silinecek_belge_ids), '?'));
            $stmt = $db->prepare("DELETE FROM personel_belgeler WHERE id IN ($placeholders) AND tc_kimlik = ?");
            $stmt->execute(array_merge($silinecek_belge_ids, [$tc_kimlik]));
        }

        foreach ($belge_id as $index => $bid) {
            if (!empty($bid)) {
                if ($bid === 'other' && !empty($other_belge[$index])) {
                    $stmt = $db->prepare("SELECT belge_id FROM hazir_belgeler WHERE belge_adi = ?");
                    $stmt->execute([$other_belge[$index]]);
                    $existing_belge = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$existing_belge) {
                        $stmt = $db->prepare("INSERT INTO hazir_belgeler (belge_adi, kullanici_id) VALUES (?, ?)");
                        $stmt->execute([$other_belge[$index], $kul_id]);
                        $bid = $db->lastInsertId();
                    } else {
                        $bid = $existing_belge['belge_id'];
                    }
                }

                if (!empty($belge_kayit_id[$index]) && is_numeric($belge_kayit_id[$index])) {
                    $stmt = $db->prepare("UPDATE personel_belgeler SET belge_id = ?, alinma_tarihi = ?, kullanici_id = ? WHERE id = ? AND tc_kimlik = ?");
                    $stmt->execute([$bid, $belge_alinma_tarihi[$index], $kul_id, $belge_kayit_id[$index], $tc_kimlik]);
                } else {
                    $stmt = $db->prepare("INSERT INTO personel_belgeler (tc_kimlik, belge_id, alinma_tarihi, kullanici_id) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$tc_kimlik, $bid, $belge_alinma_tarihi[$index], $kul_id]);
                }
            }
        }
    } else {
        $stmt = $db->prepare("DELETE FROM personel_belgeler WHERE tc_kimlik = ?");
        $stmt->execute([$tc_kimlik]);
    }

    // Ehliyet işlemleri
    if ($ehliyet_var_mi && !empty($ehliyet_id)) {
        $stmt = $db->prepare("SELECT id FROM personel_ehliyetler WHERE tc_kimlik = ?");
        $stmt->execute([$tc_kimlik]);
        $mevcut_ehliyet_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $form_ehliyet_ids = array_filter($ehliyet_kayit_id, 'is_numeric');
        $silinecek_ehliyet_ids = array_diff($mevcut_ehliyet_ids, $form_ehliyet_ids);

        if (!empty($silinecek_ehliyet_ids)) {
            $placeholders = implode(',', array_fill(0, count($silinecek_ehliyet_ids), '?'));
            $stmt = $db->prepare("DELETE FROM personel_ehliyetler WHERE id IN ($placeholders) AND tc_kimlik = ?");
            $stmt->execute(array_merge($silinecek_ehliyet_ids, [$tc_kimlik]));
        }

        foreach ($ehliyet_id as $index => $eid) {
            if (!empty($eid)) {
                if ($eid === 'other' && !empty($other_ehliyet[$index])) {
                    $stmt = $db->prepare("SELECT ehliyet_id FROM hazir_ehliyetler WHERE ehliyet_adi = ?");
                    $stmt->execute([$other_ehliyet[$index]]);
                    $existing_ehliyet = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$existing_ehliyet) {
                        $stmt = $db->prepare("INSERT INTO hazir_ehliyetler (ehliyet_adi, kullanici_id) VALUES (?, ?)");
                        $stmt->execute([$other_ehliyet[$index], $kul_id]);
                        $eid = $db->lastInsertId();
                    } else {
                        $eid = $existing_ehliyet['ehliyet_id'];
                    }
                }

                if (!empty($ehliyet_kayit_id[$index]) && is_numeric($ehliyet_kayit_id[$index])) {
                    $stmt = $db->prepare("UPDATE personel_ehliyetler SET ehliyet_id = ?, alinma_tarihi = ?, kullanici_id = ? WHERE id = ? AND tc_kimlik = ?");
                    $stmt->execute([$eid, $ehliyet_alinma_tarihi[$index], $kul_id, $ehliyet_kayit_id[$index], $tc_kimlik]);
                } else {
                    $stmt = $db->prepare("INSERT INTO personel_ehliyetler (tc_kimlik, ehliyet_id, alinma_tarihi, kullanici_id) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$tc_kimlik, $eid, $ehliyet_alinma_tarihi[$index], $kul_id]);
                }
            }
        }
    } else {
        $stmt = $db->prepare("DELETE FROM personel_ehliyetler WHERE tc_kimlik = ?");
        $stmt->execute([$tc_kimlik]);
    }

    // İş kazası işlemleri
    if (!empty($is_kazasi_tip_id)) {
        $stmt = $db->prepare("SELECT id FROM personel_is_kazalari WHERE tc_kimlik = ?");
        $stmt->execute([$tc_kimlik]);
        $mevcut_kaza_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $form_kaza_ids = array_filter($kaza_kayit_id, 'is_numeric');
        $silinecek_kaza_ids = array_diff($mevcut_kaza_ids, $form_kaza_ids);

        if (!empty($silinecek_kaza_ids)) {
            $placeholders = implode(',', array_fill(0, count($silinecek_kaza_ids), '?'));
            $stmt = $db->prepare("DELETE FROM personel_is_kazalari WHERE id IN ($placeholders) AND tc_kimlik = ?");
            $stmt->execute(array_merge($silinecek_kaza_ids, [$tc_kimlik]));
        }

        foreach ($is_kazasi_tip_id as $index => $kid) {
            if (!empty($kid)) {
                if ($kid === 'other' && !empty($other_kaza[$index])) {
                    $stmt = $db->prepare("SELECT is_kazasi_tip_id FROM hazir_is_kazalari WHERE is_kazasi_tipi_adi = ?");
                    $stmt->execute([$other_kaza[$index]]);
                    $existing_kaza = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$existing_kaza) {
                        $stmt = $db->prepare("INSERT INTO hazir_is_kazalari (is_kazasi_tipi_adi, kullanici_id) VALUES (?, ?)");
                        $stmt->execute([$other_kaza[$index], $kul_id]);
                        $kid = $db->lastInsertId();
                    } else {
                        $kid = $existing_kaza['is_kazasi_tip_id'];
                    }
                }
                if ($yaralanma_durumu_id[$index] === 'other' && !empty($other_yaralanma_durum[$index])) {
                    $stmt = $db->prepare("SELECT yaralanma_durum_id FROM hazir_yaralanma_durumlar WHERE yaralanma_durum_adi = ?");
                    $stmt->execute([$other_yaralanma_durum[$index]]);
                    $existing_durum = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$existing_durum) {
                        $stmt = $db->prepare("INSERT INTO hazir_yaralanma_durumlar (yaralanma_durum_adi, kullanici_id) VALUES (?, ?)");
                        $stmt->execute([$other_yaralanma_durum[$index], $kul_id]);
                        $yaralanma_durumu_id[$index] = $db->lastInsertId();
                    } else {
                        $yaralanma_durumu_id[$index] = $existing_durum['yaralanma_durum_id'];
                    }
                }
                if ($yaralanma_tip_id[$index] === 'other' && !empty($other_yaralanma_tip[$index])) {
                    $stmt = $db->prepare("SELECT yaralanma_tip_id FROM hazir_yaralanma_tipler WHERE yaralanma_tipi_adi = ?");
                    $stmt->execute([$other_yaralanma_tip[$index]]);
                    $existing_tip = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$existing_tip) {
                        $stmt = $db->prepare("INSERT INTO hazir_yaralanma_tipler (yaralanma_tipi_adi, kullanici_id) VALUES (?, ?)");
                        $stmt->execute([$other_yaralanma_tip[$index], $kul_id]);
                        $yaralanma_tip_id[$index] = $db->lastInsertId();
                    } else {
                        $yaralanma_tip_id[$index] = $existing_tip['yaralanma_tip_id'];
                    }
                }

                if (!empty($kaza_kayit_id[$index]) && is_numeric($kaza_kayit_id[$index])) {
                    $stmt = $db->prepare("
                        UPDATE personel_is_kazalari 
                        SET is_kazasi_tip_id = ?, yaralanma_durumu_id = ?, yaralanma_tip_id = ?, 
                            kaza_nedeni = ?, is_kazasi_tarihi = ?, kullanici_id = ? 
                        WHERE id = ? AND tc_kimlik = ?
                    ");
                    $stmt->execute([
                        $kid, 
                        $yaralanma_durumu_id[$index], 
                        $yaralanma_tip_id[$index], 
                        $kaza_nedeni[$index], 
                        $is_kazasi_tarihi[$index], 
                        $kul_id, 
                        $kaza_kayit_id[$index], 
                        $tc_kimlik
                    ]);
                } else {
                    $stmt = $db->prepare("
                        INSERT INTO personel_is_kazalari 
                        (tc_kimlik, is_kazasi_tip_id, yaralanma_durumu_id, yaralanma_tip_id, kaza_nedeni, is_kazasi_tarihi, kullanici_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $tc_kimlik, 
                        $kid, 
                        $yaralanma_durumu_id[$index], 
                        $yaralanma_tip_id[$index], 
                        $kaza_nedeni[$index], 
                        $is_kazasi_tarihi[$index], 
                        $kul_id
                    ]);
                }
            }
        }
    } else {
        $stmt = $db->prepare("DELETE FROM personel_is_kazalari WHERE tc_kimlik = ?");
        $stmt->execute([$tc_kimlik]);
    }

    // Uyarı işlemleri
    if (!empty($uyari_tipi_id)) {
        $stmt = $db->prepare("SELECT id FROM personel_uyarilar WHERE tc_kimlik = ?");
        $stmt->execute([$tc_kimlik]);
        $mevcut_uyari_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $form_uyari_ids = array_filter($uyari_kayit_id, 'is_numeric');
        $silinecek_uyari_ids = array_diff($mevcut_uyari_ids, $form_uyari_ids);

        if (!empty($silinecek_uyari_ids)) {
            $placeholders = implode(',', array_fill(0, count($silinecek_uyari_ids), '?'));
            $stmt = $db->prepare("DELETE FROM personel_uyarilar WHERE id IN ($placeholders) AND tc_kimlik = ?");
            $stmt->execute(array_merge($silinecek_uyari_ids, [$tc_kimlik]));
        }

        foreach ($uyari_tipi_id as $index => $uid) {
            if (!empty($uid)) {
                if ($uid === 'other' && !empty($other_uyari[$index])) {
                    $stmt = $db->prepare("SELECT uyari_tip_id FROM hazir_uyarilar WHERE uyari_tipi_adi = ?");
                    $stmt->execute([$other_uyari[$index]]);
                    $existing_uyari = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$existing_uyari) {
                        $stmt = $db->prepare("INSERT INTO hazir_uyarilar (uyari_tipi_adi, kullanici_id) VALUES (?, ?)");
                        $stmt->execute([$other_uyari[$index], $kul_id]);
                        $uid = $db->lastInsertId();
                    } else {
                        $uid = $existing_uyari['uyari_tip_id'];
                    }
                }

                if (!empty($uyari_kayit_id[$index]) && is_numeric($uyari_kayit_id[$index])) {
                    $stmt = $db->prepare("
                        UPDATE personel_uyarilar 
                        SET uyari_tipi_id = ?, uyari_nedeni = ?, uyari_tarihi = ?, kullanici_id = ? 
                        WHERE id = ? AND tc_kimlik = ?
                    ");
                    $stmt->execute([
                        $uid, 
                        $uyari_nedeni[$index], 
                        $uyari_tarihi[$index], 
                        $kul_id, 
                        $uyari_kayit_id[$index], 
                        $tc_kimlik
                    ]);
                } else {
                    $stmt = $db->prepare("
                        INSERT INTO personel_uyarilar 
                        (tc_kimlik, uyari_tipi_id, uyari_nedeni, uyari_tarihi, kullanici_id) 
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $tc_kimlik, 
                        $uid, 
                        $uyari_nedeni[$index], 
                        $uyari_tarihi[$index], 
                        $kul_id
                    ]);
                }
            }
        }
    } else {
        $stmt = $db->prepare("DELETE FROM personel_uyarilar WHERE tc_kimlik = ?");
        $stmt->execute([$tc_kimlik]);
    }

    // Transaction'ı tamamla
    $db->commit();

    // Başarı mesajı
    $_SESSION['success'] = "Personel bilgileri başarıyla güncellendi.";
    header("Location: personel_listele.php");
    exit;

} catch (Exception $e) {
    // Hata durumunda transaction'ı geri al
    $db->rollBack();
    $_SESSION['errors'] = ["Veritabanı hatası: " . $e->getMessage()];
    $_SESSION['edit_tc_kimlik'] = $tc_kimlik; // Oturuma kaydet
    header("Location: personel_duzenle.php"); // tc_kimlik'siz yönlendir
    exit;
}
?>