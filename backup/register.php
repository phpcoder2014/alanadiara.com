<?php
session_start();
/*header("Location: index.php");
exit();*/
/* Create by ErdenGENCER  19.02.2010 Pazartesi */
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

if($_SESSION["3d_error"]==1) {
    echo "<string type='text/javascript'>".$_SESSION["3d_message"]."</string>";
    unset($_SESSION["3d_message"]);
    unset($_SESSION["3d_error"]);
    exit();
}else  if($_SESSION["3d_error"]==-1){
    //echo $_SESSION["3d_message"];
    $gitt=$_SESSION["3d_message"];
    unset($_SESSION["3d_message"]);
    unset($_SESSION["3d_error"]);
    unset ($_SESSION['domBasket']);
    header('Location:order_complate.php?kindpay=3&ordercode='.$gitt);
    
    exit();
}

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');
$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/register.tpl');
/**/
$main->assign("siteDomName", $siteDomName);
$main->assign("siteDomEmail", $siteDomEmail);

if($_SESSION['net_users']['id']==""){
   header('Location:login.php?git=register.php');
}
$db=new dbMysql();

$currency = $db->get_row("SELECT currency FROM currency_cron ORDER BY ctime DESC LIMIT 1");

if(isset ($_SESSION['domBasket'])){
   $totalPrice=0;
   foreach ($_SESSION['domBasket'] as $val) {
      $rows=$db->get_row("select id,name,price from domain where id='".$val."'");
      $main->assign("domainID", $rows->id);
	  $row_offer = $db->get_row("select total_cost from offers where domain_id=".$val." and status=1");
	  $row_reoffer = $db->get_row("select ro.total_cost from reoffers ro, offers o where ro.domain_id=".$val." and ro.status=1 and o.status=3");
	  if(count($row_offer) > 0 && $row_offer->total_cost > 0) $price = $row_offer->total_cost;
	  elseif (count($row_reoffer) > 0 && $row_reoffer->total_cost > 0) $price = $row_reoffer->total_cost;
	  else $price = $rows->price;
      $main->assign("domain_price", $price." $");
	  $main->assign("domain_price_tl", number_format($price * $currency->currency, 2, '.', ',')." TL");
      $main->assign("domain_name", $rows->name);
      $totalPrice += number_format($price * $currency->currency, 2, '.', '');
      $main->parse("main.rows");
   }
   $main->assign("totalPrice", number_format($totalPrice, 2, '.', ',')." TL");
}

$bank=$db->get_rows("select * from bank_account where status=1");
foreach ($bank as $banka) {
    $main->assign("bankID", $banka->id);
    $main->assign("bankName", $banka->bank_name);
    $main->assign("bankoffice_code", $banka->office_code);
    $main->assign("bankaccount_code", $banka->account_code);
if($banka->IBAN !="") $main->assign("bankaccount_IBAN", "IBAN: ".$banka->IBAN);else $main->assign("bankaccount_IBAN", " ");
    $main->assign("bankaccount_type", strtoupper($banka->account_type));
    $main->parse("main.bank_info");
}
for ($i=1;$i<=12;$i++){
$main->assign("mvall", sprintf("%02d",$i));
$main->assign("mval", $i);
$main->parse("main.ccmonth");
}
for ($i=date("Y");$i<=(date("Y")+20);$i++){
$main->assign("mval2", $i);
$main->assign("mvall2", $i);
$main->parse("main.ccyear");
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
