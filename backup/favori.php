<?php
/* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');
$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/favori.tpl');

if($_SESSION['net_users']['id']==""){
   header('Location:login.php?git=search_result.php');
}
$db=new dbMysql();


//$main->parse("main.saved");
$sql="SELECT
fav_domain.id AS favid,
domain.id,
domain.name,
domain.price
FROM
fav_domain
Inner Join domain ON fav_domain.id_dom = domain.id
WHERE
fav_domain.id_user =  '".$_SESSION['net_users']['id']."'";
if($db->num_rows($sql)>0){
    $res=$db->get_rows($sql);
    foreach ($res as $rows) {
        $main->assign("favID", $rows->favid);
        $main->assign("domainID", $rows->id);
      $main->assign("domain_price", $rows->price." $");
      $main->assign("domain_name", $rows->name);
      $totalPrice +=$rows->price;
      $main->parse("main.sepet.rows");
    }
    $main->assign("totalPrice", $totalPrice." $");
   $main->parse("main.sepet.total");
   $main->parse("main.sepet");
}else {
    $main->parse("main.no_records");
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
