-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 04 Haz 2025, 18:49:03
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `isg_takip`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aracli_kazalar`
--

CREATE TABLE `aracli_kazalar` (
  `id` int(11) NOT NULL,
  `arac_id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `is_kazasi_tip_id` int(11) NOT NULL,
  `yaralanma_durumu_id` int(11) NOT NULL,
  `yaralanma_tip_id` int(11) NOT NULL,
  `kaza_aciklamasi` text NOT NULL,
  `is_kazasi_tarihi` datetime NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `aracli_kazalar`
--

INSERT INTO `aracli_kazalar` (`id`, `arac_id`, `tc_kimlik`, `is_kazasi_tip_id`, `yaralanma_durumu_id`, `yaralanma_tip_id`, `kaza_aciklamasi`, `is_kazasi_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(4, 14, '65778899012', 12, 3, 5, 'Freni boşalan araç ile duvara yokuş aşağı giderek çarptı.', '2025-05-23 18:53:00', 2, '2025-05-23 18:53:52'),
(106, 3, '87990011234', 1, 2, 1, 'Otomobil ile yüksekten düşen malzeme çarpıştı, sürücü hafif yaralandı.', '2024-06-15 14:30:00', 1, '2024-06-15 14:35:00'),
(107, 10, '87990011234', 2, 1, 6, 'Ekskavatör çalışırken elektrik kablosuna çarptı, yaralanma olmadı.', '2024-07-10 09:15:00', 2, '2024-07-10 09:20:00'),
(108, 13, '87990011234', 3, 3, 2, 'Otomobil şantiyede el aleti düşmesi sonucu hasar gördü, sürücü orta derecede yaralandı.', '2024-08-05 11:00:00', 1, '2024-08-05 11:05:00'),
(109, 12, '87990011234', 1, 1, 6, 'Forklift üzerine malzeme düştü, sürücü yara almadı.', '2024-09-20 16:45:00', 2, '2024-09-20 16:50:00'),
(110, 13, '87990011234', 5, 2, 4, 'Otomobil kaygan zeminde kaydı, sürücü hafif ezilme yaralanması geçirdi.', '2024-10-12 08:30:00', 1, '2024-10-12 08:35:00'),
(111, 14, '87990011234', 1, 3, 5, 'Kamyon şantiyede toprak kayması sonucu devrildi, sürücü ağır yaralandı.', '2024-11-01 13:20:00', 2, '2024-11-01 13:25:00'),
(112, 3, '87990011234', 2, 3, 1, 'Otomobil kafa travması ile sonuçlanan çarpışma yaşadı.', '2024-12-25 17:10:00', 1, '2024-12-25 17:15:00'),
(113, 10, '87990011234', 3, 1, 6, 'Ekskavatör başka bir araca çarptı, yaralanma olmadı.', '2025-01-15 10:00:00', 2, '2025-01-15 10:05:00'),
(114, 16, '87990011234', 2, 2, 2, 'Otomobil başka bir araca çarptı, sürücü hafif kesik yaralanması geçirdi.', '2025-02-10 12:30:00', 1, '2025-02-10 12:35:00'),
(115, 12, '87990011234', 2, 3, 4, 'Forklift yüksekten düşen nesneyle çarpıştı, sürücü orta derecede ezildi.', '2025-03-05 15:40:00', 2, '2025-03-05 15:45:00'),
(116, 13, '19721811437', 2, 1, 6, 'Otomobil elektrik direğine çarptı, yaralanma olmadı.', '2025-04-20 09:50:00', 1, '2025-04-20 09:55:00'),
(117, 14, '87990011234', 3, 2, 3, 'Kamyon şantiyede el aleti ile kaza yaptı, sürücü hafif yanık geçirdi.', '2025-05-01 11:25:00', 2, '2025-05-01 11:30:00'),
(118, 3, '87990011234', 3, 3, 5, 'Otomobil üzerine ağır malzeme düştü, sürücü ağır yaralandı.', '2025-05-10 14:00:00', 1, '2025-05-10 14:05:00'),
(119, 10, '87990011234', 1, 1, 6, 'Ekskavatör kaygan zeminde kaydı, yaralanma olmadı.', '2025-05-15 16:20:00', 2, '2025-05-15 16:25:00'),
(120, 13, '25867362266', 1, 3, 1, 'Otomobil toprak kayması sonucu hasar gördü, sürücü orta derecede kırık geçirdi.', '2025-05-20 08:10:00', 1, '2025-05-20 08:15:00'),
(121, 12, '25867362266', 1, 2, 2, 'Forklift kafa travması ile sonuçlanan bir çarpışma yaşadı.', '2025-05-25 10:30:00', 2, '2025-05-25 10:35:00'),
(122, 13, '25867362266', 1, 1, 6, 'Otomobil şantiyede başka bir araca çarptı, yaralanma olmadı.', '2025-05-27 12:15:00', 1, '2025-05-27 12:20:00'),
(123, 14, '25867362266', 1, 2, 5, 'Kamyon başka bir araca çarptı, sürücü hafif çarpma yaralanması geçirdi.', '2025-05-28 09:00:00', 2, '2025-05-28 09:05:00'),
(124, 3, '25867362266', 1, 3, 4, 'Otomobil yüksekten düşen malzeme ile çarpıştı, sürücü orta derecede ezildi.', '2025-05-28 15:45:00', 1, '2025-05-28 15:50:00'),
(125, 10, '25867362266', 2, 1, 6, 'Ekskavatör elektrik kablosuna çarptı, yaralanma olmadı.', '2025-05-29 07:30:00', 2, '2025-05-29 07:35:00'),
(162, 3, '11122233355', 1, 2, 1, 'Araç manevra yaparken düşük hızda bir direğe çarptı.', '2024-01-15 09:30:00', 2, '2025-05-29 01:42:54'),
(163, 10, '45892975001', 2, 2, 2, 'Araç fren tutmaması sonucu başka bir araca çarptı.', '2024-02-20 14:10:00', 2, '2025-05-29 01:42:54'),
(164, 13, '10702966706', 3, 3, 3, 'Geri geri gelirken kazaya neden oldu.', '2024-03-12 11:20:00', 1, '2025-05-29 01:42:54'),
(165, 12, '12275304637', 4, 3, 4, 'Yük taşıyan araç dönüşte devrildi.', '2024-04-07 17:45:00', 1, '2025-05-29 01:42:54'),
(166, 13, '15635475958', 5, 3, 5, 'Yokuşta fren boşalması sonucu kaza meydana geldi.', '2024-05-29 08:10:00', 2, '2025-05-29 01:42:54'),
(167, 14, '25252525252', 9, 2, 2, 'Toprak kayması sonucu araç kontrolden çıktı.', '2024-06-21 13:30:00', 2, '2025-05-29 01:42:54'),
(168, 3, '25252525252', 11, 2, 1, 'Araç kontrolsüz şekilde başka bir personeli çarptı.', '2024-07-18 12:00:00', 1, '2025-05-29 01:42:54'),
(169, 10, '15635475958', 12, 3, 4, 'Kısa mesafede çarpışma sonucu yaralanma yaşandı.', '2024-08-10 16:50:00', 2, '2025-05-29 01:42:54'),
(170, 15, '12275304637', 2, 3, 1, 'Elektrik arızası sonucu yangın ve çarpma.', '2024-09-22 18:15:00', 1, '2025-05-29 01:42:54'),
(171, 12, '25252525252', 1, 2, 2, 'Sürücünün ani frenlemesi sonucu savrulma.', '2024-10-11 07:55:00', 2, '2025-05-29 01:42:54'),
(172, 13, '25252525252', 4, 2, 3, 'Araca malzeme yüklenirken denge bozuldu.', '2024-11-19 14:05:00', 1, '2025-05-29 01:42:54'),
(173, 14, '11122233355', 3, 3, 4, 'Seyir halindeyken lastik patlaması sonucu devrilme.', '2024-12-05 10:20:00', 2, '2025-05-29 01:42:54');

--
-- Tetikleyiciler `aracli_kazalar`
--
DELIMITER $$
CREATE TRIGGER `before_delete_aracli_kazalar` BEFORE DELETE ON `aracli_kazalar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_aracli_kazalar` (
        `arac_id`, `tc_kimlik`, `is_kazasi_tip_id`, `yaralanma_durumu_id`, 
        `yaralanma_tip_id`, `kaza_aciklamasi`, `is_kazasi_tarihi`, `olusturan_kullanici_id`, 
        `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
         OLD.`arac_id`, OLD.`tc_kimlik`, OLD.`is_kazasi_tip_id`, OLD.`yaralanma_durumu_id`, 
        OLD.`yaralanma_tip_id`, OLD.`kaza_aciklamasi`, OLD.`is_kazasi_tarihi`, OLD.`kullanici_id`, 
        OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_aracli_kazalar` BEFORE UPDATE ON `aracli_kazalar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_aracli_kazalar` (
        `arac_id`, `tc_kimlik`, `is_kazasi_tip_id`, `yaralanma_durumu_id`, 
        `yaralanma_tip_id`, `kaza_aciklamasi`, `is_kazasi_tarihi`, `olusturan_kullanici_id`, 
        `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
        OLD.`arac_id`, OLD.`tc_kimlik`, OLD.`is_kazasi_tip_id`, OLD.`yaralanma_durumu_id`, 
        OLD.`yaralanma_tip_id`, OLD.`kaza_aciklamasi`, OLD.`is_kazasi_tarihi`, OLD.`kullanici_id`, 
        OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `arac_bilgi`
--

CREATE TABLE `arac_bilgi` (
  `arac_id` int(11) NOT NULL,
  `plaka_no` varchar(20) DEFAULT NULL,
  `arac_tipi_id` int(11) NOT NULL,
  `marka_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `uretim_yili` year(4) DEFAULT NULL,
  `firma_id` int(11) NOT NULL,
  `arac_durum_id` int(11) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `arac_bilgi`
--

INSERT INTO `arac_bilgi` (`arac_id`, `plaka_no`, `arac_tipi_id`, `marka_id`, `model_id`, `uretim_yili`, `firma_id`, `arac_durum_id`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(3, '38 AKP 647', 2, 1, 11, '2022', 2, 1, 2, '2025-05-07 10:37:35'),
(10, '35 CBD 457', 3, 4, 17, '2022', 3, 1, 2, '2025-05-16 10:01:38'),
(12, '35 UD 205', 4, 6, 25, '2017', 1, 1, 14, '2025-05-16 12:17:31'),
(13, '35 CHP 355', 1, 2, 13, '2022', 3, 1, 2, '2025-05-22 07:02:54'),
(14, '35 LKP 4125', 2, 9, 30, '2018', 2, 1, 2, '2025-05-23 18:22:36'),
(15, '44 KML 552', 7, 2, 34, '2023', 5, 1, 14, '2025-05-28 22:22:18'),
(16, '22 DMD 852', 1, 10, 32, '2023', 2, 1, 14, '2025-05-29 00:04:16'),
(17, '05 ADM 542', 4, 4, 19, '2016', 1, 1, 14, '2025-05-29 00:27:47');

--
-- Tetikleyiciler `arac_bilgi`
--
DELIMITER $$
CREATE TRIGGER `before_delete_arac_bilgi` BEFORE DELETE ON `arac_bilgi` FOR EACH ROW BEGIN
    INSERT INTO `silinen_arac_bilgi` (
        `arac_id`, `plaka_no`, `arac_tipi_id`, `marka_id`, `model_id`, `uretim_yili`, 
        `firma_id`, `arac_durum_id`, `olusturan_kullanici_id`, `veri_giris_tarihi`, 
        `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
        OLD.`arac_id`, OLD.`plaka_no`, OLD.`arac_tipi_id`, OLD.`marka_id`, OLD.`model_id`, 
        OLD.`uretim_yili`, OLD.`firma_id`, OLD.`arac_durum_id`, OLD.`kullanici_id`, 
        OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_arac_bilgi` BEFORE UPDATE ON `arac_bilgi` FOR EACH ROW BEGIN
    INSERT INTO `silinen_arac_bilgi` (
        `arac_id`, `plaka_no`, `arac_tipi_id`, `marka_id`, `model_id`, `uretim_yili`, 
        `firma_id`, `arac_durum_id`, `olusturan_kullanici_id`, `veri_giris_tarihi`, 
        `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
        OLD.`arac_id`, OLD.`plaka_no`, OLD.`arac_tipi_id`, OLD.`marka_id`, OLD.`model_id`, 
        OLD.`uretim_yili`, OLD.`firma_id`, OLD.`arac_durum_id`, OLD.`kullanici_id`, 
        OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `arac_muayene`
--

CREATE TABLE `arac_muayene` (
  `muayene_id` int(11) NOT NULL,
  `arac_id` int(11) NOT NULL,
  `muayene_tarihi` datetime NOT NULL,
  `muayeneden_gecti_mi` tinyint(1) DEFAULT 0,
  `muayene_gecerlilik_tarihi` datetime DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `arac_muayene`
--

INSERT INTO `arac_muayene` (`muayene_id`, `arac_id`, `muayene_tarihi`, `muayeneden_gecti_mi`, `muayene_gecerlilik_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(8, 10, '2025-05-06 10:01:00', 1, '2026-05-06 10:01:00', 2, '2025-05-16 10:21:12'),
(10, 12, '2025-05-28 12:17:00', 1, '2026-05-28 12:17:00', 14, '2025-05-16 12:17:31'),
(11, 13, '2024-05-25 17:01:00', 1, '2025-05-25 17:01:00', 2, '2025-05-22 07:02:54'),
(12, 14, '2025-05-23 18:22:00', 1, '2026-02-23 18:22:00', 2, '2025-05-23 18:22:36'),
(13, 15, '2025-01-09 22:22:00', 1, '2025-07-09 22:22:00', 14, '2025-05-28 22:22:18'),
(14, 16, '2024-05-27 00:03:00', 1, '2025-05-27 00:03:00', 14, '2025-05-29 00:04:16'),
(15, 17, '2024-06-20 00:27:00', 1, '2025-06-20 00:27:00', 14, '2025-05-29 00:27:47');

--
-- Tetikleyiciler `arac_muayene`
--
DELIMITER $$
CREATE TRIGGER `before_delete_arac_muayene` BEFORE DELETE ON `arac_muayene` FOR EACH ROW BEGIN
    INSERT INTO `silinen_arac_muayene` (
        `muayene_id`, `arac_id`, `muayene_tarihi`, `muayeneden_gecti_mi`, 
        `muayene_gecerlilik_tarihi`, `olusturan_kullanici_id`, `veri_giris_tarihi`, 
        `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
        OLD.`muayene_id`, OLD.`arac_id`, OLD.`muayene_tarihi`, OLD.`muayeneden_gecti_mi`, 
        OLD.`muayene_gecerlilik_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, 
        @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_arac_muayene` BEFORE UPDATE ON `arac_muayene` FOR EACH ROW BEGIN
    INSERT INTO `silinen_arac_muayene` (
        `muayene_id`, `arac_id`, `muayene_tarihi`, `muayeneden_gecti_mi`, 
        `muayene_gecerlilik_tarihi`, `olusturan_kullanici_id`, `veri_giris_tarihi`, 
        `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
        OLD.`muayene_id`, OLD.`arac_id`, OLD.`muayene_tarihi`, OLD.`muayeneden_gecti_mi`, 
        OLD.`muayene_gecerlilik_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, 
        @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `arac_operator_atama`
--

CREATE TABLE `arac_operator_atama` (
  `id` int(11) NOT NULL,
  `arac_id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `atama_tarihi` datetime NOT NULL,
  `gorev_sonu_tarihi` datetime DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `arac_operator_atama`
--

INSERT INTO `arac_operator_atama` (`id`, `arac_id`, `tc_kimlik`, `atama_tarihi`, `gorev_sonu_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(22, 14, '65778899012', '2025-05-23 18:46:00', '2026-01-23 18:46:00', 2, '2025-05-23 18:46:42'),
(23, 14, '32445566789', '2025-05-23 18:47:00', '2026-08-23 18:47:00', 1, '2025-05-23 18:47:26'),
(25, 15, '70433154100', '2025-05-21 22:22:00', NULL, 14, '2025-05-28 22:22:18'),
(27, 17, '77725228728', '2025-05-29 00:27:00', NULL, 14, '2025-05-29 00:27:47');

--
-- Tetikleyiciler `arac_operator_atama`
--
DELIMITER $$
CREATE TRIGGER `before_delete_arac_operator_atama` BEFORE DELETE ON `arac_operator_atama` FOR EACH ROW BEGIN
    INSERT INTO `silinen_arac_operator_atama` (
        `arac_id`, `tc_kimlik`, `atama_tarihi`, `gorev_sonu_tarihi`, 
        `olusturan_kullanici_id`, `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
        OLD.`arac_id`, OLD.`tc_kimlik`, OLD.`atama_tarihi`, OLD.`gorev_sonu_tarihi`, 
        OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_arac_operator_atama` BEFORE INSERT ON `arac_operator_atama` FOR EACH ROW BEGIN
    IF NEW.gorev_sonu_tarihi = '0000-00-00 00:00:00' THEN
        SET NEW.gorev_sonu_tarihi = NULL;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_arac_operator_atama` BEFORE UPDATE ON `arac_operator_atama` FOR EACH ROW BEGIN
    INSERT INTO `silinen_arac_operator_atama` (
        `arac_id`, `tc_kimlik`, `atama_tarihi`, `gorev_sonu_tarihi`, 
        `olusturan_kullanici_id`, `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
       OLD.`arac_id`, OLD.`tc_kimlik`, OLD.`atama_tarihi`, OLD.`gorev_sonu_tarihi`, 
        OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `duyurular`
--

CREATE TABLE `duyurular` (
  `id` int(11) NOT NULL,
  `duyuru_basligi` varchar(255) NOT NULL,
  `duyuru_icerigi` text NOT NULL,
  `duyuru_tarihi` datetime NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `duyurular`
--

INSERT INTO `duyurular` (`id`, `duyuru_basligi`, `duyuru_icerigi`, `duyuru_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(4, 'Sigara Yasağı', 'Artık koridorlarda sigara içilmeyecek. Bilginize', '2025-05-19 12:36:00', 14, '2025-05-29 00:39:53'),
(6, 'Baret Kullanımı', 'Baret Kullanımına dikkat edelim.', '2025-05-23 18:00:00', 14, '2025-05-29 00:39:47'),
(7, 'B Eğitimi', 'B Eğitimi yapılacaktır. Herkesin bilgisine.', '2025-05-23 19:14:00', 14, '2025-05-29 00:39:50'),
(8, 'C Eğitimi', 'C eğitimi yapılacaktır.', '2025-05-29 00:39:00', 14, '2025-05-29 00:39:42'),
(9, 'Şirket Genel Müdür Ziyareti', 'Şirketimizin Genel Müdürü Hakkı Orhan yarın inşaat alanımızı ve çalışanlarımızı ziyaret edecektir.', '2025-05-29 00:36:00', 14, '2025-05-29 00:36:50');

--
-- Tetikleyiciler `duyurular`
--
DELIMITER $$
CREATE TRIGGER `before_delete_duyurular` BEFORE DELETE ON `duyurular` FOR EACH ROW BEGIN
  INSERT INTO `silinen_duyurular` (
    `duyuru_basligi`, `duyuru_icerigi`, `duyuru_tarihi`,
    `kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
  ) VALUES (
    OLD.`duyuru_basligi`, OLD.`duyuru_icerigi`, OLD.`duyuru_tarihi`,
    OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_duyurular` BEFORE UPDATE ON `duyurular` FOR EACH ROW BEGIN
  INSERT INTO `silinen_duyurular` (
    `duyuru_basligi`, `duyuru_icerigi`, `duyuru_tarihi`,
    `kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
  ) VALUES (
    OLD.`duyuru_basligi`, OLD.`duyuru_icerigi`, OLD.`duyuru_tarihi`,
    OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP
  );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_arac_durumlari`
--

CREATE TABLE `hazir_arac_durumlari` (
  `arac_durum_id` int(11) NOT NULL,
  `arac_durum_adi` varchar(50) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_arac_durumlari`
--

INSERT INTO `hazir_arac_durumlari` (`arac_durum_id`, `arac_durum_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Aktif', 1, '2025-05-04 10:00:00'),
(2, 'Bakımda', 1, '2025-05-04 10:00:00'),
(3, 'Arızalı', 1, '2025-05-04 10:00:00');

--
-- Tetikleyiciler `hazir_arac_durumlari`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_arac_durumlari` BEFORE DELETE ON `hazir_arac_durumlari` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_arac_durumlari` (
        `arac_durum_id`, `arac_durum_adi`, `olusturan_kullanici_id`, `veri_giris_tarihi`, 
        `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
        OLD.`arac_durum_id`, OLD.`arac_durum_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, 
        @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_arac_durumlari` BEFORE UPDATE ON `hazir_arac_durumlari` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_arac_durumlari` (
        `arac_durum_id`, `arac_durum_adi`, `olusturan_kullanici_id`, `veri_giris_tarihi`, 
        `silen_kullanici_id`, `silinme_tarihi`
    ) VALUES (
        OLD.`arac_durum_id`, OLD.`arac_durum_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, 
        @silen_kullanici_id, CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_arac_tipleri`
--

CREATE TABLE `hazir_arac_tipleri` (
  `arac_tipi_id` int(11) NOT NULL,
  `arac_tipi_adi` varchar(100) NOT NULL,
  `muayene_gecerlilik_suresi_ay` int(11) DEFAULT 12,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_arac_tipleri`
--

INSERT INTO `hazir_arac_tipleri` (`arac_tipi_id`, `arac_tipi_adi`, `muayene_gecerlilik_suresi_ay`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Otomobil', 12, 1, '2025-05-06 07:11:21'),
(2, 'Kamyon', 9, 1, '2025-05-06 07:11:21'),
(3, 'Ekskavatör', 12, 1, '2025-05-06 07:11:21'),
(4, 'Forklift', 12, 1, '2025-05-06 07:14:01'),
(5, 'Mobil Vinç', 12, 1, '2025-05-06 07:14:01'),
(6, 'Yükleyici', 12, 1, '2025-05-06 07:14:01'),
(7, 'Otobüs', 6, 1, '2025-05-06 07:14:01'),
(8, 'SUV', 12, 1, '2025-05-06 07:14:01'),
(9, 'Pick-Up', 12, 1, '2025-05-06 07:14:01');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_belgeler`
--

CREATE TABLE `hazir_belgeler` (
  `belge_id` int(11) NOT NULL,
  `belge_adi` varchar(255) NOT NULL,
  `kullanici_id` int(11) DEFAULT 1,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_belgeler`
--

INSERT INTO `hazir_belgeler` (`belge_id`, `belge_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'İskele Kurulum Eğitimi Sertifikası', 1, '2025-04-29 15:30:10'),
(2, 'Yangınla Mücadele Eğitimi Sertifikası', 1, '2025-04-29 15:30:10'),
(3, 'Çevre Koruma Bilinçlendirme Belgesi', 1, '2025-04-29 15:30:10'),
(4, 'Risk Değerlendirme Eğitimi Sertifikası', 1, '2025-04-29 15:30:10'),
(5, 'Kişisel Koruyucu Donanım (KKD) Eğitimi Sertifikası', 1, '2025-04-29 15:30:10'),
(9, 'C1 İngilizce', 1, '2025-04-29 15:30:10'),
(10, 'Hijyen Belgesi', 1, '2025-04-29 15:30:10'),
(12, 'Kalıp Atma Teknikleri Sertifikası', 1, '2025-05-05 11:02:27'),
(13, 'İlk Yardım Sertifikası', 1, '2025-05-28 18:15:06'),
(14, 'Yüksekte Çalışma Sertifikası', 1, '2025-05-28 18:15:06');

--
-- Tetikleyiciler `hazir_belgeler`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_belgeler` BEFORE DELETE ON `hazir_belgeler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_belgeler` (
        `belge_id`, `belge_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`belge_id`, OLD.`belge_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_belgeler` BEFORE UPDATE ON `hazir_belgeler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_belgeler` (
        `belge_id`, `belge_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`belge_id`, OLD.`belge_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_ehliyetler`
--

CREATE TABLE `hazir_ehliyetler` (
  `ehliyet_id` int(11) NOT NULL,
  `ehliyet_adi` varchar(255) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_ehliyetler`
--

INSERT INTO `hazir_ehliyetler` (`ehliyet_id`, `ehliyet_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'G Sınıfı - İş Makinesi Operatörü', 1, '2025-04-29 15:30:10'),
(2, 'B Sınıfı - Otomobil', 1, '2025-04-29 15:30:10'),
(3, 'C Sınıfı - Kamyon', 1, '2025-04-29 15:30:10'),
(4, 'D Sınıfı - Otobüs', 1, '2025-04-29 15:30:10'),
(5, 'F Sınıfı - Traktör', 1, '2025-04-29 15:30:10');

--
-- Tetikleyiciler `hazir_ehliyetler`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_ehliyetler` BEFORE DELETE ON `hazir_ehliyetler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_ehliyetler` (
        `ehliyet_id`, `ehliyet_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`ehliyet_id`, OLD.`ehliyet_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_ehliyetler` BEFORE UPDATE ON `hazir_ehliyetler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_ehliyetler` (
        `ehliyet_id`, `ehliyet_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`ehliyet_id`, OLD.`ehliyet_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_firmalar`
--

CREATE TABLE `hazir_firmalar` (
  `firma_id` int(11) NOT NULL,
  `firma_adi` varchar(255) NOT NULL,
  `sektor` varchar(100) DEFAULT NULL,
  `adres` text DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT 1,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_firmalar`
--

INSERT INTO `hazir_firmalar` (`firma_id`, `firma_adi`, `sektor`, `adres`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Yükselen Yapı A.Ş.', 'İnşaat', 'İstanbul, Ataşehir, Barbaros Mah. No:23', 1, '2025-04-29 15:30:10'),
(2, 'Betonel İnşaat Ltd. Şti.', 'İnşaat', 'Ankara, Çankaya, Mustafa Kemal Mah. 215. Cadde', 1, '2025-04-29 15:30:10'),
(3, 'Mega Tünel ve Yol A.Ş.', 'Altyapı / İnşaat', 'İzmir, Bornova, Erzene Mah. No:12', 1, '2025-04-29 15:30:10'),
(4, 'Kalyon İnşaat Ltd. Şti.', 'İskele / Kalıp', 'Kocaeli, Gebze, Organize Sanayi Bölgesi', 1, '2025-04-29 15:30:10'),
(5, 'DAP Yapı ', 'İnşaat Taahhüt', 'Bursa, Nilüfer, Görükle Mah. No:56', 1, '2025-04-29 15:30:10'),
(6, 'Akyol İnşaat Ltd. Şti.', 'İnşaat', 'İzmir', 1, '2025-04-29 15:30:10'),
(7, 'İzmir Enka İnşaat Ltd. Şti.', 'İnşaat', 'İzmir', 1, '2025-05-01 09:02:29'),
(8, 'Ağaoğlu İnşaat Ltd. Şti.', 'İnşaat', 'İstanbul', 1, '2025-05-28 18:22:14'),
(9, 'Öz Altın İnşaat Ltd. Şti.', 'İnşaat', 'Ankara', 1, '2025-05-28 18:22:14'),
(10, 'Limak İnşaat Ltd. Şti.', 'İnşaat', 'Manisa', 1, '2025-05-28 18:22:53');

--
-- Tetikleyiciler `hazir_firmalar`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_firmalar` BEFORE DELETE ON `hazir_firmalar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_firmalar` (
        `firma_id`, `firma_adi`, `sektor`, `adres`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`firma_id`, OLD.`firma_adi`, OLD.`sektor`, OLD.`adres`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_firmalar` BEFORE UPDATE ON `hazir_firmalar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_firmalar` (
        `firma_id`, `firma_adi`, `sektor`, `adres`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`firma_id`, OLD.`firma_adi`, OLD.`sektor`, OLD.`adres`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_is_kazalari`
--

CREATE TABLE `hazir_is_kazalari` (
  `is_kazasi_tip_id` int(11) NOT NULL,
  `is_kazasi_tipi_adi` varchar(255) NOT NULL,
  `kullanici_id` int(11) DEFAULT 1,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_is_kazalari`
--

INSERT INTO `hazir_is_kazalari` (`is_kazasi_tip_id`, `is_kazasi_tipi_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Yüksekten Düşme', 1, '2025-04-29 15:30:10'),
(2, 'Elektrik Çarpması', 1, '2025-04-29 15:30:10'),
(3, 'El Aletiyle Yaralanma', 1, '2025-04-29 15:30:10'),
(4, 'Malzeme Düşmesi', 1, '2025-04-29 15:30:10'),
(5, 'Kayma/Düşme', 1, '2025-04-29 15:30:10'),
(9, 'Toprak Kayması', 1, '2025-04-29 15:30:10'),
(10, 'Kafa Tramvası', 1, '2025-04-29 15:30:10'),
(11, 'Araç Çarpması', 1, '2025-05-05 11:02:27'),
(12, 'Çökme ', 2, '2025-05-15 16:13:52');

--
-- Tetikleyiciler `hazir_is_kazalari`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_is_kazalari` BEFORE DELETE ON `hazir_is_kazalari` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_is_kazalari` (
        `is_kazasi_tip_id`, `is_kazasi_tipi_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`is_kazasi_tip_id`, OLD.`is_kazasi_tipi_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_is_kazalari` BEFORE UPDATE ON `hazir_is_kazalari` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_is_kazalari` (
        `is_kazasi_tip_id`, `is_kazasi_tipi_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`is_kazasi_tip_id`, OLD.`is_kazasi_tipi_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_markalar`
--

CREATE TABLE `hazir_markalar` (
  `marka_id` int(11) NOT NULL,
  `marka_adi` varchar(50) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_markalar`
--

INSERT INTO `hazir_markalar` (`marka_id`, `marka_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Volvo', 1, '2025-05-06 07:14:34'),
(2, 'Mercedes-Benz', 1, '2025-05-06 07:14:34'),
(3, 'Scania', 1, '2025-05-06 07:14:34'),
(4, 'Caterpillar', 1, '2025-05-06 07:14:34'),
(5, 'Ford', 1, '2025-05-06 07:14:34'),
(6, 'Toyota', 1, '2025-05-06 07:14:34'),
(7, 'Hyundai', 1, '2025-05-06 07:14:34'),
(8, 'Hitachi', 1, '2025-05-06 07:14:34'),
(9, 'MAN', 1, '2025-05-06 07:14:34'),
(10, 'Renault', 1, '2025-05-06 07:14:34');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_meslekler`
--

CREATE TABLE `hazir_meslekler` (
  `meslek_id` int(11) NOT NULL,
  `meslek_adi` varchar(100) NOT NULL,
  `kullanici_id` int(11) DEFAULT 1,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_meslekler`
--

INSERT INTO `hazir_meslekler` (`meslek_id`, `meslek_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'İnşaat Ustası', 1, '2025-04-29 15:30:10'),
(2, 'İskele Kurulum Elemanı', 1, '2025-04-29 15:30:10'),
(3, 'Şantiye Şefi', 1, '2025-04-29 15:30:10'),
(4, 'İnşaat Mühendisi', 1, '2025-04-29 15:30:10'),
(5, 'Vinç Operatörü', 1, '2025-04-29 15:30:10'),
(6, 'Demir Bağcı', 1, '2025-04-29 15:30:10'),
(7, 'Kalıpçı', 1, '2025-04-29 15:30:10'),
(8, 'Elektrik Tesisatçısı', 1, '2025-04-29 15:30:10'),
(9, 'Kaynakçı', 1, '2025-04-29 15:30:10'),
(10, 'Baret Sorumlusu', 1, '2025-04-29 15:30:10'),
(11, 'Güvenlik', 1, '2025-04-29 15:30:10'),
(12, 'Boyacı', 1, '2025-05-01 09:02:29');

--
-- Tetikleyiciler `hazir_meslekler`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_meslekler` BEFORE DELETE ON `hazir_meslekler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_meslekler` (
        `meslek_id`, `meslek_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`meslek_id`, OLD.`meslek_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_meslekler` BEFORE UPDATE ON `hazir_meslekler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_meslekler` (
        `meslek_id`, `meslek_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`meslek_id`, OLD.`meslek_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_modeller`
--

CREATE TABLE `hazir_modeller` (
  `model_id` int(11) NOT NULL,
  `marka_id` int(11) NOT NULL,
  `arac_tipi_id` int(11) NOT NULL,
  `model_adi` varchar(50) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_modeller`
--

INSERT INTO `hazir_modeller` (`model_id`, `marka_id`, `arac_tipi_id`, `model_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 1, 1, 'Volvo XC90', 1, '2025-05-06 07:11:21'),
(2, 1, 2, 'Volvo FH', 1, '2025-05-06 07:11:21'),
(3, 1, 3, 'Volvo EC950', 1, '2025-05-06 07:11:21'),
(4, 2, 2, 'Scania R450', 1, '2025-05-06 07:11:21'),
(9, 1, 1, 'Volvo S60', 1, '2025-05-06 07:16:11'),
(10, 1, 2, 'Volvo FH16', 1, '2025-05-06 07:16:11'),
(11, 1, 3, 'Volvo EC950F', 1, '2025-05-06 07:16:11'),
(12, 1, 5, 'Volvo L90H', 1, '2025-05-06 07:16:11'),
(13, 2, 1, 'Mercedes C200', 1, '2025-05-06 07:16:11'),
(14, 2, 2, 'Mercedes Actros', 1, '2025-05-06 07:16:11'),
(15, 2, 7, 'Mercedes Tourismo', 1, '2025-05-06 07:16:11'),
(16, 3, 2, 'Scania R500', 1, '2025-05-06 07:16:11'),
(17, 4, 3, 'CAT 320 GC', 1, '2025-05-06 07:16:11'),
(18, 4, 6, 'CAT 950M', 1, '2025-05-06 07:16:11'),
(19, 4, 4, 'CAT DP70N', 1, '2025-05-06 07:16:11'),
(20, 5, 1, 'Ford Focus', 1, '2025-05-06 07:16:11'),
(21, 5, 9, 'Ford Ranger', 1, '2025-05-06 07:16:11'),
(22, 5, 2, 'Ford F-MAX', 1, '2025-05-06 07:16:11'),
(23, 6, 1, 'Toyota Corolla', 1, '2025-05-06 07:16:11'),
(24, 6, 9, 'Toyota Hilux', 1, '2025-05-06 07:16:11'),
(25, 6, 4, 'Toyota 8FD45N', 1, '2025-05-06 07:16:11'),
(26, 7, 1, 'Hyundai Elantra', 1, '2025-05-06 07:16:11'),
(27, 7, 8, 'Hyundai Tucson', 1, '2025-05-06 07:16:11'),
(28, 8, 3, 'Hitachi ZX350LC', 1, '2025-05-06 07:16:11'),
(29, 8, 6, 'Hitachi ZW220-6', 1, '2025-05-06 07:16:11'),
(30, 9, 2, 'MAN TGX', 1, '2025-05-06 07:16:11'),
(31, 9, 7, 'MAN Lion\'s Coach', 1, '2025-05-06 07:16:11'),
(32, 10, 1, 'Renault Megane', 1, '2025-05-06 07:16:11'),
(33, 10, 2, 'Renault Trucks T', 1, '2025-05-06 07:16:11'),
(34, 2, 7, 'Mercedes Trevego', 14, '2025-05-28 22:22:18');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_rapor_durumlari`
--

CREATE TABLE `hazir_rapor_durumlari` (
  `id` int(11) NOT NULL,
  `rapor_durum_adi` varchar(100) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_rapor_durumlari`
--

INSERT INTO `hazir_rapor_durumlari` (`id`, `rapor_durum_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Sorun yok', 1, '2025-05-19 12:04:00'),
(2, 'Sorun devam ediyor', 1, '2025-05-19 12:04:00'),
(3, 'Sorun çözüldü', 1, '2025-05-19 12:04:00');

--
-- Tetikleyiciler `hazir_rapor_durumlari`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_rapor_durumlari` BEFORE DELETE ON `hazir_rapor_durumlari` FOR EACH ROW BEGIN
  INSERT INTO `silinen_hazir_rapor_durumlari` (
    `rapor_durum_adi`, `kullanici_id`, `olusturulma_tarihi`,
    `silen_kullanici_id`, `silinme_tarihi`
  ) VALUES (
     OLD.`rapor_durum_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`,
    @silen_kullanici_id, CURRENT_TIMESTAMP
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_rapor_durumlari` BEFORE UPDATE ON `hazir_rapor_durumlari` FOR EACH ROW BEGIN
  INSERT INTO `silinen_hazir_rapor_durumlari` (
    `rapor_durum_adi`, `kullanici_id`, `olusturulma_tarihi`,
    `silen_kullanici_id`, `silinme_tarihi`
  ) VALUES (
    OLD.`rapor_durum_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`,
    @silen_kullanici_id, CURRENT_TIMESTAMP
  );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_uyarilar`
--

CREATE TABLE `hazir_uyarilar` (
  `uyari_tip_id` int(11) NOT NULL,
  `uyari_tipi_adi` varchar(255) NOT NULL,
  `kullanici_id` int(11) DEFAULT 1,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_uyarilar`
--

INSERT INTO `hazir_uyarilar` (`uyari_tip_id`, `uyari_tipi_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Kişisel Koruyucu Ekipman Eksikliği', 1, '2025-04-29 15:30:10'),
(2, 'Çalışma Alanında Düzensizlik', 1, '2025-04-29 15:30:10'),
(3, 'Yüksekte Emniyetsiz Çalışma', 1, '2025-04-29 15:30:10'),
(4, 'Talimatlara Uymama', 1, '2025-04-29 15:30:10'),
(5, 'Sigara İhlali', 1, '2025-04-29 15:30:10'),
(7, 'İzinsiz Giriş', 1, '2025-04-29 15:30:10'),
(8, 'Yakıcı Madde Kullanımı', 1, '2025-05-05 11:02:27');

--
-- Tetikleyiciler `hazir_uyarilar`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_uyarilar` BEFORE DELETE ON `hazir_uyarilar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_uyarilar` (
        `uyari_tip_id`, `uyari_tipi_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`uyari_tip_id`, OLD.`uyari_tipi_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_uyarilar` BEFORE UPDATE ON `hazir_uyarilar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_uyarilar` (
        `uyari_tip_id`, `uyari_tipi_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`uyari_tip_id`, OLD.`uyari_tipi_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_yaralanma_durumlar`
--

CREATE TABLE `hazir_yaralanma_durumlar` (
  `yaralanma_durum_id` int(11) NOT NULL,
  `yaralanma_durum_adi` varchar(255) NOT NULL,
  `kullanici_id` int(11) DEFAULT 1,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_yaralanma_durumlar`
--

INSERT INTO `hazir_yaralanma_durumlar` (`yaralanma_durum_id`, `yaralanma_durum_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Hafif Yaralanma', 1, '2025-04-29 15:30:10'),
(2, 'Orta Derece Yaralanma', 1, '2025-04-29 15:30:10'),
(3, 'Ağır Yaralanma', 1, '2025-04-29 15:30:10'),
(6, 'Ölüm', 1, '2025-05-05 11:02:27');

--
-- Tetikleyiciler `hazir_yaralanma_durumlar`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_yaralanma_durumlar` BEFORE DELETE ON `hazir_yaralanma_durumlar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_yaralanma_durumlar` (
        `yaralanma_durum_id`, `yaralanma_durum_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`yaralanma_durum_id`, OLD.`yaralanma_durum_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_yaralanma_durumlar` BEFORE UPDATE ON `hazir_yaralanma_durumlar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_yaralanma_durumlar` (
        `yaralanma_durum_id`, `yaralanma_durum_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`yaralanma_durum_id`, OLD.`yaralanma_durum_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hazir_yaralanma_tipler`
--

CREATE TABLE `hazir_yaralanma_tipler` (
  `yaralanma_tip_id` int(11) NOT NULL,
  `yaralanma_tipi_adi` varchar(255) NOT NULL,
  `kullanici_id` int(11) DEFAULT 1,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hazir_yaralanma_tipler`
--

INSERT INTO `hazir_yaralanma_tipler` (`yaralanma_tip_id`, `yaralanma_tipi_adi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Kesik', 1, '2025-04-29 15:30:10'),
(2, 'Kırık', 1, '2025-04-29 15:30:10'),
(3, 'Yanık', 1, '2025-04-29 15:30:10'),
(4, 'Ezilme', 1, '2025-04-29 15:30:10'),
(5, 'Şok', 1, '2025-04-29 15:30:10'),
(6, 'Uzuv Kaybı', 1, '2025-05-05 11:02:27');

--
-- Tetikleyiciler `hazir_yaralanma_tipler`
--
DELIMITER $$
CREATE TRIGGER `before_delete_hazir_yaralanma_tipler` BEFORE DELETE ON `hazir_yaralanma_tipler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_yaralanma_tipler` (
        `yaralanma_tip_id`, `yaralanma_tipi_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`yaralanma_tip_id`, OLD.`yaralanma_tipi_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_hazir_yaralanma_tipler` BEFORE UPDATE ON `hazir_yaralanma_tipler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_hazir_yaralanma_tipler` (
        `yaralanma_tip_id`, `yaralanma_tipi_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`yaralanma_tip_id`, OLD.`yaralanma_tipi_adi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `kul_id` int(11) NOT NULL,
  `kul_isim` varchar(100) NOT NULL,
  `kul_mail` varchar(100) NOT NULL,
  `kul_sifre` varchar(100) NOT NULL,
  `verify_token` varchar(255) DEFAULT NULL,
  `email_verified` tinyint(4) DEFAULT 0,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`kul_id`, `kul_isim`, `kul_mail`, `kul_sifre`, `verify_token`, `email_verified`, `olusturan_kullanici_id`, `veri_giris_tarihi`) VALUES
(1, 'Melih Ölmez', 'meliholmez3535@gmail.com', 'b99d193b66a6542917d2b7bee52c2574', NULL, 1, 1, '2025-05-25 19:53:23'),
(2, 'mertcan', 'mertcan@gmail.com', '4d257f05424a530db98bf788c460c920', '', 1, 1, '2025-05-25 19:53:44'),
(14, 'Mertcan Ülbeyi', 'mertcanulbeyi@gmail.com', '4d257f05424a530db98bf788c460c920', NULL, 1, 1, '2025-05-26 17:59:43'),
(15, 'İlyas Yalçın', 'ilyasyalcin@gmail.com', '3be4004478fa1ceb4f4b9256e17000c7', '2d1af85f8077b924bbd15d7bc480106c', 0, 14, '2025-05-29 13:29:58');

--
-- Tetikleyiciler `kullanicilar`
--
DELIMITER $$
CREATE TRIGGER `after_kullanicilar_delete` AFTER DELETE ON `kullanicilar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_kullanicilar` (
        `kul_id`,
        `kul_isim`,
        `kul_mail`,
        `kul_sifre`,
        `olusturan_kullanici_id`,
        `olusturulma_tarihi`,
        `silen_kullanici_id`,
        `silinme_tarihi`
    )
    VALUES (
        OLD.kul_id,
        OLD.kul_isim,
        OLD.kul_mail,
        OLD.kul_sifre,
        OLD.olusturan_kullanici_id,
        OLD.veri_giris_tarihi,
        @silen_kullanici_id,
        CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_kullanicilar_update` AFTER UPDATE ON `kullanicilar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_kullanicilar` (
        `kul_id`,
        `kul_isim`,
        `kul_mail`,
        `kul_sifre`,
        `olusturan_kullanici_id`,
        `olusturulma_tarihi`,
        `silen_kullanici_id`,
        `silinme_tarihi`
    )
    VALUES (
        OLD.kul_id,
        OLD.kul_isim,
        OLD.kul_mail,
        OLD.kul_sifre,
        OLD.olusturan_kullanici_id,
        OLD.veri_giris_tarihi,
        @silen_kullanici_id,
        CURRENT_TIMESTAMP
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_belgeler`
--

CREATE TABLE `personel_belgeler` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `belge_id` int(11) NOT NULL,
  `alinma_tarihi` date DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personel_belgeler`
--

INSERT INTO `personel_belgeler` (`id`, `tc_kimlik`, `belge_id`, `alinma_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(18, '78578578578', 2, '2025-04-18', 1, '2025-04-29 15:30:10'),
(19, '78578578578', 3, '2025-04-18', 1, '2025-04-29 15:30:10'),
(28, '55555555555', 9, '2025-04-10', 1, '2025-04-29 15:30:10'),
(33, '44455566677', 5, '2013-08-10', 1, '2025-04-29 15:30:10'),
(34, '99900011122', 4, '2019-07-25', 1, '2025-04-29 15:30:10'),
(36, '11122233355', 5, '2021-09-15', 1, '2025-04-29 15:30:10'),
(54, '85858585858', 10, '2025-05-02', 2, '2025-05-03 16:18:09'),
(55, '48648648648', 5, '2025-05-01', 2, '2025-05-05 11:02:27'),
(56, '48648648648', 12, '2025-05-01', 2, '2025-05-05 11:02:27'),
(57, '25252525252', 2, '2025-05-19', 2, '2025-05-19 15:37:00'),
(58, '25252525252', 3, '2025-05-19', 2, '2025-05-19 15:37:00'),
(59, '25252525252', 9, '2025-05-19', 2, '2025-05-19 15:37:49'),
(62, '15635475958', 2, '2021-10-24', 2, '2025-05-24 13:54:54'),
(63, '15635475958', 5, '2025-04-29', 2, '2025-05-24 13:54:54'),
(65, '62656265626', 3, '2025-05-13', 2, '2025-05-24 14:03:44'),
(66, '62656265626', 4, '2025-05-06', 2, '2025-05-24 14:03:44'),
(69, '15635475958', 12, '2025-05-26', 2, '2025-05-26 13:16:06'),
(70, '22222222222', 1, '2025-05-01', 14, '2025-05-26 19:08:00'),
(71, '22222222222', 3, '2025-05-05', 14, '2025-05-26 19:08:00');

--
-- Tetikleyiciler `personel_belgeler`
--
DELIMITER $$
CREATE TRIGGER `before_delete_personel_belgeler` BEFORE DELETE ON `personel_belgeler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_belgeler` (
        `tc_kimlik`, `belge_id`, `alinma_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`belge_id`, OLD.`alinma_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_personel_belgeler` BEFORE UPDATE ON `personel_belgeler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_belgeler` (
        `tc_kimlik`, `belge_id`, `alinma_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`belge_id`, OLD.`alinma_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_ehliyetler`
--

CREATE TABLE `personel_ehliyetler` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `ehliyet_id` int(11) NOT NULL,
  `alinma_tarihi` date DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personel_ehliyetler`
--

INSERT INTO `personel_ehliyetler` (`id`, `tc_kimlik`, `ehliyet_id`, `alinma_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(16, '78578578578', 2, '2025-04-16', 1, '2025-04-29 15:30:10'),
(19, '55555555555', 2, '2021-06-08', 1, '2025-04-29 15:30:10'),
(32, '85858585858', 2, '2005-11-10', 2, '2025-05-03 16:18:09'),
(33, '48648648648', 2, '2024-05-05', 2, '2025-05-05 11:02:27'),
(34, '25252525252', 2, '2025-05-19', 2, '2025-05-19 15:37:00'),
(37, '15635475958', 2, '2023-06-24', 2, '2025-05-24 13:54:54'),
(40, '62656265626', 2, '2017-07-26', 2, '2025-05-24 14:03:44'),
(43, '22222222222', 2, '2023-06-23', 14, '2025-05-26 19:08:00');

--
-- Tetikleyiciler `personel_ehliyetler`
--
DELIMITER $$
CREATE TRIGGER `before_delete_personel_ehliyetler` BEFORE DELETE ON `personel_ehliyetler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_ehliyetler` (
        `tc_kimlik`, `ehliyet_id`, `alinma_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`ehliyet_id`, OLD.`alinma_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_personel_ehliyetler` BEFORE UPDATE ON `personel_ehliyetler` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_ehliyetler` (
        `tc_kimlik`, `ehliyet_id`, `alinma_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`ehliyet_id`, OLD.`alinma_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_gerekli_belge`
--

CREATE TABLE `personel_gerekli_belge` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `ise_giris_egitimi_var_mi` tinyint(1) DEFAULT 0,
  `operatorluk_belgesi_var_mi` tinyint(1) DEFAULT 0,
  `mesleki_yeterlilik_belgesi_var_mi` tinyint(1) DEFAULT 0,
  `saglik_tetkikleri_oldu_mu` tinyint(1) DEFAULT 0,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personel_gerekli_belge`
--

INSERT INTO `personel_gerekli_belge` (`id`, `tc_kimlik`, `ise_giris_egitimi_var_mi`, `operatorluk_belgesi_var_mi`, `mesleki_yeterlilik_belgesi_var_mi`, `saglik_tetkikleri_oldu_mu`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(21, '78578578578', 1, 1, 1, 1, 1, '2025-04-29 15:30:10'),
(23, '45645645645', 1, 1, 1, 1, 1, '2025-04-29 15:30:10'),
(24, '78978978978', 1, 1, 1, 1, 1, '2025-04-29 15:30:10'),
(25, '55555555555', 1, 1, 1, 1, 1, '2025-04-29 15:30:10'),
(30, '44455566677', 1, 0, 1, 1, 1, '2025-04-29 15:30:10'),
(31, '99900011122', 1, 0, 1, 1, 1, '2025-04-29 15:30:10'),
(33, '11122233355', 1, 0, 1, 1, 1, '2025-04-29 15:30:10'),
(35, '53535353535', 1, 1, 1, 1, 2, '2025-05-01 09:33:54'),
(39, '85858585858', 1, 0, 1, 0, 2, '2025-05-03 16:18:09'),
(41, '70578578578', 1, 0, 1, 1, 1, '2025-05-03 21:52:31'),
(43, '44435566677', 1, 1, 1, 1, 1, '2025-05-03 21:52:31'),
(44, '21134455678', 1, 1, 1, 1, 1, '2025-05-03 21:52:58'),
(45, '48648648648', 1, 0, 1, 1, 2, '2025-05-05 11:02:27'),
(46, '21334455678', 1, 1, 1, 1, 1, '2025-05-05 11:20:58'),
(47, '32445566789', 1, 0, 1, 1, 1, '2025-05-05 11:20:58'),
(49, '54667788901', 1, 0, 0, 1, 1, '2025-05-05 11:20:58'),
(50, '65778899012', 1, 0, 1, 1, 1, '2025-05-05 11:20:58'),
(52, '87990011234', 1, 1, 1, 0, 1, '2025-05-05 11:20:58'),
(53, '99001122345', 1, 0, 1, 1, 1, '2025-05-05 11:20:58'),
(55, '21223344567', 1, 0, 0, 1, 1, '2025-05-05 11:20:58'),
(56, '25252525252', 1, 1, 1, 1, 2, '2025-05-19 15:37:00'),
(57, '15635475958', 1, 0, 1, 1, 2, '2025-05-24 11:31:00'),
(58, '62656265626', 1, 1, 1, 1, 2, '2025-05-24 14:02:15'),
(59, '22222222222', 1, 1, 1, 1, 14, '2025-05-26 19:08:00'),
(60, '73384842199', 1, 0, 0, 0, 1, '2025-05-28 18:03:04'),
(61, '73454019380', 1, 1, 1, 1, 1, '2025-05-28 18:03:04'),
(62, '88728743152', 1, 0, 0, 0, 1, '2025-05-28 18:03:04'),
(63, '12275304637', 0, 0, 1, 0, 1, '2025-05-28 18:03:04'),
(64, '84145933272', 0, 0, 1, 0, 1, '2025-05-28 18:03:04'),
(66, '39106518017', 1, 0, 0, 1, 1, '2025-05-28 18:03:04'),
(67, '68230047868', 1, 1, 0, 1, 1, '2025-05-28 18:03:04'),
(69, '40892687056', 1, 1, 0, 0, 1, '2025-05-28 18:03:04'),
(71, '40331739363', 1, 1, 1, 1, 1, '2025-05-28 18:03:04'),
(72, '53556093204', 0, 0, 1, 0, 1, '2025-05-28 18:03:04'),
(73, '77725228728', 1, 0, 0, 0, 1, '2025-05-28 18:03:04'),
(75, '54615679334', 1, 1, 1, 0, 1, '2025-05-28 18:03:04'),
(76, '91803397401', 1, 0, 0, 0, 1, '2025-05-28 18:03:04'),
(77, '85621850163', 1, 0, 1, 1, 1, '2025-05-28 18:03:04'),
(78, '29619431142', 1, 0, 0, 0, 1, '2025-05-28 18:03:04'),
(80, '72126464715', 1, 0, 1, 1, 1, '2025-05-28 18:03:04'),
(81, '75692459375', 0, 0, 0, 0, 1, '2025-05-28 18:03:04'),
(83, '52909656101', 0, 1, 0, 1, 1, '2025-05-28 18:03:04'),
(84, '61125457387', 0, 1, 0, 1, 1, '2025-05-28 18:03:04'),
(85, '25867362266', 0, 1, 1, 1, 1, '2025-05-28 18:03:04'),
(87, '25286692966', 1, 1, 0, 1, 1, '2025-05-28 18:03:04'),
(88, '68876767721', 0, 1, 0, 1, 1, '2025-05-28 18:03:04'),
(89, '85807836134', 0, 1, 1, 0, 1, '2025-05-28 18:03:04'),
(90, '95849825449', 0, 1, 1, 1, 1, '2025-05-28 18:03:04'),
(91, '29466711684', 0, 1, 0, 0, 1, '2025-05-28 18:03:04'),
(92, '45892975001', 0, 1, 0, 1, 1, '2025-05-28 18:03:04'),
(93, '41520643611', 1, 0, 1, 0, 1, '2025-05-28 18:03:04'),
(94, '61115542622', 0, 1, 0, 1, 1, '2025-05-28 18:03:04'),
(95, '96565234138', 0, 1, 1, 1, 1, '2025-05-28 18:03:04'),
(96, '37918055269', 0, 0, 1, 1, 1, '2025-05-28 18:03:04'),
(97, '84866618892', 1, 1, 1, 0, 1, '2025-05-28 18:03:04'),
(98, '61799042102', 1, 0, 0, 1, 1, '2025-05-28 18:03:04'),
(99, '48149177850', 1, 1, 0, 0, 1, '2025-05-28 18:03:04'),
(101, '45187053494', 0, 1, 0, 0, 1, '2025-05-28 18:03:04'),
(103, '62344565574', 1, 1, 0, 1, 1, '2025-05-28 18:03:04'),
(105, '88317121003', 1, 1, 0, 0, 1, '2025-05-28 18:03:04'),
(106, '10702966706', 1, 0, 1, 1, 1, '2025-05-28 18:03:04'),
(111, '44220969029', 0, 1, 1, 0, 1, '2025-05-28 18:03:04'),
(112, '70433154100', 0, 1, 1, 0, 1, '2025-05-28 18:03:04'),
(116, '11586938068', 1, 0, 1, 1, 1, '2025-05-28 18:03:04'),
(120, '85082148628', 0, 1, 0, 0, 1, '2025-05-28 18:03:04'),
(121, '86103579727', 1, 0, 0, 0, 1, '2025-05-28 18:03:04'),
(123, '89944171545', 0, 1, 1, 0, 1, '2025-05-28 18:03:04'),
(125, '46554947170', 0, 1, 0, 1, 1, '2025-05-28 18:03:04'),
(127, '76008350583', 0, 0, 0, 0, 1, '2025-05-28 18:03:04'),
(130, '18914117393', 1, 0, 1, 0, 1, '2025-05-28 18:03:04'),
(131, '19721811437', 0, 0, 1, 1, 1, '2025-05-28 18:03:04'),
(139, '45228851587', 0, 0, 0, 1, 1, '2025-05-28 18:03:04');

--
-- Tetikleyiciler `personel_gerekli_belge`
--
DELIMITER $$
CREATE TRIGGER `before_delete_personel_gerekli_belge` BEFORE DELETE ON `personel_gerekli_belge` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_gerekli_belge` (
        `tc_kimlik`, `ise_giris_egitimi_var_mi`, `operatorluk_belgesi_var_mi`, `mesleki_yeterlilik_belgesi_var_mi`, `saglik_tetkikleri_oldu_mu`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`ise_giris_egitimi_var_mi`, OLD.`operatorluk_belgesi_var_mi`, OLD.`mesleki_yeterlilik_belgesi_var_mi`, OLD.`saglik_tetkikleri_oldu_mu`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_personel_gerekli_belge` BEFORE UPDATE ON `personel_gerekli_belge` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_gerekli_belge` (
        `tc_kimlik`, `ise_giris_egitimi_var_mi`, `operatorluk_belgesi_var_mi`, `mesleki_yeterlilik_belgesi_var_mi`, `saglik_tetkikleri_oldu_mu`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`ise_giris_egitimi_var_mi`, OLD.`operatorluk_belgesi_var_mi`, OLD.`mesleki_yeterlilik_belgesi_var_mi`, OLD.`saglik_tetkikleri_oldu_mu`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_is_kazalari`
--

CREATE TABLE `personel_is_kazalari` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `is_kazasi_tip_id` int(11) NOT NULL,
  `yaralanma_durumu_id` int(11) NOT NULL,
  `yaralanma_tip_id` int(11) NOT NULL,
  `kaza_nedeni` text NOT NULL,
  `is_kazasi_tarihi` datetime DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personel_is_kazalari`
--

INSERT INTO `personel_is_kazalari` (`id`, `tc_kimlik`, `is_kazasi_tip_id`, `yaralanma_durumu_id`, `yaralanma_tip_id`, `kaza_nedeni`, `is_kazasi_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(19, '44455566677', 3, 2, 1, 'Elini testereye kaptırdı...', '2025-03-05 11:25:00', 1, '2025-04-29 15:30:10'),
(20, '99900011122', 5, 1, 4, 'Dikkatsizlik sonucu ayağı burkuldu.', '2025-04-12 14:00:00', 1, '2025-04-29 15:30:10'),
(22, '11122233355', 3, 1, 1, 'Ölçüm yaparken düşen bir cisim nedeniyle hafif yaralandı.', '2025-04-20 15:15:00', 1, '2025-04-29 15:30:10'),
(34, '85858585858', 5, 2, 2, 'Şantiyede koşarken düşüp kolunu kırdı.', '2025-05-03 19:10:00', 2, '2025-05-03 16:18:09'),
(35, '85858585858', 1, 1, 2, 'Yüksekten düşüp ayağını kırdı.', '2025-05-03 19:15:00', 2, '2025-05-03 16:18:09'),
(36, '48648648648', 11, 6, 6, 'Aracın şoför telefon ile ilgilendiğinden ötürü kaza meydana gelmiştir.', '2025-05-05 13:20:00', 2, '2025-05-05 11:02:27'),
(37, '25252525252', 3, 1, 1, 'Elini Kesti', '2025-05-19 18:32:00', 2, '2025-05-19 15:37:00'),
(38, '25252525252', 4, 2, 2, 'Koli Düştü', '2025-05-19 18:35:00', 2, '2025-05-19 15:37:00'),
(39, '25252525252', 2, 1, 3, 'Hafif Yanık', '2025-05-19 18:36:00', 2, '2025-05-19 15:37:00'),
(40, '25252525252', 9, 2, 4, 'Ezildi', '2025-05-19 18:39:00', 2, '2025-05-19 15:39:18'),
(42, '15635475958', 10, 1, 5, 'Kaygan zeminde yere Düşme', '2025-05-14 14:29:00', 2, '2025-05-24 13:54:54'),
(43, '15635475958', 3, 2, 1, 'Bıçak Kesiği', '2025-05-26 16:03:00', 2, '2025-05-26 13:03:58'),
(44, '12275304637', 5, 1, 1, 'Kaygan zeminde yürürken kayıp düştü.', '2023-01-10 08:45:00', 14, '2025-05-28 22:45:27'),
(45, '37918055269', 2, 2, 2, 'Elektrik panosunu tamir ederken elektrik çarptı.', '2023-02-22 15:00:00', 1, '2025-05-28 22:45:27'),
(46, '12275304637', 4, 3, 4, 'Yukarıdan düşen parça başına çarptı.', '2023-03-18 13:15:00', 14, '2025-05-28 22:45:27'),
(47, '37918055269', 1, 2, 3, 'İskelede dengesini kaybedip düştü.', '2023-04-06 09:20:00', 2, '2025-05-28 22:45:27'),
(48, '12275304637', 3, 1, 2, 'El aletiyle çalışırken parmaklarını kesti.', '2023-05-27 11:30:00', 14, '2025-05-28 22:45:27'),
(49, '37918055269', 10, 2, 5, 'Ağır yükün başına çarpması sonucu bilinç kaybı yaşandı.', '2023-06-14 17:50:00', 1, '2025-05-28 22:45:27'),
(50, '12275304637', 9, 2, 4, 'Toprak kayması sonucu personel göçük altında kaldı.', '2023-07-01 10:05:00', 14, '2025-05-28 22:45:27'),
(51, '37918055269', 2, 3, 1, 'Elektrik tesisatında yanık oluştu.', '2023-08-16 16:40:00', 2, '2025-05-28 22:45:27'),
(52, '12275304637', 4, 2, 3, 'Malzeme taşıma sırasında dengesini kaybetti.', '2023-09-20 12:00:00', 14, '2025-05-28 22:45:27'),
(53, '37918055269', 5, 1, 2, 'Yere düşen malzemeye takılarak düştü.', '2023-10-12 07:50:00', 1, '2025-05-28 22:45:27'),
(54, '12275304637', 3, 2, 1, 'Aletle çalışırken kolunu yaraladı.', '2023-11-04 08:10:00', 14, '2025-05-28 22:45:27'),
(55, '37918055269', 1, 2, 5, 'Yüksekten düşme sonucu hayati tehlike yaşandı.', '2023-12-21 18:30:00', 1, '2025-05-28 22:45:27');

--
-- Tetikleyiciler `personel_is_kazalari`
--
DELIMITER $$
CREATE TRIGGER `before_delete_personel_is_kazalari` BEFORE DELETE ON `personel_is_kazalari` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_is_kazalari` (
        `tc_kimlik`, `is_kazasi_tip_id`, `yaralanma_durumu_id`, `yaralanma_tip_id`, `kaza_nedeni`, `is_kazasi_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`is_kazasi_tip_id`, OLD.`yaralanma_durumu_id`, OLD.`yaralanma_tip_id`, OLD.`kaza_nedeni`, OLD.`is_kazasi_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_personel_is_kazalari` BEFORE UPDATE ON `personel_is_kazalari` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_is_kazalari` (
        `tc_kimlik`, `is_kazasi_tip_id`, `yaralanma_durumu_id`, `yaralanma_tip_id`, `kaza_nedeni`, `is_kazasi_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`is_kazasi_tip_id`, OLD.`yaralanma_durumu_id`, OLD.`yaralanma_tip_id`, OLD.`kaza_nedeni`, OLD.`is_kazasi_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_kisisel_bilgi`
--

CREATE TABLE `personel_kisisel_bilgi` (
  `tc_kimlik` varchar(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `cinsiyet` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Erkek ise 1, Kadın ise 0',
  `dogum_tarihi` date NOT NULL,
  `telefon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `adres` text DEFAULT NULL,
  `pp_dosya_yolu` text DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personel_kisisel_bilgi`
--

INSERT INTO `personel_kisisel_bilgi` (`tc_kimlik`, `ad_soyad`, `cinsiyet`, `dogum_tarihi`, `telefon`, `email`, `adres`, `pp_dosya_yolu`, `kullanici_id`, `veri_giris_tarihi`) VALUES
('10702966706', 'Yepelek Güçlü', 0, '1965-06-28', '05236519941', 'ermutlusama@hotmail.com', '02921 Erdoğan Landing Suite 915, Tolonbayberg, VA 57762', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('11122233355', 'Zeynep Öztürk', 0, '1995-04-03', '05421112233', 'zeynep.ozturk@example.com', 'İstanbul, Kadıköy', '4de66ffa091bc348.png', 1, '2025-04-29 15:30:10'),
('11586938068', 'Gök Akar', 0, '1979-02-08', '05256527080', 'apaydinertas@camurcuoglu.com', '610 Zülgarni Overpass Suite 279, Uçbeyiburgh, KS 98668', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('12275304637', 'Celil Öcalan', 1, '1974-08-30', '05742054210', 'cogay76@yahoo.com', '512 Fırat Crossroad, Port Abdülahat, AK 39608', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('15635475958', 'Kayla Uçar', 1, '1998-06-16', '05349652415', 'kayla.ucar@gmail.com', 'Buca / İzmir', '8127c9e78bc4e43b.png', 2, '2025-05-24 11:31:00'),
('18914117393', 'Ömriye Akçay', 0, '2007-02-26', '05761847230', 'sensoygucal@roketsan.info', '75354 Ferhan Fork, Gülenbury, NH 58721', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('19721811437', 'Ayça Dumanlı', 0, '1973-07-10', '05483564208', 'sakaryaunek@yahoo.com', '99488 Sernur Skyway, Akçamouth, AR 29719', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('21134455678', 'Ege Can', 1, '1987-09-10', '05551233456', 'ege.can@example.com', 'İstanbul, Esenyurt', '8127c9e78bc4e43b.png', 1, '2025-05-03 21:52:58'),
('21223344567', 'Ceren Kaya', 0, '1992-05-27', '05460122345', 'ceren.kaya@example.com', 'Mersin, Toroslar', '4de66ffa091bc348.png', 1, '2025-05-05 11:20:58'),
('21334455678', 'Emre Can', 1, '1987-09-10', '05371233456', 'emre.can@example.com', 'İstanbul, Esenyurt', '8127c9e78bc4e43b.png', 1, '2025-05-05 11:20:58'),
('22222222222', 'Mertcan Ülbeyi', 1, '2004-06-29', '05452369271', 'mertcanulbeyi@gmail.com', '4693 sokak no:31 daire 3 Çamkule Mahallesi Bornova İzmir', '8127c9e78bc4e43b.png', 14, '2025-05-26 19:08:00'),
('25252525252', 'Cahit Arf', 1, '1984-11-14', '05789632574', 'cahit.arf@gmail.com', 'İzmir / Bornova', '8127c9e78bc4e43b.png', 2, '2025-05-19 15:37:00'),
('25286692966', 'Tanır Yaman', 1, '1979-07-04', '05691557924', 'durduvala@toyota.org', '449 Akça Walks Apt. 260, Sevdinarberg, DC 33226', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('25867362266', 'Nurkan Yaman', 1, '1978-09-21', '05571775140', 'rseven@yahoo.com', '2190 Sittik Bypass Suite 384, Port Ağbegim, WA 65422', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('29466711684', 'Cemal Sezer', 1, '1988-10-28', '05481260422', 'isezer@erdogan.com', '55426 Saire Springs, North Gülsevil, AL 80496', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('29619431142', 'Haciali Şafak', 1, '2005-11-22', '05159996504', 'tigin93@hayrioglu.com', '356 Alemdar Turnpike Suite 519, New Hasret, TX 72603', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('32445566789', 'Aslıhan Güler', 0, '1991-04-25', '05482344567', 'aslihan.guler@example.com', 'Ankara, Mamak', 'f24a81e648e85178.png', 1, '2025-05-05 11:20:58'),
('37918055269', 'Bulunç Soylu', 1, '1989-01-14', '05999404389', 'zuferyaman@hotmail.com', '105 Aslan Pine Apt. 491, Ertaşhaven, AR 65985', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('39106518017', 'Bilgütay Hançer', 1, '1991-01-23', '05971870728', 'oyuksel@akdeniz.info', 'PSC 8668, Box 8263, APO AA 27152', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('40331739363', 'Sadat Arslan', 1, '1966-08-12', '05517518304', 'doganalpcamurcuoglu@gmail.com', '98454 Çorlu Landing, Ertaşshire, UT 30104', '8127c9e78bc4e43b.png ', 1, '2025-05-28 18:03:04'),
('40892687056', 'Ermutlu Durmuş', 1, '2005-03-10', '05284395995', 'goknur35@gmail.com', '8547 Ertaş Extension, Hamiberg, ND 64505', '8127c9e78bc4e43b.png ', 1, '2025-05-28 18:03:04'),
('41520643611', 'Sıla Gül', 1, '2006-10-16', '05701277063', 'akdenizsevla@yahoo.com', '640 Emiş Lights, East Evdeland, AL 47830', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('44220969029', 'Maynur Çorlu', 0, '1997-09-02', '05915452267', 'ozokcu77@toyota.com', '56527 Resulcan Mill Suite 434, East Zehranurville, TN 45562', 'f24a81e648e85178.png', 1, '2025-05-28 18:03:04'),
('44435566677', 'Elif Ak', 0, '1985-03-22', '05354445566', 'elif.kayaa@example.com', 'Adana, Seyhan', 'f24a81e648e85178.png', 1, '2025-05-03 21:52:31'),
('44455566677', 'Elif Kaya', 0, '1985-03-22', '05354445566', 'elif.kaya@example.com', 'Adana, Seyhan', 'f24a81e648e85178.png', 1, '2025-04-29 15:30:10'),
('45187053494', 'Burcuhan Sezgin', 0, '1986-02-23', '05419518023', 'turabimanco@demirel.com', '442 Ünsever Walk, Durmuşside, CT 61061', 'f24a81e648e85178.png', 1, '2025-05-28 18:03:04'),
('45228851587', 'Gülşeref Arsoy', 0, '1985-06-03', '05669346547', 'wdurmus@tupras.com', '99305 Çamurcuoğlu Underpass Apt. 648, Aliabbashaven, WI 49647', 'f24a81e648e85178.png', 1, '2025-05-28 18:03:04'),
('45645645645', 'Sinan Aka', 1, '2004-06-25', '05417544874', 'sinan@gmail.com', 'Ankara/Sincan', '8127c9e78bc4e43b.png ', 1, '2025-04-29 15:30:10'),
('45892975001', 'Yetişal Şener', 1, '1975-09-30', '05577590175', 'nbilge@gmail.com', 'PSC 2196, Box 5652, APO AE 36803', '8127c9e78bc4e43b.png ', 1, '2025-05-28 18:03:04'),
('46554947170', 'Gülsiye Sezer', 0, '1966-01-29', '05758428241', 'gulker@hotmail.com', '1891 Demir Tunnel, South Korayborough, KS 69070', 'f24a81e648e85178.png', 1, '2025-05-28 18:03:04'),
('48149177850', 'Hakikat Yılmaz', 1, '1991-01-19', '05027588425', 'bilirgulseren@gmail.com', '41038 Sezer Landing, Korutürkland, CT 66748', '8127c9e78bc4e43b.png ', 1, '2025-05-28 18:03:04'),
('48648648648', 'Cabbar Keş', 1, '2000-01-01', '05486484848', 'cabbar@gmail.com', 'Mersin', '8127c9e78bc4e43b.png ', 2, '2025-05-05 11:02:27'),
('52909656101', 'Öztürk Akçay', 1, '1990-05-03', '05426617979', 'ashandemir@yahoo.com', '596 Zengin Crescent Suite 579, Port Tanses, ME 81042', '8127c9e78bc4e43b.png ', 1, '2025-05-28 18:03:04'),
('53535353535', 'Kara Mesut', 1, '1982-07-14', '05535335353', 'karamesut@gmail.com', 'Ağrı', '8127c9e78bc4e43b.png ', 2, '2025-05-01 09:33:54'),
('53556093204', 'Tunç İnönü', 1, '1967-08-08', '05854678544', 'ykisakurek@lc.com', '76591 Mülâyim Forest, Aslanton, TX 37250', '8127c9e78bc4e43b.png ', 1, '2025-05-28 18:03:04'),
('54615679334', 'Efe Durmuş', 1, '1990-12-29', '05084840862', 'tutkucanzengin@hotmail.com', '11426 Özakan Island Suite 110, Atiyyestad, KY 13900', '8127c9e78bc4e43b.png ', 1, '2025-05-28 18:03:04'),
('54667788901', 'Merve Yılmaz', 0, '1989-07-18', '05404566789', 'merve.yilmaz@example.com', 'Bursa, Osmangazi', 'f24a81e648e85178.png', 1, '2025-05-05 11:20:58'),
('55555555555', 'Elif Eylül', 0, '1987-07-07', '05348521478', 'elif_eylul@gmail.com', 'Bolu', '4de66ffa091bc348.png', 1, '2025-04-29 15:30:10'),
('61115542622', 'Kenter Bilge', 1, '2004-02-15', '05037129387', 'hatin27@gmail.com', '43006 Şener Station, Şafakfurt, SC 70829', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('61125457387', 'Gözel Demir', 1, '1981-09-05', '05438889937', 'akcaycakar@hotmail.com', '9296 Akçay Oval Apt. 827, Famiburgh, NV 81688', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('61799042102', 'Pehlil Gülen', 1, '1970-02-19', '05414183285', 'taylak51@hotmail.com', '49493 Arsoy Ramp Suite 383, North İlbek, IN 71482', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('62344565574', 'Nejdet Bilir', 0, '1978-03-07', '05024103865', 'aybet20@hotmail.com', '476 Yorulmaz Corners, Çıdalberg, ID 84734', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('62656265626', 'Menekşe Alçak', 0, '1988-10-14', '05626526354', 'meneksealcak@gmail.com', 'Melikgazi / Kayseri', '4de66ffa091bc348.png', 2, '2025-05-24 14:02:15'),
('65778899012', 'Tolga Şen', 1, '1986-03-30', '05315677890', 'tolga.sen@example.com', 'Kocaeli, Gebze', '8127c9e78bc4e43b.png', 1, '2025-05-05 11:20:58'),
('68230047868', 'Muktedir Arsoy', 1, '1971-09-27', '05046226567', 'aslancaglasin@tofas.org', '98373 Hançer Bridge, East Elöve, WV 63274', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('68876767721', 'Çeviköz Karadeniz', 1, '1992-03-02', '05278282742', 'meleknursener@yahoo.com', '1168 Sezer Wells Suite 775, Port Şennurland, NH 83710', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('70433154100', 'Çağlar Kısakürek', 0, '2001-01-02', '05593917251', 'pdurdu@yahoo.com', '089 Ensari Throughway Suite 223, East Atilhan, CO 90039', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('70578578578', 'Cafer Buruş', 1, '2005-06-08', '05487558565', 'cafer@gmail.com', 'Konya/Mevlana', '8127c9e78bc4e43b.png', 1, '2025-05-03 21:52:31'),
('72126464715', 'Özgür Arsoy', 1, '2001-12-24', '05956046618', 'ocalanolca@selcuk.com', '656 Afer Courts, Mishathaven, NC 73638', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('73384842199', 'Kamar Mansız', 1, '1980-06-22', '05419866524', 'cemiylemanco@koruturk.biz', '339 Gülhisar Mission Suite 191, Port Hayelhaven, OK 70842', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('73454019380', 'Günşen Akdeniz', 1, '2000-11-08', '05330833016', 'tarhankilicbay@hotmail.com', '707 Gelengül Mall, Durduton, NH 88682', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('75692459375', 'Mucahit Karakucak', 1, '2005-07-01', '05437993712', 'aliabbas71@seven.com', '4573 Ergül Mountains, South İmrihanmouth, CA 39857', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('76008350583', 'Cemiyle Alemdar', 0, '1977-05-02', '05007432927', 'yeneral56@opet.com', '22557 Akgündüz Street, West Uygunburgh, MO 52962', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('77725228728', 'Cebesoy İhsanoğlu', 1, '1986-11-05', '05899642353', 'muarramanco@havelsan.biz', 'USNV Aksu, FPO AP 32020', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('78578578578', 'Cafer Buruk', 1, '2005-06-08', '05487558565', 'cafer@gmail.com', 'Konya/Mevlana', '8127c9e78bc4e43b.png', 1, '2025-04-29 15:30:10'),
('78978978978', 'Caner Er', 1, '1992-01-29', '05874574125', 'caner@gmail.com', 'Bursa / Orhangazi', '8127c9e78bc4e43b.png', 1, '2025-04-29 15:30:10'),
('84145933272', 'Rüknettin Kısakürek', 1, '1981-01-27', '05454990453', 'jergul@akar.com', '033 Denkel Ranch Apt. 790, Lake Avşin, NV 31216', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('84866618892', 'Kamar Aksu', 1, '1982-10-21', '05658271091', 'bilginfeyha@turk.com', '2208 Bilgin Islands Apt. 654, New Âdemville, TX 07404', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('85082148628', 'Işin Gül', 0, '2003-06-27', '05132475469', 'sernur13@bilgin.biz', '4947 Bilkay Center Apt. 738, Port Tülinfurt, ME 54647', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('85621850163', 'Amaç Aksu', 1, '1995-01-09', '05489025039', 'ehancer@gmail.com', '799 Aşhan Coves, West Abid, MA 81790', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('85807836134', 'Sabih Gülen', 1, '1980-04-08', '05903404384', 'emisaslan@yahoo.com', '929 Çetin Junctions Apt. 017, North Gülelmouth, AL 56573', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('85858585858', 'Menekşe Uygur', 0, '1979-10-16', '05858558585', 'menekse_uygur@gmail.com', 'Hatay', '4de66ffa091bc348.png', 2, '2025-05-03 16:18:09'),
('86103579727', 'Ergül Akar', 0, '1988-10-06', '05902330307', 'umanco@hotmail.com', '5250 Aysuna Harbor, Port Haluk, WV 21267', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('87990011234', 'Onur Çelik', 1, '1982-06-22', '05337899012', 'onur.celik@example.com', 'Antalya, Muratpaşa', '8127c9e78bc4e43b.png', 1, '2025-05-05 11:20:58'),
('88317121003', 'Gürcüye Durdu', 0, '1991-11-17', '05352135680', 'hunalpyuksel@hotmail.com', '059 Kitan Field Suite 842, Türkland, IN 79362', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('88728743152', 'Kopan Eraslan', 1, '1965-08-25', '05262067240', 'turksekim@sezer.com', 'USS İhsanoğlu, FPO AA 04660', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('89944171545', 'Cihan Kısakürek', 0, '1999-09-12', '05944665743', 'bedrialemdar@gmail.com', '8985 Akgündüz Spur Apt. 575, East Ünek, IN 29903', '4de66ffa091bc348.png', 1, '2025-05-28 18:03:04'),
('91803397401', 'Mehmet Dumanlı', 1, '2003-05-07', '05101666870', 'yildirimcebesoy@yahoo.com', '0466 İzel Points Apt. 982, Aksuberg, ME 35681', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('95849825449', 'Toğan Hançer', 1, '1976-04-06', '05074517133', 'sensoyyertan@yahoo.com', '64361 Abdulgazi Forest, Erdoğanberg, IN 68585', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('96565234138', 'Ezgütekin Çorlu', 1, '1986-07-14', '05189092753', 'gorgunayduran@gulen.com', '303 Akyıldız Crescent Suite 990, Sultaneburgh, AL 93286', '8127c9e78bc4e43b.png', 1, '2025-05-28 18:03:04'),
('99001122345', 'Gizem Öztürk', 0, '1990-02-08', '05448900123', 'gizem.ozturk@example.com', 'Trabzon, Akçaabat', '4de66ffa091bc348.png', 1, '2025-05-05 11:20:58'),
('99900011122', 'Gürkan Tekin', 1, '1991-06-12', '05409990011', 'gurkan.tekin@example.com', 'İzmir, Konak', '8127c9e78bc4e43b.png', 1, '2025-04-29 15:30:10');

--
-- Tetikleyiciler `personel_kisisel_bilgi`
--
DELIMITER $$
CREATE TRIGGER `before_delete_personel_kisisel_bilgi` BEFORE DELETE ON `personel_kisisel_bilgi` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_kisisel_bilgi` (
        `tc_kimlik`, `ad_soyad`, `cinsiyet`, `dogum_tarihi`, `telefon`, `email`, `adres`, `pp_dosya_yolu`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`ad_soyad`, OLD.`cinsiyet`, OLD.`dogum_tarihi`, OLD.`telefon`, OLD.`email`, OLD.`adres`, OLD.`pp_dosya_yolu`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_personel_kisisel_bilgi` BEFORE UPDATE ON `personel_kisisel_bilgi` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_kisisel_bilgi` (
        `tc_kimlik`, `ad_soyad`, `cinsiyet`, `dogum_tarihi`, `telefon`, `email`, `adres`, `pp_dosya_yolu`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`ad_soyad`, OLD.`cinsiyet`, OLD.`dogum_tarihi`, OLD.`telefon`, OLD.`email`, OLD.`adres`, OLD.`pp_dosya_yolu`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_saglik_tetkikleri`
--

CREATE TABLE `personel_saglik_tetkikleri` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `saglik_tetkikleri_oldu_mu` tinyint(1) DEFAULT 0,
  `tarih` date DEFAULT NULL,
  `gecerlilik_tarihi` date DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personel_saglik_tetkikleri`
--

INSERT INTO `personel_saglik_tetkikleri` (`id`, `tc_kimlik`, `saglik_tetkikleri_oldu_mu`, `tarih`, `gecerlilik_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(9, '78578578578', 1, '2025-04-03', '2026-04-03', 1, '2025-04-29 15:30:10'),
(11, '45645645645', 1, '2024-05-01', '2025-05-01', 1, '2025-04-29 15:30:10'),
(12, '78978978978', 1, '2024-04-05', '2025-04-05', 1, '2025-04-29 15:30:10'),
(13, '55555555555', 1, '2024-11-13', '2025-11-13', 1, '2025-04-29 15:30:10'),
(18, '44455566677', 1, '2025-04-26', '2026-04-26', 1, '2025-04-29 15:30:10'),
(19, '99900011122', 1, '2025-05-06', '2026-05-06', 1, '2025-04-29 15:30:10'),
(21, '11122233355', 1, '2025-05-10', '2026-05-10', 1, '2025-04-29 15:30:10'),
(23, '53535353535', 1, '2025-04-15', '2026-04-15', 2, '2025-05-01 09:33:54'),
(27, '85858585858', 0, NULL, NULL, 2, '2025-05-03 16:18:09'),
(29, '70578578578', 1, '2023-12-15', '2024-12-15', 1, '2025-05-03 21:52:31'),
(31, '44435566677', 1, '2024-07-01', '2025-07-01', 1, '2025-05-03 21:52:31'),
(32, '21134455678', 1, '2024-09-20', '2025-09-20', 1, '2025-05-03 21:52:58'),
(33, '48648648648', 1, '2025-05-04', '2026-05-04', 2, '2025-05-05 11:02:27'),
(34, '21334455678', 1, '2024-09-20', '2025-09-20', 1, '2025-05-05 11:20:58'),
(35, '32445566789', 1, '2024-08-10', '2025-08-10', 1, '2025-05-05 11:20:58'),
(36, '54667788901', 1, '2024-07-05', '2025-07-05', 1, '2025-05-05 11:20:58'),
(37, '65778899012', 1, '2024-10-01', '2025-10-01', 1, '2025-05-05 11:20:58'),
(39, '99001122345', 1, '2024-05-15', '2025-05-15', 1, '2025-05-05 11:20:58'),
(41, '21223344567', 1, '2024-08-25', '2025-08-25', 1, '2025-05-05 11:20:58'),
(42, '25252525252', 1, '2025-05-19', '2026-05-19', 2, '2025-05-19 15:37:00'),
(43, '15635475958', 1, '2024-08-29', '2025-08-29', 2, '2025-05-24 11:31:00'),
(44, '62656265626', 1, '2025-05-06', '2026-05-06', 2, '2025-05-24 14:02:15'),
(45, '22222222222', 1, '2025-05-01', '2026-05-01', 14, '2025-05-26 19:08:00'),
(46, '73454019380', 1, '2024-09-27', '2025-09-27', 1, '2025-05-28 18:03:04'),
(48, '39106518017', 1, '2025-05-27', '2026-05-27', 1, '2025-05-28 18:03:04'),
(49, '68230047868', 1, '2024-08-26', '2025-08-26', 1, '2025-05-28 18:03:04'),
(51, '40331739363', 1, '2024-12-09', '2025-12-09', 1, '2025-05-28 18:03:04'),
(52, '85621850163', 1, '2024-11-10', '2025-11-10', 1, '2025-05-28 18:03:04'),
(54, '72126464715', 1, '2025-01-11', '2026-01-11', 1, '2025-05-28 18:03:04'),
(56, '52909656101', 1, '2025-01-21', '2026-01-21', 1, '2025-05-28 18:03:04'),
(57, '61125457387', 1, '2024-12-28', '2025-12-28', 1, '2025-05-28 18:03:04'),
(58, '25867362266', 1, '2024-08-19', '2025-08-19', 1, '2025-05-28 18:03:04'),
(59, '25286692966', 1, '2025-03-30', '2026-03-30', 1, '2025-05-28 18:03:04'),
(60, '68876767721', 1, '2025-02-18', '2026-02-18', 1, '2025-05-28 18:03:04'),
(61, '95849825449', 1, '2024-03-29', '2025-03-29', 1, '2025-05-28 18:03:04'),
(62, '45892975001', 1, '2024-12-14', '2025-12-14', 1, '2025-05-28 18:03:04'),
(63, '61115542622', 1, '2025-01-31', '2026-01-31', 1, '2025-05-28 18:03:04'),
(64, '96565234138', 1, '2025-03-26', '2026-03-26', 1, '2025-05-28 18:03:04'),
(65, '37918055269', 1, '2025-04-01', '2026-04-01', 1, '2025-05-28 18:03:04'),
(66, '61799042102', 1, '2025-04-14', '2026-04-14', 1, '2025-05-28 18:03:04'),
(68, '62344565574', 1, '2025-04-27', '2026-04-27', 1, '2025-05-28 18:03:04'),
(69, '10702966706', 1, '2024-06-24', '2025-06-24', 1, '2025-05-28 18:03:04'),
(74, '11586938068', 1, '2024-06-20', '2025-06-20', 1, '2025-05-28 18:03:04'),
(78, '46554947170', 1, '2025-02-03', '2026-02-03', 1, '2025-05-28 18:03:04'),
(81, '19721811437', 1, '2025-04-13', '2026-04-13', 1, '2025-05-28 18:03:04'),
(84, '45228851587', 1, '2024-07-13', '2025-07-13', 1, '2025-05-28 18:03:04');

--
-- Tetikleyiciler `personel_saglik_tetkikleri`
--
DELIMITER $$
CREATE TRIGGER `before_delete_personel_saglik_tetkikleri` BEFORE DELETE ON `personel_saglik_tetkikleri` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_saglik_tetkikleri` (
        `tc_kimlik`, `saglik_tetkikleri_oldu_mu`, `tarih`, `gecerlilik_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`saglik_tetkikleri_oldu_mu`, OLD.`tarih`, OLD.`gecerlilik_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_personel_saglik_tetkikleri` BEFORE UPDATE ON `personel_saglik_tetkikleri` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_saglik_tetkikleri` (
        `tc_kimlik`, `saglik_tetkikleri_oldu_mu`, `tarih`, `gecerlilik_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`saglik_tetkikleri_oldu_mu`, OLD.`tarih`, OLD.`gecerlilik_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `personel_saglik_tetkikleri_insert_trg` BEFORE INSERT ON `personel_saglik_tetkikleri` FOR EACH ROW SET NEW.gecerlilik_tarihi = DATE_ADD(NEW.tarih, INTERVAL 1 YEAR)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `personel_saglik_tetkikleri_update_trg` BEFORE UPDATE ON `personel_saglik_tetkikleri` FOR EACH ROW SET NEW.gecerlilik_tarihi = DATE_ADD(NEW.tarih, INTERVAL 1 YEAR)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_sirket_bilgi`
--

CREATE TABLE `personel_sirket_bilgi` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `firma_id` int(11) DEFAULT NULL,
  `meslek_id` int(11) DEFAULT NULL,
  `ise_giris_tarihi` date DEFAULT NULL,
  `toplam_deneyim_yili` int(11) DEFAULT 0,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personel_sirket_bilgi`
--

INSERT INTO `personel_sirket_bilgi` (`id`, `tc_kimlik`, `firma_id`, `meslek_id`, `ise_giris_tarihi`, `toplam_deneyim_yili`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(28, '78578578578', 2, 3, '2025-04-10', 0, 1, '2025-04-29 15:30:10'),
(30, '45645645645', 2, 8, '2025-04-24', 0, 1, '2025-04-29 15:30:10'),
(31, '78978978978', 2, 6, '2025-04-24', 0, 1, '2025-04-29 15:30:10'),
(32, '55555555555', 6, 11, '2022-10-11', 3, 1, '2025-04-29 15:30:10'),
(37, '44455566677', 2, 6, '2013-08-15', 12, 1, '2025-04-29 15:30:10'),
(38, '99900011122', 1, 4, '2019-08-01', 6, 1, '2025-04-29 15:30:10'),
(43, '53535353535', 2, 5, '2023-04-05', 2, 2, '2025-05-01 09:33:54'),
(47, '85858585858', 6, 11, '2003-03-14', 22, 2, '2025-05-03 16:18:09'),
(49, '70578578578', 2, 2, '2023-06-01', 2, 1, '2025-05-03 21:52:31'),
(51, '44435566677', 5, 5, '2019-04-10', 6, 1, '2025-05-03 21:52:31'),
(52, '21134455678', 1, 1, '2021-06-15', 4, 1, '2025-05-03 21:52:58'),
(53, '48648648648', 1, 7, '2025-05-05', 0, 2, '2025-05-05 11:02:27'),
(54, '21334455678', 1, 1, '2021-06-15', 4, 1, '2025-05-05 11:20:58'),
(55, '32445566789', 2, 4, '2022-03-01', 3, 1, '2025-05-05 11:20:58'),
(57, '54667788901', 5, 2, '2023-01-10', 2, 1, '2025-05-05 11:20:58'),
(58, '65778899012', 4, 7, '2021-08-25', 4, 1, '2025-05-05 11:20:58'),
(60, '87990011234', 2, 8, '2020-04-10', 5, 1, '2025-05-05 11:20:58'),
(61, '99001122345', 5, 9, '2022-12-05', 3, 1, '2025-05-05 11:20:58'),
(63, '21223344567', 4, 10, '2023-03-20', 2, 1, '2025-05-05 11:20:58'),
(64, '25252525252', 4, 4, '2025-05-19', 0, 2, '2025-05-19 15:37:00'),
(65, '15635475958', 5, 3, '2024-06-26', 1, 2, '2025-05-24 11:31:00'),
(66, '62656265626', 5, 4, '2022-06-22', 3, 2, '2025-05-24 14:02:15'),
(67, '22222222222', 1, 4, '2025-05-01', 0, 14, '2025-05-26 19:08:00'),
(68, '73384842199', 1, 7, '2021-09-04', 4, 1, '2025-05-28 18:03:04'),
(69, '73454019380', 3, 9, '2023-01-09', 2, 1, '2025-05-28 18:03:04'),
(70, '88728743152', 1, 7, '2022-11-04', 3, 1, '2025-05-28 18:03:04'),
(71, '12275304637', 4, 8, '2024-05-04', 1, 1, '2025-05-28 18:03:04'),
(72, '84145933272', 1, 4, '2024-12-28', 1, 1, '2025-05-28 18:03:04'),
(74, '39106518017', 5, 9, '2023-03-19', 2, 1, '2025-05-28 18:03:04'),
(75, '68230047868', 5, 8, '2022-06-19', 3, 1, '2025-05-28 18:03:04'),
(77, '40892687056', 2, 7, '2021-12-19', 4, 1, '2025-05-28 18:03:04'),
(79, '40331739363', 5, 10, '2022-12-14', 3, 1, '2025-05-28 18:03:04'),
(80, '53556093204', 3, 6, '2025-01-08', 0, 1, '2025-05-28 18:03:04'),
(81, '77725228728', 5, 10, '2025-03-22', 0, 1, '2025-05-28 18:03:04'),
(83, '54615679334', 1, 4, '2021-07-12', 4, 1, '2025-05-28 18:03:04'),
(84, '91803397401', 1, 5, '2024-08-06', 1, 1, '2025-05-28 18:03:04'),
(85, '85621850163', 2, 8, '2023-08-20', 2, 1, '2025-05-28 18:03:04'),
(86, '29619431142', 5, 9, '2020-08-18', 5, 1, '2025-05-28 18:03:04'),
(88, '72126464715', 2, 10, '2025-05-04', 0, 1, '2025-05-28 18:03:04'),
(89, '75692459375', 3, 1, '2020-10-12', 5, 1, '2025-05-28 18:03:04'),
(91, '52909656101', 5, 6, '2021-02-14', 4, 1, '2025-05-28 18:03:04'),
(92, '61125457387', 3, 8, '2020-11-09', 5, 1, '2025-05-28 18:03:04'),
(93, '25867362266', 1, 1, '2025-01-28', 0, 1, '2025-05-28 18:03:04'),
(95, '25286692966', 4, 10, '2021-08-24', 4, 1, '2025-05-28 18:03:04'),
(96, '68876767721', 2, 3, '2023-06-13', 2, 1, '2025-05-28 18:03:04'),
(97, '85807836134', 2, 9, '2022-02-14', 3, 1, '2025-05-28 18:03:04'),
(98, '95849825449', 4, 3, '2024-03-09', 1, 1, '2025-05-28 18:03:04'),
(99, '29466711684', 2, 5, '2021-10-18', 4, 1, '2025-05-28 18:03:04'),
(100, '45892975001', 1, 6, '2021-02-12', 4, 1, '2025-05-28 18:03:04'),
(101, '41520643611', 2, 7, '2023-02-04', 2, 1, '2025-05-28 18:03:04'),
(102, '61115542622', 1, 1, '2024-12-22', 1, 1, '2025-05-28 18:03:04'),
(103, '96565234138', 2, 8, '2021-07-27', 4, 1, '2025-05-28 18:03:04'),
(104, '37918055269', 4, 2, '2025-03-23', 0, 1, '2025-05-28 18:03:04'),
(105, '84866618892', 1, 7, '2021-12-05', 4, 1, '2025-05-28 18:03:04'),
(106, '61799042102', 5, 10, '2023-05-08', 2, 1, '2025-05-28 18:03:04'),
(107, '48149177850', 2, 7, '2021-07-15', 4, 1, '2025-05-28 18:03:04'),
(109, '45187053494', 4, 1, '2021-04-08', 4, 1, '2025-05-28 18:03:04'),
(111, '62344565574', 5, 4, '2024-11-15', 1, 1, '2025-05-28 18:03:04'),
(113, '88317121003', 3, 7, '2022-04-06', 3, 1, '2025-05-28 18:03:04'),
(114, '10702966706', 1, 4, '2024-07-18', 1, 1, '2025-05-28 18:03:04'),
(119, '44220969029', 3, 5, '2025-01-03', 0, 1, '2025-05-28 18:03:04'),
(120, '70433154100', 2, 3, '2024-02-08', 1, 1, '2025-05-28 18:03:04'),
(124, '11586938068', 5, 7, '2021-03-03', 4, 1, '2025-05-28 18:03:04'),
(128, '85082148628', 1, 2, '2020-11-10', 5, 1, '2025-05-28 18:03:04'),
(129, '86103579727', 1, 3, '2024-07-26', 1, 1, '2025-05-28 18:03:04'),
(131, '89944171545', 5, 7, '2021-07-08', 4, 1, '2025-05-28 18:03:04'),
(133, '46554947170', 5, 5, '2022-02-12', 3, 1, '2025-05-28 18:03:04'),
(135, '76008350583', 1, 5, '2024-04-14', 1, 1, '2025-05-28 18:03:04'),
(138, '18914117393', 3, 8, '2025-04-11', 0, 1, '2025-05-28 18:03:04'),
(139, '19721811437', 5, 2, '2024-04-07', 1, 1, '2025-05-28 18:03:04'),
(147, '45228851587', 2, 5, '2024-10-10', 1, 1, '2025-05-28 18:03:04');

--
-- Tetikleyiciler `personel_sirket_bilgi`
--
DELIMITER $$
CREATE TRIGGER `before_delete_personel_sirket_bilgi` BEFORE DELETE ON `personel_sirket_bilgi` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_sirket_bilgi` (
        `tc_kimlik`, `firma_id`, `meslek_id`, `ise_giris_tarihi`, `toplam_deneyim_yili`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`firma_id`, OLD.`meslek_id`, OLD.`ise_giris_tarihi`, OLD.`toplam_deneyim_yili`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_personel_sirket_bilgi` BEFORE UPDATE ON `personel_sirket_bilgi` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_sirket_bilgi` (
        `tc_kimlik`, `firma_id`, `meslek_id`, `ise_giris_tarihi`, `toplam_deneyim_yili`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`firma_id`, OLD.`meslek_id`, OLD.`ise_giris_tarihi`, OLD.`toplam_deneyim_yili`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `personel_sirket_bilgi_insert_trg` BEFORE INSERT ON `personel_sirket_bilgi` FOR EACH ROW SET NEW.toplam_deneyim_yili = YEAR(CURDATE()) - YEAR(NEW.ise_giris_tarihi)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `personel_sirket_bilgi_update_trg` BEFORE UPDATE ON `personel_sirket_bilgi` FOR EACH ROW SET NEW.toplam_deneyim_yili = YEAR(CURDATE()) - YEAR(NEW.ise_giris_tarihi)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_uyarilar`
--

CREATE TABLE `personel_uyarilar` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `uyari_tipi_id` int(11) NOT NULL,
  `uyari_nedeni` text NOT NULL,
  `uyari_tarihi` datetime NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personel_uyarilar`
--

INSERT INTO `personel_uyarilar` (`id`, `tc_kimlik`, `uyari_tipi_id`, `uyari_nedeni`, `uyari_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(9, '78578578578', 1, 'Koruyucu ekipman takmadı.', '2025-04-10 00:00:00', 1, '2025-04-29 15:30:10'),
(21, '78578578578', 5, 'Şantiye içinde sigara içti.', '2025-04-10 11:15:00', 1, '2025-04-29 15:30:10'),
(23, '45645645645', 4, 'Şantiye talimatlarına uymadı.', '2025-03-25 16:00:00', 1, '2025-04-29 15:30:10'),
(24, '78978978978', 7, 'Deneme ihlali: Ekipman yanlış kullanıldı.', '2025-03-15 10:20:00', 1, '2025-04-29 15:30:10'),
(27, '78578578578', 3, 'Yüksekte güvenlik ağı olmadan çalıştı.', '2024-11-05 15:30:00', 1, '2025-04-29 15:30:10'),
(29, '44455566677', 1, 'Ekipman eksikliği.', '2025-02-10 09:10:00', 1, '2025-04-29 15:30:10'),
(30, '99900011122', 3, 'Yüksekte halat kontrolü sağlamadı.', '2025-03-20 10:45:00', 1, '2025-04-29 15:30:10'),
(32, '11122233355', 1, 'Ekipman eksikliği.', '2025-04-05 08:30:00', 1, '2025-04-29 15:30:10'),
(39, '85858585858', 4, 'Talimatlar uymadı.', '2025-05-03 19:14:00', 2, '2025-05-03 16:18:09'),
(40, '48648648648', 8, 'Yanıcı madde etrafında çakmak kullanımı.', '2025-05-05 13:20:00', 2, '2025-05-05 11:02:27'),
(41, '25252525252', 5, 'Sigara İçti', '2025-05-19 18:32:00', 2, '2025-05-19 15:37:00'),
(42, '25252525252', 2, 'Dağınık Çalışıyor', '2025-05-19 18:35:00', 2, '2025-05-19 15:37:00'),
(43, '25252525252', 4, 'Talimatlara uymuyor', '2025-05-19 18:35:00', 2, '2025-05-19 15:37:00'),
(44, '25252525252', 3, 'Dikkatsiz çalışma', '2025-05-19 18:38:00', 2, '2025-05-19 15:38:29'),
(50, '15635475958', 1, 'Ekipman eksikliği.', '2025-05-24 14:24:00', 2, '2025-05-24 13:54:54'),
(51, '15635475958', 2, 'Düzensiz çalışma.', '2025-05-26 15:54:00', 2, '2025-05-26 12:55:11'),
(52, '22222222222', 3, 'Yüksekte dikkatsiz çalışma.', '2025-05-28 23:40:00', 14, '2025-05-28 20:40:35');

--
-- Tetikleyiciler `personel_uyarilar`
--
DELIMITER $$
CREATE TRIGGER `before_delete_personel_uyarilar` BEFORE DELETE ON `personel_uyarilar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_uyarilar` (
        `tc_kimlik`, `uyari_tipi_id`, `uyari_nedeni`, `uyari_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`uyari_tipi_id`, OLD.`uyari_nedeni`, OLD.`uyari_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_personel_uyarilar` BEFORE UPDATE ON `personel_uyarilar` FOR EACH ROW BEGIN
    INSERT INTO `silinen_personel_uyarilar` (
        `tc_kimlik`, `uyari_tipi_id`, `uyari_nedeni`, `uyari_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`
    )
    VALUES (
        OLD.`tc_kimlik`, OLD.`uyari_tipi_id`, OLD.`uyari_nedeni`, OLD.`uyari_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`, @silen_kullanici_id, CURRENT_TIMESTAMP()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `raporlar`
--

CREATE TABLE `raporlar` (
  `id` int(11) NOT NULL,
  `rapor_basligi` varchar(255) NOT NULL,
  `rapor_icerigi` text NOT NULL,
  `rapor_durumu` int(11) DEFAULT NULL,
  `rapor_tarihi` datetime NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `raporlar`
--

INSERT INTO `raporlar` (`id`, `rapor_basligi`, `rapor_icerigi`, `rapor_durumu`, `rapor_tarihi`, `kullanici_id`, `veri_giris_tarihi`) VALUES
(6, 'Baret Kullanımı', 'Baret takılmıyor. Bilginize.', 3, '2025-05-24 16:22:00', 2, '2025-05-24 16:35:04'),
(10, 'Herkes Sahada', 'Herkes Sahada olsun.', 1, '2025-05-23 19:36:00', 2, '2025-05-23 19:37:15'),
(13, 'Duman Salınımı', 'Duman salınımı mevcuttur.', 2, '2025-05-29 00:44:00', 14, '2025-05-29 00:44:20'),
(14, 'Duman Salınımı 2', 'Duman salınımı devam ediyor.', 2, '2025-05-07 17:28:00', 2, '2025-05-24 16:29:44');

--
-- Tetikleyiciler `raporlar`
--
DELIMITER $$
CREATE TRIGGER `before_delete_raporlar` BEFORE DELETE ON `raporlar` FOR EACH ROW BEGIN
  INSERT INTO `silinen_raporlar` (
    `rapor_basligi`, `rapor_icerigi`, `rapor_durumu`,
    `rapor_tarihi`, `kullanici_id`, `olusturulma_tarihi`,
    `silen_kullanici_id`, `silinme_tarihi`
  ) VALUES (
    OLD.`rapor_basligi`, OLD.`rapor_icerigi`, OLD.`rapor_durumu`,
    OLD.`rapor_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`,
    @silen_kullanici_id, CURRENT_TIMESTAMP
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_raporlar` BEFORE UPDATE ON `raporlar` FOR EACH ROW BEGIN
  INSERT INTO `silinen_raporlar` (
    `rapor_basligi`, `rapor_icerigi`, `rapor_durumu`,
    `rapor_tarihi`, `kullanici_id`, `olusturulma_tarihi`,
    `silen_kullanici_id`, `silinme_tarihi`
  ) VALUES (
    OLD.`rapor_basligi`, OLD.`rapor_icerigi`, OLD.`rapor_durumu`,
    OLD.`rapor_tarihi`, OLD.`kullanici_id`, OLD.`veri_giris_tarihi`,
    @silen_kullanici_id, CURRENT_TIMESTAMP
  );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_aracli_kazalar`
--

CREATE TABLE `silinen_aracli_kazalar` (
  `id` int(11) NOT NULL,
  `arac_id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `is_kazasi_tip_id` int(11) NOT NULL,
  `yaralanma_durumu_id` int(11) NOT NULL,
  `yaralanma_tip_id` int(11) NOT NULL,
  `kaza_aciklamasi` text NOT NULL,
  `is_kazasi_tarihi` datetime NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `silinen_aracli_kazalar`
--

INSERT INTO `silinen_aracli_kazalar` (`id`, `arac_id`, `tc_kimlik`, `is_kazasi_tip_id`, `yaralanma_durumu_id`, `yaralanma_tip_id`, `kaza_aciklamasi`, `is_kazasi_tarihi`, `olusturan_kullanici_id`, `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 2, '11122233344', 5, 4, 5, 'Süratli bir şekilde feci çarpma.', '2025-05-14 18:26:00', 2, '2025-05-15 18:27:00', 2, '2025-05-15 18:27:30'),
(2, 2, '21334455678', 1, 4, 5, 'Şiddetli çarpma.', '2025-05-12 18:27:00', 2, '2025-05-15 18:27:54', 2, '2025-05-15 18:34:30'),
(3, 2, '21334455678', 11, 4, 5, 'Şiddetli çarpma.', '2025-05-12 18:27:00', 2, '2025-05-15 18:27:54', 2, '2025-05-15 19:14:28'),
(4, 17, '77725228728', 13, 7, 7, 'a 65', '2025-05-29 00:31:00', 14, '2025-05-29 00:32:25', NULL, '2025-05-29 00:32:48'),
(5, 17, '77725228728', 13, 7, 7, 'a 655555', '2025-05-29 00:32:00', 14, '2025-05-29 00:32:25', NULL, '2025-05-29 00:33:19');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_arac_bilgi`
--

CREATE TABLE `silinen_arac_bilgi` (
  `id` int(11) NOT NULL,
  `arac_id` int(11) NOT NULL,
  `plaka_no` varchar(20) DEFAULT NULL,
  `arac_tipi_id` int(11) NOT NULL,
  `marka_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `uretim_yili` year(4) DEFAULT NULL,
  `firma_id` int(11) NOT NULL,
  `arac_durum_id` int(11) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `silinen_arac_bilgi`
--

INSERT INTO `silinen_arac_bilgi` (`id`, `arac_id`, `plaka_no`, `arac_tipi_id`, `marka_id`, `model_id`, `uretim_yili`, `firma_id`, `arac_durum_id`, `olusturan_kullanici_id`, `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 3, '35 UD 2005', 2, 1, 11, '2022', 1, 2, 1, '2025-05-07 10:37:35', 2, '2025-05-14 20:08:25'),
(2, 3, '35 UD 205', 2, 1, 11, '2022', 1, 2, 2, '2025-05-07 10:37:35', 2, '2025-05-14 20:09:03'),
(3, 3, '35 UD 205', 2, 1, 11, '2022', 1, 1, 2, '2025-05-07 10:37:35', 2, '2025-05-14 20:10:18'),
(4, 3, '35 UD 205', 2, 1, 11, '2022', 1, 2, 2, '2025-05-07 10:37:35', 2, '2025-05-14 20:11:01'),
(5, 3, '38 UND 647', 2, 1, 11, '2022', 1, 2, 2, '2025-05-07 10:37:35', 2, '2025-05-14 20:14:52'),
(6, 3, '38 AKP 647', 2, 1, 11, '2022', 1, 2, 2, '2025-05-07 10:37:35', 2, '2025-05-14 20:18:12'),
(14, 2, '35353535353', 1, 5, 20, '2019', 2, 1, 2, '2025-05-06 07:55:54', NULL, '2025-05-16 09:46:15'),
(15, 2, '35353535353', 1, 5, 20, '2019', 2, 3, 2, '2025-05-06 07:55:54', NULL, '2025-05-16 09:46:34'),
(16, 2, '35353535353', 1, 5, 20, '2019', 2, 3, 2, '2025-05-06 07:55:54', NULL, '2025-05-16 09:53:50'),
(17, 2, '35353535353', 1, 5, 20, '2018', 2, 2, 2, '2025-05-06 07:55:54', NULL, '2025-05-16 09:54:59'),
(18, 10, '35 CBD 457', 3, 4, 17, '2022', 3, 3, 2, '2025-05-16 10:01:38', NULL, '2025-05-16 10:19:05'),
(19, 10, '35 CBD 457', 3, 4, 17, '2022', 3, 1, 2, '2025-05-16 10:01:38', NULL, '2025-05-16 10:19:55'),
(20, 10, '35 CBD 457', 3, 4, 17, '2022', 3, 2, 2, '2025-05-16 10:01:38', NULL, '2025-05-16 10:20:39'),
(21, 10, '35 CBD 457', 3, 4, 17, '2022', 3, 3, 2, '2025-05-16 10:01:38', NULL, '2025-05-16 10:21:12'),
(22, 10, '35 CBD 457', 3, 4, 17, '2022', 3, 1, 2, '2025-05-16 10:01:38', NULL, '2025-05-16 10:33:30'),
(23, 9, '35 ABC 4795', 6, 8, 29, '2021', 2, 3, 2, '2025-05-16 09:31:22', NULL, '2025-05-16 10:34:36'),
(24, 11, '35 HH 647', 1, 1, 1, '2023', 6, 1, 2, '2025-05-16 10:44:21', NULL, '2025-05-16 10:45:14'),
(25, 11, '35 HH 647', 1, 1, 1, '2023', 6, 3, 2, '2025-05-16 10:44:21', NULL, '2025-05-16 10:46:03'),
(26, 11, '35 HH 647', 1, 1, 1, '2023', 6, 2, 2, '2025-05-16 10:44:21', NULL, '2025-05-16 10:46:30'),
(27, 12, '35 UD 205', 4, 6, 25, '2015', 1, 1, 2, '2025-05-16 12:17:31', NULL, '2025-05-16 12:28:08'),
(28, 13, '35 CHP 355', 1, 2, 13, '2022', 3, 1, 2, '2025-05-22 07:02:54', NULL, '2025-05-23 18:16:59'),
(29, 13, '35 CHP 355', 1, 2, 13, '2022', 3, 3, 2, '2025-05-22 07:02:54', NULL, '2025-05-23 18:17:21'),
(30, 14, '35 LKP 4125', 2, 9, 30, '2018', 2, 1, 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:30:42'),
(32, 14, '35 LKP 4125', 2, 9, 30, '2018', 2, 1, 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:38:39'),
(33, 14, '35 LKP 4125', 2, 9, 30, '2018', 2, 1, 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:45:43'),
(34, 14, '35 LKP 4125', 2, 9, 30, '2018', 2, 1, 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:46:42'),
(35, 13, '35 CHP 355', 1, 2, 13, '2022', 3, 1, 2, '2025-05-22 07:02:54', NULL, '2025-05-23 18:59:05'),
(36, 2, '35353535353', 1, 5, 20, '2018', 2, 1, 2, '2025-05-06 07:55:54', 2, '2025-05-24 14:18:25'),
(37, 9, '35 ABC 4795', 6, 8, 29, '2021', 2, 1, 2, '2025-05-16 09:31:22', NULL, '2025-05-24 16:19:00'),
(38, 9, '35 MLH 4795', 6, 8, 29, '2021', 2, 1, 2, '2025-05-16 09:31:22', 2, '2025-05-24 16:19:59'),
(39, 13, '35 CHP 355', 1, 2, 13, '2022', 3, 1, 2, '2025-05-22 07:02:54', NULL, '2025-05-25 19:00:09'),
(40, 11, '35 HH 647', 1, 1, 1, '2023', 6, 1, 2, '2025-05-16 10:44:21', NULL, '2025-05-25 19:00:27'),
(41, 13, '35 CHP 355', 1, 2, 13, '2022', 3, 1, 2, '2025-05-22 07:02:54', NULL, '2025-05-25 19:06:13'),
(42, 12, '35 UD 205', 4, 6, 25, '2015', 1, 3, 2, '2025-05-16 12:17:31', NULL, '2025-05-28 21:09:58'),
(43, 11, '35 HH 647', 1, 1, 1, '2023', 6, 1, 2, '2025-05-16 10:44:21', 14, '2025-05-29 00:14:01'),
(44, 12, '35 UD 205', 4, 6, 25, '2015', 1, 3, 14, '2025-05-16 12:17:31', NULL, '2025-05-29 14:22:18');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_arac_muayene`
--

CREATE TABLE `silinen_arac_muayene` (
  `id` int(11) NOT NULL,
  `muayene_id` int(11) NOT NULL,
  `arac_id` int(11) NOT NULL,
  `muayene_tarihi` datetime NOT NULL,
  `muayeneden_gecti_mi` tinyint(1) DEFAULT 0,
  `muayene_gecerlilik_tarihi` datetime DEFAULT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `silinen_arac_muayene`
--

INSERT INTO `silinen_arac_muayene` (`id`, `muayene_id`, `arac_id`, `muayene_tarihi`, `muayeneden_gecti_mi`, `muayene_gecerlilik_tarihi`, `olusturan_kullanici_id`, `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 2, 2, '2025-05-14 09:43:00', 0, '2026-05-14 09:43:00', NULL, '2025-05-16 09:46:15', NULL, '2025-05-16 09:58:28'),
(2, 3, 2, '2025-05-14 09:43:00', 0, '2026-05-14 09:43:00', NULL, '2025-05-16 09:46:34', NULL, '2025-05-16 10:01:49'),
(3, 4, 2, '2025-05-14 09:43:00', 0, NULL, NULL, '2025-05-16 09:53:50', NULL, '2025-05-16 10:01:53'),
(4, 7, 10, '2025-02-13 10:01:00', 1, '2026-02-13 10:01:00', NULL, '2025-05-16 10:19:05', NULL, '2025-05-16 10:21:48'),
(5, 6, 10, '2025-02-13 10:01:00', 0, '2026-02-13 10:01:00', NULL, '2025-05-16 10:01:38', NULL, '2025-05-16 10:21:53'),
(6, 8, 10, '2025-05-15 10:01:00', 1, '2026-05-15 10:01:00', NULL, '2025-05-16 10:21:12', NULL, '2025-05-16 10:33:30'),
(7, 1, 9, '2025-05-13 09:31:00', 0, '2026-05-13 09:31:00', NULL, '2025-05-16 09:31:22', NULL, '2025-05-16 10:34:36'),
(8, 9, 11, '2025-02-11 10:44:00', 1, '2026-02-11 10:44:00', 2, '2025-05-16 10:44:21', NULL, '2025-05-16 10:45:14'),
(9, 9, 11, '2025-05-15 10:44:00', 0, NULL, 2, '2025-05-16 10:44:21', NULL, '2025-05-16 10:46:30'),
(10, 5, 2, '2025-05-14 09:43:00', 1, '2026-05-14 09:43:00', NULL, '2025-05-16 09:54:59', NULL, '2025-05-16 12:18:03'),
(11, 10, 12, '2025-02-05 12:17:00', 1, '2026-02-05 12:17:00', 2, '2025-05-16 12:17:31', NULL, '2025-05-16 12:28:08'),
(12, 11, 13, '2025-05-22 07:01:00', 1, '2026-05-22 07:01:00', 2, '2025-05-22 07:02:54', NULL, '2025-05-23 18:16:59'),
(13, 11, 13, '2025-05-22 07:01:00', 0, NULL, 2, '2025-05-22 07:02:54', NULL, '2025-05-23 18:17:21'),
(14, 12, 14, '2025-05-23 18:22:00', 1, '2026-02-23 18:22:00', 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:30:42'),
(16, 12, 14, '2025-05-23 18:22:00', 1, '2026-02-23 18:22:00', 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:38:39'),
(17, 12, 14, '2025-05-23 18:22:00', 1, '2026-02-23 18:22:00', 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:45:43'),
(18, 12, 14, '2025-05-23 18:22:00', 1, '2026-02-23 18:22:00', 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:46:42'),
(19, 11, 13, '2025-05-23 17:01:00', 1, '2026-05-23 17:01:00', 2, '2025-05-22 07:02:54', NULL, '2025-05-23 18:59:05'),
(20, 1, 9, '2025-05-16 09:31:00', 1, '2026-05-16 09:31:00', 2, '2025-05-16 09:31:22', NULL, '2025-05-24 16:19:00'),
(21, 11, 13, '2024-06-15 17:01:00', 1, '2025-06-15 17:01:00', 2, '2025-05-22 07:02:54', NULL, '2025-05-25 19:00:09'),
(22, 9, 11, '2025-05-16 08:44:00', 1, '2026-05-16 08:44:00', 2, '2025-05-16 10:44:21', NULL, '2025-05-25 19:00:27'),
(23, 11, 13, '2024-05-25 17:01:00', 1, '2025-05-25 17:01:00', 2, '2025-05-22 07:02:54', NULL, '2025-05-25 19:06:13'),
(24, 10, 12, '2025-05-13 12:17:00', 0, NULL, 2, '2025-05-16 12:17:31', NULL, '2025-05-28 21:09:58'),
(25, 10, 12, '2025-05-28 12:17:00', 0, NULL, 14, '2025-05-16 12:17:31', NULL, '2025-05-29 14:22:18');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_arac_operator_atama`
--

CREATE TABLE `silinen_arac_operator_atama` (
  `id` int(11) NOT NULL,
  `arac_id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `atama_tarihi` datetime NOT NULL,
  `gorev_sonu_tarihi` datetime DEFAULT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `silinen_arac_operator_atama`
--

INSERT INTO `silinen_arac_operator_atama` (`id`, `arac_id`, `tc_kimlik`, `atama_tarihi`, `gorev_sonu_tarihi`, `olusturan_kullanici_id`, `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 3, '76889900123', '2025-05-01 19:17:00', '2025-12-01 19:17:00', 1, '2025-05-14 19:17:08', NULL, '2025-05-14 19:17:15'),
(2, 3, '12312312312', '2025-05-01 19:17:00', '0000-00-00 00:00:00', 1, '2025-05-14 19:17:51', NULL, '2025-05-14 19:18:41'),
(3, 2, '70578578578', '2025-05-02 19:17:00', '2026-03-02 19:18:00', 1, '2025-05-14 19:18:05', NULL, '2025-05-14 19:18:57'),
(4, 3, '76889900123', '2025-05-01 19:27:00', '0000-00-00 00:00:00', 1, '2025-05-14 19:27:10', NULL, '2025-05-14 19:27:17'),
(5, 2, '45645645645', '2025-05-02 19:17:00', '2026-03-02 19:18:00', 1, '2025-05-14 19:18:05', NULL, '2025-05-14 19:31:19'),
(6, 3, '12312312312', '2025-05-01 19:17:00', '2025-09-17 19:18:00', 1, '2025-05-14 19:17:51', NULL, '2025-05-14 19:31:25'),
(7, 2, '11122233344', '2025-02-20 19:34:00', '0000-00-00 00:00:00', 1, '2025-05-14 19:43:53', NULL, '2025-05-14 19:43:58'),
(8, 3, '10112233456', '2025-05-01 19:17:00', '2025-09-17 19:18:00', 1, '2025-05-14 19:17:51', NULL, '2025-05-14 19:56:48'),
(9, 2, '11122233344', '2025-02-20 19:34:00', '0000-00-00 00:00:00', 1, '2025-05-14 19:34:43', NULL, '2025-05-14 19:59:40'),
(10, 3, '14345678901', '2025-05-01 19:17:00', '2025-09-17 19:18:00', 1, '2025-05-14 19:17:51', NULL, '2025-05-14 20:06:59'),
(11, 3, '12132435366', '2025-05-01 19:17:00', '2025-09-17 19:18:00', 1, '2025-05-14 19:17:51', NULL, '2025-05-14 20:22:44'),
(12, 2, '21223344567', '2025-02-20 19:34:00', NULL, 1, '2025-05-14 19:34:43', NULL, '2025-05-14 20:23:46'),
(13, 3, '12132435366', '2025-02-13 19:17:00', '2025-09-17 19:18:00', 1, '2025-05-14 19:17:51', NULL, '2025-05-14 20:28:47'),
(14, 3, '12132435366', '2025-02-13 19:17:00', '2025-09-17 19:18:00', 1, '2025-05-14 19:17:51', NULL, '2025-05-14 20:30:07'),
(15, 3, '12132435366', '2025-02-13 19:17:00', NULL, 1, '2025-05-14 19:17:51', NULL, '2025-05-14 20:30:32'),
(16, 2, '12132435366', '2025-02-13 19:17:00', NULL, 1, '2025-05-14 19:17:51', NULL, '2025-05-14 20:32:48'),
(17, 3, '10112233456', '2025-05-15 16:22:00', '0000-00-00 00:00:00', 1, '2025-05-15 18:22:41', NULL, '2025-05-15 18:22:52'),
(18, 3, '12312312312', '2025-05-07 18:23:00', '0000-00-00 00:00:00', 1, '2025-05-15 18:24:01', NULL, '2025-05-15 18:24:04'),
(19, 3, '12312312312', '2025-05-07 18:23:00', '0000-00-00 00:00:00', 1, '2025-05-15 18:23:55', NULL, '2025-05-15 18:24:06'),
(20, 2, '12345678901', '2025-02-20 19:34:00', NULL, 1, '2025-05-14 19:34:43', NULL, '2025-05-15 19:07:08'),
(21, 2, '21334455678', '2025-02-13 19:17:00', NULL, 1, '2025-05-14 19:17:51', NULL, '2025-05-15 19:07:21'),
(22, 3, '12312312312', '2025-05-07 18:23:00', '0000-00-00 00:00:00', 1, '2025-05-15 18:23:07', NULL, '2025-05-15 19:08:10'),
(23, 3, '12312312312', '2025-05-07 18:23:00', NULL, 1, '2025-05-15 18:23:07', NULL, '2025-05-22 07:34:49'),
(24, 3, '76889900123', '2025-05-06 19:01:00', '0000-00-00 00:00:00', 1, '2025-05-15 19:02:03', NULL, '2025-05-22 07:38:02'),
(25, 3, '76889900123', '2025-05-22 07:35:00', NULL, 2, '2025-05-15 19:02:03', NULL, '2025-05-22 07:42:31'),
(26, 13, '12345678901', '2025-05-22 07:43:00', '2025-11-22 07:43:00', 1, '2025-05-22 07:43:11', NULL, '2025-05-22 07:44:51'),
(27, 13, '12345678901', '2025-05-22 07:43:00', '2025-11-22 07:43:00', 1, '2025-05-22 07:44:47', NULL, '2025-05-22 07:47:12'),
(28, 13, '21134455678', '2025-05-22 07:01:00', '2025-09-22 07:02:00', 2, '2025-05-22 07:02:54', NULL, '2025-05-22 07:56:00'),
(29, 13, '21134455678', '2025-05-22 07:50:00', '2026-01-14 07:02:00', 2, '2025-05-22 07:02:54', NULL, '2025-05-22 08:15:32'),
(30, 13, '21134455678', '2025-05-22 07:50:00', '2026-01-14 07:02:00', 2, '2025-05-22 07:02:54', NULL, '2025-05-22 08:15:47'),
(31, 2, '14345678901', '2025-05-06 19:07:00', '0000-00-00 00:00:00', 1, '2025-05-15 19:07:38', NULL, '2025-05-23 18:26:13'),
(32, 12, '43556677890', '2025-05-07 12:17:00', '0000-00-00 00:00:00', 2, '2025-05-16 12:17:31', NULL, '2025-05-23 18:26:13'),
(33, 14, '87990011234', '2025-05-23 18:22:00', '0000-00-00 00:00:00', 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:26:13'),
(34, 14, '99900011122', '2025-05-23 18:22:00', '0000-00-00 00:00:00', 2, '2025-05-23 18:30:42', NULL, '2025-05-23 18:37:06'),
(35, 14, '87990011234', '2025-05-23 18:22:00', NULL, 2, '2025-05-23 18:22:36', NULL, '2025-05-23 18:37:10'),
(36, 14, '10112233456', '2025-05-05 18:45:00', '0000-00-00 00:00:00', 2, '2025-05-23 18:45:43', NULL, '2025-05-23 18:46:05'),
(37, 14, '14345678901', '2025-05-23 18:38:00', '0000-00-00 00:00:00', 2, '2025-05-23 18:38:39', NULL, '2025-05-23 18:46:09'),
(38, 13, '12345678901', '2025-05-22 07:47:00', '2026-02-22 07:47:00', 1, '2025-05-22 07:47:32', NULL, '2025-05-23 18:52:35'),
(39, 13, '21134455678', '2025-05-22 08:15:00', '2026-01-14 07:02:00', 2, '2025-05-22 07:02:54', NULL, '2025-05-23 18:59:05'),
(40, 3, '76889900123', '2025-05-22 07:41:00', '2025-12-22 07:42:00', 2, '2025-05-15 19:02:03', 2, '2025-05-24 20:52:23'),
(41, 13, '12345678901', '2025-05-23 18:47:00', '2025-12-23 07:47:00', 2, '2025-05-23 18:59:05', 2, '2025-05-24 21:05:00'),
(42, 13, '12345678901', '2025-05-23 18:47:00', '2025-12-23 07:47:00', 2, '2025-05-22 07:47:32', 2, '2025-05-24 21:05:00'),
(43, 3, '12312312312', '2025-05-22 07:33:00', NULL, 1, '2025-05-15 18:23:07', 2, '2025-05-24 21:05:53'),
(44, 13, '10112233456', '2025-05-25 19:05:00', '2026-08-25 19:05:00', 2, '2025-05-25 19:06:13', 2, '2025-05-26 15:07:07'),
(45, 12, '43556677890', '2025-05-07 12:17:00', NULL, 2, '2025-05-16 12:17:31', 2, '2025-05-26 16:13:11'),
(46, 15, '70433154100', '2025-05-28 22:22:00', '0000-00-00 00:00:00', 14, '2025-05-28 22:22:18', NULL, '2025-05-29 00:26:55'),
(47, 15, '70433154100', '2025-05-21 22:22:00', '0000-00-00 00:00:00', 14, '2025-05-28 22:22:18', NULL, '2025-05-29 00:27:04'),
(48, 16, '77725228728', '2025-05-14 00:04:00', '0000-00-00 00:00:00', 14, '2025-05-29 00:04:16', NULL, '2025-05-29 00:27:09'),
(49, 13, '45187053494', '2025-05-08 00:29:00', '2025-08-14 00:30:00', 1, '2025-05-29 00:30:04', NULL, '2025-05-29 00:30:17'),
(50, 16, '77725228728', '2025-05-14 00:04:00', NULL, 14, '2025-05-29 00:04:16', NULL, '2025-05-29 00:30:21');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_duyurular`
--

CREATE TABLE `silinen_duyurular` (
  `id` int(11) NOT NULL,
  `duyuru_basligi` varchar(255) DEFAULT NULL,
  `duyuru_icerigi` text DEFAULT NULL,
  `duyuru_tarihi` datetime DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime DEFAULT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `silinen_duyurular`
--

INSERT INTO `silinen_duyurular` (`id`, `duyuru_basligi`, `duyuru_icerigi`, `duyuru_tarihi`, `kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(3, 'Baret Kullanımı', 'Baret kullanmayı unutmalayım.', '2025-05-19 09:07:00', 1, '2025-05-19 13:08:54', NULL, '2025-05-19 13:11:04'),
(4, 'Baret Kullanımı', 'aaaaaaaaaa aaaaaaaaaaaa aaaaaaaaa', '2025-05-07 13:23:00', 2, '2025-05-19 13:23:27', NULL, '2025-05-19 16:36:37'),
(5, 'Sigara Yasağı', 'Artık koridorlarda sigara içilmeyecek.', '2025-05-19 12:36:00', 2, '2025-05-19 16:36:19', NULL, '2025-05-19 16:42:05'),
(6, 'B Eğitimi', 'B Eğitimi yapılacaktır.', '2025-05-23 19:15:00', 2, '2025-05-23 19:15:08', NULL, '2025-05-23 19:16:22'),
(7, 'B Eğitimi', 'B Eğitimi yapılacaktır. Herkesin bilgisine.', '2025-05-23 19:15:00', 2, '2025-05-23 19:16:22', NULL, '2025-05-23 19:16:39'),
(8, 'B Eğitimi', 'B Eğitimi yapılacaktır. Herkesin bilgisine. Kolay gelsin.', '2025-05-23 19:16:00', 2, '2025-05-23 19:16:39', NULL, '2025-05-23 19:16:52'),
(9, 'B Eğitimi', 'B Eğitimi yapılacaktır. Herkesin bilgisine. Kolay gelsin.', '2025-05-23 20:16:00', 2, '2025-05-23 19:16:52', NULL, '2025-05-23 19:27:33'),
(10, 'Yeni İş Sağlığı Eğitimi', 'Tüm personel için 20 Mayıs tarihinde iş sağlığı eğitimi yapılacaktır.', '2025-05-19 10:00:00', 1, '2025-05-19 12:04:00', NULL, '2025-05-24 16:03:00'),
(11, 'Yeni İş Sağlığı Eğitimi', 'Tüm personel için 20 Mayıs tarihinde iş sağlığı eğitimi yapılacaktır.', '2025-05-24 16:00:00', 2, '2025-05-24 16:03:00', NULL, '2025-05-24 16:03:48'),
(12, 'Yangın Tatbikatı', '2 gün sonra yangın tatbikatı yapılacaktır.', '2025-05-23 17:02:00', 2, '2025-05-23 19:04:53', 2, '2025-05-24 16:12:02'),
(13, 'Yangın Tatbikatı', '2 gün sonra yangın tatbikatı yapılacaktır.', '2025-05-24 16:02:00', 2, '2025-05-24 16:12:02', 2, '2025-05-24 16:14:12'),
(14, 'C Eğitimi', 'C eğitimi yapılacaktır.', '2025-05-23 19:31:00', 2, '2025-05-23 19:31:53', 14, '2025-05-29 00:37:20'),
(15, 'C Eğitimi', 'C eğitimi yapılacaktır.', '2025-05-27 19:31:00', 14, '2025-05-29 00:37:20', 14, '2025-05-29 00:37:43'),
(16, 'C Eğitimi', 'C eğitimi yapılacaktır.', '2025-05-28 00:31:00', 14, '2025-05-29 00:37:43', 14, '2025-05-29 00:39:24'),
(17, 'C Eğitimi', 'C eğitimi yapılacaktır.', '2025-05-29 00:31:00', 14, '2025-05-29 00:39:24', 14, '2025-05-29 00:39:42'),
(18, 'Baret Kullanımı', 'Baret Kullanımına dikkat edelim.', '2025-05-23 18:00:00', 2, '2025-05-23 19:05:58', 14, '2025-05-29 00:39:47'),
(19, 'B Eğitimi', 'B Eğitimi yapılacaktır. Herkesin bilgisine.', '2025-05-23 19:14:00', 2, '2025-05-23 19:27:33', 14, '2025-05-29 00:39:50'),
(20, 'Sigara Yasağı', 'Artık koridorlarda sigara içilmeyecek. Bilginize', '2025-05-19 12:36:00', 2, '2025-05-19 16:42:05', 14, '2025-05-29 00:39:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_arac_durumlari`
--

CREATE TABLE `silinen_hazir_arac_durumlari` (
  `id` int(11) NOT NULL,
  `arac_durum_id` int(11) NOT NULL,
  `arac_durum_adi` varchar(50) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_arac_tipleri`
--

CREATE TABLE `silinen_hazir_arac_tipleri` (
  `id` int(11) NOT NULL,
  `arac_tipi_id` int(11) NOT NULL,
  `arac_tipi_adi` varchar(100) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_belgeler`
--

CREATE TABLE `silinen_hazir_belgeler` (
  `id` int(11) NOT NULL,
  `belge_id` int(11) NOT NULL,
  `belge_adi` varchar(255) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_hazir_belgeler`
--

INSERT INTO `silinen_hazir_belgeler` (`id`, `belge_id`, `belge_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 11, 'İzmir', NULL, '2025-05-01 12:02:29', NULL, '2025-05-28 21:12:18'),
(2, 12, 'Kalıp Atma Teknikleri 35', 1, '2025-05-05 14:02:27', NULL, '2025-05-28 21:12:41'),
(3, 10, 'Hijyen Belgesi 35', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:13:05');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_ehliyetler`
--

CREATE TABLE `silinen_hazir_ehliyetler` (
  `id` int(11) NOT NULL,
  `ehliyet_id` int(11) NOT NULL,
  `ehliyet_adi` varchar(255) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_firmalar`
--

CREATE TABLE `silinen_hazir_firmalar` (
  `id` int(11) NOT NULL,
  `firma_id` int(11) NOT NULL,
  `firma_adi` varchar(255) NOT NULL,
  `sektor` varchar(255) DEFAULT NULL,
  `adres` varchar(500) DEFAULT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_hazir_firmalar`
--

INSERT INTO `silinen_hazir_firmalar` (`id`, `firma_id`, `firma_adi`, `sektor`, `adres`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(2, 6, 'Avukatlık Birosu', NULL, NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:18:41'),
(3, 7, 'İzmir', NULL, NULL, 1, '2025-05-01 12:02:29', NULL, '2025-05-28 21:19:27'),
(4, 4, 'Kaya Kalıp ve İskele Sistemleri', 'İskele / Kalıp', 'Kocaeli, Gebze, Organize Sanayi Bölgesi', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:19:42'),
(5, 6, 'Akyol İnşaat Ltd. Şti.', NULL, NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:19:56'),
(6, 7, 'İzmir Enka İnşaat Ltd. Şti.', NULL, NULL, 1, '2025-05-01 12:02:29', NULL, '2025-05-28 21:20:07'),
(7, 6, 'Akyol İnşaat Ltd. Şti.', 'İnşaat', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:20:23'),
(8, 7, 'İzmir Enka İnşaat Ltd. Şti.', 'İnşaat', NULL, 1, '2025-05-01 12:02:29', NULL, '2025-05-28 21:20:27'),
(9, 8, 'Ağaoğlu İnşaat Ltd. Şti.', 'İnşaat', 'İzmir', 1, '2025-05-28 21:22:14', NULL, '2025-05-28 21:23:11'),
(10, 5, 'Usta Mühendislik ve Taahhüt', 'İnşaat Taahhüt', 'Bursa, Nilüfer, Görükle Mah. No:56', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:23:33');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_is_kazalari`
--

CREATE TABLE `silinen_hazir_is_kazalari` (
  `id` int(11) NOT NULL,
  `is_kazasi_tip_id` int(11) NOT NULL,
  `is_kazasi_tipi_adi` varchar(255) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_hazir_is_kazalari`
--

INSERT INTO `silinen_hazir_is_kazalari` (`id`, `is_kazasi_tip_id`, `is_kazasi_tipi_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(3, 11, 'Araç Çarpması 35', 1, '2025-05-05 14:02:27', NULL, '2025-05-28 21:24:20'),
(4, 12, 'Araba Çarpması', 2, '2025-05-15 19:13:52', NULL, '2025-05-28 21:25:33'),
(5, 12, '', 2, '2025-05-15 19:13:52', NULL, '2025-05-28 21:25:55'),
(6, 12, 'Diğer', 2, '2025-05-15 19:13:52', NULL, '2025-05-28 21:33:16'),
(8, 12, 'Başka', 2, '2025-05-15 19:13:52', NULL, '2025-05-28 21:36:48'),
(9, 12, 'Darbeye Bağlı Yaralanma', 2, '2025-05-15 19:13:52', NULL, '2025-05-28 21:38:26'),
(10, 12, 'Duvar Çökmesi', 2, '2025-05-15 19:13:52', NULL, '2025-05-28 21:39:00'),
(11, 13, 'A 35', 14, '2025-05-29 00:32:25', NULL, '2025-05-29 00:34:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_markalar`
--

CREATE TABLE `silinen_hazir_markalar` (
  `id` int(11) NOT NULL,
  `marka_id` int(11) NOT NULL,
  `marka_adi` varchar(50) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `silinen_hazir_markalar`
--

INSERT INTO `silinen_hazir_markalar` (`id`, `marka_id`, `marka_adi`, `olusturan_kullanici_id`, `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 1, 'Volvo', 1, '2025-05-04 10:00:00', NULL, '2025-05-05 14:52:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_meslekler`
--

CREATE TABLE `silinen_hazir_meslekler` (
  `id` int(11) NOT NULL,
  `meslek_id` int(11) NOT NULL,
  `meslek_adi` varchar(255) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_hazir_meslekler`
--

INSERT INTO `silinen_hazir_meslekler` (`id`, `meslek_id`, `meslek_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 11, 'Avukat', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:39:36'),
(2, 12, 'İzmir', NULL, '2025-05-01 12:02:29', NULL, '2025-05-28 21:39:56'),
(3, 12, 'Boyacı', NULL, '2025-05-01 12:02:29', NULL, '2025-05-28 21:40:01');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_modeller`
--

CREATE TABLE `silinen_hazir_modeller` (
  `id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `marka_id` int(11) NOT NULL,
  `model_adi` varchar(50) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `veri_giris_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `silinen_hazir_modeller`
--

INSERT INTO `silinen_hazir_modeller` (`id`, `model_id`, `marka_id`, `model_adi`, `olusturan_kullanici_id`, `veri_giris_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 1, 1, 'EC210', 1, '2025-05-04 10:00:00', NULL, '2025-05-06 07:05:07');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_rapor_durumlari`
--

CREATE TABLE `silinen_hazir_rapor_durumlari` (
  `id` int(11) NOT NULL,
  `rapor_durum_adi` varchar(100) DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime DEFAULT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `silinen_hazir_rapor_durumlari`
--

INSERT INTO `silinen_hazir_rapor_durumlari` (`id`, `rapor_durum_adi`, `kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 'Sorun yok', NULL, '2025-05-19 12:04:00', NULL, '2025-05-28 21:41:53'),
(2, 'Sorun devam ediyor', NULL, '2025-05-19 12:04:00', NULL, '2025-05-28 21:41:56'),
(3, 'Sorun çözüldü', NULL, '2025-05-19 12:04:00', NULL, '2025-05-28 21:41:58');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_uyarilar`
--

CREATE TABLE `silinen_hazir_uyarilar` (
  `id` int(11) NOT NULL,
  `uyari_tip_id` int(11) NOT NULL,
  `uyari_tipi_adi` varchar(255) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_hazir_uyarilar`
--

INSERT INTO `silinen_hazir_uyarilar` (`id`, `uyari_tip_id`, `uyari_tipi_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 7, 'İhlal Örnek Deneme 1', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:43:54'),
(2, 7, '', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:45:49'),
(3, 8, 'Örnek Uyarı 35', 1, '2025-05-05 14:02:27', NULL, '2025-05-28 21:46:26'),
(4, 8, 'Yanıcı Madde Kullanımı', 1, '2025-05-05 14:02:27', NULL, '2025-05-28 21:46:39');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_yaralanma_durumlar`
--

CREATE TABLE `silinen_hazir_yaralanma_durumlar` (
  `id` int(11) NOT NULL,
  `yaralanma_durum_id` int(11) NOT NULL,
  `yaralanma_durum_adi` varchar(255) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_hazir_yaralanma_durumlar`
--

INSERT INTO `silinen_hazir_yaralanma_durumlar` (`id`, `yaralanma_durum_id`, `yaralanma_durum_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 6, 'Orta Derece Yaralanma 35', 1, '2025-05-05 14:02:27', NULL, '2025-05-28 21:47:50'),
(2, 4, 'Uzuv Kaybı', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:49:03'),
(6, 5, 'Hayati Tehlike', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:50:35'),
(7, 7, 'A 45', 14, '2025-05-29 00:32:25', NULL, '2025-05-29 00:33:56');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_hazir_yaralanma_tipler`
--

CREATE TABLE `silinen_hazir_yaralanma_tipler` (
  `id` int(11) NOT NULL,
  `yaralanma_tip_id` int(11) NOT NULL,
  `yaralanma_tipi_adi` varchar(255) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_hazir_yaralanma_tipler`
--

INSERT INTO `silinen_hazir_yaralanma_tipler` (`id`, `yaralanma_tip_id`, `yaralanma_tipi_adi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 6, 'Bilinç Kaybı 35', 1, '2025-05-05 14:02:27', NULL, '2025-05-28 21:52:57'),
(3, 7, 'A 55', 14, '2025-05-29 00:32:25', NULL, '2025-05-29 00:33:52');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_kullanicilar`
--

CREATE TABLE `silinen_kullanicilar` (
  `id` int(11) NOT NULL,
  `kul_id` int(11) NOT NULL,
  `kul_isim` varchar(100) NOT NULL,
  `kul_mail` varchar(100) NOT NULL,
  `kul_sifre` varchar(100) NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_personel_belgeler`
--

CREATE TABLE `silinen_personel_belgeler` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `belge_id` int(11) NOT NULL,
  `alinma_tarihi` date DEFAULT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_personel_belgeler`
--

INSERT INTO `silinen_personel_belgeler` (`id`, `tc_kimlik`, `belge_id`, `alinma_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, '38383838383', 5, '2025-01-09', 2, '2025-05-01 13:07:28', 2, '2025-05-03 16:18:31'),
(2, '35353535353', 4, '2025-05-21', NULL, '2025-05-01 12:51:41', NULL, '2025-05-03 16:35:30'),
(3, '35353535353', 11, '0000-00-00', 2, '2025-05-01 12:22:29', NULL, '2025-05-03 16:35:38'),
(4, '35353535353', 9, '2024-10-17', 2, '2025-05-03 16:35:10', 2, '2025-05-03 16:37:33'),
(5, '53535353535', 2, '2025-01-14', 2, '2025-05-01 12:35:44', 2, '2025-05-03 16:45:54'),
(6, '53535353535', 2, '2025-01-14', NULL, '2025-05-03 16:45:54', 2, '2025-05-03 16:46:22'),
(7, '45454545454', 4, '2025-02-13', 2, '2025-05-03 18:26:25', 2, '2025-05-03 18:32:51'),
(8, '15635475958', 2, '2021-10-24', 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(9, '15635475958', 5, '2025-04-29', 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(10, '62656265626', 3, '2025-05-13', 2, '2025-05-24 17:02:15', 2, '2025-05-24 17:03:44'),
(11, '12345678901', 1, '2017-04-15', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(12, '12312312312', 1, '2023-01-09', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(13, '12312312312', 10, '2025-04-24', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(14, '12312312312', 9, '2025-04-27', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(15, '11122233344', 1, '2015-03-01', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:07:13'),
(16, '35353535353', 2, '2025-02-19', NULL, '2025-05-03 16:38:23', 2, '2025-05-26 15:11:18'),
(17, '15635475958', 2, '2025-05-26', 2, '2025-05-26 15:04:50', 2, '2025-05-26 16:15:38'),
(18, '15635475958', 9, '2025-05-20', 2, '2025-05-26 15:21:27', 2, '2025-05-26 16:15:38'),
(19, '15635475958', 2, '2021-10-24', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:15:38'),
(20, '15635475958', 5, '2025-04-29', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:15:38'),
(21, '15635475958', 2, '2021-10-24', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:16:06'),
(22, '15635475958', 5, '2025-04-29', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:16:06'),
(23, '10112233344', 3, '2010-05-10', 1, '2025-04-29 18:30:10', 14, '2025-05-26 21:53:19'),
(24, '10112233344', 3, '2010-05-10', 14, '2025-04-29 18:30:10', 14, '2025-05-28 20:35:36');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_personel_ehliyetler`
--

CREATE TABLE `silinen_personel_ehliyetler` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `ehliyet_id` int(11) NOT NULL,
  `alinma_tarihi` date DEFAULT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_personel_ehliyetler`
--

INSERT INTO `silinen_personel_ehliyetler` (`id`, `tc_kimlik`, `ehliyet_id`, `alinma_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, '38383838383', 2, '2024-09-13', 2, '2025-05-01 13:07:28', 2, '2025-05-03 16:18:31'),
(2, '45454545454', 1, '2025-02-14', 2, '2025-05-03 18:26:25', 2, '2025-05-03 18:32:52'),
(3, '15635475958', 2, '2023-06-24', 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(4, '15635475958', 3, '2025-05-14', 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(5, '62656265626', 2, '2017-07-26', 2, '2025-05-24 17:02:15', 2, '2025-05-24 17:03:44'),
(6, '62656265626', 3, '2025-03-04', 2, '2025-05-24 17:02:15', 2, '2025-05-24 17:03:44'),
(7, '12345678901', 1, '2010-05-12', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(8, '10112233456', 2, '2019-06-12', 2, '2025-05-26 14:54:31', 2, '2025-05-26 15:07:07'),
(9, '10112233456', 3, '2025-05-01', 2, '2025-05-26 14:54:31', 2, '2025-05-26 15:07:07'),
(10, '15635475958', 2, '2023-06-24', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:15:38'),
(11, '15635475958', 2, '2023-06-24', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:16:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_personel_gerekli_belge`
--

CREATE TABLE `silinen_personel_gerekli_belge` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `ise_giris_egitimi_var_mi` tinyint(1) DEFAULT NULL,
  `operatorluk_belgesi_var_mi` tinyint(1) DEFAULT NULL,
  `mesleki_yeterlilik_belgesi_var_mi` tinyint(1) DEFAULT NULL,
  `saglik_tetkikleri_oldu_mu` tinyint(1) DEFAULT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_personel_gerekli_belge`
--

INSERT INTO `silinen_personel_gerekli_belge` (`id`, `tc_kimlik`, `ise_giris_egitimi_var_mi`, `operatorluk_belgesi_var_mi`, `mesleki_yeterlilik_belgesi_var_mi`, `saglik_tetkikleri_oldu_mu`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, '38383838383', 1, 1, 1, 1, 2, '2025-05-01 12:55:03', 2, '2025-05-03 16:18:31'),
(2, '35353535353', 1, 1, 1, 1, 2, '2025-05-01 12:02:29', 2, '2025-05-03 16:37:33'),
(3, '35353535353', 1, 1, 1, 1, 2, '2025-05-01 12:02:29', 2, '2025-05-03 16:38:23'),
(4, '53535353535', 1, 1, 1, 1, 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:45:54'),
(5, '53535353535', 1, 1, 1, 1, 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:46:22'),
(6, '53535353535', 1, 1, 1, 1, 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:46:54'),
(7, '45454545454', 1, 1, 1, 1, 2, '2025-05-03 18:26:25', 2, '2025-05-03 18:32:51'),
(8, '10112233456', 1, 0, 1, 1, 1, '2025-05-05 14:20:58', 2, '2025-05-19 18:53:33'),
(9, '10112233456', 1, 0, 1, 1, 1, '2025-05-05 14:20:58', 2, '2025-05-19 19:27:04'),
(10, '10112233456', 1, 0, 1, 1, 1, '2025-05-05 14:20:58', 2, '2025-05-19 19:28:11'),
(11, '15635475958', 1, 0, 1, 1, 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(12, '62656265626', 1, 1, 1, 1, 2, '2025-05-24 17:02:15', 2, '2025-05-24 17:03:44'),
(14, '14345678901', 1, 1, 1, 1, 1, '2025-05-04 00:52:31', 2, '2025-05-24 20:44:04'),
(17, '76889900123', 1, 0, 1, 1, 1, '2025-05-05 14:20:58', 2, '2025-05-24 20:52:23'),
(18, '12345678901', 1, 1, 1, 1, 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(19, '12312312312', 1, 1, 1, 1, 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(20, '11122233344', 1, 0, 1, 1, 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:07:13'),
(21, '10112233456', 1, 0, 1, 1, 1, '2025-05-05 14:20:58', 2, '2025-05-26 14:54:31'),
(22, '10112233456', 1, 0, 1, 1, 1, '2025-05-05 14:20:58', 2, '2025-05-26 15:07:07'),
(23, '35353535353', 1, 1, 1, 1, 2, '2025-05-01 12:02:29', 2, '2025-05-26 15:11:18'),
(24, '55055555555', 1, 0, 1, 1, 1, '2025-05-04 00:52:31', 2, '2025-05-26 15:19:45'),
(25, '43556677890', 1, 1, 1, 0, 1, '2025-05-05 14:20:58', 2, '2025-05-26 16:13:11'),
(26, '15635475958', 1, 0, 1, 1, 2, '2025-05-24 14:31:00', 2, '2025-05-26 16:15:38'),
(27, '15635475958', 1, 0, 1, 1, 2, '2025-05-24 14:31:00', 2, '2025-05-26 16:16:06'),
(28, '10112233344', 1, 0, 1, 1, 1, '2025-04-29 18:30:10', 14, '2025-05-26 21:53:19'),
(29, '21223344567', 1, 0, 0, 1, 1, '2025-05-05 14:20:58', 14, '2025-05-26 21:57:52'),
(30, '10112233344', 1, 0, 1, 1, 1, '2025-04-29 18:30:10', 14, '2025-05-28 20:35:36'),
(31, '10405411526', 0, 1, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:15'),
(32, '14933163735', 1, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:20'),
(33, '17146774186', 1, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:30'),
(34, '22999119148', 1, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:15'),
(35, '25579415521', 1, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:20'),
(36, '30745054447', 0, 1, 0, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:25'),
(37, '33206163294', 1, 1, 0, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:31'),
(38, '37562890028', 1, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:36'),
(39, '37474112490', 0, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:41'),
(40, '46660993538', 1, 1, 0, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:46'),
(41, '48909010016', 1, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:53'),
(42, '62208635365', 1, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:14'),
(43, '69725217525', 0, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:28'),
(44, '68844431069', 0, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:37'),
(45, '79030673830', 1, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:43'),
(46, '83982258713', 0, 0, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:54'),
(47, '78985625753', 0, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:02'),
(48, '68007229034', 1, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:08'),
(49, '88861015819', 0, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:20'),
(50, '97177517016', 1, 0, 0, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:26'),
(51, '99293784270', 0, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:33'),
(52, '97296590277', 1, 1, 0, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:42'),
(53, '53714785969', 1, 1, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:52'),
(54, '87014626383', 1, 0, 0, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:10:00'),
(55, '82922169958', 1, 1, 0, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:10:34'),
(56, '07374167243', 0, 1, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:14:57'),
(57, '00292185156', 1, 1, 0, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:00'),
(58, '03758997571', 1, 0, 0, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:01'),
(59, '04078593213', 0, 0, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:03'),
(60, '05064138367', 0, 1, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:05'),
(61, '08668826343', 1, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:07'),
(62, '09977008860', 1, 1, 0, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:09'),
(63, '11234567890', 1, 1, 1, 1, 14, '2025-05-28 23:21:28', 14, '2025-05-28 23:21:56'),
(64, '41520643611', 1, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-29 01:00:57'),
(65, '11586938068', 1, 0, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-29 13:55:41'),
(66, '10702966706', 1, 0, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-29 13:56:01'),
(67, '12275304637', 0, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:10:33'),
(68, '12275304637', 0, 0, 1, 0, 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:11:29'),
(69, '11586938068', 1, 0, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:12:19'),
(70, '44435566677', 1, 1, 1, 1, 1, '2025-05-04 00:52:31', 14, '2025-05-29 14:31:20'),
(71, '11586938068', 1, 0, 1, 1, 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:31:28'),
(72, '21134455678', 1, 1, 1, 1, 1, '2025-05-04 00:52:58', 14, '2025-05-29 14:32:50');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_personel_is_kazalari`
--

CREATE TABLE `silinen_personel_is_kazalari` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `is_kazasi_tip_id` int(11) NOT NULL,
  `yaralanma_durumu_id` int(11) NOT NULL,
  `yaralanma_tip_id` int(11) NOT NULL,
  `kaza_nedeni` varchar(500) DEFAULT NULL,
  `is_kazasi_tarihi` date NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_personel_is_kazalari`
--

INSERT INTO `silinen_personel_is_kazalari` (`id`, `tc_kimlik`, `is_kazasi_tip_id`, `yaralanma_durumu_id`, `yaralanma_tip_id`, `kaza_nedeni`, `is_kazasi_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, '38383838383', 4, 1, 4, 'aaaaaaaaaaaa aaaaaaaaaaaaaa aaaaaaa', '2024-12-20', 2, '2025-05-01 12:55:03', 2, '2025-05-03 16:18:31'),
(2, '38383838383', 2, 2, 3, 'aaaaaaaaaaaaaaa aaaaaaaaaaaaaaaa aaaaaaa', '2025-04-28', 2, '2025-05-01 12:55:03', 2, '2025-05-03 16:18:31'),
(3, '35353535353', 4, 2, 4, 'aaaaaaaaaaaaaaaaaaa aaaaaaaaaaa aaaaaaa', '2025-05-03', NULL, '2025-05-03 16:26:53', 2, '2025-05-03 16:37:33'),
(4, '35353535353', 4, 2, 4, 'aaaaaaaaaaaaaaaaaaa aaaaaaaaaaa aaaaaaa', '2025-05-01', NULL, '2025-05-03 16:37:33', 2, '2025-05-03 16:38:23'),
(5, '53535353535', 1, 2, 2, 'aaaaaaaa aaaaaaaaaa aaaaaaaaaaa aaaaaaaaaa aaaaaaa', '2025-02-04', 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:45:54'),
(6, '53535353535', 1, 2, 2, 'aaaaaaaa aaaaaaaaaa aaaaaaaaaaa aaaaaaaaaa aaaaaaa', '2025-02-04', NULL, '2025-05-03 16:45:54', 2, '2025-05-03 16:46:22'),
(7, '53535353535', 1, 2, 2, 'aaaaaaaa aaaaaaaaaa aaaaaaaaaaa aaaaaaaaaa aaaaaaa', '2025-02-04', NULL, '2025-05-03 16:46:22', 2, '2025-05-03 16:46:54'),
(8, '45454545454', 3, 1, 3, 'aaaaaaaaaaaa', '2025-04-10', 2, '2025-05-03 18:26:25', 2, '2025-05-03 18:32:52'),
(9, '15635475958', 10, 1, 5, 'Kaygan zeminde yere Düşme', '2025-05-14', 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(10, '12345678901', 1, 1, 1, 'İskeleden düşme sonucu baş bölgesinden yaralanma', '2025-04-10', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(11, '12312312312', 10, 2, 5, 'Şantiye içindeki bir demire takılarak dengesiz bir şekilde düşmeye bağlı yaralanma', '2025-04-25', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(12, '35353535353', 4, 2, 4, 'aaaaaaaaaaaaaaaaaaa aaaaaaaaaaa aaaaaaa', '2025-05-01', NULL, '2025-05-03 16:38:23', 2, '2025-05-26 15:11:18'),
(13, '15635475958', 10, 1, 5, 'Kaygan zeminde yere Düşme', '2025-05-14', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:15:38'),
(14, '15635475958', 3, 2, 1, 'Bıçak Kesiği', '2025-05-26', 2, '2025-05-26 16:03:58', 2, '2025-05-26 16:15:38'),
(15, '15635475958', 10, 1, 5, 'Kaygan zeminde yere Düşme', '2025-05-14', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:16:06'),
(16, '15635475958', 3, 2, 1, 'Bıçak Kesiği', '2025-05-26', 2, '2025-05-26 16:03:58', 2, '2025-05-26 16:16:06'),
(17, '10112233344', 1, 2, 1, 'Dikkatsizlik sonucu elini kesici aletle yaraladı.', '2025-02-01', 1, '2025-04-29 18:30:10', 14, '2025-05-26 21:53:19'),
(18, '10112233344', 1, 2, 1, 'Dikkatsizlik sonucu elini kesici aletle yaraladı.', '2025-02-01', 14, '2025-04-29 18:30:10', 14, '2025-05-28 20:35:36'),
(19, '78578578578', 1, 5, 5, 'Dikkatsizliğe bağlı düşme', '2025-04-03', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 21:50:26'),
(20, '85858585858', 1, 1, 2, 'lllllllllllllll lllllllllllllllll lllllllllll', '2025-05-03', 2, '2025-05-03 19:18:09', NULL, '2025-05-28 21:59:07'),
(21, '85858585858', 5, 2, 2, 'jkkkkkkkkkkkkkk kkkkkkkkkkk kkkkkkkk kkkkkkkk', '2025-05-03', 2, '2025-05-03 19:18:09', NULL, '2025-05-28 21:59:14'),
(22, '85858585858', 1, 1, 2, 'Şantiyede koşarken düşüp kolunu kırdı.', '2025-05-03', 2, '2025-05-03 19:18:09', NULL, '2025-05-28 21:59:51'),
(23, '25252525252', 9, 2, 4, 'ezildi', '2025-05-19', 2, '2025-05-19 18:39:18', NULL, '2025-05-28 22:00:15'),
(24, '12275304637', 5, 1, 1, 'Kaygan zeminde yürürken kayıp düştü.', '2023-01-10', 2, '2025-05-29 01:45:27', 14, '2025-05-29 14:10:33'),
(25, '12275304637', 4, 3, 4, 'Yukarıdan düşen parça başına çarptı.', '2023-03-18', 2, '2025-05-29 01:45:27', 14, '2025-05-29 14:10:33'),
(26, '12275304637', 3, 1, 2, 'El aletiyle çalışırken parmaklarını kesti.', '2023-05-27', 1, '2025-05-29 01:45:27', 14, '2025-05-29 14:10:33'),
(27, '12275304637', 9, 2, 4, 'Toprak kayması sonucu personel göçük altında kaldı.', '2023-07-01', 2, '2025-05-29 01:45:27', 14, '2025-05-29 14:10:33'),
(28, '12275304637', 4, 2, 3, 'Malzeme taşıma sırasında dengesini kaybetti.', '2023-09-20', 1, '2025-05-29 01:45:27', 14, '2025-05-29 14:10:33'),
(29, '12275304637', 3, 2, 1, 'Aletle çalışırken kolunu yaraladı.', '2023-11-04', 2, '2025-05-29 01:45:27', 14, '2025-05-29 14:10:33'),
(30, '12275304637', 5, 1, 1, 'Kaygan zeminde yürürken kayıp düştü.', '2023-01-10', 14, '2025-05-29 01:45:27', 14, '2025-05-29 14:11:29'),
(31, '12275304637', 4, 3, 4, 'Yukarıdan düşen parça başına çarptı.', '2023-03-18', 14, '2025-05-29 01:45:27', 14, '2025-05-29 14:11:29'),
(32, '12275304637', 3, 1, 2, 'El aletiyle çalışırken parmaklarını kesti.', '2023-05-27', 14, '2025-05-29 01:45:27', 14, '2025-05-29 14:11:29'),
(33, '12275304637', 9, 2, 4, 'Toprak kayması sonucu personel göçük altında kaldı.', '2023-07-01', 14, '2025-05-29 01:45:27', 14, '2025-05-29 14:11:29'),
(34, '12275304637', 4, 2, 3, 'Malzeme taşıma sırasında dengesini kaybetti.', '2023-09-20', 14, '2025-05-29 01:45:27', 14, '2025-05-29 14:11:29'),
(35, '12275304637', 3, 2, 1, 'Aletle çalışırken kolunu yaraladı.', '2023-11-04', 14, '2025-05-29 01:45:27', 14, '2025-05-29 14:11:29');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_personel_kisisel_bilgi`
--

CREATE TABLE `silinen_personel_kisisel_bilgi` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `ad_soyad` varchar(255) NOT NULL,
  `cinsiyet` tinyint(1) DEFAULT NULL COMMENT 'Erkek ise 1, Kadın ise 0',
  `dogum_tarihi` date DEFAULT NULL,
  `telefon` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `adres` varchar(500) DEFAULT NULL,
  `pp_dosya_yolu` varchar(255) DEFAULT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_personel_kisisel_bilgi`
--

INSERT INTO `silinen_personel_kisisel_bilgi` (`id`, `tc_kimlik`, `ad_soyad`, `cinsiyet`, `dogum_tarihi`, `telefon`, `email`, `adres`, `pp_dosya_yolu`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, '38383838383', 'Kayseri Kayseri', 1, '1983-07-15', '05383833838', 'kayseri@gmail.com', 'aaaaaaaaa aaaaaaaaaaaa aaaaaaaaaa', '', 2, '2025-05-01 12:55:03', 2, '2025-05-03 16:18:31'),
(2, '35353535353', 'İzmirli Poyraz', 1, '1997-11-11', '05353533535', 'izmirizmir@gmail.com', 'aaaaaaa aaaaaaaa aaaaaaaa aaaaaaaaaa aaaaaaaaa aaaaaaaaa', '', 2, '2025-05-01 12:02:29', 2, '2025-05-03 16:37:33'),
(3, '35353535353', 'İzmirli Poyraz', 1, '1997-11-11', '05353533535', 'izmirizmir@gmail.com', 'aaaaaaa aaaaaaaa aaaaaaaa aaaaaaaaaa aaaaaaaaa aaaaaaaaa', '', 2, '2025-05-01 12:02:29', 2, '2025-05-03 16:38:23'),
(4, '53535353535', 'Kara Mesut', 1, '1982-07-14', '05535335353', 'karamesut@gmail.com', 'mmmmmmmmmmmm mmmmmmm mmmmm mmmmm mmm mmmmmmmmm mmmmmm mmmmmmmmm mmmmmmm', '', 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:45:54'),
(5, '53535353535', 'Kara Mesut', 1, '1982-07-14', '05535335353', 'karamesut@gmail.com', 'mmmmmmmmmmmm mmmmmmm mmmmm mmmmm mmm mmmmmmmmm mmmmmm mmmmmmmmm mmmmmmm', '', 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:46:22'),
(6, '53535353535', 'Kara Mesut', 1, '1982-07-14', '05535335353', 'karamesut@gmail.com', 'mmmmmmmmmmmm mmmmmmm mmmmm mmmmm mmm mmmmmmmmm mmmmmm mmmmmmmmm mmmmmmm', '', 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:46:54'),
(7, '45454545454', 'Doruk Kuzay', 1, '1991-07-03', '05454554545', 'dorukkuzay@gmail.com', 'aaaaaaaaa aaaaaaaaaaaaaaa aaaaaaaaaaaaaaa', '41910cbd82a20d6a_Adsztasarm1.png', 2, '2025-05-03 18:26:25', 2, '2025-05-03 18:32:52'),
(8, '10112233456', 'Barış Demir', 1, '1988-08-15', '05359011234', 'baris.demir@example.com', 'Samsun, İlkadım', NULL, 1, '2025-05-05 14:20:58', 2, '2025-05-19 18:53:33'),
(9, '10112233456', 'Barış Demir', 1, '1988-08-15', '05359011234', 'baris.demir@example.com', 'Samsun, İlkadım', NULL, 1, '2025-05-05 14:20:58', 2, '2025-05-19 19:27:04'),
(10, '10112233456', 'Barış Demir', 1, '1988-08-15', '05359011234', 'baris.demir@example.com', 'Samsun, İlkadım', NULL, 1, '2025-05-05 14:20:58', 2, '2025-05-19 19:28:11'),
(11, '15635475958', 'Kayla Uçar', 1, '1998-06-16', '05349652415', 'kayla.ucar@gmail.com', 'Buca / İzmir', '', 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(12, '62656265626', 'Menekşe Alçak', 0, '1988-10-14', '05626526354', 'meneksealcak@gmail.com', 'Melikgazi / Kayseri', '', 2, '2025-05-24 17:02:15', 2, '2025-05-24 17:03:44'),
(14, '14345678901', 'Ali Kayar', 1, '1985-04-12', '05321234567', 'ali.kaya@example.com', 'İstanbul, Pendik', NULL, 1, '2025-05-04 00:52:31', 2, '2025-05-24 20:44:04'),
(17, '76889900123', 'Buse Kaplan', 0, '1993-11-12', '05426788901', 'buse.kaplan@example.com', 'Adana, Yüreğir', NULL, 1, '2025-05-05 14:20:58', 2, '2025-05-24 20:52:23'),
(18, '12345678901', 'Ali Kaya', 1, '1985-04-12', '05321234567', 'ali.kaya@example.com', 'İstanbul, Pendik, Güzelyalı Mah.', 'uploads/ali_kaya.jpg', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(19, '12312312312', 'Alp Türk', 1, '2002-03-25', '05478547451', 'alp@gmail.com', 'İzmir/Konak', '680bd2b0e9a43_6808cd1ed7311_db_soruları.png', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(20, '11122233344', 'Ahmet Yılmaz', 1, '1980-05-15', '05321112233', 'ahmet.yilmaz@example.com', 'İstanbul, Şişli', NULL, 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:07:13'),
(21, '10112233456', 'Barış Demir', 1, '1988-08-15', '05359011234', 'baris.demir@example.com', 'Samsun, İlkadım', NULL, 1, '2025-05-05 14:20:58', 2, '2025-05-26 14:54:31'),
(22, '10112233456', 'Barış Demiroğlu', 1, '1988-08-15', '05359011234', 'baris.demir@example.com', 'Samsun, İlkadım', NULL, 1, '2025-05-05 14:20:58', 2, '2025-05-26 15:07:07'),
(23, '35353535353', 'İzmirli Poyraz', 1, '1997-11-11', '05353533535', 'izmirizmir@gmail.com', 'aaaaaaa aaaaaaaa aaaaaaaa aaaaaaaaaa aaaaaaaaa aaaaaaaaa', '', 2, '2025-05-01 12:02:29', 2, '2025-05-26 15:11:18'),
(24, '55055555555', 'Elif Ekim', 0, '1987-07-07', '05348521478', 'elif_eylul@gmail.com', 'Üsküdar / İstanbul', NULL, 1, '2025-05-04 00:52:31', 2, '2025-05-26 15:19:45'),
(25, '43556677890', 'Serkan Aydın', 1, '1984-12-05', '05393455678', 'serkan.aydin@example.com', 'İzmir, Buca', NULL, 1, '2025-05-05 14:20:58', 2, '2025-05-26 16:13:11'),
(26, '15635475958', 'Kayla Uçar', 1, '1998-06-16', '05349652415', 'kayla.ucar@gmail.com', 'Buca / İzmir', '', 2, '2025-05-24 14:31:00', 2, '2025-05-26 16:15:38'),
(27, '15635475958', 'Kayla Uçar', 1, '1998-06-16', '05349652415', 'kayla.ucar@gmail.com', 'Buca / İzmir', '', 2, '2025-05-24 14:31:00', 2, '2025-05-26 16:16:06'),
(28, '10112233344', 'Hakan Yıldırım', 0, '1983-11-28', '05411011223', 'hakan.yildirim@example.com', 'Ankara, Keçiören', NULL, 1, '2025-04-29 18:30:10', 14, '2025-05-26 21:53:19'),
(29, '21223344567', 'Ceren Kaya', 0, '1992-05-27', '05460122345', 'ceren.kaya@example.com', 'Mersin, Toroslar', NULL, 1, '2025-05-05 14:20:58', 14, '2025-05-26 21:57:52'),
(30, '62656265626', 'Menekşe Alçak', 0, '1988-10-14', '05626526354', 'meneksealcak@gmail.com', 'Melikgazi / Kayseri', '', 2, '2025-05-24 17:02:15', NULL, '2025-05-28 20:23:07'),
(31, '15635475958', 'Kayla Uçar', 1, '1998-06-16', '05349652415', 'kayla.ucar@gmail.com', 'Buca / İzmir', '', 2, '2025-05-24 14:31:00', NULL, '2025-05-28 20:23:12'),
(32, '62656265626', 'Menekşe Alçak', 0, '1988-10-14', '05626526354', 'meneksealcak@gmail.com', 'Melikgazi / Kayseri', '8127c9e78bc4e43b.png', 2, '2025-05-24 17:02:15', NULL, '2025-05-28 20:24:04'),
(33, '15635475958', 'Kayla Uçar', 1, '1998-06-16', '05349652415', 'kayla.ucar@gmail.com', 'Buca / İzmir', '8127c9e78bc4e43b.png', 2, '2025-05-24 14:31:00', NULL, '2025-05-28 20:24:10'),
(34, '15635475958', 'Kayla Uçar', 1, '1998-06-16', '05349652415', 'kayla.ucar@gmail.com', 'Buca / İzmir', '', 2, '2025-05-24 14:31:00', NULL, '2025-05-28 20:33:45'),
(35, '21134455678', 'Ege Can', 1, '1987-09-10', '05551233456', 'emre.can@example.com', 'İstanbul, Esenyurt', NULL, 1, '2025-05-04 00:52:58', NULL, '2025-05-28 20:33:48'),
(36, '21334455678', 'Emre Can', 1, '1987-09-10', '05371233456', 'emre.can@example.com', 'İstanbul, Esenyurt', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-28 20:33:52'),
(37, '25252525252', 'Cahit Arf', 1, '1984-11-14', '05789632574', 'cahit.arf@gmail.com', 'İzmir / Bornova', '', 2, '2025-05-19 18:37:00', NULL, '2025-05-28 20:34:01'),
(38, '45645645645', 'Sinan Aka', 1, '2004-06-25', '05417544874', 'sinan@gmail.com', 'Ankara/Sincan', '680bf2a6c4d83_6808cd1ed7311_db_soruları.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 20:34:10'),
(39, '48648648648', 'Cabbar Keş', 1, '2000-01-01', '05486484848', 'cabbar@gmail.com', 'aaaaaaaaaaaaa aaaaaaaaaaaaaaaaa', '', 2, '2025-05-05 14:02:27', NULL, '2025-05-28 20:34:20'),
(40, '53535353535', 'Kara Mesut', 1, '1982-07-14', '05535335353', 'karamesut@gmail.com', 'mmmmmmmmmmmm mmmmmmm mmmmm mmmmm mmm mmmmmmmmm mmmmmm mmmmmmmmm mmmmmmm', '', 2, '2025-05-01 12:33:54', NULL, '2025-05-28 20:34:26'),
(41, '65778899012', 'Tolga Şen', 1, '1986-03-30', '05315677890', 'tolga.sen@example.com', 'Kocaeli, Gebze', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-28 20:34:29'),
(42, '70578578578', 'Cafer Buruş', 1, '2005-06-08', '05487558565', 'cafer@gmail.com', 'Konya/Mevlana', NULL, 1, '2025-05-04 00:52:31', NULL, '2025-05-28 20:34:31'),
(43, '78578578578', 'Cafer Buruk', 1, '2005-06-08', '05487558565', 'cafer@gmail.com', 'Konya/Mevlana', '', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 20:34:34'),
(44, '78978978978', 'Caner Er', 1, '1992-01-29', '05874574125', 'caner@gmail.com', 'Bursa / Orhangazi', '680bf491415b4_6808cd1ed7311_db_soruları.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 20:34:37'),
(45, '87990011234', 'Onur Çelik', 1, '1982-06-22', '05337899012', 'onur.celik@example.com', 'Antalya, Muratpaşa', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-28 20:34:44'),
(46, '99900011122', 'Gürkan Tekin', 1, '1991-06-12', '05409990011', 'gurkan.tekin@example.com', 'İzmir, Konak', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-28 20:34:46'),
(47, '10112233344', 'Hakan Yıldırım', 0, '1983-11-28', '05411011223', 'hakan.yildirim@example.com', 'Ankara, Keçiören', '1fa6909e4e3e0b31.jpg', 1, '2025-04-29 18:30:10', 14, '2025-05-28 20:35:36'),
(48, '12132435366', 'Murat Kılıç', 0, '1979-08-10', '05431213243', 'murat.kilic@example.com', 'Bursa, Yıldırım', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-28 20:36:18'),
(49, '11122233355', 'Zeynep Öztürk', 0, '1995-04-03', '05421112233', 'zeynep.ozturk@example.com', 'İstanbul, Kadıköy', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-28 20:36:50'),
(50, '32445566789', 'Aslıhan Güler', 0, '1991-04-25', '05482344567', 'aslihan.guler@example.com', 'Ankara, Mamak', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-28 20:36:53'),
(51, '44435566677', 'Elif Ak', 0, '1985-03-22', '05354445566', 'elif.kaya@example.com', 'Adana, Seyhan', NULL, 1, '2025-05-04 00:52:31', NULL, '2025-05-28 20:36:56'),
(52, '44455566677', 'Elif Kaya', 0, '1985-03-22', '05354445566', 'elif.kaya@example.com', 'Adana, Seyhan', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-28 20:36:58'),
(53, '54667788901', 'Merve Yılmaz', 0, '1989-07-18', '05404566789', 'merve.yilmaz@example.com', 'Bursa, Osmangazi', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-28 20:37:00'),
(54, '55555555555', 'Elif Eylül', 0, '1987-07-07', '05348521478', 'elif_eylul@gmail.com', 'aaaaaa aaaaaaaa aaaaaaaaa aaaaaaaa aaaaaaa aaaaaa aaaaaa Üsküdar / İstanbul', '', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 20:37:02'),
(55, '62656265626', 'Menekşe Alçak', 0, '1988-10-14', '05626526354', 'meneksealcak@gmail.com', 'Melikgazi / Kayseri', '', 2, '2025-05-24 17:02:15', NULL, '2025-05-28 20:37:05'),
(56, '85858585858', 'Menekşe Uygur', 0, '1979-10-16', '05858558585', 'menekse_uygur@gmail.com', 'aaaaaaaaaaaaa aaaaaaaaaaaaaaaa aaaaaaaaaaaaaaaa aaaaaaaaa', '', 2, '2025-05-03 19:18:09', NULL, '2025-05-28 20:37:11'),
(57, '48648648648', 'Cabbar Keş', 1, '2000-01-01', '05486484848', 'cabbar@gmail.com', 'aaaaaaaaaaaaa aaaaaaaaaaaaaaaaa', '8127c9e78bc4e43b.png', 2, '2025-05-05 14:02:27', NULL, '2025-05-28 20:37:23'),
(58, '53535353535', 'Kara Mesut', 1, '1982-07-14', '05535335353', 'karamesut@gmail.com', 'mmmmmmmmmmmm mmmmmmm mmmmm mmmmm mmm mmmmmmmmm mmmmmm mmmmmmmmm mmmmmmm', '8127c9e78bc4e43b.png', 2, '2025-05-01 12:33:54', NULL, '2025-05-28 20:37:29'),
(59, '55555555555', 'Elif Eylül', 0, '1987-07-07', '05348521478', 'elif_eylul@gmail.com', 'aaaaaa aaaaaaaa aaaaaaaaa aaaaaaaa aaaaaaa aaaaaa aaaaaa Üsküdar / İstanbul', '4de66ffa091bc348.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 20:37:37'),
(60, '85858585858', 'Menekşe Uygur', 0, '1979-10-16', '05858558585', 'menekse_uygur@gmail.com', 'aaaaaaaaaaaaa aaaaaaaaaaaaaaaa aaaaaaaaaaaaaaaa aaaaaaaaa', '4de66ffa091bc348.png', 2, '2025-05-03 19:18:09', NULL, '2025-05-28 20:37:43'),
(61, '99001122345', 'Gizem Öztürk', 0, '1990-02-08', '05448900123', 'gizem.ozturk@example.com', 'Trabzon, Akçaabat', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-28 20:38:03'),
(63, '91803397401', 'Cindoruk Memili Dumanlı', 1, '2003-05-07', '05101666870', 'yildirimcebesoy@yahoo.com', '0466 İzel Points Apt. 982, Aksuberg, ME 35681', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 21:04:46'),
(64, '87014626383', 'Bayan Özay Şilan Çetin', 0, '1989-09-22', '05625324472', 'celemhancer@sok.com', '969 Jankat Rapids, Nihanbury, OK 14146', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 21:05:18'),
(65, '69725217525', 'Dr. Mehdiye Feryas Türk', 0, '2002-09-30', '05999173816', 'vildaneihsanoglu@yahoo.com', '11972 Fitnat Club, West Satıabury, LA 35120', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 21:05:26'),
(66, '45892975001', 'Dr. Yetişal Dorukhan Şener', 1, '1975-09-30', '05577590175', 'nbilge@gmail.com', 'PSC 2196, Box 5652, APO AE 36803', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:00:42'),
(67, '79030673830', 'Koray Enginiz Durmuş Hayrioğlu', 1, '1984-09-15', '05633752431', 'pduran@yahoo.com', '771 İnönü Villages Suite 355, Türkshire, IN 97658', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:00:52'),
(68, '83982258713', 'Çağlasın Örik Yorulmaz Yaman', 1, '1999-09-26', '05795687833', 'semsinisadurdu@yahoo.com', '42836 Aslan Circle Apt. 687, West Sanurfurt, NC 42278', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:00:58'),
(69, '83982258713', 'ÇağlasınYorulmaz Yaman', 1, '1999-09-26', '05795687833', 'semsinisadurdu@yahoo.com', '42836 Aslan Circle Apt. 687, West Sanurfurt, NC 42278', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:06'),
(70, '88861015819', 'Bay Metinkaya Özsözlü Arslan', 1, '1970-02-24', '05626551104', 'oyaman@yahoo.com', '661 Tevetoğlu Neck, Duranhaven, UT 55328', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:11'),
(71, '03758997571', 'Kerime Feryas Arslan Tarhan', 0, '1982-06-30', '05608801994', 'sezersuat@inonu.com', '98386 Firdevis Tunnel Suite 066, Çetinmouth, AR 02200', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:16'),
(72, '03758997571', 's Arslan Tarhan', 0, '1982-06-30', '05608801994', 'sezersuat@inonu.com', '98386 Firdevis Tunnel Suite 066, Çetinmouth, AR 02200', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:19'),
(73, '22999119148', 'Dr. Nebiha Sevim Eraslan Bilir', 0, '1997-03-15', '05511686854', 'gzengin@gmail.com', '994 Tarhan Coves Suite 154, Sayinview, VT 85890', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:26'),
(74, '37562890028', 'Dr. Tüzenur Görsev Sezer Zengin', 0, '1977-04-06', '05167014508', 'kisakureksevican@tofas.com', '9346 Arslan Mission Apt. 269, Zöhrehanside, MD 93807', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:31'),
(75, '48909010016', 'Dr. Dilhuş Akgündüz Seven', 0, '1995-06-08', '05260332309', 'eraslanservinaz@yilmaz.net', '588 Akise Shore, South Uğurtan, TX 84571', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:35'),
(76, '62208635365', 'Bayan İsra Özpetek Yorulmaz Şafak', 0, '1974-05-04', '05792524191', 'rasul56@havelsan.com', '13291 Dirlik Expressway Suite 756, New Yağızkurtborough, ME 61232', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:40'),
(77, '70433154100', 'Bayan Çağlar Bahtinur Karadeniz Kısakürek', 0, '2001-01-02', '05593917251', 'pdurdu@yahoo.com', '089 Ensari Throughway Suite 223, East Atilhan, CO 90039', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:46'),
(78, '70433154100', 'Çağlar Karadeniz Kısakürek', 0, '2001-01-02', '05593917251', 'pdurdu@yahoo.com', '089 Ensari Throughway Suite 223, East Atilhan, CO 90039', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:01:54'),
(79, '45187053494', 'Burcuhan Ferhan Sezgin', 0, '1986-02-23', '05419518023', 'turabimanco@demirel.com', '442 Ünsever Walk, Durmuşside, CT 61061', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:02:05'),
(80, '45228851587', 'Gülşeref Müret Şama Arsoy', 0, '1985-06-03', '05669346547', 'wdurmus@tupras.com', '99305 Çamurcuoğlu Underpass Apt. 648, Aliabbashaven, WI 49647', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:02:11'),
(83, '19721811437', 'Bayan Teybet Dumanlı Hayrioğlu', 0, '1973-07-10', '05483564208', 'sakaryaunek@yahoo.com', '99488 Sernur Skyway, Akçamouth, AR 29719', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:02:33'),
(84, '07374167243', 'Ahsen Şendoğan Şama', 1, '1972-11-20', '05265479356', 'gokceakcay@ford.com', '62266 Nura Flat Suite 966, Lake Mehmetzahirborough, WV 92744', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:02:40'),
(85, '25867362266', 'Bay Nurkan Yaman', 1, '1978-09-21', '05571775140', 'rseven@yahoo.com', '2190 Sittik Bypass Suite 384, Port Ağbegim, WA 65422', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:02:47'),
(86, '29619431142', 'Dr. Haciali Şafak', 1, '2005-11-22', '05159996504', 'tigin93@hayrioglu.com', '356 Alemdar Turnpike Suite 519, New Hasret, TX 72603', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:02:51'),
(87, '41520643611', 'Saydam Sıla Tevetoğlu Gül', 1, '2006-10-16', '05701277063', 'akdenizsevla@yahoo.com', '640 Emiş Lights, East Evdeland, AL 47830', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:02:57'),
(88, '29466711684', 'Abdülcemal Sezer', 1, '1988-10-28', '05481260422', 'isezer@erdogan.com', '55426 Saire Springs, North Gülsevil, AL 80496', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:03:21'),
(89, '45892975001', 'Yetişal Dorukhan Şener', 1, '1975-09-30', '05577590175', 'nbilge@gmail.com', 'PSC 2196, Box 5652, APO AE 36803', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:03:29'),
(90, '52909656101', 'Öztürk Sadittin Akçay', 1, '1990-05-03', '05426617979', 'ashandemir@yahoo.com', '596 Zengin Crescent Suite 579, Port Tanses, ME 81042', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:03:34'),
(91, '53556093204', 'Tunçboğa Toğan İnönü', 1, '1967-08-08', '05854678544', 'ykisakurek@lc.com', '76591 Mülâyim Forest, Aslanton, TX 37250', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:03:41'),
(92, '54615679334', 'Dr. Efe Hüryaşar Durmuş', 1, '1990-12-29', '05084840862', 'tutkucanzengin@hotmail.com', '11426 Özakan Island Suite 110, Atiyyestad, KY 13900', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:03:48'),
(93, '61799042102', 'Ülfet Pehlil Türk Gülen', 1, '1970-02-19', '05414183285', 'taylak51@hotmail.com', '49493 Arsoy Ramp Suite 383, North İlbek, IN 71482', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:03:58'),
(94, '68230047868', 'Muktedir Şekim Arsoy', 1, '1971-09-27', '05046226567', 'aslancaglasin@tofas.org', '98373 Hançer Bridge, East Elöve, WV 63274', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:04:05'),
(95, '68876767721', 'Bay Çeviköz Karadeniz', 1, '1992-03-02', '05278282742', 'meleknursener@yahoo.com', '1168 Sezer Wells Suite 775, Port Şennurland, NH 83710', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:04:09'),
(96, '75692459375', 'Mucahit Karakucak Gülen Ertaş', 1, '2005-07-01', '05437993712', 'aliabbas71@seven.com', '4573 Ergül Mountains, South İmrihanmouth, CA 39857', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:04:17'),
(97, '85807836134', 'Özokçu Sabih Gülen', 1, '1980-04-08', '05903404384', 'emisaslan@yahoo.com', '929 Çetin Junctions Apt. 017, North Gülelmouth, AL 56573', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-28 22:04:35'),
(98, '10405411526', 'Fadıla Şadıman Eraslan Hayrioğlu', 0, '1970-01-02', '05676267658', 'zdurmus@a101.com', '998 Demir Mall, East Kısmet, OR 99903', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:15'),
(99, '14933163735', 'Selmin Hadrey Çamurcuoğlu Şener', 0, '1990-06-21', '05130554885', 'soykutdumanli@tarhan.com', 'USS Kısakürek, FPO AE 56666', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:20'),
(100, '17146774186', 'Tanyu Akdeniz Sezer', 0, '1972-02-17', '05872050614', 'silakoruturk@ocalan.com', '73678 Alemdar Centers, North Tayyip, NY 53373', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:30'),
(101, '22999119148', 'Nebiha Sevim Eraslan Bilir', 0, '1997-03-15', '05511686854', 'gzengin@gmail.com', '994 Tarhan Coves Suite 154, Sayinview, VT 85890', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:15'),
(102, '25579415521', 'Dr. Nezengül Sernur Demir', 0, '1972-01-28', '05447012045', 'samayargi@aselsan.com', '102 Yaman View Suite 149, East Eral, MO 28758', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:20'),
(103, '30745054447', 'Fatigül Vefia İnönü Yüksel', 0, '1969-04-23', '05564679970', 'tulin20@hotmail.com', '6660 Uzbay Stream Apt. 875, Harizside, LA 13294', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:25'),
(104, '33206163294', 'Arıpınar Kısakürek Yorulmaz', 0, '1976-07-13', '05687937416', 'seferbilgin@arcelik.com', '072 Nazimet Unions, East Onursuport, VT 84746', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:31'),
(105, '37562890028', 'Tüzenur Görsev Sezer Zengin', 0, '1977-04-06', '05167014508', 'kisakureksevican@tofas.com', '9346 Arslan Mission Apt. 269, Zöhrehanside, MD 93807', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:36'),
(106, '37474112490', 'Zilfa Altınbike Kısakürek Çetin', 0, '1989-11-18', '05313291212', 'kergul@hotmail.com', '05930 Yorulmaz Valleys Suite 859, East Denizalp, TN 32109', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:41'),
(107, '46660993538', 'Amre İnsaf Zorlu Aksu', 0, '1973-10-29', '05444328309', 'kisakureksafura@yahoo.com', '010 Yasemen Crescent, Dumanlıfurt, OH 33627', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:46'),
(108, '48909010016', 'Dilhuş Akgündüz Seven', 0, '1995-06-08', '05260332309', 'eraslanservinaz@yilmaz.net', '588 Akise Shore, South Uğurtan, TX 84571', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:53'),
(109, '62208635365', 'İsra Özpetek Yorulmaz Şafak', 0, '1974-05-04', '05792524191', 'rasul56@havelsan.com', '13291 Dirlik Expressway Suite 756, New Yağızkurtborough, ME 61232', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:14'),
(110, '69725217525', 'Mehdiye Feryas Türk', 0, '2002-09-30', '05999173816', 'vildaneihsanoglu@yahoo.com', '11972 Fitnat Club, West Satıabury, LA 35120', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:28'),
(111, '68844431069', 'Özbilge Cevale Çetin', 0, '1984-11-29', '05218159970', 'yeraslan@hotmail.com', '24629 Türk Locks Suite 307, Zülgarnitown, WY 40223', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:37'),
(112, '79030673830', 'Enginiz Durmuş Hayrioğlu', 1, '1984-09-15', '05633752431', 'pduran@yahoo.com', '771 İnönü Villages Suite 355, Türkshire, IN 97658', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:43'),
(113, '83982258713', 'Çağlasın Yorulmaz Yaman', 1, '1999-09-26', '05795687833', 'semsinisadurdu@yahoo.com', '42836 Aslan Circle Apt. 687, West Sanurfurt, NC 42278', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:54'),
(114, '78985625753', 'Nurşan Sezer Akdeniz', 0, '1997-12-27', '05003354216', 'ecemisyaman@hotmail.com', '69797 Neşrin Light, Nihantown, TX 95826', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:02'),
(115, '68007229034', 'Elifnur Şirivan Şafak', 0, '1994-09-01', '05985524140', 'yguclu@gmail.com', '505 Aksay Springs, Lake Şermanbury, UT 45635', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:08'),
(116, '88861015819', 'Metinkaya Özsözlü Arslan', 1, '1970-02-24', '05626551104', 'oyaman@yahoo.com', '661 Tevetoğlu Neck, Duranhaven, UT 55328', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:20'),
(117, '97177517016', 'Gülper Gülçe Mansız Demirel', 0, '1985-03-21', '05772175354', 'ozdessensoy@gmail.com', '158 Zengin Ports Suite 846, New Dorukhantown, FL 07456', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:26'),
(118, '99293784270', 'Bayan Feyzin Dumanlı Erdoğan', 0, '1967-09-20', '05348039652', 'hayrioglurasul@gmail.com', '2181 Tevetoğlu Ridges, Demireltown, SD 22127', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:34'),
(119, '97296590277', 'Naide Deryanur İnönü Seven', 0, '1968-08-10', '05667467621', 'qertas@peak.com', 'USS Fırat, FPO AP 06867', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:42'),
(120, '53714785969', 'Serol Bariş Aslan', 1, '2002-05-27', '05093796603', 'inonuiklim@yahoo.com', '34074 Bilge Viaduct Apt. 713, Port Muhiye, MI 93660', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:52'),
(121, '87014626383', 'Özay Şilan Çetin', 0, '1989-09-22', '05625324472', 'celemhancer@sok.com', '969 Jankat Rapids, Nihanbury, OK 14146', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:10:00'),
(122, '82922169958', 'Elvan Aksu Zengin', 0, '1982-10-19', '05160917798', 'berrin20@ihsanoglu.org', '9851 Fırat Forges Apt. 818, South Hanbiken, MO 12552', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:10:34'),
(123, '07374167243', 'Ahsen Şama', 1, '1972-11-20', '05265479356', 'gokceakcay@ford.com', '62266 Nura Flat Suite 966, Lake Mehmetzahirborough, WV 92744', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:14:57'),
(124, '00292185156', 'Ayşeana Sakarya', 0, '1987-12-23', '05103930870', 'ilalmissezer@petkim.net', '3106 Manço Center Suite 061, Silanurfort, CT 03874', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:00'),
(125, '03758997571', 'Arslan Tarhan', 0, '1982-06-30', '05608801994', 'sezersuat@inonu.com', '98386 Firdevis Tunnel Suite 066, Çetinmouth, AR 02200', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:01'),
(126, '04078593213', 'Müret Korutürk', 0, '1970-09-27', '05806578759', 'tezol21@selcuk.com', '31541 Aysoy Lights Suite 626, Akgündüzfurt, ND 49698', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:03'),
(127, '05064138367', 'Döner Yılmaz', 0, '1969-08-22', '05937597383', 'kopanalemdar@arsoy.com', '01372 Tükelalp Crossing Apt. 702, Alemdarfurt, AK 50666', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:05'),
(128, '08668826343', 'Recepali Türk', 1, '1970-07-03', '05856624628', 'sevenevcimen@vestel.com', '87509 Ülker Oval, North Gülterside, AL 02693', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:07'),
(129, '09977008860', 'Uluğbey Akçay', 1, '2002-03-25', '05080100623', 'vinonu@gmail.com', '501 Bilgin Villages, West Nejdetfurt, MD 04384', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:09'),
(131, '11234567890', 'Mertcan', 1, '2007-05-09', '05452369271', 'mertcanulbeyiaaaaaaa@gmail.com', '4693 sokak no:31 daire 3 Çamkule Mahallesi Bornova İzmir', 'e4e1328f5777cae4.png', 14, '2025-05-28 23:21:28', 14, '2025-05-28 23:21:56'),
(132, '41520643611', 'Sıla Gül', 1, '2006-10-16', '05701277063', 'akdenizsevla@yahoo.com', '640 Emiş Lights, East Evdeland, AL 47830', NULL, 1, '2025-05-28 21:03:04', 14, '2025-05-29 01:00:57'),
(133, '10702966706', 'Yepelek Güçlü', 0, '1965-06-28', '05236519941', 'ermutlusama@hotmail.com', '02921 Erdoğan Landing Suite 915, Tolonbayberg, VA 57762', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:01:58'),
(134, '11586938068', 'Gök Akar', 0, '1979-02-08', '05256527047', 'apaydinertas@camurcuoglu.com', '610 Zülgarni Overpass Suite 279, Uçbeyiburgh, KS 98668', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:02:02'),
(135, '18914117393', 'Ömriye Akçay', 0, '2007-02-26', '05761847230', 'sensoygucal@roketsan.info', '75354 Ferhan Fork, Gülenbury, NH 58721', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:02:05'),
(136, '19721811437', 'Ayça Dumanlı', 0, '1973-07-10', '05483564208', 'sakaryaunek@yahoo.com', '99488 Sernur Skyway, Akçamouth, AR 29719', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:02:08'),
(137, '44220969029', 'Maynur Çorlu', 0, '1997-09-02', '05915452267', 'ozokcu77@toyota.com', '56527 Resulcan Mill Suite 434, East Zehranurville, TN 45562', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:02:12'),
(138, '45187053494', 'Burcuhan Sezgin', 0, '1986-02-23', '05419518023', 'turabimanco@demirel.com', '442 Ünsever Walk, Durmuşside, CT 61061', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:02:16'),
(139, '45228851587', 'Gülşeref Arsoy', 0, '1985-06-03', '05669346547', 'wdurmus@tupras.com', '99305 Çamurcuoğlu Underpass Apt. 648, Aliabbashaven, WI 49647', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:02:19'),
(140, '10702966706', 'Yepelek Güçlü', 0, '1965-06-28', '05236519941', 'ermutlusama@hotmail.com', '02921 Erdoğan Landing Suite 915, Tolonbayberg, VA 57762', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(141, '11122233355', 'Zeynep Öztürk', 0, '1995-04-03', '05421112233', 'zeynep.ozturk@example.com', 'İstanbul, Kadıköy', '4de66ffa091bc348.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:08:01'),
(142, '11586938068', 'Gök Akar', 0, '1979-02-08', '05256527047', 'apaydinertas@camurcuoglu.com', '610 Zülgarni Overpass Suite 279, Uçbeyiburgh, KS 98668', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(143, '12275304637', 'Celilay Öcalan', 1, '1974-08-30', '05742054932', 'cogay76@yahoo.com', '512 Fırat Crossroad, Port Abdülahat, AK 39608', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(144, '15635475958', 'Kayla Uçar', 1, '1998-06-16', '05349652415', 'kayla.ucar@gmail.com', 'Buca / İzmir', '8127c9e78bc4e43b.png', 2, '2025-05-24 14:31:00', NULL, '2025-05-29 01:08:01'),
(145, '18914117393', 'Ömriye Akçay', 0, '2007-02-26', '05761847230', 'sensoygucal@roketsan.info', '75354 Ferhan Fork, Gülenbury, NH 58721', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(146, '19721811437', 'Ayça Dumanlı', 0, '1973-07-10', '05483564208', 'sakaryaunek@yahoo.com', '99488 Sernur Skyway, Akçamouth, AR 29719', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(147, '21134455678', 'Ege Can', 1, '1987-09-10', '05551233456', 'emre.can@example.com', 'İstanbul, Esenyurt', '8127c9e78bc4e43b.png', 1, '2025-05-04 00:52:58', NULL, '2025-05-29 01:08:01'),
(148, '21223344567', 'Ceren Kaya', 0, '1992-05-27', '05460122345', 'ceren.kaya@example.com', 'Mersin, Toroslar', '4de66ffa091bc348.png', 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:08:01'),
(149, '21334455678', 'Emre Can', 1, '1987-09-10', '05371233456', 'emre.can@example.com', 'İstanbul, Esenyurt', '8127c9e78bc4e43b.png', 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:08:01'),
(150, '22222222222', 'Mertcan Ülbeyi', 1, '2004-06-29', '05452369271', 'mertcanulbeyi@gmail.com', '4693 sokak no:31 daire 3 Çamkule Mahallesi Bornova İzmir', '8127c9e78bc4e43b.png', 14, '2025-05-26 22:08:00', NULL, '2025-05-29 01:08:01'),
(151, '25252525252', 'Cahit Arf', 1, '1984-11-14', '05789632574', 'cahit.arf@gmail.com', 'İzmir / Bornova', '8127c9e78bc4e43b.png', 2, '2025-05-19 18:37:00', NULL, '2025-05-29 01:08:01'),
(152, '25286692966', 'Tanır Yaman', 1, '1979-07-04', '05691557924', 'durduvala@toyota.org', '449 Akça Walks Apt. 260, Sevdinarberg, DC 33226', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(153, '25867362266', 'Nurkan Yaman', 1, '1978-09-21', '05571775140', 'rseven@yahoo.com', '2190 Sittik Bypass Suite 384, Port Ağbegim, WA 65422', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(154, '29466711684', 'Cemal Sezer', 1, '1988-10-28', '05481260422', 'isezer@erdogan.com', '55426 Saire Springs, North Gülsevil, AL 80496', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(155, '29619431142', 'Haciali Şafak', 1, '2005-11-22', '05159996504', 'tigin93@hayrioglu.com', '356 Alemdar Turnpike Suite 519, New Hasret, TX 72603', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(156, '32445566789', 'Aslıhan Güler', 0, '1991-04-25', '05482344567', 'aslihan.guler@example.com', 'Ankara, Mamak', '4de66ffa091bc348.png', 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:08:01'),
(157, '37918055269', 'Bulunç Soylu', 1, '1989-01-14', '05999404389', 'zuferyaman@hotmail.com', '105 Aslan Pine Apt. 491, Ertaşhaven, AR 65985', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(158, '39106518017', 'Bilgütay Hançer', 1, '1991-01-23', '05971870728', 'oyuksel@akdeniz.info', 'PSC 8668, Box 8263, APO AA 27152', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(159, '40331739363', 'Sadat Arslan', 1, '1966-08-12', '05517518304', 'doganalpcamurcuoglu@gmail.com', '98454 Çorlu Landing, Ertaşshire, UT 30104', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(160, '40892687056', 'Ermutlu Durmuş', 1, '2005-03-10', '05284395995', 'goknur35@gmail.com', '8547 Ertaş Extension, Hamiberg, ND 64505', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(161, '41520643611', 'Sıla Gül', 1, '2006-10-16', '05701277063', 'akdenizsevla@yahoo.com', '640 Emiş Lights, East Evdeland, AL 47830', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(162, '44220969029', 'Maynur Çorlu', 0, '1997-09-02', '05915452267', 'ozokcu77@toyota.com', '56527 Resulcan Mill Suite 434, East Zehranurville, TN 45562', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(163, '44435566677', 'Elif Ak', 0, '1985-03-22', '05354445566', 'elif.kaya@example.com', 'Adana, Seyhan', '4de66ffa091bc348.png', 1, '2025-05-04 00:52:31', NULL, '2025-05-29 01:08:01'),
(164, '44455566677', 'Elif Kaya', 0, '1985-03-22', '05354445566', 'elif.kaya@example.com', 'Adana, Seyhan', '4de66ffa091bc348.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:08:01'),
(165, '45187053494', 'Burcuhan Sezgin', 0, '1986-02-23', '05419518023', 'turabimanco@demirel.com', '442 Ünsever Walk, Durmuşside, CT 61061', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(166, '45228851587', 'Gülşeref Arsoy', 0, '1985-06-03', '05669346547', 'wdurmus@tupras.com', '99305 Çamurcuoğlu Underpass Apt. 648, Aliabbashaven, WI 49647', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(167, '45645645645', 'Sinan Aka', 1, '2004-06-25', '05417544874', 'sinan@gmail.com', 'Ankara/Sincan', '8127c9e78bc4e43b.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:08:01'),
(168, '45892975001', 'Yetişal Şener', 1, '1975-09-30', '05577590175', 'nbilge@gmail.com', 'PSC 2196, Box 5652, APO AE 36803', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(169, '46554947170', 'Gülsiye Sezer', 0, '1966-01-29', '05758428241', 'gulker@hotmail.com', '1891 Demir Tunnel, South Korayborough, KS 69070', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(170, '48149177850', 'Hakikat Yılmaz', 1, '1991-01-19', '05027588425', 'bilirgulseren@gmail.com', '41038 Sezer Landing, Korutürkland, CT 66748', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(171, '48648648648', 'Cabbar Keş', 1, '2000-01-01', '05486484848', 'cabbar@gmail.com', 'Mersin', '8127c9e78bc4e43b.png', 2, '2025-05-05 14:02:27', NULL, '2025-05-29 01:08:01'),
(172, '52909656101', 'Öztürk Akçay', 1, '1990-05-03', '05426617979', 'ashandemir@yahoo.com', '596 Zengin Crescent Suite 579, Port Tanses, ME 81042', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(173, '53535353535', 'Kara Mesut', 1, '1982-07-14', '05535335353', 'karamesut@gmail.com', 'Ağrı', '8127c9e78bc4e43b.png', 2, '2025-05-01 12:33:54', NULL, '2025-05-29 01:08:01'),
(174, '53556093204', 'Tunç İnönü', 1, '1967-08-08', '05854678544', 'ykisakurek@lc.com', '76591 Mülâyim Forest, Aslanton, TX 37250', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(175, '54615679334', 'Efe Durmuş', 1, '1990-12-29', '05084840862', 'tutkucanzengin@hotmail.com', '11426 Özakan Island Suite 110, Atiyyestad, KY 13900', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(176, '54667788901', 'Merve Yılmaz', 0, '1989-07-18', '05404566789', 'merve.yilmaz@example.com', 'Bursa, Osmangazi', '4de66ffa091bc348.png', 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:08:01'),
(177, '55555555555', 'Elif Eylül', 0, '1987-07-07', '05348521478', 'elif_eylul@gmail.com', 'Bolu', '4de66ffa091bc348.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:08:01'),
(178, '61115542622', 'Kenter Bilge', 1, '2004-02-15', '05037129387', 'hatin27@gmail.com', '43006 Şener Station, Şafakfurt, SC 70829', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(179, '61125457387', 'Gözel Demir', 1, '1981-09-05', '05438889937', 'akcaycakar@hotmail.com', '9296 Akçay Oval Apt. 827, Famiburgh, NV 81688', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(180, '61799042102', 'Pehlil Gülen', 1, '1970-02-19', '05414183285', 'taylak51@hotmail.com', '49493 Arsoy Ramp Suite 383, North İlbek, IN 71482', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(181, '62344565574', 'Nejdet Bilir', 0, '1978-03-07', '05024103865', 'aybet20@hotmail.com', '476 Yorulmaz Corners, Çıdalberg, ID 84734', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(182, '62656265626', 'Menekşe Alçak', 0, '1988-10-14', '05626526354', 'meneksealcak@gmail.com', 'Melikgazi / Kayseri', '4de66ffa091bc348.png', 2, '2025-05-24 17:02:15', NULL, '2025-05-29 01:08:01'),
(183, '65778899012', 'Tolga Şen', 1, '1986-03-30', '05315677890', 'tolga.sen@example.com', 'Kocaeli, Gebze', '8127c9e78bc4e43b.png', 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:08:01'),
(184, '68230047868', 'Muktedir Arsoy', 1, '1971-09-27', '05046226567', 'aslancaglasin@tofas.org', '98373 Hançer Bridge, East Elöve, WV 63274', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(185, '68876767721', 'Çeviköz Karadeniz', 1, '1992-03-02', '05278282742', 'meleknursener@yahoo.com', '1168 Sezer Wells Suite 775, Port Şennurland, NH 83710', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(186, '70433154100', 'Çağlar Kısakürek', 0, '2001-01-02', '05593917251', 'pdurdu@yahoo.com', '089 Ensari Throughway Suite 223, East Atilhan, CO 90039', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(187, '70578578578', 'Cafer Buruş', 1, '2005-06-08', '05487558565', 'cafer@gmail.com', 'Konya/Mevlana', '8127c9e78bc4e43b.png', 1, '2025-05-04 00:52:31', NULL, '2025-05-29 01:08:01'),
(188, '72126464715', 'Özgür Arsoy', 1, '2001-12-24', '05956046618', 'ocalanolca@selcuk.com', '656 Afer Courts, Mishathaven, NC 73638', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(189, '73384842199', 'Kamar Mansız', 1, '1980-06-22', '05419866524', 'cemiylemanco@koruturk.biz', '339 Gülhisar Mission Suite 191, Port Hayelhaven, OK 70842', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(190, '73454019380', 'Günşen Akdeniz', 1, '2000-11-08', '05330833016', 'tarhankilicbay@hotmail.com', '707 Gelengül Mall, Durduton, NH 88682', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(191, '75692459375', 'Mucahit Karakucak', 1, '2005-07-01', '05437993712', 'aliabbas71@seven.com', '4573 Ergül Mountains, South İmrihanmouth, CA 39857', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(192, '76008350583', 'Cemiyle Alemdar', 0, '1977-05-02', '05007432927', 'yeneral56@opet.com', '22557 Akgündüz Street, West Uygunburgh, MO 52962', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(193, '77725228728', 'Cebesoy İhsanoğlu', 1, '1986-11-05', '05899642353', 'muarramanco@havelsan.biz', 'USNV Aksu, FPO AP 32020', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(194, '78578578578', 'Cafer Buruk', 1, '2005-06-08', '05487558565', 'cafer@gmail.com', 'Konya/Mevlana', '8127c9e78bc4e43b.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:08:01'),
(195, '78978978978', 'Caner Er', 1, '1992-01-29', '05874574125', 'caner@gmail.com', 'Bursa / Orhangazi', '8127c9e78bc4e43b.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:08:01'),
(196, '84145933272', 'Rüknettin Kısakürek', 1, '1981-01-27', '05454990453', 'jergul@akar.com', '033 Denkel Ranch Apt. 790, Lake Avşin, NV 31216', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(197, '84866618892', 'Kamar Aksu', 1, '1982-10-21', '05658271091', 'bilginfeyha@turk.com', '2208 Bilgin Islands Apt. 654, New Âdemville, TX 07404', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(198, '85082148628', 'Işin Gül', 0, '2003-06-27', '05132475469', 'sernur13@bilgin.biz', '4947 Bilkay Center Apt. 738, Port Tülinfurt, ME 54647', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(199, '85621850163', 'Amaç Aksu', 1, '1995-01-09', '05489025039', 'ehancer@gmail.com', '799 Aşhan Coves, West Abid, MA 81790', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(200, '85807836134', 'Sabih Gülen', 1, '1980-04-08', '05903404384', 'emisaslan@yahoo.com', '929 Çetin Junctions Apt. 017, North Gülelmouth, AL 56573', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(201, '85858585858', 'Menekşe Uygur', 0, '1979-10-16', '05858558585', 'menekse_uygur@gmail.com', 'Hatay', '4de66ffa091bc348.png', 2, '2025-05-03 19:18:09', NULL, '2025-05-29 01:08:01'),
(202, '86103579727', 'Ergül Akar', 0, '1988-10-06', '05902330307', 'umanco@hotmail.com', '5250 Aysuna Harbor, Port Haluk, WV 21267', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(203, '87990011234', 'Onur Çelik', 1, '1982-06-22', '05337899012', 'onur.celik@example.com', 'Antalya, Muratpaşa', '8127c9e78bc4e43b.png', 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:08:01'),
(204, '88317121003', 'Gürcüye Durdu', 0, '1991-11-17', '05352135680', 'hunalpyuksel@hotmail.com', '059 Kitan Field Suite 842, Türkland, IN 79362', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(205, '88728743152', 'Kopan Eraslan', 1, '1965-08-25', '05262067240', 'turksekim@sezer.com', 'USS İhsanoğlu, FPO AA 04660', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(206, '89944171545', 'Cihan Kısakürek', 0, '1999-09-12', '05944665743', 'bedrialemdar@gmail.com', '8985 Akgündüz Spur Apt. 575, East Ünek, IN 29903', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(207, '91803397401', 'Mehmet Dumanlı', 1, '2003-05-07', '05101666870', 'yildirimcebesoy@yahoo.com', '0466 İzel Points Apt. 982, Aksuberg, ME 35681', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(208, '95849825449', 'Toğan Hançer', 1, '1976-04-06', '05074517133', 'sensoyyertan@yahoo.com', '64361 Abdulgazi Forest, Erdoğanberg, IN 68585', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(209, '96565234138', 'Ezgütekin Çorlu', 1, '1986-07-14', '05189092753', 'gorgunayduran@gulen.com', '303 Akyıldız Crescent Suite 990, Sultaneburgh, AL 93286', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:01'),
(210, '99001122345', 'Gizem Öztürk', 0, '1990-02-08', '05448900123', 'gizem.ozturk@example.com', 'Trabzon, Akçaabat', '4de66ffa091bc348.png', 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:08:01'),
(211, '99900011122', 'Gürkan Tekin', 1, '1991-06-12', '05409990011', 'gurkan.tekin@example.com', 'İzmir, Konak', '8127c9e78bc4e43b.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:08:01'),
(212, '12275304637', 'Celilay Öcalan', 1, '1974-08-30', '05742054932', 'cogay76@yahoo.com', '512 Fırat Crossroad, Port Abdülahat, AK 39608', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:36'),
(213, '12275304637', 'Celil Öcalan', 1, '1974-08-30', '05742054932', 'cogay76@yahoo.com', '512 Fırat Crossroad, Port Abdülahat, AK 39608', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:08:47'),
(214, '22222222222', 'Mertcan Ülbeyi', 1, '2004-06-29', '05452369271', 'mertcanulbeyi@gmail.com', '4693 sokak no:31 daire 3 Çamkule Mahallesi Bornova İzmir', NULL, 14, '2025-05-26 22:08:00', NULL, '2025-05-29 01:10:41'),
(215, '21334455678', 'Emre Can', 1, '1987-09-10', '05371233456', 'emre.can@example.com', 'İstanbul, Esenyurt', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:10:43'),
(216, '21134455678', 'Ege Can', 1, '1987-09-10', '05551233456', 'emre.can@example.com', 'İstanbul, Esenyurt', NULL, 1, '2025-05-04 00:52:58', NULL, '2025-05-29 01:10:44'),
(217, '15635475958', 'Kayla Uçar', 1, '1998-06-16', '05349652415', 'kayla.ucar@gmail.com', 'Buca / İzmir', NULL, 2, '2025-05-24 14:31:00', NULL, '2025-05-29 01:10:48'),
(218, '25252525252', 'Cahit Arf', 1, '1984-11-14', '05789632574', 'cahit.arf@gmail.com', 'İzmir / Bornova', NULL, 2, '2025-05-19 18:37:00', NULL, '2025-05-29 01:10:51'),
(219, '25286692966', 'Tanır Yaman', 1, '1979-07-04', '05691557924', 'durduvala@toyota.org', '449 Akça Walks Apt. 260, Sevdinarberg, DC 33226', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:10:55'),
(220, '25867362266', 'Nurkan Yaman', 1, '1978-09-21', '05571775140', 'rseven@yahoo.com', '2190 Sittik Bypass Suite 384, Port Ağbegim, WA 65422', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:10:58'),
(221, '29466711684', 'Cemal Sezer', 1, '1988-10-28', '05481260422', 'isezer@erdogan.com', '55426 Saire Springs, North Gülsevil, AL 80496', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:00'),
(222, '29619431142', 'Haciali Şafak', 1, '2005-11-22', '05159996504', 'tigin93@hayrioglu.com', '356 Alemdar Turnpike Suite 519, New Hasret, TX 72603', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:02'),
(223, '37918055269', 'Bulunç Soylu', 1, '1989-01-14', '05999404389', 'zuferyaman@hotmail.com', '105 Aslan Pine Apt. 491, Ertaşhaven, AR 65985', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:04'),
(224, '39106518017', 'Bilgütay Hançer', 1, '1991-01-23', '05971870728', 'oyuksel@akdeniz.info', 'PSC 8668, Box 8263, APO AA 27152', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:06'),
(225, '40331739363', 'Sadat Arslan', 1, '1966-08-12', '05517518304', 'doganalpcamurcuoglu@gmail.com', '98454 Çorlu Landing, Ertaşshire, UT 30104', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:14'),
(226, '40892687056', 'Ermutlu Durmuş', 1, '2005-03-10', '05284395995', 'goknur35@gmail.com', '8547 Ertaş Extension, Hamiberg, ND 64505', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:18'),
(227, '41520643611', 'Sıla Gül', 1, '2006-10-16', '05701277063', 'akdenizsevla@yahoo.com', '640 Emiş Lights, East Evdeland, AL 47830', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:20'),
(228, '45645645645', 'Sinan Aka', 1, '2004-06-25', '05417544874', 'sinan@gmail.com', 'Ankara/Sincan', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:11:22'),
(229, '45892975001', 'Yetişal Şener', 1, '1975-09-30', '05577590175', 'nbilge@gmail.com', 'PSC 2196, Box 5652, APO AE 36803', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:25'),
(230, '48149177850', 'Hakikat Yılmaz', 1, '1991-01-19', '05027588425', 'bilirgulseren@gmail.com', '41038 Sezer Landing, Korutürkland, CT 66748', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:28'),
(231, '48648648648', 'Cabbar Keş', 1, '2000-01-01', '05486484848', 'cabbar@gmail.com', 'Mersin', NULL, 2, '2025-05-05 14:02:27', NULL, '2025-05-29 01:11:30'),
(232, '52909656101', 'Öztürk Akçay', 1, '1990-05-03', '05426617979', 'ashandemir@yahoo.com', '596 Zengin Crescent Suite 579, Port Tanses, ME 81042', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:32'),
(233, '53535353535', 'Kara Mesut', 1, '1982-07-14', '05535335353', 'karamesut@gmail.com', 'Ağrı', NULL, 2, '2025-05-01 12:33:54', NULL, '2025-05-29 01:11:33'),
(234, '53556093204', 'Tunç İnönü', 1, '1967-08-08', '05854678544', 'ykisakurek@lc.com', '76591 Mülâyim Forest, Aslanton, TX 37250', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:35'),
(235, '54615679334', 'Efe Durmuş', 1, '1990-12-29', '05084840862', 'tutkucanzengin@hotmail.com', '11426 Özakan Island Suite 110, Atiyyestad, KY 13900', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:37'),
(236, '61115542622', 'Kenter Bilge', 1, '2004-02-15', '05037129387', 'hatin27@gmail.com', '43006 Şener Station, Şafakfurt, SC 70829', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:46'),
(237, '61125457387', 'Gözel Demir', 1, '1981-09-05', '05438889937', 'akcaycakar@hotmail.com', '9296 Akçay Oval Apt. 827, Famiburgh, NV 81688', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:48'),
(238, '61799042102', 'Pehlil Gülen', 1, '1970-02-19', '05414183285', 'taylak51@hotmail.com', '49493 Arsoy Ramp Suite 383, North İlbek, IN 71482', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:50'),
(239, '65778899012', 'Tolga Şen', 1, '1986-03-30', '05315677890', 'tolga.sen@example.com', 'Kocaeli, Gebze', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:11:52'),
(240, '68230047868', 'Muktedir Arsoy', 1, '1971-09-27', '05046226567', 'aslancaglasin@tofas.org', '98373 Hançer Bridge, East Elöve, WV 63274', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:53'),
(241, '68876767721', 'Çeviköz Karadeniz', 1, '1992-03-02', '05278282742', 'meleknursener@yahoo.com', '1168 Sezer Wells Suite 775, Port Şennurland, NH 83710', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:55'),
(242, '70578578578', 'Cafer Buruş', 1, '2005-06-08', '05487558565', 'cafer@gmail.com', 'Konya/Mevlana', NULL, 1, '2025-05-04 00:52:31', NULL, '2025-05-29 01:11:57'),
(243, '72126464715', 'Özgür Arsoy', 1, '2001-12-24', '05956046618', 'ocalanolca@selcuk.com', '656 Afer Courts, Mishathaven, NC 73638', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:11:59'),
(244, '73384842199', 'Kamar Mansız', 1, '1980-06-22', '05419866524', 'cemiylemanco@koruturk.biz', '339 Gülhisar Mission Suite 191, Port Hayelhaven, OK 70842', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:01'),
(245, '73454019380', 'Günşen Akdeniz', 1, '2000-11-08', '05330833016', 'tarhankilicbay@hotmail.com', '707 Gelengül Mall, Durduton, NH 88682', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:04'),
(246, '75692459375', 'Mucahit Karakucak', 1, '2005-07-01', '05437993712', 'aliabbas71@seven.com', '4573 Ergül Mountains, South İmrihanmouth, CA 39857', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:08'),
(247, '77725228728', 'Cebesoy İhsanoğlu', 1, '1986-11-05', '05899642353', 'muarramanco@havelsan.biz', 'USNV Aksu, FPO AP 32020', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:10'),
(248, '78578578578', 'Cafer Buruk', 1, '2005-06-08', '05487558565', 'cafer@gmail.com', 'Konya/Mevlana', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:12:22'),
(249, '78978978978', 'Caner Er', 1, '1992-01-29', '05874574125', 'caner@gmail.com', 'Bursa / Orhangazi', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:12:24'),
(250, '84145933272', 'Rüknettin Kısakürek', 1, '1981-01-27', '05454990453', 'jergul@akar.com', '033 Denkel Ranch Apt. 790, Lake Avşin, NV 31216', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:27'),
(251, '84866618892', 'Kamar Aksu', 1, '1982-10-21', '05658271091', 'bilginfeyha@turk.com', '2208 Bilgin Islands Apt. 654, New Âdemville, TX 07404', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:29'),
(252, '85621850163', 'Amaç Aksu', 1, '1995-01-09', '05489025039', 'ehancer@gmail.com', '799 Aşhan Coves, West Abid, MA 81790', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:30'),
(253, '85807836134', 'Sabih Gülen', 1, '1980-04-08', '05903404384', 'emisaslan@yahoo.com', '929 Çetin Junctions Apt. 017, North Gülelmouth, AL 56573', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:32'),
(254, '87990011234', 'Onur Çelik', 1, '1982-06-22', '05337899012', 'onur.celik@example.com', 'Antalya, Muratpaşa', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:12:34'),
(255, '88728743152', 'Kopan Eraslan', 1, '1965-08-25', '05262067240', 'turksekim@sezer.com', 'USS İhsanoğlu, FPO AA 04660', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:36'),
(256, '91803397401', 'Mehmet Dumanlı', 1, '2003-05-07', '05101666870', 'yildirimcebesoy@yahoo.com', '0466 İzel Points Apt. 982, Aksuberg, ME 35681', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:39');
INSERT INTO `silinen_personel_kisisel_bilgi` (`id`, `tc_kimlik`, `ad_soyad`, `cinsiyet`, `dogum_tarihi`, `telefon`, `email`, `adres`, `pp_dosya_yolu`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(257, '95849825449', 'Toğan Hançer', 1, '1976-04-06', '05074517133', 'sensoyyertan@yahoo.com', '64361 Abdulgazi Forest, Erdoğanberg, IN 68585', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:41'),
(258, '96565234138', 'Ezgütekin Çorlu', 1, '1986-07-14', '05189092753', 'gorgunayduran@gulen.com', '303 Akyıldız Crescent Suite 990, Sultaneburgh, AL 93286', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:43'),
(259, '99900011122', 'Gürkan Tekin', 1, '1991-06-12', '05409990011', 'gurkan.tekin@example.com', 'İzmir, Konak', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:12:45'),
(260, '10702966706', 'Yepelek Güçlü', 0, '1965-06-28', '05236519941', 'ermutlusama@hotmail.com', '02921 Erdoğan Landing Suite 915, Tolonbayberg, VA 57762', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:12:55'),
(261, '11122233355', 'Zeynep Öztürk', 0, '1995-04-03', '05421112233', 'zeynep.ozturk@example.com', 'İstanbul, Kadıköy', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:12:59'),
(262, '11586938068', 'Gök Akar', 0, '1979-02-08', '05256527047', 'apaydinertas@camurcuoglu.com', '610 Zülgarni Overpass Suite 279, Uçbeyiburgh, KS 98668', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:05'),
(263, '19721811437', 'Ayça Dumanlı', 0, '1973-07-10', '05483564208', 'sakaryaunek@yahoo.com', '99488 Sernur Skyway, Akçamouth, AR 29719', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:06'),
(264, '18914117393', 'Ömriye Akçay', 0, '2007-02-26', '05761847230', 'sensoygucal@roketsan.info', '75354 Ferhan Fork, Gülenbury, NH 58721', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:09'),
(265, '21223344567', 'Ceren Kaya', 0, '1992-05-27', '05460122345', 'ceren.kaya@example.com', 'Mersin, Toroslar', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:13:11'),
(266, '32445566789', 'Aslıhan Güler', 0, '1991-04-25', '05482344567', 'aslihan.guler@example.com', 'Ankara, Mamak', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:13:13'),
(267, '44220969029', 'Maynur Çorlu', 0, '1997-09-02', '05915452267', 'ozokcu77@toyota.com', '56527 Resulcan Mill Suite 434, East Zehranurville, TN 45562', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:15'),
(268, '44435566677', 'Elif Ak', 0, '1985-03-22', '05354445566', 'elif.kaya@example.com', 'Adana, Seyhan', NULL, 1, '2025-05-04 00:52:31', NULL, '2025-05-29 01:13:17'),
(269, '44455566677', 'Elif Kaya', 0, '1985-03-22', '05354445566', 'elif.kaya@example.com', 'Adana, Seyhan', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:13:19'),
(270, '45187053494', 'Burcuhan Sezgin', 0, '1986-02-23', '05419518023', 'turabimanco@demirel.com', '442 Ünsever Walk, Durmuşside, CT 61061', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:21'),
(271, '45228851587', 'Gülşeref Arsoy', 0, '1985-06-03', '05669346547', 'wdurmus@tupras.com', '99305 Çamurcuoğlu Underpass Apt. 648, Aliabbashaven, WI 49647', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:23'),
(272, '46554947170', 'Gülsiye Sezer', 0, '1966-01-29', '05758428241', 'gulker@hotmail.com', '1891 Demir Tunnel, South Korayborough, KS 69070', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:25'),
(273, '54667788901', 'Merve Yılmaz', 0, '1989-07-18', '05404566789', 'merve.yilmaz@example.com', 'Bursa, Osmangazi', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:13:27'),
(274, '55555555555', 'Elif Eylül', 0, '1987-07-07', '05348521478', 'elif_eylul@gmail.com', 'Bolu', NULL, 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:13:37'),
(275, '62344565574', 'Nejdet Bilir', 0, '1978-03-07', '05024103865', 'aybet20@hotmail.com', '476 Yorulmaz Corners, Çıdalberg, ID 84734', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:39'),
(276, '62656265626', 'Menekşe Alçak', 0, '1988-10-14', '05626526354', 'meneksealcak@gmail.com', 'Melikgazi / Kayseri', NULL, 2, '2025-05-24 17:02:15', NULL, '2025-05-29 01:13:41'),
(277, '70433154100', 'Çağlar Kısakürek', 0, '2001-01-02', '05593917251', 'pdurdu@yahoo.com', '089 Ensari Throughway Suite 223, East Atilhan, CO 90039', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:43'),
(278, '76008350583', 'Cemiyle Alemdar', 0, '1977-05-02', '05007432927', 'yeneral56@opet.com', '22557 Akgündüz Street, West Uygunburgh, MO 52962', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:45'),
(279, '85082148628', 'Işin Gül', 0, '2003-06-27', '05132475469', 'sernur13@bilgin.biz', '4947 Bilkay Center Apt. 738, Port Tülinfurt, ME 54647', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:47'),
(280, '85858585858', 'Menekşe Uygur', 0, '1979-10-16', '05858558585', 'menekse_uygur@gmail.com', 'Hatay', NULL, 2, '2025-05-03 19:18:09', NULL, '2025-05-29 01:13:48'),
(281, '86103579727', 'Ergül Akar', 0, '1988-10-06', '05902330307', 'umanco@hotmail.com', '5250 Aysuna Harbor, Port Haluk, WV 21267', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:51'),
(282, '88317121003', 'Gürcüye Durdu', 0, '1991-11-17', '05352135680', 'hunalpyuksel@hotmail.com', '059 Kitan Field Suite 842, Türkland, IN 79362', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:52'),
(283, '89944171545', 'Cihan Kısakürek', 0, '1999-09-12', '05944665743', 'bedrialemdar@gmail.com', '8985 Akgündüz Spur Apt. 575, East Ünek, IN 29903', NULL, 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:54'),
(284, '99001122345', 'Gizem Öztürk', 0, '1990-02-08', '05448900123', 'gizem.ozturk@example.com', 'Trabzon, Akçaabat', NULL, 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:13:56'),
(285, '10702966706', 'Yepelek Güçlü', 0, '1965-06-28', '05236519941', 'ermutlusama@hotmail.com', '02921 Erdoğan Landing Suite 915, Tolonbayberg, VA 57762', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:13:59'),
(286, '11122233355', 'Zeynep Öztürk', 0, '1995-04-03', '05421112233', 'zeynep.ozturk@example.com', 'İstanbul, Kadıköy', 'f24a81e648e85178.png', 1, '2025-04-29 18:30:10', NULL, '2025-05-29 01:14:01'),
(287, '11586938068', 'Gök Akar', 0, '1979-02-08', '05256527047', 'apaydinertas@camurcuoglu.com', '610 Zülgarni Overpass Suite 279, Uçbeyiburgh, KS 98668', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:14:04'),
(288, '18914117393', 'Ömriye Akçay', 0, '2007-02-26', '05761847230', 'sensoygucal@roketsan.info', '75354 Ferhan Fork, Gülenbury, NH 58721', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:14:06'),
(289, '19721811437', 'Ayça Dumanlı', 0, '1973-07-10', '05483564208', 'sakaryaunek@yahoo.com', '99488 Sernur Skyway, Akçamouth, AR 29719', 'f24a81e648e85178.png', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:14:09'),
(290, '21223344567', 'Ceren Kaya', 0, '1992-05-27', '05460122345', 'ceren.kaya@example.com', 'Mersin, Toroslar', 'f24a81e648e85178.png', 1, '2025-05-05 14:20:58', NULL, '2025-05-29 01:14:12'),
(291, '41520643611', 'Sıla Gül', 1, '2006-10-16', '05701277063', 'akdenizsevla@yahoo.com', '640 Emiş Lights, East Evdeland, AL 47830', '8127c9e78bc4e43b.png ', 1, '2025-05-28 21:03:04', NULL, '2025-05-29 01:14:45'),
(292, '11586938068', 'Gök Akar', 0, '1979-02-08', '05256527047', 'apaydinertas@camurcuoglu.com', '610 Zülgarni Overpass Suite 279, Uçbeyiburgh, KS 98668', '4de66ffa091bc348.png', 1, '2025-05-28 21:03:04', 14, '2025-05-29 13:55:41'),
(293, '10702966706', 'Yepelek Güçlü', 0, '1965-06-28', '05236519941', 'ermutlusama@hotmail.com', '02921 Erdoğan Landing Suite 915, Tolonbayberg, VA 57762', '4de66ffa091bc348.png', 1, '2025-05-28 21:03:04', 14, '2025-05-29 13:56:01'),
(294, '12275304637', 'Celil Öcalan', 1, '1974-08-30', '05742054932', 'cogay76@yahoo.com', '512 Fırat Crossroad, Port Abdülahat, AK 39608', '8127c9e78bc4e43b.png', 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:10:33'),
(295, '12275304637', 'Celil Öcalan', 1, '1974-08-30', '05742054220', 'cogay76@yahoo.com', '512 Fırat Crossroad, Port Abdülahat, AK 39608', '8127c9e78bc4e43b.png', 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:11:29'),
(296, '11586938068', 'Gök Akar', 0, '1979-02-08', '05256527047', 'apaydinertas@camurcuoglu.com', '610 Zülgarni Overpass Suite 279, Uçbeyiburgh, KS 98668', '4de66ffa091bc348.png', 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:12:19'),
(297, '44435566677', 'Elif Ak', 0, '1985-03-22', '05354445566', 'elif.kaya@example.com', 'Adana, Seyhan', 'f24a81e648e85178.png', 1, '2025-05-04 00:52:31', 14, '2025-05-29 14:31:20'),
(298, '11586938068', 'Gök Akar', 0, '1979-02-08', '05256527080', 'apaydinertas@camurcuoglu.com', '610 Zülgarni Overpass Suite 279, Uçbeyiburgh, KS 98668', '4de66ffa091bc348.png', 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:31:28'),
(299, '21134455678', 'Ege Can', 1, '1987-09-10', '05551233456', 'emre.can@example.com', 'İstanbul, Esenyurt', '8127c9e78bc4e43b.png', 1, '2025-05-04 00:52:58', 14, '2025-05-29 14:32:50');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_personel_saglik_tetkikleri`
--

CREATE TABLE `silinen_personel_saglik_tetkikleri` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `saglik_tetkikleri_oldu_mu` tinyint(1) DEFAULT NULL,
  `tarih` date DEFAULT NULL,
  `gecerlilik_tarihi` date DEFAULT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_personel_saglik_tetkikleri`
--

INSERT INTO `silinen_personel_saglik_tetkikleri` (`id`, `tc_kimlik`, `saglik_tetkikleri_oldu_mu`, `tarih`, `gecerlilik_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, '38383838383', 1, '2024-10-17', '2025-10-17', 2, '2025-05-01 12:55:03', 2, '2025-05-03 16:18:31'),
(2, '35353535353', 1, '2025-02-25', '2026-02-25', 2, '2025-05-01 12:02:29', 2, '2025-05-03 16:37:33'),
(3, '35353535353', 1, '2025-02-25', '2026-02-25', 2, '2025-05-01 12:02:29', 2, '2025-05-03 16:38:23'),
(4, '53535353535', 1, '2025-04-15', '2026-04-15', 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:45:54'),
(5, '53535353535', 1, '2025-04-15', '2026-04-15', 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:46:22'),
(6, '53535353535', 1, '2025-04-15', '2026-04-15', 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:46:54'),
(7, '45454545454', 1, '2025-04-29', '2026-04-29', 2, '2025-05-03 18:26:25', 2, '2025-05-03 18:32:51'),
(8, '10112233456', 1, '2024-04-10', '2025-04-10', 1, '2025-05-05 14:20:58', 2, '2025-05-19 18:53:33'),
(9, '10112233456', 1, '2024-04-10', '2025-04-10', 1, '2025-05-05 14:20:58', 2, '2025-05-19 19:27:04'),
(10, '10112233456', 1, '2024-04-10', '2025-04-10', 1, '2025-05-05 14:20:58', 2, '2025-05-19 19:28:11'),
(11, '15635475958', 1, '2024-08-29', '2025-08-29', 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(12, '62656265626', 1, '2025-05-06', '2026-05-06', 2, '2025-05-24 17:02:15', 2, '2025-05-24 17:03:44'),
(14, '14345678901', 1, '2024-01-10', '2025-01-10', 1, '2025-05-04 00:52:31', 2, '2025-05-24 20:44:04'),
(17, '76889900123', 1, '2024-06-20', '2025-06-20', 1, '2025-05-05 14:20:58', 2, '2025-05-24 20:52:23'),
(18, '12345678901', 1, '2025-04-09', '2026-04-09', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(19, '12312312312', 1, '2024-05-21', '2025-05-21', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(20, '11122233344', 1, '2025-04-20', '2026-04-20', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:07:13'),
(21, '10112233456', 1, '2024-04-10', '2025-04-10', 1, '2025-05-05 14:20:58', 2, '2025-05-26 14:54:31'),
(22, '10112233456', 1, '2024-04-10', '2025-04-10', 1, '2025-05-05 14:20:58', 2, '2025-05-26 15:07:07'),
(23, '35353535353', 1, '2025-02-25', '2026-02-25', 2, '2025-05-01 12:02:29', 2, '2025-05-26 15:11:18'),
(24, '55055555555', 1, '2024-03-20', '2025-03-20', 1, '2025-05-04 00:52:31', 2, '2025-05-26 15:19:45'),
(25, '15635475958', 1, '2024-08-29', '2025-08-29', 2, '2025-05-24 14:31:00', 2, '2025-05-26 16:15:38'),
(26, '15635475958', 1, '2024-08-29', '2025-08-29', 2, '2025-05-24 14:31:00', 2, '2025-05-26 16:16:06'),
(27, '10112233344', 1, '2025-05-08', '2026-05-08', 1, '2025-04-29 18:30:10', 14, '2025-05-26 21:53:19'),
(28, '21223344567', 1, '2024-08-25', '2025-08-25', 1, '2025-05-05 14:20:58', 14, '2025-05-26 21:57:52'),
(29, '10112233344', 1, '2025-05-08', '2026-05-08', 1, '2025-04-29 18:30:10', 14, '2025-05-28 20:35:36'),
(30, '17146774186', 1, '2024-12-30', '2025-12-30', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:30'),
(31, '25579415521', 1, '2022-07-19', '2023-07-19', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:20'),
(32, '30745054447', 1, '2024-12-10', '2025-12-10', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:25'),
(33, '33206163294', 1, '2023-12-09', '2024-12-09', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:31'),
(34, '62208635365', 1, '2024-11-23', '2025-11-23', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:14'),
(35, '69725217525', 1, '2023-06-13', '2024-06-13', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:28'),
(36, '68844431069', 1, '2025-02-20', '2026-02-20', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:37'),
(37, '79030673830', 1, '2025-05-11', '2026-05-11', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:43'),
(38, '83982258713', 1, '2024-08-21', '2025-08-21', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:54'),
(39, '78985625753', 1, '2024-12-31', '2025-12-31', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:02'),
(40, '68007229034', 1, '2023-08-27', '2024-08-27', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:08'),
(41, '88861015819', 1, '2024-10-11', '2025-10-11', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:20'),
(42, '97177517016', 1, '2024-10-13', '2025-10-13', 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:26'),
(43, '07374167243', 1, '2022-11-12', '2023-11-12', 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:14:57'),
(44, '00292185156', 1, '2023-07-02', '2024-07-02', 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:00'),
(45, '04078593213', 1, '2025-02-05', '2026-02-05', 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:03'),
(46, '11234567890', 1, '2025-05-15', '2026-05-15', 14, '2025-05-28 23:21:28', 14, '2025-05-28 23:21:56'),
(47, '11586938068', 1, '2022-07-15', '2023-07-15', 1, '2025-05-28 21:03:04', 14, '2025-05-29 13:55:41'),
(48, '10702966706', 1, '2023-06-24', '2024-06-24', 1, '2025-05-28 21:03:04', 14, '2025-05-29 13:56:01'),
(49, '11586938068', 1, '2024-06-20', '2025-06-20', 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:12:19'),
(50, '44435566677', 1, '2024-02-01', '2025-02-01', 1, '2025-05-04 00:52:31', 14, '2025-05-29 14:31:20'),
(51, '11586938068', 1, '2024-06-20', '2025-06-20', 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:31:28'),
(52, '21134455678', 1, '2024-09-20', '2025-09-20', 1, '2025-05-04 00:52:58', 14, '2025-05-29 14:32:50');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_personel_sirket_bilgi`
--

CREATE TABLE `silinen_personel_sirket_bilgi` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `firma_id` int(11) NOT NULL,
  `meslek_id` int(11) NOT NULL,
  `ise_giris_tarihi` date DEFAULT NULL,
  `toplam_deneyim_yili` int(11) DEFAULT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_personel_sirket_bilgi`
--

INSERT INTO `silinen_personel_sirket_bilgi` (`id`, `tc_kimlik`, `firma_id`, `meslek_id`, `ise_giris_tarihi`, `toplam_deneyim_yili`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, '38383838383', 3, 5, '2023-10-17', 2, 2, '2025-05-01 12:55:03', 2, '2025-05-03 16:18:31'),
(2, '35353535353', 7, 12, '2023-10-25', 2, 2, '2025-05-01 12:02:29', 2, '2025-05-03 16:37:33'),
(3, '35353535353', 7, 12, '2023-10-25', 2, 2, '2025-05-01 12:02:29', 2, '2025-05-03 16:38:23'),
(4, '53535353535', 2, 5, '2023-04-05', 2, 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:45:54'),
(5, '53535353535', 2, 5, '2023-04-05', 2, 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:46:22'),
(6, '53535353535', 2, 5, '2023-04-05', 2, 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:46:54'),
(7, '45454545454', 4, 4, '2024-12-13', 1, 2, '2025-05-03 18:26:25', 2, '2025-05-03 18:32:51'),
(8, '10112233456', 3, 6, '2021-10-01', 4, 1, '2025-05-05 14:20:58', 2, '2025-05-19 18:53:33'),
(9, '10112233456', 3, 6, '2021-10-01', 4, 1, '2025-05-05 14:20:58', 2, '2025-05-19 19:27:04'),
(10, '10112233456', 3, 5, '2021-10-01', 4, 1, '2025-05-05 14:20:58', 2, '2025-05-19 19:28:11'),
(11, '15635475958', 5, 5, '2024-06-26', 1, 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(12, '62656265626', 5, 4, '2022-06-22', 3, 2, '2025-05-24 17:02:15', 2, '2025-05-24 17:03:44'),
(14, '14345678901', 1, 1, '2020-01-15', 5, 1, '2025-05-04 00:52:31', 2, '2025-05-24 20:44:04'),
(17, '76889900123', 1, 3, '2022-05-15', 3, 1, '2025-05-05 14:20:58', 2, '2025-05-24 20:52:23'),
(18, '12345678901', 1, 1, '2016-03-10', 9, 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(19, '12312312312', 1, 2, '2025-04-21', 0, 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(20, '11122233344', 1, 1, '2010-01-01', 15, 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:07:13'),
(21, '10112233456', 3, 5, '2021-10-01', 4, 1, '2025-05-05 14:20:58', 2, '2025-05-26 14:54:31'),
(22, '10112233456', 3, 5, '2021-10-01', 4, 1, '2025-05-05 14:20:58', 2, '2025-05-26 15:07:07'),
(23, '35353535353', 7, 12, '2023-10-25', 2, 2, '2025-05-01 12:02:29', 2, '2025-05-26 15:11:18'),
(24, '55055555555', 4, 4, '2021-09-01', 4, 1, '2025-05-04 00:52:31', 2, '2025-05-26 15:19:45'),
(25, '43556677890', 3, 5, '2020-11-20', 5, 1, '2025-05-05 14:20:58', 2, '2025-05-26 16:13:11'),
(26, '15635475958', 5, 3, '2024-06-26', 1, 2, '2025-05-24 14:31:00', 2, '2025-05-26 16:15:38'),
(27, '15635475958', 5, 3, '2024-06-26', 1, 2, '2025-05-24 14:31:00', 2, '2025-05-26 16:16:06'),
(28, '10112233344', 2, 10, '2009-05-12', 16, 1, '2025-04-29 18:30:10', 14, '2025-05-26 21:53:19'),
(29, '21223344567', 4, 10, '2023-03-20', 2, 1, '2025-05-05 14:20:58', 14, '2025-05-26 21:57:52'),
(30, '10112233344', 2, 10, '2009-05-12', 16, 1, '2025-04-29 18:30:10', 14, '2025-05-28 20:35:36'),
(31, '10405411526', 5, 4, '2021-04-03', 4, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:15'),
(32, '14933163735', 1, 1, '2022-10-11', 3, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:20'),
(33, '17146774186', 4, 9, '2021-07-22', 4, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:05:30'),
(34, '22999119148', 4, 9, '2022-12-11', 3, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:15'),
(35, '25579415521', 3, 8, '2024-04-15', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:20'),
(36, '30745054447', 2, 7, '2024-05-28', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:25'),
(37, '33206163294', 1, 6, '2022-09-29', 3, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:31'),
(38, '37562890028', 4, 10, '2025-02-09', 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:36'),
(39, '37474112490', 5, 9, '2022-09-02', 3, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:41'),
(40, '46660993538', 5, 3, '2024-06-08', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:46'),
(41, '48909010016', 4, 5, '2023-09-04', 2, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:06:53'),
(42, '62208635365', 2, 6, '2022-08-28', 3, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:14'),
(43, '69725217525', 4, 7, '2024-05-08', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:28'),
(44, '68844431069', 5, 5, '2023-01-18', 2, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:37'),
(45, '79030673830', 1, 8, '2021-08-23', 4, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:43'),
(46, '83982258713', 4, 4, '2023-12-11', 2, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:08:54'),
(47, '78985625753', 4, 10, '2021-07-06', 4, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:02'),
(48, '68007229034', 5, 1, '2023-01-21', 2, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:08'),
(49, '88861015819', 5, 3, '2025-01-10', 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:20'),
(50, '97177517016', 1, 6, '2020-11-18', 5, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:26'),
(51, '99293784270', 2, 2, '2024-02-05', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:33'),
(52, '97296590277', 2, 1, '2025-02-08', 0, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:42'),
(53, '53714785969', 2, 7, '2024-01-17', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:09:52'),
(54, '87014626383', 1, 10, '2024-08-15', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:10:00'),
(55, '82922169958', 2, 9, '2023-05-17', 2, 1, '2025-05-28 21:03:04', 14, '2025-05-28 22:10:34'),
(56, '07374167243', 3, 8, '2020-12-07', 5, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:14:57'),
(57, '00292185156', 3, 10, '2022-02-28', 3, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:00'),
(58, '03758997571', 4, 6, '2022-07-16', 3, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:01'),
(59, '04078593213', 4, 3, '2021-04-07', 4, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:03'),
(60, '05064138367', 2, 1, '2024-01-12', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:05'),
(61, '08668826343', 5, 2, '2024-11-26', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:07'),
(62, '09977008860', 1, 5, '2022-09-23', 3, 1, '2025-05-28 21:03:04', 14, '2025-05-28 23:15:09'),
(63, '11234567890', 3, 3, '2025-04-29', 0, 14, '2025-05-28 23:21:28', 14, '2025-05-28 23:21:56'),
(64, '41520643611', 2, 7, '2023-02-04', 2, 1, '2025-05-28 21:03:04', 14, '2025-05-29 01:00:57'),
(65, '11586938068', 5, 7, '2021-03-03', 4, 1, '2025-05-28 21:03:04', 14, '2025-05-29 13:55:41'),
(66, '10702966706', 1, 4, '2024-07-18', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-29 13:56:01'),
(67, '12275304637', 4, 8, '2024-05-04', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:10:33'),
(68, '12275304637', 4, 8, '2024-05-04', 1, 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:11:29'),
(69, '11586938068', 5, 7, '2021-03-03', 4, 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:12:19'),
(70, '44435566677', 5, 5, '2019-04-10', 6, 1, '2025-05-04 00:52:31', 14, '2025-05-29 14:31:20'),
(71, '11586938068', 5, 7, '2021-03-03', 4, 1, '2025-05-28 21:03:04', 14, '2025-05-29 14:31:28'),
(72, '21134455678', 1, 1, '2021-06-15', 4, 1, '2025-05-04 00:52:58', 14, '2025-05-29 14:32:50');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_personel_uyarilar`
--

CREATE TABLE `silinen_personel_uyarilar` (
  `id` int(11) NOT NULL,
  `tc_kimlik` varchar(11) NOT NULL,
  `uyari_tipi_id` int(11) NOT NULL,
  `uyari_nedeni` varchar(500) DEFAULT NULL,
  `uyari_tarihi` date NOT NULL,
  `olusturan_kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime NOT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `silinen_personel_uyarilar`
--

INSERT INTO `silinen_personel_uyarilar` (`id`, `tc_kimlik`, `uyari_tipi_id`, `uyari_nedeni`, `uyari_tarihi`, `olusturan_kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, '38383838383', 5, 'aaaaaaaaaa aaaaaaaaaaaaa', '2024-10-18', 2, '2025-05-01 12:55:03', 2, '2025-05-03 16:18:31'),
(2, '38383838383', 5, 'aaaaaaaaaaa aaaaaaaaaaaaaaaa aaaaaaaaaa', '2024-05-17', 2, '2025-05-01 12:55:03', 2, '2025-05-03 16:18:31'),
(3, '53535353535', 1, 'aaaaaaaaaa aaaaaaaaaaaa aaaaaaaaaa aaaaaaa', '2025-02-03', 2, '2025-05-01 12:33:54', 2, '2025-05-03 16:45:54'),
(4, '45454545454', 3, 'aaaaaa aaaaaaaaa aaaaaaaa', '2025-04-23', 2, '2025-05-03 18:26:25', 2, '2025-05-03 18:32:52'),
(5, '10112233456', 2, 'Düzensiz çalışma, karmaşıklığa yol açıyor.', '2025-05-19', NULL, '2025-05-19 18:53:33', 2, '2025-05-19 19:27:04'),
(6, '10112233456', 2, 'Düzensiz çalışma, karmaşıklığa yol açıyor.', '2025-05-19', 2, '2025-05-19 19:27:04', 2, '2025-05-19 19:28:11'),
(7, '15635475958', 1, 'Ekipman eksikliği.', '2025-05-24', 2, '2025-05-24 14:31:00', 2, '2025-05-24 16:54:54'),
(8, '12345678901', 1, 'Kask takmadan şantiye alanına girdi.', '2022-06-15', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(9, '12345678901', 1, 'Baretsiz çalışma yapıyor.', '2025-04-27', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(10, '12345678901', 3, 'Yüksekte kemersiz çalışıyor.', '2025-04-27', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(11, '12345678901', 1, 'Test uyarı 1', '2025-03-10', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(12, '12345678901', 2, 'Test uyarı 2', '2025-04-15', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(13, '12345678901', 1, 'Baret takmadan çalıştı.', '2025-04-20', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(14, '12345678901', 2, 'Malzemeleri düzensiz bıraktı.', '2025-02-28', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:00'),
(15, '12312312312', 7, 'aaaaa aaaaa aaaaaa aaaaaaa', '2025-04-16', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(16, '12312312312', 2, 'Çalışma alanında malzeme düzensizliği.', '2025-04-05', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(17, '12312312312', 5, 'Yasak alanda sigara içti.', '2024-10-20', 1, '2025-04-29 18:30:10', 2, '2025-05-24 21:05:53'),
(18, '10112233456', 2, 'Düzensiz çalışma, karmaşıklığa yol açıyor.', '2025-05-19', 2, '2025-05-19 19:28:11', 2, '2025-05-26 14:54:31'),
(19, '10112233456', 4, 'talimatlara uymuyor.', '2025-05-19', 2, '2025-05-19 19:28:11', 2, '2025-05-26 14:54:31'),
(20, '10112233456', 2, 'Düzensiz çalışma, karmaşıklığa yol açıyor.', '2025-05-19', 2, '2025-05-19 19:28:11', 2, '2025-05-26 15:07:07'),
(21, '10112233456', 4, 'talimatlara uymuyor.', '2025-05-19', 2, '2025-05-19 19:28:11', 2, '2025-05-26 15:07:07'),
(22, '15635475958', 1, 'Ekipman eksikliği.', '2025-05-24', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:15:38'),
(23, '15635475958', 2, 'Düzensiz çalışma.', '2025-05-26', 2, '2025-05-26 15:55:11', 2, '2025-05-26 16:15:38'),
(24, '15635475958', 1, 'Ekipman eksikliği.', '2025-05-24', 2, '2025-05-24 16:54:54', 2, '2025-05-26 16:16:06'),
(25, '15635475958', 2, 'Düzensiz çalışma.', '2025-05-26', 2, '2025-05-26 15:55:11', 2, '2025-05-26 16:16:06'),
(26, '10112233344', 4, 'nnnnn nnnnnnn nnnnnnn nnnnnn nnnnn', '2025-01-18', 1, '2025-04-29 18:30:10', 14, '2025-05-26 21:53:19'),
(27, '10112233344', 4, 'nnnnn nnnnnnn nnnnnnn nnnnnn nnnnn', '2025-01-18', 14, '2025-04-29 18:30:10', 14, '2025-05-28 20:35:36'),
(28, '25252525252', 3, 'dikkatsiz çalışma', '2025-05-19', 2, '2025-05-19 18:38:29', NULL, '2025-05-28 22:14:23'),
(29, '85858585858', 4, 'aaaaaaaaaaaa aaaaaaaaaaaaaaa aaaaaaaaaaa', '2025-05-03', 2, '2025-05-03 19:18:09', NULL, '2025-05-28 22:14:37'),
(30, '78578578578', 1, 'aaaaaaaaaaaaaa', '2025-04-10', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 22:14:49'),
(31, '44455566677', 1, 'bbbbbb bbbbbbbb bbbbbbb', '2025-02-10', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 22:15:01'),
(32, '99900011122', 3, 'cccccc ccccccc ccccc ccccccc cccccccc', '2025-03-20', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 22:15:15'),
(33, '11122233355', 1, 'mmmmmmmm mmmmmmmm mmmmmmmmm mmmmmmm', '2025-04-05', 1, '2025-04-29 18:30:10', NULL, '2025-05-28 22:15:51'),
(34, '48648648648', 8, 'Örnek Deneme kuralsızlık uyarı 35', '2025-05-05', 2, '2025-05-05 14:02:27', NULL, '2025-05-28 22:17:47');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `silinen_raporlar`
--

CREATE TABLE `silinen_raporlar` (
  `id` int(11) NOT NULL,
  `rapor_basligi` varchar(255) DEFAULT NULL,
  `rapor_icerigi` text DEFAULT NULL,
  `rapor_durumu` int(11) DEFAULT NULL,
  `rapor_tarihi` datetime DEFAULT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `olusturulma_tarihi` datetime DEFAULT NULL,
  `silen_kullanici_id` int(11) DEFAULT NULL,
  `silinme_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `silinen_raporlar`
--

INSERT INTO `silinen_raporlar` (`id`, `rapor_basligi`, `rapor_icerigi`, `rapor_durumu`, `rapor_tarihi`, `kullanici_id`, `olusturulma_tarihi`, `silen_kullanici_id`, `silinme_tarihi`) VALUES
(1, 'Baret Kullanımı İncelemesi', 'Tüm personelin baret taktığı görüldü, sorun bulunmamaktadır.', 1, '2025-05-19 09:30:00', 2, '2025-05-19 12:04:00', NULL, '2025-05-19 12:58:02'),
(2, 'Şantiye Girişinde Çukur', 'Ana giriş kapısı önünde halen kapatılmamış bir çukur var, güvenlik tehlikesi oluşturuyor.', 2, '2025-05-18 14:00:00', 2, '2025-05-19 12:04:00', NULL, '2025-05-19 13:02:05'),
(4, 'aaaaaaa', 'bbbb bbbbbbb bbbbbbbb bbbbbbb bbbbbbb', 2, '2025-05-19 11:18:00', 2, '2025-05-19 12:18:49', NULL, '2025-05-19 12:22:03'),
(5, 'aaaaaaa', 'bbbb bbbbbbb bbbbbbbb bbbbbbb bbbbbbb', 2, '2025-05-19 11:18:00', 2, '2025-05-19 12:19:59', NULL, '2025-05-19 12:22:05'),
(6, 'Baret Kullanımı', 'Baret takılmıyor.', 2, '2025-05-07 13:11:00', 1, '2025-05-19 13:11:54', NULL, '2025-05-19 13:12:43'),
(7, 'Baret Kullanımı', 'aaaaaaa aaaaaaaa aaaaaaaa aaaaaaaa', 3, '2025-05-08 13:26:00', 2, '2025-05-19 13:26:20', NULL, '2025-05-19 13:26:32'),
(8, 'Baret Kullanımı', 'aaaaaaa aaaaaaaa aaaaaaaa aaaaaaaa', 3, '2025-05-15 13:26:00', 2, '2025-05-19 13:26:32', NULL, '2025-05-19 13:26:39'),
(9, 'Baret Kullanımı İncelemesi', 'Tüm personelin baret taktığı görüldü, sorun bulunmamaktadır.', 1, '2025-05-19 10:30:00', 2, '2025-05-19 12:58:02', NULL, '2025-05-19 13:26:43'),
(10, 'Duman Salınımı', 'Duman salınımı mevcuttur', 2, '2025-05-14 16:43:00', 2, '2025-05-19 16:43:39', NULL, '2025-05-19 16:45:57'),
(11, 'Duman Salınımı', 'Duman salınımı mevcuttur', 2, '2025-05-14 16:43:00', 2, '2025-05-19 16:45:57', NULL, '2025-05-19 16:51:01'),
(12, 'Duman Salınımı', 'Duman salınımı mevcuttur. Bilginize sunarız...', 2, '2025-05-14 16:43:00', 2, '2025-05-19 16:51:01', NULL, '2025-05-19 16:51:11'),
(13, 'Baret Sayısı', 'Baret sayısı eksik.', 2, '2025-05-07 16:57:00', 2, '2025-05-19 16:58:03', NULL, '2025-05-19 16:58:14'),
(14, 'Baret Sayısı', 'Baret sayısı eksik. Sorun Devam Ediyor.', 2, '2025-05-07 16:57:00', 2, '2025-05-19 16:58:14', NULL, '2025-05-19 16:58:18'),
(15, 'Sigara Yasağı', 'bbbbbbbbbbbb', 1, '2025-05-19 18:31:00', 2, '2025-05-19 18:32:25', NULL, '2025-05-19 18:32:30'),
(16, 'Herkes Sahada', 'Herkes Sahada', 1, '2025-05-15 16:58:00', 2, '2025-05-19 16:58:49', NULL, '2025-05-23 19:37:15'),
(17, 'Duman Salınımı', 'Duman salınımı mevcuttur. Bilginize sunarız...', 2, '2025-05-14 16:43:00', 2, '2025-05-19 16:51:11', NULL, '2025-05-23 19:39:48'),
(21, 'Yangın Tüpü Eksikliği', 'Daha önce bildirilen yangın tüpü eksikliği giderilmiş, dolum yapılmış.', 3, '2025-05-17 15:45:00', 1, '2025-05-19 12:04:00', NULL, '2025-05-24 16:24:18'),
(22, 'Yangın Tüpü Eksikliği', 'Yangın Tüpü Eksikliği mevcuttur.', 2, '2025-05-23 19:44:00', 2, '2025-05-23 19:44:38', NULL, '2025-05-24 16:25:17'),
(23, 'Duman Salınımı', 'Duman salınımı mevcuttur. Bilginize sunarız...', 2, '2025-05-23 19:39:00', 2, '2025-05-23 19:39:48', 2, '2025-05-24 16:27:48'),
(24, 'Baret Kullanımı', 'Baret takılmıyor.', 3, '2025-05-07 13:11:00', 1, '2025-05-19 13:12:43', 2, '2025-05-24 16:34:30'),
(25, 'Baret Kullanımı', 'Baret takılmıyor. Bilginize.', 3, '2025-05-07 13:11:00', 2, '2025-05-24 16:34:30', 2, '2025-05-24 16:35:04'),
(26, 'Duman Salınımı', 'Duman salınımı mevcuttur.', 2, '2025-05-24 16:27:00', 2, '2025-05-24 16:28:12', 14, '2025-05-29 00:44:20');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `aracli_kazalar`
--
ALTER TABLE `aracli_kazalar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arac_id` (`arac_id`),
  ADD KEY `tc_kimlik` (`tc_kimlik`),
  ADD KEY `is_kazasi_tip_id` (`is_kazasi_tip_id`),
  ADD KEY `yaralanma_durumu_id` (`yaralanma_durumu_id`),
  ADD KEY `yaralanma_tip_id` (`yaralanma_tip_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `arac_bilgi`
--
ALTER TABLE `arac_bilgi`
  ADD PRIMARY KEY (`arac_id`),
  ADD UNIQUE KEY `plaka_no` (`plaka_no`),
  ADD KEY `arac_tipi_id` (`arac_tipi_id`),
  ADD KEY `marka_id` (`marka_id`),
  ADD KEY `model_id` (`model_id`),
  ADD KEY `firma_id` (`firma_id`),
  ADD KEY `arac_durum_id` (`arac_durum_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `arac_muayene`
--
ALTER TABLE `arac_muayene`
  ADD PRIMARY KEY (`muayene_id`),
  ADD KEY `arac_id` (`arac_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `arac_operator_atama`
--
ALTER TABLE `arac_operator_atama`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arac_id` (`arac_id`),
  ADD KEY `tc_kimlik` (`tc_kimlik`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `duyurular`
--
ALTER TABLE `duyurular`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_arac_durumlari`
--
ALTER TABLE `hazir_arac_durumlari`
  ADD PRIMARY KEY (`arac_durum_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_arac_tipleri`
--
ALTER TABLE `hazir_arac_tipleri`
  ADD PRIMARY KEY (`arac_tipi_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_belgeler`
--
ALTER TABLE `hazir_belgeler`
  ADD PRIMARY KEY (`belge_id`),
  ADD KEY `fk_hazir_belgeler_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_ehliyetler`
--
ALTER TABLE `hazir_ehliyetler`
  ADD PRIMARY KEY (`ehliyet_id`),
  ADD KEY `fk_hazir_ehliyetler_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_firmalar`
--
ALTER TABLE `hazir_firmalar`
  ADD PRIMARY KEY (`firma_id`),
  ADD KEY `fk_hazir_firmalar_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_is_kazalari`
--
ALTER TABLE `hazir_is_kazalari`
  ADD PRIMARY KEY (`is_kazasi_tip_id`),
  ADD KEY `fk_hazir_is_kazalari_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_markalar`
--
ALTER TABLE `hazir_markalar`
  ADD PRIMARY KEY (`marka_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_meslekler`
--
ALTER TABLE `hazir_meslekler`
  ADD PRIMARY KEY (`meslek_id`),
  ADD UNIQUE KEY `meslek_adi` (`meslek_adi`),
  ADD KEY `fk_hazir_meslekler_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_modeller`
--
ALTER TABLE `hazir_modeller`
  ADD PRIMARY KEY (`model_id`),
  ADD KEY `marka_id` (`marka_id`),
  ADD KEY `arac_tipi_id` (`arac_tipi_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_rapor_durumlari`
--
ALTER TABLE `hazir_rapor_durumlari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_uyarilar`
--
ALTER TABLE `hazir_uyarilar`
  ADD PRIMARY KEY (`uyari_tip_id`),
  ADD KEY `fk_hazir_uyarilar_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_yaralanma_durumlar`
--
ALTER TABLE `hazir_yaralanma_durumlar`
  ADD PRIMARY KEY (`yaralanma_durum_id`),
  ADD KEY `fk_hazir_yaralanma_durumlar_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `hazir_yaralanma_tipler`
--
ALTER TABLE `hazir_yaralanma_tipler`
  ADD PRIMARY KEY (`yaralanma_tip_id`),
  ADD KEY `fk_hazir_yaralanma_tipler_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`kul_id`),
  ADD KEY `olusturan_kullanici_id` (`olusturan_kullanici_id`);

--
-- Tablo için indeksler `personel_belgeler`
--
ALTER TABLE `personel_belgeler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tc_kimlik` (`tc_kimlik`),
  ADD KEY `belge_id` (`belge_id`),
  ADD KEY `fk_personel_belgeler_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `personel_ehliyetler`
--
ALTER TABLE `personel_ehliyetler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tc_kimlik` (`tc_kimlik`),
  ADD KEY `ehliyet_id` (`ehliyet_id`),
  ADD KEY `fk_personel_ehliyetler_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `personel_gerekli_belge`
--
ALTER TABLE `personel_gerekli_belge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tc_kimlik` (`tc_kimlik`),
  ADD KEY `fk_personel_gerekli_belge_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `personel_is_kazalari`
--
ALTER TABLE `personel_is_kazalari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tc_kimlik` (`tc_kimlik`),
  ADD KEY `is_kazasi_tip_id` (`is_kazasi_tip_id`),
  ADD KEY `yaralanma_durumu_id` (`yaralanma_durumu_id`),
  ADD KEY `yaralanma_tip_id` (`yaralanma_tip_id`),
  ADD KEY `fk_personel_is_kazalari_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `personel_kisisel_bilgi`
--
ALTER TABLE `personel_kisisel_bilgi`
  ADD PRIMARY KEY (`tc_kimlik`),
  ADD KEY `fk_personel_kisisel_bilgi_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `personel_saglik_tetkikleri`
--
ALTER TABLE `personel_saglik_tetkikleri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tc_kimlik` (`tc_kimlik`),
  ADD KEY `fk_personel_saglik_tetkikleri_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `personel_sirket_bilgi`
--
ALTER TABLE `personel_sirket_bilgi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tc_kimlik` (`tc_kimlik`),
  ADD KEY `firma_id` (`firma_id`),
  ADD KEY `meslek_id` (`meslek_id`),
  ADD KEY `fk_personel_sirket_bilgi_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `personel_uyarilar`
--
ALTER TABLE `personel_uyarilar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tc_kimlik` (`tc_kimlik`),
  ADD KEY `uyari_tipi_id` (`uyari_tipi_id`),
  ADD KEY `fk_personel_uyarilar_kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `raporlar`
--
ALTER TABLE `raporlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rapor_durumu` (`rapor_durumu`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `silinen_aracli_kazalar`
--
ALTER TABLE `silinen_aracli_kazalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_arac_bilgi`
--
ALTER TABLE `silinen_arac_bilgi`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_arac_muayene`
--
ALTER TABLE `silinen_arac_muayene`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_arac_operator_atama`
--
ALTER TABLE `silinen_arac_operator_atama`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_duyurular`
--
ALTER TABLE `silinen_duyurular`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_arac_durumlari`
--
ALTER TABLE `silinen_hazir_arac_durumlari`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_arac_tipleri`
--
ALTER TABLE `silinen_hazir_arac_tipleri`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_belgeler`
--
ALTER TABLE `silinen_hazir_belgeler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_ehliyetler`
--
ALTER TABLE `silinen_hazir_ehliyetler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_firmalar`
--
ALTER TABLE `silinen_hazir_firmalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_is_kazalari`
--
ALTER TABLE `silinen_hazir_is_kazalari`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_markalar`
--
ALTER TABLE `silinen_hazir_markalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_meslekler`
--
ALTER TABLE `silinen_hazir_meslekler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_modeller`
--
ALTER TABLE `silinen_hazir_modeller`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_rapor_durumlari`
--
ALTER TABLE `silinen_hazir_rapor_durumlari`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_uyarilar`
--
ALTER TABLE `silinen_hazir_uyarilar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_yaralanma_durumlar`
--
ALTER TABLE `silinen_hazir_yaralanma_durumlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_hazir_yaralanma_tipler`
--
ALTER TABLE `silinen_hazir_yaralanma_tipler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_kullanicilar`
--
ALTER TABLE `silinen_kullanicilar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_personel_belgeler`
--
ALTER TABLE `silinen_personel_belgeler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_personel_ehliyetler`
--
ALTER TABLE `silinen_personel_ehliyetler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_personel_gerekli_belge`
--
ALTER TABLE `silinen_personel_gerekli_belge`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_personel_is_kazalari`
--
ALTER TABLE `silinen_personel_is_kazalari`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_personel_kisisel_bilgi`
--
ALTER TABLE `silinen_personel_kisisel_bilgi`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_personel_saglik_tetkikleri`
--
ALTER TABLE `silinen_personel_saglik_tetkikleri`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_personel_sirket_bilgi`
--
ALTER TABLE `silinen_personel_sirket_bilgi`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_personel_uyarilar`
--
ALTER TABLE `silinen_personel_uyarilar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `silinen_raporlar`
--
ALTER TABLE `silinen_raporlar`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `aracli_kazalar`
--
ALTER TABLE `aracli_kazalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- Tablo için AUTO_INCREMENT değeri `arac_bilgi`
--
ALTER TABLE `arac_bilgi`
  MODIFY `arac_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Tablo için AUTO_INCREMENT değeri `arac_muayene`
--
ALTER TABLE `arac_muayene`
  MODIFY `muayene_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `arac_operator_atama`
--
ALTER TABLE `arac_operator_atama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Tablo için AUTO_INCREMENT değeri `duyurular`
--
ALTER TABLE `duyurular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_arac_durumlari`
--
ALTER TABLE `hazir_arac_durumlari`
  MODIFY `arac_durum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_arac_tipleri`
--
ALTER TABLE `hazir_arac_tipleri`
  MODIFY `arac_tipi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_belgeler`
--
ALTER TABLE `hazir_belgeler`
  MODIFY `belge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_ehliyetler`
--
ALTER TABLE `hazir_ehliyetler`
  MODIFY `ehliyet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_firmalar`
--
ALTER TABLE `hazir_firmalar`
  MODIFY `firma_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_is_kazalari`
--
ALTER TABLE `hazir_is_kazalari`
  MODIFY `is_kazasi_tip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_markalar`
--
ALTER TABLE `hazir_markalar`
  MODIFY `marka_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_meslekler`
--
ALTER TABLE `hazir_meslekler`
  MODIFY `meslek_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_modeller`
--
ALTER TABLE `hazir_modeller`
  MODIFY `model_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_rapor_durumlari`
--
ALTER TABLE `hazir_rapor_durumlari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_uyarilar`
--
ALTER TABLE `hazir_uyarilar`
  MODIFY `uyari_tip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_yaralanma_durumlar`
--
ALTER TABLE `hazir_yaralanma_durumlar`
  MODIFY `yaralanma_durum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `hazir_yaralanma_tipler`
--
ALTER TABLE `hazir_yaralanma_tipler`
  MODIFY `yaralanma_tip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `kul_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `personel_belgeler`
--
ALTER TABLE `personel_belgeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Tablo için AUTO_INCREMENT değeri `personel_ehliyetler`
--
ALTER TABLE `personel_ehliyetler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Tablo için AUTO_INCREMENT değeri `personel_gerekli_belge`
--
ALTER TABLE `personel_gerekli_belge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- Tablo için AUTO_INCREMENT değeri `personel_is_kazalari`
--
ALTER TABLE `personel_is_kazalari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Tablo için AUTO_INCREMENT değeri `personel_saglik_tetkikleri`
--
ALTER TABLE `personel_saglik_tetkikleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- Tablo için AUTO_INCREMENT değeri `personel_sirket_bilgi`
--
ALTER TABLE `personel_sirket_bilgi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- Tablo için AUTO_INCREMENT değeri `personel_uyarilar`
--
ALTER TABLE `personel_uyarilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Tablo için AUTO_INCREMENT değeri `raporlar`
--
ALTER TABLE `raporlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_aracli_kazalar`
--
ALTER TABLE `silinen_aracli_kazalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_arac_bilgi`
--
ALTER TABLE `silinen_arac_bilgi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_arac_muayene`
--
ALTER TABLE `silinen_arac_muayene`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_arac_operator_atama`
--
ALTER TABLE `silinen_arac_operator_atama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_duyurular`
--
ALTER TABLE `silinen_duyurular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_arac_durumlari`
--
ALTER TABLE `silinen_hazir_arac_durumlari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_arac_tipleri`
--
ALTER TABLE `silinen_hazir_arac_tipleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_belgeler`
--
ALTER TABLE `silinen_hazir_belgeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_ehliyetler`
--
ALTER TABLE `silinen_hazir_ehliyetler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_firmalar`
--
ALTER TABLE `silinen_hazir_firmalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_is_kazalari`
--
ALTER TABLE `silinen_hazir_is_kazalari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_markalar`
--
ALTER TABLE `silinen_hazir_markalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_meslekler`
--
ALTER TABLE `silinen_hazir_meslekler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_modeller`
--
ALTER TABLE `silinen_hazir_modeller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_rapor_durumlari`
--
ALTER TABLE `silinen_hazir_rapor_durumlari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_uyarilar`
--
ALTER TABLE `silinen_hazir_uyarilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_yaralanma_durumlar`
--
ALTER TABLE `silinen_hazir_yaralanma_durumlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_hazir_yaralanma_tipler`
--
ALTER TABLE `silinen_hazir_yaralanma_tipler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_kullanicilar`
--
ALTER TABLE `silinen_kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_personel_belgeler`
--
ALTER TABLE `silinen_personel_belgeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_personel_ehliyetler`
--
ALTER TABLE `silinen_personel_ehliyetler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_personel_gerekli_belge`
--
ALTER TABLE `silinen_personel_gerekli_belge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_personel_is_kazalari`
--
ALTER TABLE `silinen_personel_is_kazalari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_personel_kisisel_bilgi`
--
ALTER TABLE `silinen_personel_kisisel_bilgi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=300;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_personel_saglik_tetkikleri`
--
ALTER TABLE `silinen_personel_saglik_tetkikleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_personel_sirket_bilgi`
--
ALTER TABLE `silinen_personel_sirket_bilgi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_personel_uyarilar`
--
ALTER TABLE `silinen_personel_uyarilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Tablo için AUTO_INCREMENT değeri `silinen_raporlar`
--
ALTER TABLE `silinen_raporlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `aracli_kazalar`
--
ALTER TABLE `aracli_kazalar`
  ADD CONSTRAINT `aracli_kazalar_ibfk_1` FOREIGN KEY (`arac_id`) REFERENCES `arac_bilgi` (`arac_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `aracli_kazalar_ibfk_2` FOREIGN KEY (`tc_kimlik`) REFERENCES `personel_kisisel_bilgi` (`tc_kimlik`) ON UPDATE CASCADE,
  ADD CONSTRAINT `aracli_kazalar_ibfk_3` FOREIGN KEY (`is_kazasi_tip_id`) REFERENCES `hazir_is_kazalari` (`is_kazasi_tip_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `aracli_kazalar_ibfk_4` FOREIGN KEY (`yaralanma_durumu_id`) REFERENCES `hazir_yaralanma_durumlar` (`yaralanma_durum_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `aracli_kazalar_ibfk_5` FOREIGN KEY (`yaralanma_tip_id`) REFERENCES `hazir_yaralanma_tipler` (`yaralanma_tip_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `aracli_kazalar_ibfk_6` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `arac_bilgi`
--
ALTER TABLE `arac_bilgi`
  ADD CONSTRAINT `arac_bilgi_ibfk_1` FOREIGN KEY (`arac_tipi_id`) REFERENCES `hazir_arac_tipleri` (`arac_tipi_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `arac_bilgi_ibfk_2` FOREIGN KEY (`marka_id`) REFERENCES `hazir_markalar` (`marka_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `arac_bilgi_ibfk_3` FOREIGN KEY (`model_id`) REFERENCES `hazir_modeller` (`model_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `arac_bilgi_ibfk_4` FOREIGN KEY (`firma_id`) REFERENCES `hazir_firmalar` (`firma_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `arac_bilgi_ibfk_5` FOREIGN KEY (`arac_durum_id`) REFERENCES `hazir_arac_durumlari` (`arac_durum_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `arac_bilgi_ibfk_6` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `arac_muayene`
--
ALTER TABLE `arac_muayene`
  ADD CONSTRAINT `arac_muayene_ibfk_1` FOREIGN KEY (`arac_id`) REFERENCES `arac_bilgi` (`arac_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `arac_muayene_ibfk_2` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `arac_operator_atama`
--
ALTER TABLE `arac_operator_atama`
  ADD CONSTRAINT `arac_operator_atama_ibfk_1` FOREIGN KEY (`arac_id`) REFERENCES `arac_bilgi` (`arac_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `arac_operator_atama_ibfk_2` FOREIGN KEY (`tc_kimlik`) REFERENCES `personel_kisisel_bilgi` (`tc_kimlik`) ON UPDATE CASCADE,
  ADD CONSTRAINT `arac_operator_atama_ibfk_3` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `duyurular`
--
ALTER TABLE `duyurular`
  ADD CONSTRAINT `duyurular_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`);

--
-- Tablo kısıtlamaları `hazir_arac_durumlari`
--
ALTER TABLE `hazir_arac_durumlari`
  ADD CONSTRAINT `hazir_arac_durumlari_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `hazir_arac_tipleri`
--
ALTER TABLE `hazir_arac_tipleri`
  ADD CONSTRAINT `hazir_arac_tipleri_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`);

--
-- Tablo kısıtlamaları `hazir_belgeler`
--
ALTER TABLE `hazir_belgeler`
  ADD CONSTRAINT `fk_hazir_belgeler_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `hazir_ehliyetler`
--
ALTER TABLE `hazir_ehliyetler`
  ADD CONSTRAINT `fk_hazir_ehliyetler_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `hazir_firmalar`
--
ALTER TABLE `hazir_firmalar`
  ADD CONSTRAINT `fk_hazir_firmalar_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `hazir_is_kazalari`
--
ALTER TABLE `hazir_is_kazalari`
  ADD CONSTRAINT `fk_hazir_is_kazalari_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `hazir_markalar`
--
ALTER TABLE `hazir_markalar`
  ADD CONSTRAINT `hazir_markalar_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`);

--
-- Tablo kısıtlamaları `hazir_meslekler`
--
ALTER TABLE `hazir_meslekler`
  ADD CONSTRAINT `fk_hazir_meslekler_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `hazir_modeller`
--
ALTER TABLE `hazir_modeller`
  ADD CONSTRAINT `hazir_modeller_ibfk_1` FOREIGN KEY (`marka_id`) REFERENCES `hazir_markalar` (`marka_id`),
  ADD CONSTRAINT `hazir_modeller_ibfk_2` FOREIGN KEY (`arac_tipi_id`) REFERENCES `hazir_arac_tipleri` (`arac_tipi_id`),
  ADD CONSTRAINT `hazir_modeller_ibfk_3` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`);

--
-- Tablo kısıtlamaları `hazir_rapor_durumlari`
--
ALTER TABLE `hazir_rapor_durumlari`
  ADD CONSTRAINT `hazir_rapor_durumlari_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`);

--
-- Tablo kısıtlamaları `hazir_uyarilar`
--
ALTER TABLE `hazir_uyarilar`
  ADD CONSTRAINT `fk_hazir_uyarilar_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `hazir_yaralanma_durumlar`
--
ALTER TABLE `hazir_yaralanma_durumlar`
  ADD CONSTRAINT `fk_hazir_yaralanma_durumlar_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `hazir_yaralanma_tipler`
--
ALTER TABLE `hazir_yaralanma_tipler`
  ADD CONSTRAINT `fk_hazir_yaralanma_tipler_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD CONSTRAINT `kullanicilar_ibfk_1` FOREIGN KEY (`olusturan_kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `personel_belgeler`
--
ALTER TABLE `personel_belgeler`
  ADD CONSTRAINT `fk_personel_belgeler_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `personel_belgeler_ibfk_1` FOREIGN KEY (`tc_kimlik`) REFERENCES `personel_kisisel_bilgi` (`tc_kimlik`),
  ADD CONSTRAINT `personel_belgeler_ibfk_2` FOREIGN KEY (`belge_id`) REFERENCES `hazir_belgeler` (`belge_id`);

--
-- Tablo kısıtlamaları `personel_ehliyetler`
--
ALTER TABLE `personel_ehliyetler`
  ADD CONSTRAINT `fk_personel_ehliyetler_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `personel_ehliyetler_ibfk_1` FOREIGN KEY (`tc_kimlik`) REFERENCES `personel_kisisel_bilgi` (`tc_kimlik`),
  ADD CONSTRAINT `personel_ehliyetler_ibfk_2` FOREIGN KEY (`ehliyet_id`) REFERENCES `hazir_ehliyetler` (`ehliyet_id`);

--
-- Tablo kısıtlamaları `personel_gerekli_belge`
--
ALTER TABLE `personel_gerekli_belge`
  ADD CONSTRAINT `fk_personel_gerekli_belge_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `personel_gerekli_belge_ibfk_1` FOREIGN KEY (`tc_kimlik`) REFERENCES `personel_kisisel_bilgi` (`tc_kimlik`);

--
-- Tablo kısıtlamaları `personel_is_kazalari`
--
ALTER TABLE `personel_is_kazalari`
  ADD CONSTRAINT `fk_personel_is_kazalari_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `personel_is_kazalari_ibfk_1` FOREIGN KEY (`tc_kimlik`) REFERENCES `personel_kisisel_bilgi` (`tc_kimlik`),
  ADD CONSTRAINT `personel_is_kazalari_ibfk_2` FOREIGN KEY (`is_kazasi_tip_id`) REFERENCES `hazir_is_kazalari` (`is_kazasi_tip_id`),
  ADD CONSTRAINT `personel_is_kazalari_ibfk_3` FOREIGN KEY (`yaralanma_durumu_id`) REFERENCES `hazir_yaralanma_durumlar` (`yaralanma_durum_id`),
  ADD CONSTRAINT `personel_is_kazalari_ibfk_4` FOREIGN KEY (`yaralanma_tip_id`) REFERENCES `hazir_yaralanma_tipler` (`yaralanma_tip_id`);

--
-- Tablo kısıtlamaları `personel_kisisel_bilgi`
--
ALTER TABLE `personel_kisisel_bilgi`
  ADD CONSTRAINT `fk_personel_kisisel_bilgi_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `personel_saglik_tetkikleri`
--
ALTER TABLE `personel_saglik_tetkikleri`
  ADD CONSTRAINT `fk_personel_saglik_tetkikleri_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `personel_saglik_tetkikleri_ibfk_1` FOREIGN KEY (`tc_kimlik`) REFERENCES `personel_kisisel_bilgi` (`tc_kimlik`);

--
-- Tablo kısıtlamaları `personel_sirket_bilgi`
--
ALTER TABLE `personel_sirket_bilgi`
  ADD CONSTRAINT `fk_personel_sirket_bilgi_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `personel_sirket_bilgi_ibfk_1` FOREIGN KEY (`tc_kimlik`) REFERENCES `personel_kisisel_bilgi` (`tc_kimlik`),
  ADD CONSTRAINT `personel_sirket_bilgi_ibfk_2` FOREIGN KEY (`firma_id`) REFERENCES `hazir_firmalar` (`firma_id`),
  ADD CONSTRAINT `personel_sirket_bilgi_ibfk_3` FOREIGN KEY (`meslek_id`) REFERENCES `hazir_meslekler` (`meslek_id`);

--
-- Tablo kısıtlamaları `personel_uyarilar`
--
ALTER TABLE `personel_uyarilar`
  ADD CONSTRAINT `fk_personel_uyarilar_kullanici_id` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `personel_uyarilar_ibfk_1` FOREIGN KEY (`tc_kimlik`) REFERENCES `personel_kisisel_bilgi` (`tc_kimlik`),
  ADD CONSTRAINT `personel_uyarilar_ibfk_2` FOREIGN KEY (`uyari_tipi_id`) REFERENCES `hazir_uyarilar` (`uyari_tip_id`);

--
-- Tablo kısıtlamaları `raporlar`
--
ALTER TABLE `raporlar`
  ADD CONSTRAINT `raporlar_ibfk_1` FOREIGN KEY (`rapor_durumu`) REFERENCES `hazir_rapor_durumlari` (`id`),
  ADD CONSTRAINT `raporlar_ibfk_2` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kul_id`);

DELIMITER $$
--
-- Olaylar
--
CREATE DEFINER=`root`@`localhost` EVENT `saglik_tetkik_gecerlilik_kontrol` ON SCHEDULE EVERY 1 DAY STARTS '2025-04-21 00:01:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE personel_saglik_tetkikleri
    SET saglik_tetkikleri_oldu_mu = FALSE
    WHERE saglik_tetkikleri_oldu_mu = TRUE
    AND gecerlilik_tarihi < CURDATE();
END$$

CREATE DEFINER=`root`@`localhost` EVENT `deneyim_guncelleme_event` ON SCHEDULE EVERY 1 DAY STARTS '2025-04-21 00:01:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE personel_sirket_bilgi
    SET toplam_deneyim_yili = ( YEAR(CURDATE()) - YEAR(ise_giris_tarihi) )
    WHERE ise_giris_tarihi IS NOT NULL$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
