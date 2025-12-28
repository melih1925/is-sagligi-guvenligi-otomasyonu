<!--
Alınan Kolon Bilgileri

T.C. Kimlik No
Ad Soyad
Cinsiyet
Doğum Tarihi
Telefon
E-mail
Adres
Firma Adı
Meslek Adı
İşe Giriş Tarihi
İşe Giriş Eğitimi Var mı?
Operatörlük Belgesi Var mı?
Mesleki Yeterlilik Belgesi Var mı?
Sağlık Tetkikleri Var mı?
Sağlık Tetkikleri Tarihi
    Kolon Adı	                                 Şartlar
T.C. Kimlik No	                    11 haneli sayı (örn. 21334455678), metin formatında, önde sıfır olabilir.
Ad Soyad                        	Boş olamaz, metin (örn. Emre Can).
Cinsiyet	                        Sadece Erkek veya Kadın.
Doğum Tarihi	                    YYYY-MM-DD formatında (örn. 1987-09-10).
Telefon                         	11 haneli, 05 ile başlar (örn. 05371233456), metin formatında.
E-mail	                            Geçerli e-posta formatı (örn. emre.can@example.com).
Adres	                            Boş olamaz, metin (örn. İstanbul, Esenyurt).
Firma Adı                       	hazir_firmalar tablosunda olmalı (örn. Yükselen Yapı A.Ş.).
Meslek Adı                      	hazir_meslekler tablosunda olmalı (örn. İnşaat Ustası).
İşe Giriş Tarihi                   	YYYY-MM-DD formatında (örn. 2021-06-15).
İşe Giriş Eğitimi Var mı?	        Sadece Evet veya Hayır.
Operatörlük Belgesi Var mı?	        Sadece Evet veya Hayır.
Mesleki Yeterlilik Belgesi Var mı?	Sadece Evet veya Hayır.
Sağlık Tetkikleri Var mı?	        Sadece Evet veya Hayır.
Sağlık Tetkikleri Tarihi	        Evet ise YYYY-MM-DD formatında (örn. 2024-09-20), Hayır ise boş.
-->

<?php
require_once 'header.php';
require_once __DIR__ . '/excel.php'; // SimpleXLSX'in tanımlandığı dosya

use Shuchkin\SimpleXLSX;

if (isset($_POST['personelyukle'])) {
    if ($_FILES['excel_dosyasi']['error'] == 0) {
        // Dosya uzantısını kontrol et
        $dosya_ismi = $_FILES['excel_dosyasi']['name'];
        $uzanti = pathinfo($dosya_ismi, PATHINFO_EXTENSION);
        if (strtolower($uzanti) !== 'xlsx') {
            echo '<div class="alert alert-danger">Hata: Sadece .xlsx formatında dosyalar yüklenebilir.</div>';
        } else {
            $gecici_isim = $_FILES['excel_dosyasi']['tmp_name'];
            $sayi = rand(1000, 9999);
            $isim = $sayi . $dosya_ismi;
            move_uploaded_file($gecici_isim, "gecici/$isim");

            if ($xlsx = SimpleXLSX::parse("gecici/$isim")) {
                $expected_columns = 15; // Excel'deki kolon sayısı
                $kayit_sayisi = 0; // Başarılı kayıt sayısını takip et
                $hata_var = false; // Hata durumunu kontrol et

                foreach ($xlsx->rows() as $key => $satir) {
                    if ($key == 0) continue; // Başlık satırını atla

                    // Satırın kolon sayısını kontrol et
                    if (count($satir) != $expected_columns) {
                        $hata_var = true;
                        echo '<div class="alert alert-danger">Hata: Satır ' . ($key + 1) . ', beklenen kolon sayısı ' . $expected_columns . ', bulunan ' . count($satir) . '</div>';
                        continue;
                    }

                    try {
                        $db->beginTransaction();

                        // Veri doğrulama
                        $tc_kimlik = $satir[0];
                        if (!preg_match('/^\d{11}$/', $tc_kimlik)) {
                            throw new Exception('Satır ' . ($key + 1) . ': T.C. Kimlik No 11 haneli bir sayı olmalıdır.');
                        }

                        $ad_soyad = trim($satir[1]);
                        if (empty($ad_soyad)) {
                            throw new Exception('Satır ' . ($key + 1) . ': Ad Soyad boş olamaz.');
                        }

                        $cinsiyet = ($satir[2] == 'Erkek') ? 1 : (($satir[2] == 'Kadın') ? 0 : null);
                        if ($cinsiyet === null) {
                            throw new Exception('Satır ' . ($key + 1) . ': Cinsiyet "Erkek" veya "Kadın" olmalıdır.');
                        }

                        $dogum_tarihi = $satir[3];
                        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dogum_tarihi)) {
                            throw new Exception('Satır ' . ($key + 1) . ': Doğum Tarihi YYYY-MM-DD formatında olmalıdır.');
                        }

                        $telefon = $satir[4];
                        if (!preg_match('/^05\d{9}$/', $telefon)) {
                            throw new Exception('Satır ' . ($key + 1) . ': Telefon numarası 05xxxxxxxxx formatında olmalıdır.');
                        }

                        $email = $satir[5];
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            throw new Exception('Satır ' . ($key + 1) . ': Geçersiz e-posta adresi.');
                        }

                        $adres = trim($satir[6]);
                        if (empty($adres)) {
                            throw new Exception('Satır ' . ($key + 1) . ': Adres boş olamaz.');
                        }

                        $firma_adi = trim($satir[7]);
                        $meslek_adi = trim($satir[8]);

                        // Firma ID'sini al
                        $firma_sorgu = $db->prepare("SELECT firma_id FROM hazir_firmalar WHERE firma_adi = :firma_adi");
                        $firma_sorgu->execute(['firma_adi' => $firma_adi]);
                        $firma = $firma_sorgu->fetch(PDO::FETCH_ASSOC);
                        $firma_id = $firma ? $firma['firma_id'] : null;
                        if (!$firma_id) {
                            throw new Exception('Satır ' . ($key + 1) . ': Firma adı "' . htmlspecialchars($firma_adi) . '" veritabanında bulunamadı.');
                        }

                        // Meslek ID'sini al
                        $meslek_sorgu = $db->prepare("SELECT meslek_id FROM hazir_meslekler WHERE meslek_adi = :meslek_adi");
                        $meslek_sorgu->execute(['meslek_adi' => $meslek_adi]);
                        $meslek = $meslek_sorgu->fetch(PDO::FETCH_ASSOC);
                        $meslek_id = $meslek ? $meslek['meslek_id'] : null;
                        if (!$meslek_id) {
                            throw new Exception('Satır ' . ($key + 1) . ': Meslek adı "' . htmlspecialchars($meslek_adi) . '" veritabanında bulunamadı.');
                        }

                        $ise_giris_tarihi = $satir[9];
                        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $ise_giris_tarihi)) {
                            throw new Exception('Satır ' . ($key + 1) . ': İşe Giriş Tarihi YYYY-MM-DD formatında olmalıdır.');
                        }

                        $ise_giris_egitimi = ($satir[10] == 'Evet') ? 1 : (($satir[10] == 'Hayır') ? 0 : null);
                        if ($ise_giris_egitimi === null) {
                            throw new Exception('Satır ' . ($key + 1) . ': İşe Giriş Eğitimi "Evet" veya "Hayır" olmalıdır.');
                        }

                        $operatorluk_belgesi = ($satir[11] == 'Evet') ? 1 : (($satir[11] == 'Hayır') ? 0 : null);
                        if ($operatorluk_belgesi === null) {
                            throw new Exception('Satır ' . ($key + 1) . ': Operatörlük Belgesi "Evet" veya "Hayır" olmalıdır.');
                        }

                        $mesleki_yeterlilik = ($satir[12] == 'Evet') ? 1 : (($satir[12] == 'Hayır') ? 0 : null);
                        if ($mesleki_yeterlilik === null) {
                            throw new Exception('Satır ' . ($key + 1) . ': Mesleki Yeterlilik Belgesi "Evet" veya "Hayır" olmalıdır.');
                        }

                        $saglik_tetkikleri = ($satir[13] == 'Evet') ? 1 : (($satir[13] == 'Hayır') ? 0 : null);
                        if ($saglik_tetkikleri === null) {
                            throw new Exception('Satır ' . ($key + 1) . ': Sağlık Tetkikleri "Evet" veya "Hayır" olmalıdır.');
                        }

                        $saglik_tetkik_tarihi = $satir[14];
                        if ($saglik_tetkikleri && !empty($saglik_tetkik_tarihi)) {
                            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $saglik_tetkik_tarihi)) {
                                throw new Exception('Satır ' . ($key + 1) . ': Sağlık Tetkikleri Tarihi YYYY-MM-DD formatında olmalıdır.');
                            }
                        } elseif ($saglik_tetkikleri && empty($saglik_tetkik_tarihi)) {
                            throw new Exception('Satır ' . ($key + 1) . ': Sağlık Tetkikleri "Evet" ise tarih belirtilmelidir.');
                        } elseif (!$saglik_tetkikleri && !empty($saglik_tetkik_tarihi)) {
                            throw new Exception('Satır ' . ($key + 1) . ': Sağlık Tetkikleri "Hayır" ise tarih belirtilmemelidir.');
                        }

                        // 1. personel_kisisel_bilgi
                        $sorgu = $db->prepare("INSERT INTO personel_kisisel_bilgi SET 
                            tc_kimlik=:tc_kimlik,
                            ad_soyad=:ad_soyad,
                            cinsiyet=:cinsiyet,
                            dogum_tarihi=:dogum_tarihi,
                            telefon=:telefon,
                            email=:email,
                            adres=:adres,
                            kullanici_id=:kullanici_id");
                        $sorgu->execute([
                            'tc_kimlik' => $tc_kimlik,
                            'ad_soyad' => $ad_soyad,
                            'cinsiyet' => $cinsiyet,
                            'dogum_tarihi' => $dogum_tarihi,
                            'telefon' => $telefon,
                            'email' => $email,
                            'adres' => $adres,
                            'kullanici_id' => 1 // Varsayılan kullanıcı ID
                        ]);

                        // 2. personel_sirket_bilgi
                        $sorgu = $db->prepare("INSERT INTO personel_sirket_bilgi SET 
                            tc_kimlik=:tc_kimlik,
                            firma_id=:firma_id,
                            meslek_id=:meslek_id,
                            ise_giris_tarihi=:ise_giris_tarihi,
                            kullanici_id=:kullanici_id");
                        $sorgu->execute([
                            'tc_kimlik' => $tc_kimlik,
                            'firma_id' => $firma_id,
                            'meslek_id' => $meslek_id,
                            'ise_giris_tarihi' => $ise_giris_tarihi,
                            'kullanici_id' => 1
                        ]);

                        // 3. personel_gerekli_belge
                        $sorgu = $db->prepare("INSERT INTO personel_gerekli_belge SET 
                            tc_kimlik=:tc_kimlik,
                            ise_giris_egitimi_var_mi=:ise_giris_egitimi,
                            operatorluk_belgesi_var_mi=:operatorluk_belgesi,
                            mesleki_yeterlilik_belgesi_var_mi=:mesleki_yeterlilik,
                            saglik_tetkikleri_oldu_mu=:saglik_tetkikleri,
                            kullanici_id=:kullanici_id");
                        $sorgu->execute([
                            'tc_kimlik' => $tc_kimlik,
                            'ise_giris_egitimi' => $ise_giris_egitimi,
                            'operatorluk_belgesi' => $operatorluk_belgesi,
                            'mesleki_yeterlilik' => $mesleki_yeterlilik,
                            'saglik_tetkikleri' => $saglik_tetkikleri,
                            'kullanici_id' => 1
                        ]);

                        // 4. personel_saglik_tetkikleri
                        if ($saglik_tetkikleri && !empty($saglik_tetkik_tarihi)) {
                            $sorgu = $db->prepare("INSERT INTO personel_saglik_tetkikleri SET 
                                tc_kimlik=:tc_kimlik,
                                saglik_tetkikleri_oldu_mu=:saglik_tetkikleri,
                                tarih=:tarih,
                                kullanici_id=:kullanici_id");
                            $sorgu->execute([
                                'tc_kimlik' => $tc_kimlik,
                                'saglik_tetkikleri' => 1,
                                'tarih' => $saglik_tetkik_tarihi,
                                'kullanici_id' => 1
                            ]);
                        }

                        $db->commit();
                        $kayit_sayisi++; // Başarılı kayıt sayısını artır
                    } catch (Exception $e) {
                        $db->rollBack();
                        $hata_var = true;
                        echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
                    }
                }

                // Başarılı kayıt mesajı
                if ($kayit_sayisi > 0 && !$hata_var) {
                    echo '<div class="alert alert-success">' . $kayit_sayisi . ' adet personel başarılı bir şekilde kayıt edildi.</div>';
                } elseif ($kayit_sayisi == 0 && !$hata_var) {
                    echo '<div class="alert alert-danger">Hata: Hiçbir personel kaydı yapılamadı. Lütfen dosya içeriğini kontrol edin.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Hata: Excel dosyası okunamadı: ' . htmlspecialchars(SimpleXLSX::parseError()) . '</div>';
            }

            unlink("gecici/$isim");
        }
    } else {
        $hata_mesaji = '';
        switch ($_FILES['excel_dosyasi']['error']) {
            case UPLOAD_ERR_NO_FILE:
                $hata_mesaji = 'Hata: Dosya seçilmedi.';
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $hata_mesaji = 'Hata: Dosya boyutu çok büyük.';
                break;
            default:
                $hata_mesaji = 'Hata: Dosya yüklenirken bir sorun oluştu.';
                break;
        }
        echo '<div class="alert alert-danger">' . $hata_mesaji . '</div>';
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Personel Yükleme İşlemi</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label>Excel Dosyası</label>
                                <input type="file" name="excel_dosyasi" class="form-control">
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" name="personelyukle" class="btn btn-primary btn-lg">Yükle</button>
                        </div>
                    </form>
                    <!-- Bilgilendirme Metni -->
                    <div class="alert alert-info mt-4">
                        <h6>Excel Dosyası Hazırlama Bilgileri</h6>
                        <p>Lütfen Excel dosyanızı aşağıdaki kolon şartlarına uygun şekilde hazırlayın. Dosya <strong>.xlsx</strong> formatında olmalı ve tam 15 kolon içermelidir.</p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Kolon Adı</th>
                                    <th>Şartlar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>T.C. Kimlik No</td>
                                    <td>11 haneli sayı (örn. 21334455678), metin formatında, önde sıfır olabilir.</td>
                                </tr>
                                <tr>
                                    <td>Ad Soyad</td>
                                    <td>Boş olamaz, metin (örn. Emre Can).</td>
                                </tr>
                                <tr>
                                    <td>Cinsiyet</td>
                                    <td>Sadece Erkek veya Kadın.</td>
                                </tr>
                                <tr>
                                    <td>Doğum Tarihi</td>
                                    <td>YYYY-MM-DD formatında (örn. 1987-09-10).</td>
                                </tr>
                                <tr>
                                    <td>Telefon</td>
                                    <td>11 haneli, 05 ile başlar (örn. 05371233456), metin formatında.</td>
                                </tr>
                                <tr>
                                    <td>E-mail</td>
                                    <td>Geçerli e-posta formatı (örn. emre.can@example.com).</td>
                                </tr>
                                <tr>
                                    <td>Adres</td>
                                    <td>Boş olamaz, metin (örn. İstanbul, Esenyurt).</td>
                                </tr>
                                <tr>
                                    <td>Firma Adı</td>
                                    <td>Sistemdeki firmalardan biri (örn. Yükselen Yapı A.Ş.).</td>
                                </tr>
                                <tr>
                                    <td>Meslek Adı</td>
                                    <td>Sistemdeki mesleklerden biri (örn. İnşaat Ustası).</td>
                                </tr>
                                <tr>
                                    <td>İşe Giriş Tarihi</td>
                                    <td>YYYY-MM-DD formatında (örn. 2021-06-15).</td>
                                </tr>
                                <tr>
                                    <td>İşe Giriş Eğitimi Var mı?</td>
                                    <td>Sadece Evet veya Hayır.</td>
                                </tr>
                                <tr>
                                    <td>Operatörlük Belgesi Var mı?</td>
                                    <td>Sadece Evet veya Hayır.</td>
                                </tr>
                                <tr>
                                    <td>Mesleki Yeterlilik Belgesi Var mı?</td>
                                    <td>Sadece Evet veya Hayır.</td>
                                </tr>
                                <tr>
                                    <td>Sağlık Tetkikleri Var mı?</td>
                                    <td>Sadece Evet veya Hayır.</td>
                                </tr>
                                <tr>
                                    <td>Sağlık Tetkikleri Tarihi</td>
                                    <td>Evet ise YYYY-MM-DD formatında (örn. 2024-09-20), Hayır ise boş.</td>
                                </tr>
                            </tbody>
                        </table>
                        <p><strong>Not:</strong> T.C. Kimlik No ve Telefon kolonlarını Excel'de "Metin" formatına ayarlayın, önde sıfırların kaybolmasını önlemek için. Firma ve Meslek adları sistemde tanımlı olmalıdır.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>