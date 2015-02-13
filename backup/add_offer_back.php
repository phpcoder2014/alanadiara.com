<?php 
session_start();

include_once('./inc/dbMysql.php');
include_once('./inc/func.php');

$domainID = $_REQUEST['domainid'];
//$price = $_REQUEST['price'];
$ps = $_REQUEST['user_ps'];
$offerid = $_REQUEST['offerid'];

$db = new dbMysql();

$query = "SELECT price_min, price, id from domain where id=".$domainID;
$price = $row-> price_min;
$row = $db->get_row($query);

if($row->price_min < $price){
	$db->insert("offers",array("customer_id"=>$_SESSION['net_users']['id'], "domain_id"=>$domainID, "ps"=>$ps, "total_cost"=>$price, "time"=>time(), "ipno"=>$_SERVER['REMOTE_ADDR']));
	$db->updateSql("update offers set status=-1 where id=".$offerid);
	$db->updateSql("update reoffers set status=1 where id=".$offerid);
	echo 'Teklifiniz alındı mesajı.';
} else {
	echo 'Bu alanadı için en az teklif miktarı: '.$row->price_min.' $';
}

?>