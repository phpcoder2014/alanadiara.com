<?php
session_start();


include_once('../inc/dbMysql.php');
include_once('../inc/func.php');

$db=new dbMysql();
$param=array('name'=>'erden GEN','email'=>'erden.gencer@turkticaret.net');
$param['domain']="erden.info";
$param['userID']="10";
$param['domID']="10";
//if(!gkupon($param))echo "HATA";else echo "OK";
$sqlg="SELECT id FROM gkpn WHERE used='0' limit 1";
$res=$db->get_row($sqlg);
echo $res->id."^++++";
?>
