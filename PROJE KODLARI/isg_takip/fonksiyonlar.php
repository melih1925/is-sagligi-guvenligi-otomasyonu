<?php
//header.php de kullanıldı.
function oturum()
{
	if (!isset($_SESSION['kul_id']) OR !isset($_SESSION['kul_isim']) OR !isset($_SESSION['kul_mail']) ) {
		header("location:giris.php?oturumac");
		exit;
	} else {
		return true;
	}
}


function tek($sql)
{
	global $db;

	$sorgu=$db->prepare($sql);
	$sorgu->execute();
	$liste=$sorgu->fetch(PDO::FETCH_ASSOC);

	return $liste;
}

?>
