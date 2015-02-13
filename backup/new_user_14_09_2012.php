<?php
session_start();
/* Create by ErdenGENCER  15.02.2010 Pazartesi */
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');
$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/new_user.tpl');

/**/
$main->assign("siteDomName", $siteDomName);
$main->assign("siteDomEmail", $siteDomEmail);

$db=new dbMysql();
if($btn=="Send"){
    include("securimage/securimage.php");
  $img = new Securimage();
   if(!filled_out($_POST)){
      $main->assign("mesaj", "Lütfen formu eksiksiz doldurun.");
      $main->parse("main.new_user.mesaj");
      $main->assign("n_name", $name);
      $main->assign("n_email", $email);
	  $main->assign("n_phone", $phone);
      $main->assign("n_soru", $soru);
      $main->assign("n_cevap", $cevap);
      $main->parse("main.new_user");
   }else if(!$img->check($_POST['code'])){
      $main->assign("mesaj", "Güvenlik no hatalı.");
      $main->parse("main.new_user.mesaj");
      $main->assign("n_name", $name);
      $main->assign("n_email", $email);
	  $main->assign("n_phone", $phone);
      $main->assign("n_soru", $soru);
      $main->assign("n_cevap", $cevap);
      $main->parse("main.new_user.securecode");
      $main->parse("main.new_user");
   }else {
      //$name=$_POST['name'];
      $num=$db->num_rows("select id from user where email='".$email."'");
      if($num>0 and $email<>'kuzgunn@w.cn') {
          $main->assign("mesaj", "Bu email adresi sistemde kayıtlı.");
          $main->parse("main.new_user.mesaj");
          $main->parse("main.new_user.securecode");
          $main->parse("main.new_user");
      }else {
          $code=createReqCode();
          $user_id=$db->insert("user", array("name"=>$name,"email"=>$email,"phone"=>$phone,"pass"=>md5($pass),"soru"=>$soru,"cevap"=>$cevap,"status"=>0));
          $req_table=$db->insert("user_req", array("id"=>$code,"user_id"=>$user_id,"status"=>"0","time"=>time()));
          userSendReqCode(array("name"=>$name,"email"=>$email,"code"=>$code));
          $main->assign("username", $name);
          $main->parse("main.saved");
      }
   }
}else {
   $main->parse("main.new_user.securecode");
   $main->parse("main.new_user");
}



//$main->parse("main.saved");

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
