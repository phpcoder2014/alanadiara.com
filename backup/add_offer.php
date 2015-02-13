<?php 
session_start();
include_once('inc/dbMysql.php');

include_once('inc/func.php');


$domainID = $_POST['domainID'];
$price = $_POST['price'];
$ps = $_POST['user_ps'];
$db = new dbMysql();

$query = "SELECT price_min,name,price from domain where id=".$domainID;
//$price = $row-> price_min;
$row = $db->get_row($query);

if($row->price_min < $price){
	$id =  $db->insert(
		"offers",
		array(
			"customer_id"=>$_SESSION['net_users']['id'], 
			"domain_id"=>$domainID, 
			"ps"=>$ps, 
			"total_cost"=>$price, 
			"time"=>time(), 
			"ipno"=>$_SERVER['REMOTE_ADDR']
			)
	);
	$to      = 'aker.canguzel@turkticaret.net;handan.turker@turkticaret.net;myaniklar@turkticaret.net;agokhan@turkticaret.net';
	$subject = 'Alanadı Teklif';
	$message = 'Alanadı: '.$row->name.' - Link: http://www.alanadiara.com/'.$row->name.'.htm'."\r\n".'Yapılan Teklif: '.$price.' $'."\r\n".
		'Not: '.$ps."\r\n".'Min. Teklif: '.$row->price_min.' $'."\r\n".'Yönetim Paneli: http://www.alanadiara.com/yonetim/';
	$headers = 'From: info@alanadiara.com' . "\r\n" .
	    'Reply-To: info@alanadiara.com';
	mail($to, $subject, $message, $headers);
	echo "1";
} else {
	echo ''.$row->price_min;
}

?>