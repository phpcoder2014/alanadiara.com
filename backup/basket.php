<?php
/* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');
$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/basket.tpl');

$db=new dbMysql();
print_r($_SESSION['domBasket']);

$main->parse("main.saved");
//echo $_SERVER['HTTP_REFERER'];
//print_r($_SERVER);
$main->assign("alisveris_devam", $_SERVER['HTTP_REFERER']);
if(isset ($_SESSION['domBasket'])){
   $totalPrice=0;
   foreach ($_SESSION['domBasket'] as $val) {
      $rows=$db->get_row("select id, name, price from domain where orderid=0 and id=".$val);
      $main->assign("domainID", $rows->id);
	  $row_offer = $db->get_row("select total_cost from offers where domain_id=".$val." and status=1");
	  $row_reoffer = $db->get_row("select ro.total_cost from reoffers ro, offers o where ro.domain_id=".$val." and ro.status=1 and o.status=3");
	  
	  if(count($row_offer) > 0 && $row_offer->total_cost > 0) $price = $row_offer->total_cost;
	  elseif (count($row_reoffer) > 0 && $row_reoffer->total_cost > 0) $price = $row_reoffer->total_cost;
	  else $price = $rows->price;
      
	  $main->assign("domain_price", $price." $");
      $main->assign("domain_name", $rows->name);
      $totalPrice +=$price;
      $main->parse("main.sepet.rows");
   }
   $main->assign("totalPrice", $totalPrice." $");
   $main->parse("main.sepet.total");
   $main->parse("main.sepet");
}else {
    //no_record
    $main->parse("main.no_record");
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
