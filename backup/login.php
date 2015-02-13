<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');
$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/login.tpl');

$backurl = '';
$backurl = $_GET['from'];
$_SESSION['net_users']['url'] = $_GET['from'];

if($btn=="Send") {
   $db=new dbMysql();
   $num=$db->num_rows("select id,email,name,status from user where email='".$email."' and pass='".md5($pass)."'");
   if($num>0) {
       $user=$db->get_row("select id,email,name,status from user where email='".$email."' and pass='".md5($pass)."'");
      if($user->status==0){
         header('Location: activate.php');
      }else {
		$_SESSION['net_users']['id']=$user->id;
		$_SESSION['net_users']['name']=$user->name;
		$_SESSION['net_users']['email']=$user->email;
 		if($backurl == '') header("Location: account_management.php");
		else header("Location: ".$backurl);
      }
   }else {
      $main->assign("mesaj", "Kullnıcı adı veya şifrenizi hatalı girdiniz. Kontrol ederek yeniden deneyiniz!");
      $main->parse("main.mesaj");
   }
}

$main->assign("300x250", banner300X250());
$main->assign("back_url", $_GET['from']);
$main->parse("main");
$index->assign("MAIN", $main->text("main"));

$search->parse("main");
$index->assign("domain_search", $search->text("main"));

$footer->parse("main");
$index->assign("FOOTER", $footer->text("main"));

$index->assign("HEADER", header_q());

$index->parse('main');
$index->out('main');
?>
