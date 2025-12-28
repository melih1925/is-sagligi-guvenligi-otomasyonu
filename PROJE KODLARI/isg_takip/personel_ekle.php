<?php
session_start();
require_once 'header.php';

// CSRF token oluşturrrrrr
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Başarı mesajı için yönlendirme kontrolü
$show_success = isset($_SESSION['from_sql']) && $_SESSION['from_sql'] === true;
unset($_SESSION['from_sql']); // Bir sonraki yüklemede mesajı temizle
?>


    <!-- #################################################################### -->


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

    /* Profil fotoğrafı ön izleme için stil */
    .profile-preview {
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
        border-radius: 50%;
        margin-top: 10px;
        display: none; /* Varsayılan olarak gizli */
    }
</style>


    <!-- #################################################################### -->


<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Personel Ekle</h1>

    <!-- Hata ve başarı mesajları -->
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

    <form action="personel_ekle_sql.php" method="POST" enctype="multipart/form-data" id="personelEkleForm">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Kişisel Bilgiler -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kişisel Bilgiler</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="tc_kimlik">T.C. Kimlik No</label>
                    <input type="text" class="form-control" id="tc_kimlik" name="tc_kimlik" pattern="[1-9]\d{10}" title="T.C. Kimlik No 11 haneli olmalı ve sayılardan oluşmalıdır." maxlength="11" required>
                </div>
                <div class="form-group">
                    <label for="ad_soyad">Ad Soyad</label>
                    <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" pattern="[A-Za-zÇçĞğİıÖöŞşÜü\s]+" title="Sadece Türkçe karakterler ve boşluk kullanılabilir" required>
                </div>
                <div class="form-group">
                    <label>Cinsiyet</label>
                    <div>
                        <input type="radio" name="cinsiyet" id="cinsiyet_erkek" value="1" checked aria-label="Erkek">
                        <label for="cinsiyet_erkek">Erkek</label>
                        <input type="radio" name="cinsiyet" id="cinsiyet_kadin" value="0" aria-label="Kadın">
                        <label for="cinsiyet_kadin">Kadın</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-md-6 col-lg-2">
                        <label for="dogum_tarihi">Doğum Tarihi</label>
                        <input type="date" class="form-control" id="dogum_tarihi" name="dogum_tarihi" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="telefon">Telefon</label>
                    <input type="text" class="form-control" id="telefon" name="telefon" pattern="0[0-9]{10}" title="Telefon numarası '05457159272' formatında olmalıdır" maxlength="11" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Geçerli bir e-posta adresi giriniz" required>
                </div>
                <div class="form-group">
                    <label for="adres">Adres</label>
                    <textarea class="form-control" id="adres" name="adres" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="pp_dosya_yolu">Profil Fotoğrafı</label>
                    <input type="file" class="form-control-file" id="pp_dosya_yolu" name="pp_dosya_yolu" accept="image/jpeg,image/png,image/gif">
                    <img id="profilePreview" class="profile-preview" alt="Profil Fotoğrafı Ön İzleme">
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
                        <?php
                        $firmalar = $db->query("SELECT * FROM hazir_firmalar")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($firmalar as $firma) {
                            echo "<option value='{$firma['firma_id']}'>{$firma['firma_adi']}</option>";
                        }
                        ?>
                        <option value="other">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="other_firma" name="other_firma" style="display:none;" placeholder="Yeni firma adı">
                </div>
                <div class="form-group">
                    <label for="meslek_id">Meslek</label>
                    <select class="form-control" id="meslek_id" name="meslek_id" onchange="toggleOther(this, 'other_meslek')" required>
                        <option value="">Seçiniz</option>
                        <?php
                        $meslekler = $db->query("SELECT * FROM hazir_meslekler")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($meslekler as $meslek) {
                            echo "<option value='{$meslek['meslek_id']}'>{$meslek['meslek_adi']}</option>";
                        }
                        ?>
                        <option value="other">Diğer</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="other_meslek" name="other_meslek" style="display:none;" placeholder="Yeni meslek adı">
                </div>
                <div class="row">
                    <div class="form-group col-12 col-md-6 col-lg-2">
                        <label for="ise_giris_tarihi">İşe Giriş Tarihi</label>
                        <input type="date" class="form-control" id="ise_giris_tarihi" name="ise_giris_tarihi" min="1950-01-01" required>
                    </div>
                </div>
            </div>
        </div>


        <!-- #################################################################### -->



        <!-- Gerekli Belgeler -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Gerekli Belgeler</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>İşe Giriş Eğitimi Var mı?</label>
                    <div>
                        <input type="radio" name="ise_giris_egitimi_var_mi" id="ise_giris_egitimi_evet" value="1" checked aria-label="Evet">
                        <label for="ise_giris_egitimi_evet">Evet</label>
                        <input type="radio" name="ise_giris_egitimi_var_mi" id="ise_giris_egitimi_hayir" value="0" aria-label="Hayır">
                        <label for="ise_giris_egitimi_hayir">Hayır</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Operatörlük Belgesi Var mı?</label>
                    <div>
                        <input type="radio" name="operatorluk_belgesi_var_mi" id="operatorluk_belgesi_evet" value="1" checked aria-label="Evet">
                        <label for="operatorluk_belgesi_evet">Evet</label>
                        <input type="radio" name="operatorluk_belgesi_var_mi" id="operatorluk_belgesi_hayir" value="0" aria-label="Hayır">
                        <label for="operatorluk_belgesi_hayir">Hayır</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mesleki Yeterlilik Belgesi Var mı?</label>
                    <div>
                        <input type="radio" name="mesleki_yeterlilik_belgesi_var_mi" id="mesleki_yeterlilik_evet" value="1" checked aria-label="Evet">
                        <label for="mesleki_yeterlilik_evet">Evet</label>
                        <input type="radio" name="mesleki_yeterlilik_belgesi_var_mi" id="mesleki_yeterlilik_hayir" value="0" aria-label="Hayır">
                        <label for="mesleki_yeterlilik_hayir">Hayır</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Sağlık Tetkikleri Var mı?</label>
                    <div>
                        <input type="radio" name="saglik_tetkikleri_oldu_mu" id="saglik_tetkikleri_evet" value="1" checked aria-label="Evet">
                        <label for="saglik_tetkikleri_evet">Evet</label>
                        <input type="radio" name="saglik_tetkikleri_oldu_mu" id="saglik_tetkikleri_hayir" value="0" aria-label="Hayır">
                        <label for="saglik_tetkikleri_hayir">Hayır</label>
                    </div>
                </div>
                <div class="row" id="tetkik_tarihi">
                    <div class="form-group col-12 col-md-6 col-lg-2">
                        <label for="saglik_tetkik_tarihi">Sağlık Tetkikleri Yapılma Tarihi</label>
                        <input type="date" class="form-control" id="saglik_tetkik_tarihi" name="saglik_tetkik_tarihi" min="1950-01-01">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sertifikalar -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Sertifikalar</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Sertifikası Var mı?</label>
                    <div>
                        <input type="radio" name="sertifika_var_mi" id="sertifika_evet" value="1" onclick="toggleSection('sertifikalar', true)" aria-label="Evet">
                        <label for="sertifika_evet">Evet</label>
                        <input type="radio" name="sertifika_var_mi" id="sertifika_hayir" value="0" onclick="toggleSection('sertifikalar', false)" checked aria-label="Hayır">
                        <label for="sertifika_hayir">Hayır</label>
                    </div>
                </div>
                <div id="sertifikalar" style="display:none;">
                    <div class="sertifika-ekle" id="sertifika_1">
                        <div class="form-group">
                            <label for="belge_id_1">Sertifika</label>
                            <select class="form-control" id="belge_id_1" name="belge_id[]" onchange="toggleOther(this, 'other_belge_1')">
                                <option value="">Seçiniz</option>
                                <?php
                                $belgeler = $db->query("SELECT * FROM hazir_belgeler")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($belgeler as $belge) {
                                    echo "<option value='{$belge['belge_id']}'>{$belge['belge_adi']}</option>";
                                }
                                ?>
                                <option value="other">Diğer</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="other_belge_1" name="other_belge[]" style="display:none;" placeholder="Yeni belge adı">
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6 col-lg-2">
                                <label for="belge_alinma_tarihi_1">Belge Alınma Tarihi</label>
                                <input type="date" class="form-control" id="belge_alinma_tarihi_1" name="belge_alinma_tarihi[]" min="1950-01-01">
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger mt-2" onclick="removeElement('sertifika_1')">Sertifikayı Sil</button>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" onclick="addSertifika()">Yeni Belge Ekle</button>
                </div>
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
                        <input type="radio" name="ehliyet_var_mi" id="ehliyet_evet" value="1" onclick="toggleSection('ehliyetler', true); toggleEhliyetRequired(true)" aria-label="Evet">
                        <label for="ehliyet_evet">Evet</label>
                        <input type="radio" name="ehliyet_var_mi" id="ehliyet_hayir" value="0" onclick="toggleSection('ehliyetler', false); toggleEhliyetRequired(false)" checked aria-label="Hayır">
                        <label for="ehliyet_hayir">Hayır</label>
                    </div>
                </div>
                <div id="ehliyetler" style="display:none;">
                    <div class="ehliyet-ekle" id="ehliyet_1">
                        <div class="form-group">
                            <label for="ehliyet_id_1">Ehliyet Sınıfı</label>
                            <select class="form-control" id="ehliyet_id_1" name="ehliyet_id[]" aria-required="true">
                                <option value="">Seçiniz</option>
                                <?php
                                $ehliyetler = $db->query("SELECT * FROM hazir_ehliyetler")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($ehliyetler as $ehliyet) {
                                    echo "<option value='{$ehliyet['ehliyet_id']}'>{$ehliyet['ehliyet_adi']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6 col-lg-2">
                                <label for="ehliyet_alinma_tarihi_1">Ehliyet Alınma Tarihi</label>
                                <input type="date" class="form-control" id="ehliyet_alinma_tarihi_1" name="ehliyet_alinma_tarihi[]" min="1950-01-01" aria-required="true">
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger mt-2" onclick="removeElement('ehliyet_1')">Ehliyeti Sil</button>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" onclick="addEhliyet()">Yeni Ehliyet Ekle</button>
                </div>
            </div>
        </div>


        <!-- #################################################################### -->



        <!-- İş Kazaları -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">İş Kazaları</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>İş Kazası Var mı?</label>
                    <div>
                        <input type="radio" name="is_kazasi_var_mi" id="is_kazasi_evet" value="1" onclick="toggleSection('is_kazalari', true)" aria-label="Evet">
                        <label for="is_kazasi_evet">Evet</label>
                        <input type="radio" name="is_kazasi_var_mi" id="is_kazasi_hayir" value="0" onclick="toggleSection('is_kazalari', false)" checked aria-label="Hayır">
                        <label for="is_kazasi_hayir">Hayır</label>
                    </div>
                </div>
                <div id="is_kazalari" style="display:none;">
                    <div class="kaza-ekle" id="kaza_1">
                        <div class="form-group">
                            <label for="is_kazasi_tip_id_1">İş Kazası Tipi</label>
                            <select class="form-control" id="is_kazasi_tip_id_1" name="is_kazasi_tip_id[]" onchange="toggleOther(this, 'other_kaza_1')">
                                <option value="">Seçiniz</option>
                                <?php
                                $kazalar = $db->query("SELECT * FROM hazir_is_kazalari")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($kazalar as $kaza) {
                                    echo "<option value='{$kaza['is_kazasi_tip_id']}'>{$kaza['is_kazasi_tipi_adi']}</option>";
                                }
                                ?>
                                <option value="other">Diğer</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="other_kaza_1" name="other_kaza[]" style="display:none;" placeholder="Yeni kaza tipi">
                        </div>
                        <div class="form-group">
                            <label for="yaralanma_durumu_id_1">Yaralanma Durumu</label>
                            <select class="form-control" id="yaralanma_durumu_id_1" name="yaralanma_durumu_id[]" onchange="toggleOther(this, 'other_yaralanma_durum_1')">
                                <option value="">Seçiniz</option>
                                <?php
                                $durumlar = $db->query("SELECT * FROM hazir_yaralanma_durumlar")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($durumlar as $durum) {
                                    echo "<option value='{$durum['yaralanma_durum_id']}'>{$durum['yaralanma_durum_adi']}</option>";
                                }
                                ?>
                                <option value="other">Diğer</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="other_yaralanma_durum_1" name="other_yaralanma_durum[]" style="display:none;" placeholder="Yeni yaralanma durumu">
                        </div>
                        <div class="form-group">
                            <label for="yaralanma_tip_id_1">Yaralanma Tipi</label>
                            <select class="form-control" id="yaralanma_tip_id_1" name="yaralanma_tip_id[]" onchange="toggleOther(this, 'other_yaralanma_tip_1')">
                                <option value="">Seçiniz</option>
                                <?php
                                $tipler = $db->query("SELECT * FROM hazir_yaralanma_tipler")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($tipler as $tip) {
                                    echo "<option value='{$tip['yaralanma_tip_id']}'>{$tip['yaralanma_tipi_adi']}</option>";
                                }
                                ?>
                                <option value="other">Diğer</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="other_yaralanma_tip_1" name="other_yaralanma_tip[]" style="display:none;" placeholder="Yeni yaralanma tipi">
                        </div>
                        <div class="form-group">
                            <label for="kaza_nedeni_1">Kaza Nedeni</label>
                            <textarea class="form-control" id="kaza_nedeni_1" name="kaza_nedeni[]" rows="4"></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6 col-lg-2">
                                <label for="is_kazasi_tarihi_1">İş Kazası Tarihi</label>
                                <input type="datetime-local" class="form-control" id="is_kazasi_tarihi_1" name="is_kazasi_tarihi[]" min="1950-01-01">
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger mt-2" onclick="removeElement('kaza_1')">Kazayı Sil</button>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" onclick="addKaza()">Yeni Kaza Ekle</button>
                </div>
            </div>
        </div>

        <!-- Uyarılar -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Uyarılar</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Uyarı Var mı?</label>
                    <div>
                        <input type="radio" name="uyari_var_mi" id="uyari_evet" value="1" onclick="toggleSection('uyarilar', true)" aria-label="Evet">
                        <label for="uyari_evet">Evet</label>
                        <input type="radio" name="uyari_var_mi" id="uyari_hayir" value="0" onclick="toggleSection('uyarilar', false)" checked aria-label="Hayır">
                        <label for="uyari_hayir">Hayır</label>
                    </div>
                </div>
                <div id="uyarilar" style="display:none;">
                    <div class="uyari-ekle" id="uyari_1">
                        <div class="form-group">
                            <label for="uyari_tipi_id_1">Uyarı Tipi</label>
                            <select class="form-control" id="uyari_tipi_id_1" name="uyari_tipi_id[]" onchange="toggleOther(this, 'other_uyari_1')">
                                <option value="">Seçiniz</option>
                                <?php
                                $uyarilar = $db->query("SELECT * FROM hazir_uyarilar")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($uyarilar as $uyari) {
                                    echo "<option value='{$uyari['uyari_tip_id']}'>{$uyari['uyari_tipi_adi']}</option>";
                                }
                                ?>
                                <option value="other">Diğer</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="other_uyari_1" name="other_uyari[]" style="display:none;" placeholder="Yeni uyarı tipi">
                        </div>
                        <div class="form-group">
                            <label for="uyari_nedeni_1">Uyarı Nedeni</label>
                            <textarea class="form-control" id="uyari_nedeni_1" name="uyari_nedeni[]" rows="4"></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6 col-lg-2">
                                <label for="uyari_tarihi_1">Uyarı Tarihi</label>
                                <input type="datetime-local" class="form-control" id="uyari_tarihi_1" name="uyari_tarihi[]" min="1950-01-01">
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger mt-2" onclick="removeElement('uyari_1')">Uyarıyı Sil</button>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" onclick="addUyari()">Yeni Uyarı Ekle</button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-block" id="submitButton">
            Personeli Kaydet
            <span class="loading-spinner"><i class="fas fa-spinner fa-spin"></i></span>
        </button>
    </form>
</div>


    <!-- #################################################################### -->



<script>
// Bölüm göster/gizle
function toggleSection(sectionId, show) {
    document.getElementById(sectionId).style.display = show ? 'block' : 'none';
}

// "Diğer" seçeneği için input göster/gizle
function toggleOther(select, inputId) {
    const input = document.getElementById(inputId);
    input.style.display = select.value === 'other' ? 'block' : 'none';
    input.required = select.value === 'other';
}

// Eleman silme
function removeElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.remove();
    }
}

// Tarih input'larına max tarihi ayarla
function setMaxDate(input) {
    const now = new Date();
    now.setMinutes(now.getMinutes() + 180);

    let maxDate;
    if (input.type === "date") {
        maxDate = now.toISOString().slice(0, 10); // Örnek: "2025-05-03"
    } else if (input.type === "datetime-local") {
        maxDate = now.toISOString().slice(0, 16); // Örnek: "2025-05-03T14:30"
    }
    if (maxDate) {
        input.setAttribute('max', maxDate);
    }
}

// Sağlık Tetkikleri Var mı? Sorusundan sonra hayır cevabı gelirse date formu görünmeyecek.
const evetRadio = document.getElementById('saglik_tetkikleri_evet');
const hayirRadio = document.getElementById('saglik_tetkikleri_hayir');
const tarihDiv = document.getElementById('tetkik_tarihi');

function gosterGizle() {
    if (hayirRadio.checked) {
        tarihDiv.style.display = 'none';
        document.getElementById('saglik_tetkik_tarihi').value = '';
    } else {
        tarihDiv.style.display = 'block';
    }
}

window.addEventListener('DOMContentLoaded', () => {
    gosterGizle();
});

evetRadio.addEventListener('change', gosterGizle);
hayirRadio.addEventListener('change', gosterGizle);

// 18 yaşından küçük girilememesi için
window.addEventListener('DOMContentLoaded', () => {
    const bugun = new Date();
    const onsekizYilOnce = new Date(bugun.getFullYear() - 18, bugun.getMonth(), bugun.getDate());
    const yil = onsekizYilOnce.getFullYear();
    const ay = String(onsekizYilOnce.getMonth() + 1).padStart(2, '0');
    const gun = String(onsekizYilOnce.getDate()).padStart(2, '0');
    const maksTarih = `${yil}-${ay}-${gun}`;
    const dogumTarihiInput = document.getElementById('dogum_tarihi');
    dogumTarihiInput.max = maksTarih;
});

// Ehliyet alanlarını zorunlu yap
function toggleEhliyetRequired(required) {
    const ehliyetInputs = document.querySelectorAll('select[name="ehliyet_id[]"], input[name="ehliyet_alinma_tarihi[]"]');
    ehliyetInputs.forEach(input => {
        input.required = required;
        input.setAttribute('aria-required', required);
    });
}



    // ####################################################################################33



// Sertifika ekleme
let sertifikaCount = 1;
function addSertifika() {
    sertifikaCount++;
    const container = document.getElementById('sertifikalar');
    const newSertifika = document.createElement('div');
    newSertifika.className = 'sertifika-ekle';
    newSertifika.id = `sertifika_${sertifikaCount}`;
    newSertifika.innerHTML = `<br>
        <div class="form-group">
            <label for="belge_id_${sertifikaCount}">Sertifika</label>
            <select class="form-control" id="belge_id_${sertifikaCount}" name="belge_id[]" onchange="toggleOther(this, 'other_belge_${sertifikaCount}')">
                <option value="">Seçiniz</option>
                <?php foreach ($belgeler as $belge) {
                    echo "<option value='{$belge['belge_id']}'>{$belge['belge_adi']}</option>";
                } ?>
                <option value="other">Diğer</option>
            </select>
            <input type="text" class="form-control mt-2" id="other_belge_${sertifikaCount}" name="other_belge[]" style="display:none;" placeholder="Yeni belge adı">
        </div>
        <div class="row">
            <div class="form-group col-12 col-md-6 col-lg-2">
                <label for="belge_alinma_tarihi_${sertifikaCount}">Belge Alınma Tarihi</label>
                <input type="date" class="form-control" id="belge_alinma_tarihi_${sertifikaCount}" name="belge_alinma_tarihi[]" min="1950-01-01">
            </div>
        </div>
        <button type="button" class="btn btn-danger mt-2" onclick="removeElement('sertifika_${sertifikaCount}')">Sertifikayı Sil</button>
    `;
    container.appendChild(newSertifika);
    const addButton = container.querySelector('button[onclick="addSertifika()"]');
    if (addButton) {
        container.appendChild(addButton);
    }
    setMaxDate(document.getElementById(`belge_alinma_tarihi_${sertifikaCount}`));
}



        // ################################################################################################



// Ehliyet ekleme
let ehliyetCount = 1;
function addEhliyet() {
    ehliyetCount++;
    const container = document.getElementById('ehliyetler');
    const newEhliyet = document.createElement('div');
    newEhliyet.className = 'ehliyet-ekle';
    newEhliyet.id = `ehliyet_${ehliyetCount}`;
    newEhliyet.innerHTML = `<br>
        <div class="form-group">
            <label for="ehliyet_id_${ehliyetCount}">Ehliyet Sınıfı</label>
            <select class="form-control" id="ehliyet_id_${ehliyetCount}" name="ehliyet_id[]" aria-required="true">
                <option value="">Seçiniz</option>
                <?php foreach ($ehliyetler as $ehliyet) {
                    echo "<option value='{$ehliyet['ehliyet_id']}'>{$ehliyet['ehliyet_adi']}</option>";
                } ?>
            </select>
        </div>
        <div class="row">
            <div class="form-group col-12 col-md-6 col-lg-2">
                <label for="ehliyet_alinma_tarihi_${ehliyetCount}">Ehliyet Alınma Tarihi</label>
                <input type="date" class="form-control" id="ehliyet_alinma_tarihi_${ehliyetCount}" name="ehliyet_alinma_tarihi[]" min="1950-01-01" aria-required="true">
            </div>
        </div>
        <button type="button" class="btn btn-danger mt-2" onclick="removeElement('ehliyet_${ehliyetCount}')">Ehliyeti Sil</button>
    `;
    container.appendChild(newEhliyet);
    const addButton = container.querySelector('button[onclick="addEhliyet()"]');
    if (addButton) {
        container.appendChild(addButton);
    }
    setMaxDate(document.getElementById(`ehliyet_alinma_tarihi_${ehliyetCount}`));
}



        // ###############################################################################3



// Kaza ekleme
let kazaCount = 1;
function addKaza() {
    kazaCount++;
    const container = document.getElementById('is_kazalari');
    const newKaza = document.createElement('div');
    newKaza.className = 'kaza-ekle';
    newKaza.id = `kaza_${kazaCount}`;
    newKaza.innerHTML = `<br>
        <div class="form-group">
            <label for="is_kazasi_tip_id_${kazaCount}">İş Kazası Tipi</label>
            <select class="form-control" id="is_kazasi_tip_id_${kazaCount}" name="is_kazasi_tip_id[]" onchange="toggleOther(this, 'other_kaza_${kazaCount}')">
                <option value="">Seçiniz</option>
                <?php foreach ($kazalar as $kaza) {
                    echo "<option value='{$kaza['is_kazasi_tip_id']}'>{$kaza['is_kazasi_tipi_adi']}</option>";
                } ?>
                <option value="other">Diğer</option>
            </select>
            <input type="text" class="form-control mt-2" id="other_kaza_${kazaCount}" name="other_kaza[]" style="display:none;" placeholder="Yeni kaza tipi">
        </div>
        <div class="form-group">
            <label for="yaralanma_durumu_id_${kazaCount}">Yaralanma Durumu</label>
            <select class="form-control" id="yaralanma_durumu_id_${kazaCount}" name="yaralanma_durumu_id[]" onchange="toggleOther(this, 'other_yaralanma_durum_${kazaCount}')">
                <option value="">Seçiniz</option>
                <?php foreach ($durumlar as $durum) {
                    echo "<option value='{$durum['yaralanma_durum_id']}'>{$durum['yaralanma_durum_adi']}</option>";
                } ?>
                <option value="other">Diğer</option>
            </select>
            <input type="text" class="form-control mt-2" id="other_yaralanma_durum_${kazaCount}" name="other_yaralanma_durum[]" style="display:none;" placeholder="Yeni yaralanma durumu">
        </div>
        <div class="form-group">
            <label for="yaralanma_tip_id_${kazaCount}">Yaralanma Tipi</label>
            <select class="form-control" id="yaralanma_tip_id_${kazaCount}" name="yaralanma_tip_id[]" onchange="toggleOther(this, 'other_yaralanma_tip_${kazaCount}')">
                <option value="">Seçiniz</option>
                <?php foreach ($tipler as $tip) {
                    echo "<option value='{$tip['yaralanma_tip_id']}'>{$tip['yaralanma_tipi_adi']}</option>";
                } ?>
                <option value="other">Diğer</option>
            </select>
            <input type="text" class="form-control mt-2" id="other_yaralanma_tip_${kazaCount}" name="other_yaralanma_tip[]" style="display:none;" placeholder="Yeni yaralanma tipi">
        </div>
        <div class="form-group">
            <label for="kaza_nedeni_${kazaCount}">Kaza Nedeni</label>
            <textarea class="form-control" id="kaza_nedeni_${kazaCount}" name="kaza_nedeni[]" rows="4"></textarea>
        </div>
        <div class="row">
            <div class="form-group col-12 col-md-6 col-lg-2">
                <label for="is_kazasi_tarihi_${kazaCount}">İş Kazası Tarihi</label>
                <input type="datetime-local" class="form-control" id="is_kazasi_tarihi_${kazaCount}" name="is_kazasi_tarihi[]" min="1950-01-01">
            </div>
        </div>
        <button type="button" class="btn btn-danger mt-2" onclick="removeElement('kaza_${kazaCount}')">Kazayı Sil</button>
    `;
    container.appendChild(newKaza);
    const addButton = container.querySelector('button[onclick="addKaza()"]');
    if (addButton) {
        container.appendChild(addButton);
    }
    setMaxDate(document.getElementById(`is_kazasi_tarihi_${kazaCount}`));
}


        // ########################################################################################



// Uyarı ekleme
let uyariCount = 1;
function addUyari() {
    uyariCount++;
    const container = document.getElementById('uyarilar');
    const newUyari = document.createElement('div');
    newUyari.className = 'uyari-ekle';
    newUyari.id = `uyari_${uyariCount}`;
    newUyari.innerHTML = `<br>
        <div class="form-group">
            <label for="uyari_tipi_id_${uyariCount}">Uyarı Tipi</label>
            <select class="form-control" id="uyari_tipi_id_${uyariCount}" name="uyari_tipi_id[]" onchange="toggleOther(this, 'other_uyari_${uyariCount}')">
                <option value="">Seçiniz</option>
                <?php foreach ($uyarilar as $uyari) {
                    echo "<option value='{$uyari['uyari_tip_id']}'>{$uyari['uyari_tipi_adi']}</option>";
                } ?>
                <option value="other">Diğer</option>
            </select>
            <input type="text" class="form-control mt-2" id="other_uyari_${uyariCount}" name="other_uyari[]" style="display:none;" placeholder="Yeni uyarı tipi">
        </div>
        <div class="form-group">
            <label for="uyari_nedeni_${uyariCount}">Uyarı Nedeni</label>
            <textarea class="form-control" id="uyari_nedeni_${uyariCount}" name="uyari_nedeni[]" rows="4"></textarea>
        </div>
        <div class="row">
            <div class="form-group col-12 col-md-6 col-lg-2">
                <label for="uyari_tarihi_${uyariCount}">Uyarı Tarihi</label>
                <input type="datetime-local" class="form-control" id="uyari_tarihi_${uyariCount}" name="uyari_tarihi[]" min="1950-01-01">
            </div>
        </div>
        <button type="button" class="btn btn-danger mt-2" onclick="removeElement('uyari_${uyariCount}')">Uyarıyı Sil</button>
    `;
    container.appendChild(newUyari);
    const addButton = container.querySelector('button[onclick="addUyari()"]');
    if (addButton) {
        container.appendChild(addButton);
    }
    setMaxDate(document.getElementById(`uyari_tarihi_${uyariCount}`));
}


        // ############################################################################################


// Form gönderiminde spinner göster
document.getElementById('personelEkleForm').addEventListener('submit', function() {
    const submitButton = document.getElementById('submitButton');
    submitButton.disabled = true;
    document.querySelector('.loading-spinner').style.display = 'inline-block';
});

// Profil fotoğrafı ön izleme
document.getElementById('pp_dosya_yolu').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('profilePreview');
    
    if (file) {
        // MIME türünü kontrol et
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validImageTypes.includes(file.type)) {
            alert('Sadece JPG, PNG veya GIF formatında dosya yükleyebilirsiniz.');
            event.target.value = ''; // Dosya inputunu sıfırla
            preview.style.display = 'none';
            preview.src = '';
            return;
        }

        // Dosya boyutunu kontrol et (5MB)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            alert('Dosya boyutu 5MB\'dan büyük olamaz.');
            event.target.value = ''; // Dosya inputunu sıfırla
            preview.style.display = 'none';
            preview.src = '';
            return;
        }

        // Dosyayı oku ve ön izlemeyi göster
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        // Dosya seçilmediyse ön izlemeyi gizle
        preview.style.display = 'none';
        preview.src = '';
    }
});

// Sayfa yüklendiğinde tarih input'larına max tarihi ayarla
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('input[type="date"], input[type="datetime-local"]').forEach(input => {
        setMaxDate(input);
    });
    toggleEhliyetRequired(false);
});
</script>

<?php require_once 'footer.php'; ?>