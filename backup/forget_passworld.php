<?php
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');

$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/forget_passworld.tpl');

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
      $main->parse("main.new_user");
   }else if(!$img->check($_POST['code'])){
      $main->assign("mesaj", "Güvenlik no hatalı.");
      $main->parse("main.new_user.mesaj");
      $main->parse("main.new_user.securecode");
      $main->parse("main.new_user");
   }else {
      $num=$db->num_rows("select * from user where email='".$email."'");
      if($num>0) {
          $res=$db->get_row("select * from user where email='".$email."'");

          $code=createReqCode();
          //$name=$_POST['name'];
          $newpasworld=rand(100000, 200000);

          $user_id=$db->updateSql("update user set status='0',pass='".md5($newpasworld)."' where id='".$res->id."'");
          $req_table=$db->insert("user_req", array("id"=>$code,"user_id"=>$res->id,"status"=>"0","time"=>time()));
          userSendForgetCode(array("name"=>$res->name,"email"=>$email,"code"=>$code,"pass"=>$newpasworld));
          $main->assign("username", $name);
          $main->parse("main.saved");
      }else {
          $main->assign("mesaj", "Sistemde kayıtlı böyle bir E-mail bulunamadı..");
          $main->parse("main.new_user.mesaj");
          $main->parse("main.new_user.securecode");
          $main->parse("main.new_user");
      }

  }


}else {
   $main->parse("main.new_user.securecode");
   $main->parse("main.new_user");
}
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
