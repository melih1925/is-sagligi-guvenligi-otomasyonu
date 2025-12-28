<!--
panel ismi  ==>sb admin 2
    BUTUN SAYAFALARDA OLMASINI ISTEDIGIMIZ HEADER BOLUMU 
-->

<?php
require_once'config.php'; 
oturum();
//fonksiyonlar.php adlı dosyada oluşturulan oturum() adlı fonksiyonun, burada sorunsuz çalışma sebebi config.php adlı dosyaya 'require_once' ile fonksiyonlar.php nin dahil edilmesidir.
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>İSG Yönetim Uygulaması</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet"> 
    
    <link rel="stylesheet" type="text/css" href="vendor/datatables/datatables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">ISG<sup>35</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home"></i>
                    <span>Anasayfa</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">





            <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#musteri_menu"
                    aria-expanded="true" aria-controls="musteri_menu">
                    <i class="fas fa-users"></i>
                    <span>Personeller</span>
                </a>
                <div id="musteri_menu" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Personel İşlemleri:</h6>
                    <a class="collapse-item" href="personel_ekle.php">Personel Ekle</a>
                    <a class="collapse-item" href="personel_listele.php">Personelleri Listele</a>
                    <a class="collapse-item" href="personel_belge_ekle.php">Personele Belge Ekle</a>
                    <a class="collapse-item" href="personel_uyari_ekle.php">Personele Uyarı Ekle</a>
                    <a class="collapse-item" href="personel_is_kazasi_ekle.php">Personele İş Kazası Ekle</a>
                    <a class="collapse-item" href="personel_yukle_excel.php">Excel ile Personel Yükle</a>
                    <a class="collapse-item" href="uyari_sinir.php">Uyarı Sınırı</a>
                    <a class="collapse-item" href="personel_saglik.php">Sağlık Muayenesi</a>
<!--                <a class="collapse-item" href="personel_saglik_yaklasan.php">Sağlık Muayenesi Yak.</a>
                    <a class="collapse-item" href="personel_saglik_gecen.php">Sağlık Muayenesi Geç.</a>
-->
                </div>
            </div>
        </li>






        
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#araclar_menu"
            aria-expanded="true" aria-controls="randevu_menu">
            <i class="fas fa-calendar-alt"></i>
            <span>Araçlar</span>
        </a>
        <div id="araclar_menu" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Randevu İşlemleri:</h6>
            <a class="collapse-item" href="arac_ekle.php">Araç Ekle</a>
            <a class="collapse-item" href="arac_listele.php">Araç Listele</a>
            <a class="collapse-item" href="arac_operator_atama.php">Araç-Şoför Atama</a>
            <a class="collapse-item" href="aracli_kazalar_ekle.php">Araç Kazası Ekle</a>
            <a class="collapse-item" href="arac_muayene.php">Araç Muayene</a>
        </div>
    </div>
</li>

<li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#raporlama_menu"
            aria-expanded="true" aria-controls="randevu_menu">
            <i class="fas fa-calendar-alt"></i>
            <span>Raporlama</span>
        </a>
        <div id="raporlama_menu" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Rapor İşlemleri:</h6>
            <a class="collapse-item" href="duyuru_ekle.php">Duyuru Ekle</a>
            <a class="collapse-item" href="rapor_ekle.php">Rapor Ekle</a>
            <a class="collapse-item" href="uyarilar_analizi.php">Uyarı Analizleri</a>
            <a class="collapse-item" href="iskazalari_analizi.php">İş Kazaları Analizleri</a>
            <a class="collapse-item" href="aracli_kazalar_analizi.php">Araç Kazaları Analizleri</a>
            <a class="collapse-item" href="silinen_veriler.php">Silinen Veriler</a>
        </div>
    </div>
</li>



<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#profil_menu"
            aria-expanded="true" aria-controls="randevu_menu">
            <i class="fas fa-calendar-alt"></i>
            <span>Profil</span>
    </a>

    <div id="profil_menu" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Profil İşlemleri:</h6>
            <a class="collapse-item" href="profil.php">Profilim</a>
            <a class="collapse-item" href="kullanici_ekle.php">Kullanıcı Ekle</a>
        </div>
</li>






            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

</ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
 

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['kul_isim'] ?></span>
                                <img class="img-profile rounded-circle"
                                src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profil.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Çıkış Yap
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>

                <!-- End of Topbar -->