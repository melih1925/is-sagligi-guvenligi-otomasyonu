<!--
    VERITABANINDAN ayarlar tablosundan BILGI CEKTIGIMIZ BOLUM
-->


<?php 

@ob_start();
@session_start();

$host="localhost";
$veritabani_ismi="isg_takip"; //Veritabanı ismi
$kullanici_adi="root";//kullanıcı ismi
$sifre="";//kullanıcı şifresi = 1 

//Hem phpMyAdmin’in config.inc.php dosyasında doğru şifreyi ayarlaman gerekir.

//C:\xampp\phpMyAdmin\config.inc.php --->Şifreyi görmek ve değiştirmek için


try{
	$db = new PDO("mysql:host=$host;dbname=$veritabani_ismi;charset=utf8",$kullanici_adi,$sifre);
} catch(PDOException $e){
	echo "Veritabanı Bağlantı İşlemi Başarısız Oldu";
	echo $e->getMessage();
	exit;//Bu komutun yazılması ile altında bulunan kodlar çalışmaz.
}



require_once __DIR__.'/fonksiyonlar.php';


?>

<!--Hem de MySQL tarafında (komut satırında) şifreyi güncellemen-->
<!--
Chat GPT ile  şireyi değiştirme (xampp > shell)

Setting environment for using XAMPP for Windows.
melih@OLMEZLAPTOP c:\xampp
# mysql -u root -p
Enter password: *
ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: YES)

melih@OLMEZLAPTOP c:\xampp
# mysql -u root
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 9
Server version: 10.4.32-MariaDB mariadb.org binary distribution

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]> ALTER USER 'root'@'localhost' IDENTIFIED BY '1';
Query OK, 0 rows affected (0.035 sec)

MariaDB [(none)]> FLUSH PRIVILEGES;
Query OK, 0 rows affected (0.001 sec)

MariaDB [(none)]> exit
Bye

melih@OLMEZLAPTOP c:\xampp
# mysql -u root -p
Enter password: *
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 10
Server version: 10.4.32-MariaDB mariadb.org binary distribution

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]>
-->