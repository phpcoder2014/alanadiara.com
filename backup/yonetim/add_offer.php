<?php 
session_start();
if($_SESSION['netadmin'] !=true) {
    header("location:login.php");
}
include_once('../inc/dbMysql.php');
include_once('../inc/func.php');

$domainID = $_POST['domainID'];
$price = $_POST['price'];
$ps = $_POST['user_ps'];
$offerid = $_POST['offer'];
$db = new dbMysql();

$query = "SELECT price_min, id from domain where id=".$domainID;
$row = $db->get_row($query);

if($row->price_min < $price){
	$db->insert("reoffers",array("offer_id"=>$offerid, "user_id"=>$_SESSION['netadminid'], "domain_id"=>$domainID, "ps"=>$ps, "total_cost"=>$price, "time"=>time(), "ipno"=>$_SERVER['REMOTE_ADDR']));
	$db->updateSql("update offers set status=3 where id=".$offerid."");
	echo '<script>alert("Teklifiniz alındı mesajı.")</script>';
} else {
	echo '<script>alert("Bu alanadı için en az teklif miktarı: '.$row->price_min.' TL dır.")</script>';
}

?>