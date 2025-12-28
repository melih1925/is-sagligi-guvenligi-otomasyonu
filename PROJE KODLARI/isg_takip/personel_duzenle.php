<?php
session_start();
require_once 'header.php';

// URL'de tc_kimlik varsa, oturuma kaydet ve URL'yi temizle
if (isset($_GET['tc_kimlik'])) {
    $_SESSION['edit_tc_kimlik'] = $_GET['tc_kimlik'];
    header("Location: personel_duzenle.php"); // tc_kimlik'siz URL'ye yönlendir
    exit;
}

// Oturumdan tc_kimlik al
if (!isset($_SESSION['edit_tc_kimlik'])) {
    header("Location: personel_listele.php");
    exit;
}

$tc_kimlik = $_SESSION['edit_tc_kimlik'];

// Personel verilerini tek sorgu ile çek
$stmt = $db->prepare("
    SELECT pkb.*, psb.firma_id, psb.meslek_id, psb.ise_giris_tarihi, 
           pgb.ise_giris_egitimi_var_mi, pgb.operatorluk_belgesi_var_mi, 
           pgb.mesleki_yeterlilik_belgesi_var_mi, pgb.saglik_tetkikleri_oldu_mu,
           pst.tarih AS saglik_tetkik_tarihi
    FROM personel_kisisel_bilgi pkb
    LEFT JOIN personel_sirket_bilgi psb ON pkb.tc_kimlik = psb.tc_kimlik
    LEFT JOIN personel_gerekli_belge pgb ON pkb.tc_kimlik = pgb.tc_kimlik
    LEFT JOIN personel_saglik_tetkikleri pst ON pkb.tc_kimlik = pst.tc_kimlik
    WHERE pkb.tc_kimlik = ?
");
$stmt->execute([$tc_kimlik]);
$personel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$personel) {
    header("Location: personel_listele.php");
    exit;
}

// Sertifikalar, ehliyetler, kazalar ve uyarılar
$belgeler = $db->prepare("SELECT pb.*, hb.belge_adi FROM personel_belgeler pb JOIN hazir_belgeler hb ON pb.belge_id = hb.belge_id WHERE pb.tc_kimlik = ?");
$belgeler->execute([$tc_kimlik]);
$personel_belgeler = $belgeler->fetchAll(PDO::FETCH_ASSOC);

$ehliyetler = $db->prepare("SELECT pe.*, he.ehliyet_adi FROM personel_ehliyetler pe JOIN hazir_ehliyetler he ON pe.ehliyet_id = he.ehliyet_id WHERE pe.tc_kimlik = ?");
$ehliyetler->execute([$tc_kimlik]);
$personel_ehliyetler = $ehliyetler->fetchAll(PDO::FETCH_ASSOC);


// İş kazaları
$is_kazalari = $db->prepare("
    SELECT pik.*, 
           hik.is_kazasi_tipi_adi, 
           hyd.yaralanma_durum_adi, 
           hyt.yaralanma_tipi_adi 
    FROM personel_is_kazalari pik 
    LEFT JOIN hazir_is_kazalari hik ON pik.is_kazasi_tip_id = hik.is_kazasi_tip_id 
    LEFT JOIN hazir_yaralanma_durumlar hyd ON pik.yaralanma_durumu_id = hyd.yaralanma_durum_id 
    LEFT JOIN hazir_yaralanma_tipler hyt ON pik.yaralanma_tip_id = hyt.yaralanma_tip_id 
    WHERE pik.tc_kimlik = ?
");
$is_kazalari->execute([$tc_kimlik]);
$personel_is_kazalari = $is_kazalari->fetchAll(PDO::FETCH_ASSOC);


// Uyarılar
$uyarilar = $db->prepare("
    SELECT pu.*, hu.uyari_tipi_adi 
    FROM personel_uyarilar pu 
    LEFT JOIN hazir_uyarilar hu ON pu.uyari_tipi_id = hu.uyari_tip_id 
    WHERE pu.tc_kimlik = ?
");
$uyarilar->execute([$tc_kimlik]);
$personel_uyarilar = $uyarilar->fetchAll(PDO::FETCH_ASSOC);

// Seçenek listelerini çek
$hazir_belgeler = $db->query("SELECT * FROM hazir_belgeler")->fetchAll(PDO::FETCH_ASSOC);
$hazir_ehliyetler = $db->query("SELECT * FROM hazir_ehliyetler")->fetchAll(PDO::FETCH_ASSOC);
$hazir_is_kazalari = $db->query("SELECT * FROM hazir_is_kazalari")->fetchAll(PDO::FETCH_ASSOC);
$hazir_yaralanma_durumlar = $db->query("SELECT * FROM hazir_yaralanma_durumlar")->fetchAll(PDO::FETCH_ASSOC);
$hazir_yaralanma_tipler = $db->query("SELECT * FROM hazir_yaralanma_tipler")->fetchAll(PDO::FETCH_ASSOC);
$hazir_uyarilar = $db->query("SELECT * FROM hazir_uyarilar")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .form-group label { font-weight: bold; color: #495057; }
    h6 { font-size: 1.25rem; text-decoration: underline; }
    .card-body { padding: 1.5rem; }
    .form-control { margin-bottom: 1rem; }
    .btn-danger { margin-top: 0.5rem; }
    .alert { margin-bottom: 1rem; }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Personel Düzenle</h1>

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

    <form action="personel_duzenle_sql.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="tc_kimlik" value="<?php echo htmlspecialchars($personel['tc_kimlik']); ?>">

        <!-- Kişisel Bilgiler -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kişisel Bilgiler</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="tc_kimlik">T.C. Kimlik No</label>
                    <input type="text" class="form-control" id="tc_kimlik" name="tc_kimlik" maxlength="11" pattern="[1-9]\d{10}" value="<?php echo htmlspecialchars($personel['tc_kimlik']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="ad_soyad">Ad Soyad</label>
                    <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" pattern="[A-Za-zÇçĞğİıÖöŞşÜü\s]+" title="Sadece Türkçe karakterler ve boşluk kullanılabilir" value="<?php echo htmlspecialchars($personel['ad_soyad']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="dogum_tarihi">Doğum Tarihi</label>
                    <input type="date" class="form-control tarih" id="dogum_tarihi" name="dogum_tarihi" min="1900-01-01" value="<?php echo htmlspecialchars($personel['dogum_tarihi']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefon">Telefon</label>
                    <input type="text" class="form-control" id="telefon" name="telefon" maxlength="11" pattern="0[0-9]{10}" title="Telefon numarası '05xxxxxxxxx' formatında olmalıdır" value="<?php echo htmlspecialchars($personel['telefon']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($personel['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="adres">Adres</label>
                    <textarea class="form-control" id="adres" name="adres" rows="4"><?php echo htmlspecialchars($personel['adres'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="pp_dosya_yolu">Profil Fotoğrafı</label>
                    <input type="file" class="form-control-file" id="pp_dosya_yolu" name="pp_dosya_yolu" accept="image/jpeg,image/png,image/gif">
                </div>
            </div>
        </div>

        <!-- Şirket Bilgileri -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Şirket Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="firma_id">Firma</label>
                    <select class="form-control" id="firma_id" name="firma_id" onchange="toggleOther(this, 'other_firma')" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($db->query("SELECT * FROM hazir_firmalar")->fetchAll(PDO::FETCH_ASSOC) as $firma): ?>
                            <option value="<?php echo $firma['firma_id']; ?>" <?php echo $firma['firma_id'] == $personel['firma_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($firma['firma_adi']); ?></option>
                        <?php endforeach; ?>
                        <option value="other">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="other_firma" name="other_firma" style="display:none;" placeholder="Yeni firma adı">
                </div>
                <div class="form-group">
                    <label for="meslek_id">Meslek</label>
                    <select class="form-control" id="meslek_id" name="meslek_id" onchange="toggleOther(this, 'other_meslek')" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($db->query("SELECT * FROM hazir_meslekler")->fetchAll(PDO::FETCH_ASSOC) as $meslek): ?>
                            <option value="<?php echo $meslek['meslek_id']; ?>" <?php echo $meslek['meslek_id'] == $personel['meslek_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($meslek['meslek_adi']); ?></option>
                        <?php endforeach; ?>
                        <option value="other">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="other_meslek" name="other_meslek" style="display:none;" placeholder="Yeni meslek adı">
                </div>
                <div class="form-group">
                    <label for="ise_giris_tarihi">İşe Giriş Tarihi</label>
                    <input type="date" class="form-control tarih" id="ise_giris_tarihi" name="ise_giris_tarihi" min="1950-01-01" value="<?php echo htmlspecialchars($personel['ise_giris_tarihi']); ?>" required>
                </div>
            </div>
        </div>

        <!-- Gerekli Belgeler -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Gerekli Belgeler</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>İşe Giriş Eğitimi Var mı?</label>
                    <div>
                        <input type="radio" name="ise_giris_egitimi_var_mi" id="ise_giris_egitimi_evet" value="1" <?php echo $personel['ise_giris_egitimi_var_mi'] ? 'checked' : ''; ?> aria-label="Evet">
                        <label for="ise_giris_egitimi_evet">Evet</label>
                        <input type="radio" name="ise_giris_egitimi_var_mi" id="ise_giris_egitimi_hayir" value="0" <?php echo !$personel['ise_giris_egitimi_var_mi'] ? 'checked' : ''; ?> aria-label="Hayır">
                        <label for="ise_giris_egitimi_hayir">Hayır</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Operatörlük Belgesi Var mı?</label>
                    <div>
                        <input type="radio" name="operatorluk_belgesi_var_mi" id="operatorluk_belgesi_evet" value="1" <?php echo $personel['operatorluk_belgesi_var_mi'] ? 'checked' : ''; ?> aria-label="Evet">
                        <label for="operatorluk_belgesi_evet">Evet</label>
                        <input type="radio" name="operatorluk_belgesi_var_mi" id="operatorluk_belgesi_hayir" value="0" <?php echo !$personel['operatorluk_belgesi_var_mi'] ? 'checked' : ''; ?> aria-label="Hayır">
                        <label for="operatorluk_belgesi_hayir">Hayır</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mesleki Yeterlilik Belgesi Var mı?</label>
                    <div>
                        <input type="radio" name="mesleki_yeterlilik_belgesi_var_mi" id="mesleki_yeterlilik_evet" value="1" <?php echo $personel['mesleki_yeterlilik_belgesi_var_mi'] ? 'checked' : ''; ?> aria-label="Evet">
                        <label for="mesleki_yeterlilik_evet">Evet</label>
                        <input type="radio" name="mesleki_yeterlilik_belgesi_var_mi" id="mesleki_yeterlilik_hayir" value="0" <?php echo !$personel['mesleki_yeterlilik_belgesi_var_mi'] ? 'checked' : ''; ?> aria-label="Hayır">
                        <label for="mesleki_yeterlilik_hayir">Hayır</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Sağlık Tetkikleri Var mı?</label>
                    <div>
                        <input type="radio" name="saglik_tetkikleri_oldu_mu" id="saglik_tetkikleri_evet" value="1" <?php echo $personel['saglik_tetkikleri_oldu_mu'] ? 'checked' : ''; ?> aria-label="Evet">
                        <label for="saglik_tetkikleri_evet">Evet</label>
                        <input type="radio" name="saglik_tetkikleri_oldu_mu" id="saglik_tetkikleri_hayir" value="0" <?php echo !$personel['saglik_tetkikleri_oldu_mu'] ? 'checked' : ''; ?> aria-label="Hayır">
                        <label for="saglik_tetkikleri_hayir">Hayır</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="saglik_tetkik_tarihi">Sağlık Tetkikleri Yapılma Tarihi</label>
                    <input type="date" class="form-control tarih" id="saglik_tetkik_tarihi" name="saglik_tetkik_tarihi" min="1950-01-01" value="<?php echo htmlspecialchars($personel['saglik_tetkik_tarihi'] ?? ''); ?>">
                </div>
            </div>
        </div>

<!-- Sertifikalar -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Sertifikalar</h6>
    </div>
    <div class="card-body">
        <div id="belge-container">
            <?php foreach ($personel_belgeler as $index => $belge): ?>
                <div class="belge-ekle">
                    <!-- Sertifika kaydının ID'sini sakla -->
                    <input type="hidden" name="belge_kayit_id[]" value="<?php echo htmlspecialchars($belge['id'] ?? ''); ?>">
                    <div class="form-group">
                        <label for="belge_id_<?php echo $index + 1; ?>">Sertifika</label>
                        <select class="form-control" id="belge_id_<?php echo $index + 1; ?>" name="belge_id[]" onchange="toggleOther(this, 'other_belge_<?php echo $index + 1; ?>')">
                            <option value="">Seçiniz</option>
                            <?php foreach ($hazir_belgeler as $b): ?>
                                <option value="<?php echo $b['belge_id']; ?>" <?php echo $b['belge_id'] == $belge['belge_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($b['belge_adi']); ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="other" <?php echo !in_array($belge['belge_id'], array_column($hazir_belgeler, 'belge_id')) ? 'selected' : ''; ?>>Diğer</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="other_belge_<?php echo $index + 1; ?>" name="other_belge[]" style="display:<?php echo !in_array($belge['belge_id'], array_column($hazir_belgeler, 'belge_id')) ? 'block' : 'none'; ?>;" placeholder="Yeni sertifika adı" value="<?php echo !in_array($belge['belge_id'], array_column($hazir_belgeler, 'belge_id')) ? htmlspecialchars($belge['belge_adi'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="belge_alinma_tarihi_<?php echo $index + 1; ?>">Sertifika Alınma Tarihi</label>
                        <input type="date" class="form-control tarih" id="belge_alinma_tarihi_<?php echo $index + 1; ?>" name="belge_alinma_tarihi[]" min="1950-01-01" value="<?php echo htmlspecialchars($belge['alinma_tarihi'] ?? ''); ?>">
                    </div>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Sertifikayı Sil</button>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-primary mt-2" onclick="addBelge()">Yeni Sertifika Ekle</button>
    </div>
</div>

<!-- Ehliyetler -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Ehliyetler</h6>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Ehliyeti Var mı?</label>
            <div>
                <input type="radio" name="ehliyet_var_mi" id="ehliyet_evet" value="1" onclick="toggleSection('ehliyetler', true)" <?php echo !empty($personel_ehliyetler) ? 'checked' : ''; ?> aria-label="Evet">
                <label for="ehliyet_evet">Evet</label>
                <input type="radio" name="ehliyet_var_mi" id="ehliyet_hayir" value="0" onclick="toggleSection('ehliyetler', false)" <?php echo empty($personel_ehliyetler) ? 'checked' : ''; ?> aria-label="Hayır">
                <label for="ehliyet_hayir">Hayır</label>
            </div>
        </div>
        <div id="ehliyetler" style="display:<?php echo !empty($personel_ehliyetler) ? 'block' : 'none'; ?>;">
            <div id="ehliyet-container">
                <?php foreach ($personel_ehliyetler as $index => $ehliyet): ?>
                    <div class="ehliyet-ekle">
                        <!-- Ehliyet kaydının ID'sini sakla -->
                        <input type="hidden" name="ehliyet_kayit_id[]" value="<?php echo htmlspecialchars($ehliyet['id'] ?? ''); ?>">
                        <div class="form-group">
                            <label for="ehliyet_id_<?php echo $index + 1; ?>">Ehliyet Sınıfı</label>
                            <select class="form-control" id="ehliyet_id_<?php echo $index + 1; ?>" name="ehliyet_id[]" onchange="toggleOther(this, 'other_ehliyet_<?php echo $index + 1; ?>')">
                                <option value="">Seçiniz</option>
                                <?php foreach ($hazir_ehliyetler as $e): ?>
                                    <option value="<?php echo $e['ehliyet_id']; ?>" <?php echo $e['ehliyet_id'] == $ehliyet['ehliyet_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($e['ehliyet_adi']); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="other">Diğer</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="other_ehliyet_<?php echo $index + 1; ?>" name="other_ehliyet[]" style="display:<?php echo !in_array($ehliyet['ehliyet_id'], array_column($hazir_ehliyetler, 'ehliyet_id')) ? 'block' : 'none'; ?>;" placeholder="Yeni ehliyet sınıfı" value="<?php echo !in_array($ehliyet['ehliyet_id'], array_column($hazir_ehliyetler, 'ehliyet_id')) ? htmlspecialchars($ehliyet['ehliyet_adi']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="ehliyet_alinma_tarihi_<?php echo $index + 1; ?>">Ehliyet Alınma Tarihi</label>
                            <input type="date" class="form-control tarih" id="ehliyet_alinma_tarihi_<?php echo $index + 1; ?>" name="ehliyet_alinma_tarihi[]" min="1950-01-01" value="<?php echo htmlspecialchars($ehliyet['alinma_tarihi'] ?? ''); ?>">
                        </div>
                        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Ehliyeti Sil</button>
                        <hr>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-primary mt-2" onclick="addEhliyet()">Yeni Ehliyet Ekle</button>
        </div>
    </div>
</div>

<!-- İş Kazaları -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">İş Kazaları</h6>
    </div>
    <div class="card-body">
        <div id="kaza-container">
            <?php foreach ($personel_is_kazalari as $index => $kaza): ?>
                <div class="kaza-ekle">
                    <!-- İş kazası kaydının ID'sini sakla -->
                    <input type="hidden" name="kaza_kayit_id[]" value="<?php echo htmlspecialchars($kaza['id'] ?? ''); ?>">
                    <div class="form-group">
                        <label for="is_kazasi_tip_id_<?php echo $index + 1; ?>">İş Kazası Tipi</label>
                        <select class="form-control" id="is_kazasi_tip_id_<?php echo $index + 1; ?>" name="is_kazasi_tip_id[]" onchange="toggleOther(this, 'other_kaza_<?php echo $index + 1; ?>')">
                            <option value="">Seçiniz</option>
                            <?php foreach ($hazir_is_kazalari as $hik): ?>
                                <option value="<?php echo $hik['is_kazasi_tip_id']; ?>" <?php echo $hik['is_kazasi_tip_id'] == $kaza['is_kazasi_tip_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($hik['is_kazasi_tipi_adi']); ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="other" <?php echo !in_array($kaza['is_kazasi_tip_id'], array_column($hazir_is_kazalari, 'is_kazasi_tip_id')) ? 'selected' : ''; ?>>Diğer</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="other_kaza_<?php echo $index + 1; ?>" name="other_kaza[]" style="display:<?php echo !in_array($kaza['is_kazasi_tip_id'], array_column($hazir_is_kazalari, 'is_kazasi_tip_id')) ? 'block' : 'none'; ?>;" placeholder="Yeni kaza tipi" value="<?php echo !in_array($kaza['is_kazasi_tip_id'], array_column($hazir_is_kazalari, 'is_kazasi_tip_id')) ? htmlspecialchars($kaza['is_kazasi_tipi_adi'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="yaralanma_durumu_id_<?php echo $index + 1; ?>">Yaralanma Durumu</label>
                        <select class="form-control" id="yaralanma_durumu_id_<?php echo $index + 1; ?>" name="yaralanma_durumu_id[]" onchange="toggleOther(this, 'other_yaralanma_durum_<?php echo $index + 1; ?>')">
                            <option value="">Seçiniz</option>
                            <?php foreach ($hazir_yaralanma_durumlar as $hyd): ?>
                                <option value="<?php echo $hyd['yaralanma_durum_id']; ?>" <?php echo $hyd['yaralanma_durum_id'] == $kaza['yaralanma_durumu_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($hyd['yaralanma_durum_adi']); ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="other" <?php echo !in_array($kaza['yaralanma_durumu_id'], array_column($hazir_yaralanma_durumlar, 'yaralanma_durum_id')) ? 'selected' : ''; ?>>Diğer</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="other_yaralanma_durum_<?php echo $index + 1; ?>" name="other_yaralanma_durum[]" style="display:<?php echo !in_array($kaza['yaralanma_durumu_id'], array_column($hazir_yaralanma_durumlar, 'yaralanma_durum_id')) ? 'block' : 'none'; ?>;" placeholder="Yeni yaralanma durumu" value="<?php echo !in_array($kaza['yaralanma_durumu_id'], array_column($hazir_yaralanma_durumlar, 'yaralanma_durum_id')) ? htmlspecialchars($kaza['yaralanma_durum_adi'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="yaralanma_tip_id_<?php echo $index + 1; ?>">Yaralanma Tipi</label>
                        <select class="form-control" id="yaralanma_tip_id_<?php echo $index + 1; ?>" name="yaralanma_tip_id[]" onchange="toggleOther(this, 'other_yaralanma_tip_<?php echo $index + 1; ?>')">
                            <option value="">Seçiniz</option>
                            <?php foreach ($hazir_yaralanma_tipler as $hyt): ?>
                                <option value="<?php echo $hyt['yaralanma_tip_id']; ?>" <?php echo $hyt['yaralanma_tip_id'] == $kaza['yaralanma_tip_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($hyt['yaralanma_tipi_adi']); ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="other" <?php echo !in_array($kaza['yaralanma_tip_id'], array_column($hazir_yaralanma_tipler, 'yaralanma_tip_id')) ? 'selected' : ''; ?>>Diğer</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="other_yaralanma_tip_<?php echo $index + 1; ?>" name="other_yaralanma_tip[]" style="display:<?php echo !in_array($kaza['yaralanma_tip_id'], array_column($hazir_yaralanma_tipler, 'yaralanma_tip_id')) ? 'block' : 'none'; ?>;" placeholder="Yeni yaralanma tipi" value="<?php echo !in_array($kaza['yaralanma_tip_id'], array_column($hazir_yaralanma_tipler, 'yaralanma_tip_id')) ? htmlspecialchars($kaza['yaralanma_tipi_adi'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="kaza_nedeni_<?php echo $index + 1; ?>">Kaza Nedeni</label>
                        <textarea class="form-control" id="kaza_nedeni_<?php echo $index + 1; ?>" name="kaza_nedeni[]"><?php echo htmlspecialchars($kaza['kaza_nedeni'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="is_kazasi_tarihi_<?php echo $index + 1; ?>">Kaza Tarihi</label>
                        <input type="datetime-local" class="form-control tarih" min="1950-01-01T00:00" id="is_kazasi_tarihi_<?php echo $index + 1; ?>" name="is_kazasi_tarihi[]" value="<?php echo htmlspecialchars($kaza['is_kazasi_tarihi'] ?? ''); ?>">
                    </div>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">İş Kazasını Sil</button>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-primary mt-2" onclick="addKaza()">Yeni İş Kazası Ekle</button>
    </div>
</div>

 <!-- Uyarılar -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Uyarılar</h6>
    </div>
    <div class="card-body">
        <div id="uyari-container">
            <?php foreach ($personel_uyarilar as $index => $uyari): ?>
                <div class="uyari-ekle">
                    <!-- Uyarı kaydının ID'sini sakla -->
                    <input type="hidden" name="uyari_kayit_id[]" value="<?php echo htmlspecialchars($uyari['id'] ?? ''); ?>">
                    <div class="form-group">
                        <label for="uyari_tipi_id_<?php echo $index + 1; ?>">Uyarı Tipi</label>
                        <select class="form-control" id="uyari_tipi_id_<?php echo $index + 1; ?>" name="uyari_tipi_id[]" onchange="toggleOther(this, 'other_uyari_<?php echo $index + 1; ?>')">
                            <option value="">Seçiniz</option>
                            <?php foreach ($hazir_uyarilar as $hu): ?>
                                <option value="<?php echo $hu['uyari_tip_id']; ?>" <?php echo $hu['uyari_tip_id'] == $uyari['uyari_tipi_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($hu['uyari_tipi_adi']); ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="other" <?php echo !in_array($uyari['uyari_tipi_id'], array_column($hazir_uyarilar, 'uyari_tip_id')) ? 'selected' : ''; ?>>Diğer</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="other_uyari_<?php echo $index + 1; ?>" name="other_uyari[]" style="display:<?php echo !in_array($uyari['uyari_tipi_id'], array_column($hazir_uyarilar, 'uyari_tip_id')) ? 'block' : 'none'; ?>;" placeholder="Yeni uyarı tipi" value="<?php echo !in_array($uyari['uyari_tipi_id'], array_column($hazir_uyarilar, 'uyari_tip_id')) ? htmlspecialchars($uyari['uyari_tipi_adi'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="uyari_nedeni_<?php echo $index + 1; ?>">Uyarı Nedeni</label>
                        <textarea class="form-control" id="uyari_nedeni_<?php echo $index + 1; ?>" name="uyari_nedeni[]"><?php echo htmlspecialchars($uyari['uyari_nedeni'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="uyari_tarihi_<?php echo $index + 1; ?>">Uyarı Tarihi</label>
                        <input type="datetime-local" class="form-control tarih" min="1950-01-01T00:00" id="uyari_tarihi_<?php echo $index + 1; ?>" name="uyari_tarihi[]" value="<?php echo htmlspecialchars($uyari['uyari_tarihi'] ?? ''); ?>">
                    </div>
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Uyarıyı Sil</button>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-primary mt-2" onclick="addUyari()">Yeni Uyarı Ekle</button>
    </div>
</div>

        <button type="submit" class="btn btn-success btn-block">Personel Bilgilerini Güncelle</button>
    </form>
</div>

<script>
    // Seçenek listelerini JavaScript'e aktar
    const options = {
        belgeler: <?php echo json_encode($hazir_belgeler); ?>,
        ehliyetler: <?php echo json_encode($hazir_ehliyetler); ?>,
        is_kazalari: <?php echo json_encode($hazir_is_kazalari); ?>,
        yaralanma_durumlar: <?php echo json_encode($hazir_yaralanma_durumlar); ?>,
        yaralanma_tipler: <?php echo json_encode($hazir_yaralanma_tipler); ?>,
        uyarilar: <?php echo json_encode($hazir_uyarilar); ?>
    };

    function toggleSection(sectionId, show) {
        document.getElementById(sectionId).style.display = show ? 'block' : 'none';
    }

    function toggleOther(select, inputId) {
        const input = document.getElementById(inputId);
        input.style.display = select.value === 'other' ? 'block' : 'none';
        input.required = select.value === 'other';
    }

    function setMaxDate() {
        const today = new Date();
        today.setMinutes(today.getMinutes() + 180); // +3 saat
        const maxDate = today.toISOString().split('T')[0]; // YYYY-MM-DD
        const maxDateTime = today.toISOString().slice(0, 16); // YYYY-MM-DDTHH:MM

        document.querySelectorAll('input.tarih').forEach(input => {
            if (input.type === 'datetime-local') {
                input.setAttribute('max', maxDateTime);
            } else if (input.type === 'date') {
                input.setAttribute('max', maxDate);
            }
        });
    }

    let counts = {
        belge: <?php echo count($personel_belgeler) ?: 0; ?>,
        ehliyet: <?php echo count($personel_ehliyetler) ?: 0; ?>,
        kaza: <?php echo count($personel_is_kazalari) ?: 0; ?>,
        uyari: <?php echo count($personel_uyarilar) ?: 0; ?>
    };

function addBelge() {
    counts.belge++;
    const container = document.getElementById('belge-container');
    const template = `
        <div class="belge-ekle">
            <!-- Yeni sertifika için kayit_id boş -->
            <input type="hidden" name="belge_kayit_id[]" value="">
            <div class="form-group">
                <label for="belge_id_${counts.belge}">Sertifika</label>
                <select class="form-control" id="belge_id_${counts.belge}" name="belge_id[]" onchange="toggleOther(this, 'other_belge_${counts.belge}')">
                    <option value="">Seçiniz</option>
                    ${options.belgeler.map(b => `<option value="${b.belge_id}">${b.belge_adi}</option>`).join('')}
                    <option value="other">Diğer</option>
                </select>
                <input type="text" class="form-control mt-2" id="other_belge_${counts.belge}" name="other_belge[]" style="display:none;" placeholder="Yeni sertifika adı">
            </div>
            <div class="form-group">
                <label for="belge_alinma_tarihi_${counts.belge}">Sertifika Alınma Tarihi</label>
                <input type="date" class="form-control tarih" id="belge_alinma_tarihi_${counts.belge}" name="belge_alinma_tarihi[]" min="1950-01-01">
            </div>
            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Sertifikayı Sil</button>
            <hr>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    setMaxDate();
}

    function toggleOther(selectElement, inputId) {
        const input = document.getElementById(inputId);
        input.style.display = (selectElement.value === 'other') ? 'block' : 'none';
    }

function addEhliyet() {
    counts.ehliyet++;
    const container = document.getElementById('ehliyet-container');
    const template = `
        <div class="ehliyet-ekle">
            <!-- Yeni ehliyet için kayit_id boş -->
            <input type="hidden" name="ehliyet_kayit_id[]" value="">
            <div class="form-group">
                <label for="ehliyet_id_${counts.ehliyet}">Ehliyet Sınıfı</label>
                <select class="form-control" id="ehliyet_id_${counts.ehliyet}" name="ehliyet_id[]" onchange="toggleOther(this, 'other_ehliyet_${counts.ehliyet}')">
                    <option value="">Seçiniz</option>
                    ${options.ehliyetler.map(e => `<option value="${e.ehliyet_id}">${e.ehliyet_adi}</option>`).join('')}
                    <option value="other">Diğer</option>
                </select>
                <input type="text" class="form-control mt-2" id="other_ehliyet_${counts.ehliyet}" name="other_ehliyet[]" style="display:none;" placeholder="Yeni ehliyet sınıfı">
            </div>
            <div class="form-group">
                <label for="ehliyet_alinma_tarihi_${counts.ehliyet}">Ehliyet Alınma Tarihi</label>
                <input type="date" class="form-control tarih" id="ehliyet_alinma_tarihi_${counts.ehliyet}" name="ehliyet_alinma_tarihi[]" min="1950-01-01">
            </div>
            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Ehliyeti Sil</button>
            <hr>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    setMaxDate();
}

function addKaza() {
    counts.kaza++;
    const container = document.getElementById('kaza-container');
    const template = `
        <div class="kaza-ekle">
            <!-- Yeni iş kazası için kayit_id boş -->
            <input type="hidden" name="kaza_kayit_id[]" value="">
            <div class="form-group">
                <label for="is_kazasi_tip_id_${counts.kaza}">İş Kazası Tipi</label>
                <select class="form-control" id="is_kazasi_tip_id_${counts.kaza}" name="is_kazasi_tip_id[]" onchange="toggleOther(this, 'other_kaza_${counts.kaza}')">
                    <option value="">Seçiniz</option>
                    ${options.is_kazalari.map(k => `<option value="${k.is_kazasi_tip_id}">${k.is_kazasi_tipi_adi}</option>`).join('')}
                    <option value="other">Diğer</option>
                </select>
                <input type="text" class="form-control mt-2" id="other_kaza_${counts.kaza}" name="other_kaza[]" style="display:none;" placeholder="Yeni kaza tipi">
            </div>
            <div class="form-group">
                <label for="yaralanma_durumu_id_${counts.kaza}">Yaralanma Durumu</label>
                <select class="form-control" id="yaralanma_durumu_id_${counts.kaza}" name="yaralanma_durumu_id[]" onchange="toggleOther(this, 'other_yaralanma_durum_${counts.kaza}')">
                    <option value="">Seçiniz</option>
                    ${options.yaralanma_durumlar.map(d => `<option value="${d.yaralanma_durum_id}">${d.yaralanma_durum_adi}</option>`).join('')}
                    <option value="other">Diğer</option>
                </select>
                <input type="text" class="form-control mt-2" id="other_yaralanma_durum_${counts.kaza}" name="other_yaralanma_durum[]" style="display:none;" placeholder="Yeni yaralanma durumu">
            </div>
            <div class="form-group">
                <label for="yaralanma_tip_id_${counts.kaza}">Yaralanma Tipi</label>
                <select class="form-control" id="yaralanma_tip_id_${counts.kaza}" name="yaralanma_tip_id[]" onchange="toggleOther(this, 'other_yaralanma_tip_${counts.kaza}')">
                    <option value="">Seçiniz</option>
                    ${options.yaralanma_tipler.map(t => `<option value="${t.yaralanma_tip_id}">${t.yaralanma_tipi_adi}</option>`).join('')}
                    <option value="other">Diğer</option>
                </select>
                <input type="text" class="form-control mt-2" id="other_yaralanma_tip_${counts.kaza}" name="other_yaralanma_tip[]" style="display:none;" placeholder="Yeni yaralanma tipi">
            </div>
            <div class="form-group">
                <label for="kaza_nedeni_${counts.kaza}">Kaza Nedeni</label>
                <textarea class="form-control" id="kaza_nedeni_${counts.kaza}" name="kaza_nedeni[]"></textarea>
            </div>
            <div class="form-group">
                <label for="is_kazasi_tarihi_${counts.kaza}">Kaza Tarihi</label>
                <input type="datetime-local" min="1950-01-01T00:00" class="form-control tarih" id="is_kazasi_tarihi_${counts.kaza}" name="is_kazasi_tarihi[]">
            </div>
            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">İş Kazasını Sil</button>
            <hr>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    setMaxDate();
}

function addUyari() {
    counts.uyari++;
    const container = document.getElementById('uyari-container');
    const template = `
        <div class="uyari-ekle">
            <!-- Yeni uyarı için kayit_id boş -->
            <input type="hidden" name="uyari_kayit_id[]" value="">
            <div class="form-group">
                <label for="uyari_tipi_id_${counts.uyari}">Uyarı Tipi</label>
                <select class="form-control" id="uyari_tipi_id_${counts.uyari}" name="uyari_tipi_id[]" onchange="toggleOther(this, 'other_uyari_${counts.uyari}')">
                    <option value="">Seçiniz</option>
                    ${options.uyarilar.map(u => `<option value="${u.uyari_tip_id}">${u.uyari_tipi_adi}</option>`).join('')}
                    <option value="other">Diğer</option>
                </select>
                <input type="text" class="form-control mt-2" id="other_uyari_${counts.uyari}" name="other_uyari[]" style="display:none;" placeholder="Yeni uyarı tipi">
            </div>
            <div class="form-group">
                <label for="uyari_nedeni_${counts.uyari}">Uyarı Nedeni</label>
                <textarea class="form-control" id="uyari_nedeni_${counts.uyari}" name="uyari_nedeni[]"></textarea>
            </div>
            <div class="form-group">
                <label for="uyari_tarihi_${counts.uyari}">Uyarı Tarihi</label>
                <input type="datetime-local" min="1950-01-01T00:00" class="form-control tarih" id="uyari_tarihi_${counts.uyari}" name="uyari_tarihi[]">
            </div>
            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Uyarıyı Sil</button>
            <hr>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    setMaxDate();
}

    document.addEventListener("DOMContentLoaded", setMaxDate);
</script>

<?php require_once 'footer.php'; ?>