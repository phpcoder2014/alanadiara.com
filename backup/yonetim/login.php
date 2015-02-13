<?php
session_start();
include_once('../inc/xtpl.php');
include_once('../inc/dbMysql.php');
include_once('../inc/func.php');
$index = new XTemplate('temp/login.tpl');
$db=new dbMysql();

if(isset ($_POST['send'])) {
    if(!isset($git))$git="index.php";
    $num=$db->num_rows("select * from netadmin where email='".$_POST['kad']."' and pass='".md5($_POST['pass'])."' and state=1");
	$query = "select id from netadmin where email='".$_POST['kad']."' and pass='".md5($_POST['pass'])."' and state=1";
	$row = $db->get_row($query);
    if($num<=0) {
        $index->assign("mesaj", "Kullanıcı adı veya Şifre hatalı.");
        $index->parse('main.mesaj');
    }else {
        $_SESSION['netadmin']=true;
		$_SESSION['netadminid'] = $row->id;
        header("location:".$git);
    }
}


$index->parse('main');
$index->out('main');
?>
