#!/usr/local/bin/php
<?php
include_once('/usr/local/www/alanadiara/htdocs/inc/dbMysql.php');

$db = new dbMysql();

$data = file_get_contents("http://realtime.paragaranti.com/asp/icpiyasa_visit.htm");
$ma = explode("USD", $data);
if(!isset($ma[1]))
	{
	mail("abdullah.cakmak@turkticaret.net", "AlanAdiAra Currency", "Hata");
	exit;
	}
$maa = explode('62>', $ma[1]);
if(!isset($maa[1]))
	{
	mail("abdullah.cakmak@turkticaret.net", "AlanAdiAra Currency", "Hata");
	exit;
	}

$maaa = explode('</TD>', $maa[1]);
if(!isset($maaa[1]))
	{
	mail("abdullah.cakmak@turkticaret.net", "AlanAdiAra Currency", "Hata");
	exit();
	}
$maaa[0] = str_replace(",", ".", $maaa[0]);

if($maaa[0] > 1.00)
	{
	$db->updateSql("INSERT INTO currency_cron (currency, ctime) VALUES ('" . $maaa[0] . "', '" . mktime() . "')");
	
	mail("abdullah.cakmak@turkticaret.net", "AlanAdiAra Currency", $maaa[0]);
	}
else
	mail("abdullah.cakmak@turkticaret.net", "AlanAdiAra Currency", "Hata");
?>