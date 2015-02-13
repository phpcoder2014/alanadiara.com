<?php
ini_set('display_errors', 1);
include_once("inc/mailtpl/general.php");
include_once("inc/mailtpl/class.phpmailer.php");
$metin="deneme şakir";
$mail = new PHPMailer();
   $mail->Host		= "89.106.12.24"; // SMTP server
	$mail->Port		= 587;
	$mail->Mailer  	= "smtp";
	$mail->SMTPAuth = true;
	$mail->Username = "info@infopazari.info";
	$mail->Password = "TAZm421";

    $mail->From = "info@infopazari.info";
    $mail->FromName = "InfoPazari";
    $mail->AddAddress("erden.gencer@turkticaret.net", "Erden GENCER");
    $mail->AddAddress("erdengencer@hotmail.com", "Erden GENCER");
    $mail->AddBCC("erden.gencer@turkticaret.net", "Başlık");
    $mail->IsHTML(true);     // set email format to HTML
    $mail->Subject = "Başlık";
    $mail->Body    = GenelMailTaslak("InfoPazari.info",$metin);
    $mail->AltBody = "Üyelik aktivasyon";
    if(!$mail->Send()) echo "FF";else echo "OO";
    exit();
$mail			= new PHPMailer();

	$mail->Host		= "mail.co.tv"; // SMTP server
	$mail->Port		= 587;
	$mail->Mailer  	= "smtp";
	$mail->SMTPAuth = true;
	$mail->Username = "info@co.tv";
	$mail->Password = "aWdsaa";

	$mail->CharSet 	= "utf-8";
	$mail->From   	= "info@co.tv";
	$mail->FromName	= "Co.TV";

	$mail->Subject 	= "ererererere";

	$mail->AltBody 	= "To view the message, please use an HTML compatible email viewer!";

	//$mail->MsgHTML("To view the message, please use an HTML compatible email viewer!");
      $mail->AddAddress("erden.gencer@turkticaret.net");

	if(!$mail->Send()) echo "FALSwww";else echo "OKwww";
      exit();
$mail = new PHPMailer();
$mail->IsSMTP();
try {
  $mail->Host= "mail.turkticaret.net"; // SMTP sunucu
  $mail->SMTPDebug = 2; // SMTP için sunucuyu test ediyoruz
  $mail->SMTPAuth= true;
  $mail->Port       = 587; // Mail için port numarasını girmeliyiz.
  $mail->Username   = "erden.gencer@turkticaret.net"; // SMTP kullanıcı adınız
  $mail->Password   = "TyssW128"; // SMTP kullanıcı şifreniz
  $mail->AddAddress('erdengencer@hotmail.com', 'John Doe'); // Mail yollanacak adres ve ismi
  //$mail->SetFrom('erden.gencer@turkticaret.net', 'erden');
  $mail->IsHTML(true);     // set email format to HTML
    $mail->Subject = "ererere";
    $mail->Body    = GenelMailTaslak("InfoPazari.info","erererereere");
    $mail->AltBody = "Üyelik aktivasyon";
  /*
  $mail->MsgHTML(file_get_contents('icerik.html')); //HTML olarak mail yollamak istersek
  $mail->AddAttachment('images/phpmailer.gif');      // Dosya eklemek
  $mail->AddAttachment('images/phpmailer_mini.gif'); // dosya eklemek
   */
  $mail->Send();
  echo "Mesaj gönderildi\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage();
} catch (Exception $e) {
  echo $e->getMessage();
}

?>