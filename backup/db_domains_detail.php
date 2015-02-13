<?php
header("Content-Type: text/html; charset=utf-8");
include_once('inc/dbMysql.php');
include_once('inc/func.php');
require_once("inc/nusoap.php");

function objectToArray($d) {
	if (is_object($d)) {
		$d = get_object_vars($d);
	}
	if (is_array($d)) {
		/*
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	} else {
		return $d;
	}
}

$db=new dbMysql();
$sql = "select id from domain where info is NULL limit 10";
$count_rows = $db->num_rows($sql);
if($count_rows > 0){
	$rows = $db->get_rows($sql);
	$domains = objectToArray($rows);
	foreach($domains as $key=>$value){
		$client = new nusoap_client('http://89.106.14.247/turkticaret_test.php?wsdl');
		$data = $client->call('AlanAdiAraSearch', array(
			'username' => 'alanadiara',			// Username
			'password' => 'iYop87Ygs',			// Password
			"type" => 2,
			"domain_name_data" => $value['name'],
			"act_time" => 0						// 
			));
		if($data["status"] == 1) {
			echo $data["data"];
/*
			$details = json_decode($data["data"]);
			$detail = objectToArray($details);
			foreach($detail as $keys=>$values){
				$db->updateSql("update domain set info='".$detail[$keys][keyword]."', monthly_search='".$detail[$keys][monthly_search]."' where name='".$value['name']."'");
			}*/
		} else {
			echo "Error :" . $data["data"];
		}
	}
} else {
	echo "Error : Empty";
}
?>