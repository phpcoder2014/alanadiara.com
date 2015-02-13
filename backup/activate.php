<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');
$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/activate.tpl');
 $db=new dbMysql();
if($code !="" && strlen($code)==32) {
  
   $rows=$db->get_row("select * from user_req where id='".$code."'");
   if($rows->id !="" && $rows->status==0) {
      //echo $rows->status." ER";
      $db->updateSql("update user_req set status=1,act_time='".time()."' where id='".$code."'");
      $db->updateSql("update user set status=1 where id='".$rows->user_id."'");
      header('Location: login.php');
      $main->parse("main.success");
   }else {
       $rows=$db->get_row("select * from user_req where id='".$code."'");
   $strlt=$db->get_row("select status from user where id='".$rows->user_id."'");
   if($strlt->status==1) {
       header('Location: login.php');
   }
      $main->parse("main.code");
   }
}else {
   
    if($code=="") {
        $code=0;
    }
   $rows=$db->get_row("select * from user_req where id='".$code."'");
   $strlt=$db->get_row("select status from user where id='".$rows->user_id."'");
   if($strlt->status==1) {
       header('Location: login.php');
   }
   $main->parse("main.code");
}





$main->parse("main");
$index->assign("MAIN", $main->text("main"));

$search->parse("main");
$index->assign("domain_search", $search->text("main"));

$footer->parse("main");
$index->assign("FOOTER", $footer->text("main"));
$index->assign("HEADER",header_q());

$index->parse('main');
$index->out('main');
?>
