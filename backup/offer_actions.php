<?php 
session_start();
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$offerid = $_POST['offerid'];
$action = $_POST['action'];
$db = new dbMysql();

if($action == 'delete'){
	$db->updateSql("update offers set status=-2 where id=".$offerid." and order_id=0");
	if($db) echo 'Teklif iptal edildi.';
	else echo 'Teklif iptal edilemedi.';
}
if($action == 'delete_out'){
	$db->updateSql("update reoffers set status=-1 where id=".$offerid."");
	if($db) echo 'Teklif iptal edildi.';
	else echo 'Teklif iptal edilemedi.';
}
if($action == 'accept'){
	$db->updateSql("update offers set status=1 where id=".$offerid." and order_id=0");
	if($db) echo 'Teklif kabul edildi.';
	else echo 'Teklif kabul edilemedi.';
}
if($action == 'accept_out'){
	$db->updateSql("update reoffers set status=1 where id=".$offerid."");
	if($db) echo 'Teklif kabul edildi.';
	else echo 'Teklif kabul edilemedi.';
}

?>