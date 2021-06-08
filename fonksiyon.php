<?php 
try {
	$db=new PDO("mysql:host=localhost;dbname=chat;chatset=utf8",'root','');
	//echo "bağlantı başarılı";
} catch (Exception $e) {
	echo $e->getMessage();
}


/**
 * 
 */
class chat 
{
	public $arkaplan;
	public $yazirenk;
	
	function kisi_getir($db)
		{
			$uye=$db->prepare("SELECT * FROM kisiler");
			$uye->execute();
			while ($uyeyaz=$uye->fetch(PDO::FETCH_ASSOC)) {
				if ($uyeyaz["durum"]==1) {
					echo '<span class="text-success">'.$uyeyaz["ad"].'-online </span><br>';
				}
				else{
					echo '<span class="text-danger">'.$uyeyaz["ad"].'- offline </span><br>';	
				}
			}
		}

	function giris_kontrol($db,$kullanici_adi,$kullanici_parola){
		$sor=$db->prepare("SELECT * FROM kisiler where ad='$kullanici_adi' and sifre='$kullanici_parola'");
		$sor->execute(array());
		$sonuc=$sor->fetch(PDO::FETCH_ASSOC);
		if ($sor->rowCount()==0) {
			echo '<div class="alert alert-danger">bilgiler hatalı</div>';
			header("Refresh:3 , url=index.php");
		}
		else
		{
			$durumguncelle=$db->prepare("UPDATE kisiler set durum=1 where ad='$kullanici_adi'");
			$durumguncelle->execute();
			echo '<div class="alert alert-success">giriş yapılıyor</div>';
			header("Refresh:3 , url=chat.php");
			setcookie("kisiad",$kullanici_adi,time()+60*60);
		}
	}

	function oturum_kontrol($db,$durum=false){
		if (isset($_COOKIE['kisiad'])) {
			$kisiad=$_COOKIE['kisiad'];
			$sor=$db->prepare("SELECT * FROM kisiler where ad='$kisiad'");
			$sor->execute();
			$veri=$sor->fetch(PDO::FETCH_ASSOC);
			if ($sor->rowCount()==0) {
				header("Location:index.php");
			}
			else
			{
				if ($durum==true) {
					header("Location:chat.php");	
				}
				
			}
		}
		else
		{
			if ($durum==false) {
				header("Location:index.php");	
			}
			 
		}
	}

	function renklerebak($db){
		$kisiad=$_COOKIE['kisiad'];
		$sor=$db->prepare("SELECT * FROM kisiler where ad='$kisiad'");
		$sor->execute();
		$veri=$sor->fetch(PDO::FETCH_ASSOC);
		$this->arkaplan=$veri['arkarenk'];
		$this->yazirenk=$veri['yazirenk'];
	}


}

@$chat=$_GET['chat'];
switch ($chat) { 
	case 'oku':
		$dosya=fopen("konusmalar.txt", "r");
		while (!feof($dosya)) {
			$satir=fgets($dosya);
			print($satir);
		}
		fclose($dosya);
		break;
	case 'ekle':
		$kisiad=$_COOKIE['kisiad'];
		$kisisor=$db->prepare("SELECT * FROM kisiler where ad='$kisiad'");
		$kisisor->execute();
		$sonuc=$kisisor->fetch(PDO::FETCH_ASSOC);
		$mesaj=htmlspecialchars(strip_tags($_POST['mesaj']));
		fwrite(fopen("konusmalar.txt","a"),'<span class="pb-5" style="color:#'.$sonuc["yazirenk"].'"><kbd style="background-color:#'.$sonuc["arkarenk"].'">'.$kisiad.'</kbd>'.$mesaj.'</span><br>');
		break;
	case 'cikis':
		$kisiad=$_COOKIE['kisiad'];
		$cikis=$db->prepare("UPDATE kisiler set durum=0 where ad='$kisiad'");
		$cikis->execute();
		setcookie("kisiad",$kullanici_adi,time()-1);
		header("Location:index.php");
		break;
	case 'sohbetayar':
		if (isset($_POST['secenek'])) {
			$istek=$_POST['secenek'];
			if ($istek=='temizle') {
				unlink("konusmalar.txt");
				touch("konusmalar.txt");
				echo '<div class="alert alert-success mt-3">sohbet temizlendi</div>';
			}elseif ($istek=='kaydet') {
				copy("konusmalar.txt", "kaydedilenler/".date("d.m.Y")."-konusma.txt");
				echo '<div class="alert alert-success mt-3">sohbet kaydedildi</div>';
			}
		}

		break;
	case 'arkarenk':
		if (isset($_POST['arkaplankod'])) {
			$gelenrenk=$_POST['arkaplankod'];
			$kisiad=$_COOKIE['kisiad'];
			$sor=$db->prepare("UPDATE kisiler set arkarenk='$gelenrenk' where ad='$kisiad'");
			$sor->execute();
			echo '<div class="alert alert-success mt-3">arka plan renk değiştirildi</div>';
		}
		break;
	case 'yazirenk':
		if (isset($_POST['yazirenkkod'])) {
			$gelenrenk=$_POST['yazirenkkod'];
			$kisiad=$_COOKIE['kisiad'];
			$sor=$db->prepare("UPDATE kisiler set yazirenk='$gelenrenk' where ad='$kisiad'");
			$sor->execute();
			echo '<div class="alert alert-success mt-3">yazı rengi değiştirildi</div>';
		}
		break;
	case 'ortak':
		if ($_GET['uyead']!='') {
			fwrite(fopen("kisiler.txt","a"),'<span class="pb-5">'.$_GET['uyead'].' Yazıyor...</span><br>');
		}
		else if ($_GET['temizle']!='') {
			$dosya="kisiler.txt";
 
			$ac=fopen($dosya,"r");
			$oku=fread($ac,filesize($dosya));
			
			$str=str_replace('<span class="pb-5">'.$_GET["temizle"].' Yazıyor...</span><br>',"",$oku);
			
			$yaz="kisiler.txt";
			$yazd=fopen($yaz,"w");
			fwrite($yazd,$str);
			fclose($yazd);
			
		}
		break;
	case 'dosyaoku':
		$dosya=fopen("kisiler.txt", "r");
		while (!feof($dosya)) {
			$satir=fgets($dosya);
			print($satir);
		}
		fclose($dosya);
		break;
	default:
		# code...
		break;
}


 ?>