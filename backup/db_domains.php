<?php
header("Content-Type: text/html; charset=utf-8");
include_once('/usr/local/www/alanadiara/htdocs/inc/dbMysql.php');
include_once('/usr/local/www/alanadiara/htdocs/inc/func.php');
require_once("/usr/local/www/alanadiara/htdocs/inc/class.nusoap.php");

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

$client = new nusoap_client('http://89.106.14.247/turkticaret_test.php?wsdl');

$data = $client->call('AlanAdiAraSearch', array(
	'username' => 'alanadiara',			// Username
	'password' => 'iYop87Ygs',			// Password
	"type" => 1,
	"domain_name_data" => "",
	"act_time" => time() - ((24*8) * 60 * 60)	// 1 gun oncesi
	));

if($data["status"] == 1) {
	$domains_std = json_decode($data["data"]);
} else {
	echo "Error :" . $data["data"];
}
$domains = objectToArray($domains_std);
if(count($domains) > 0){
	$db=new dbMysql();
	foreach($domains as $key=>$value){
		$db=new dbMysql();
		$sql = "select id from domain where name='".$key."'";
		if($db->num_rows($sql) > 0) $kayitli = true;
		else $kayitli = false;
		if(!$kayitli) $db->insert("domain", array("name"=>$key, "enddate"=>$value['enddate']));
	}
}
/*
$data = $client->call('AlanAdiAraSearch', array(
	'username' => 'alanadiara',			// Username
	'password' => 'iYop87Ygs',			// Password
	"type" => 2,
	"domain_name_data" => "finansalhizmetler.net;sogukmezeler.net;fincantakimlari.net;aluminyumdiscephe.net;ucuzsporayakkabilar.net",
	"act_time" => 0						// 
	));

if($data["status"] == 1) print_r(json_decode($data["data"]));
else echo "Error :" . $data["data"];
*/
?>