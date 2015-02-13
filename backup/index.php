<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');

$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/main.tpl');
/**/
$main->assign("siteDomName", $siteDomName);
$main->assign("siteDomEmail", $siteDomEmail);

$db=new dbMysql();

$tabid=array();
//if(isset($_SESSION['net_users']['id']) && $_SESSION['net_users']['id'] > 0) $sql = "select * from domain where orderid=0 order by rand() limit 8";
//else $sql = "select * from domain where orderid=0 and offer=0 order by rand() limit 8";

$sql = "select * from domain where orderid=0 and state = 0 order by rand() limit 9";

$tab1=$db->get_rows($sql);
$i=1;
foreach ($tab1 as $val) {
   $tabid[]=$val->id;
   $main->assign("domainID", $val->id);
   $main->assign("domain_price1", "");
//$main->assign("domain_price1", $val->price." $");
   $main->assign("domainName1", $val->name.$val->suffix);
   $main->assign("domainNameLink", strtolower($val->name.$val->suffix).".htm");
   $main->assign("domainOfferLink", strtolower($val->name.$val->suffix).".teklif");
   ($i%2==0)?$main->assign("bgcolor1", "bgcolor='#f3f2eb'"):$main->assign("bgcolor1", "bgcolor='#e7e5d8'");
   if($val->offer == 1) $main->parse("main.tab1_rows.offer");
   else $main->parse("main.tab1_rows.order");
   $main->parse("main.tab1_rows");
   $i++;
}
//echo "select * from domain where type=1 and id not in ('".implode(",", $tabid)."') order by rand() limit 6";
/*
$tab2=$db->get_rows("select * from domain where orderid=0 and id not in (".implode(",", $tabid).") and domain.price <  '".$premium."' order by hit desc limit 6");
$i=1;
foreach ($tab2 as $val) {
   $tabid[]=$val->id;
   $main->assign("domainID", $val->id);
   $main->assign("domain_price1", $val->price." TL");
   $main->assign("domainName1", $val->name.$val->suffix);
   $main->assign("domainNameLink", strtolower($val->name.$val->suffix).".htm");
   ($i%2==0)?$main->assign("bgcolor1", "bgcolor='#f3f2eb'"):$main->assign("bgcolor1", "bgcolor='#e7e5d8'");

   $main->parse("main.tab2_rows");
   $i++;
}
$tab3=$db->get_rows("select * from domain where orderid=0 and id not in (".implode(",", $tabid).") and domain.price >=  '".$premium."' order by rand(),price desc limit 6");
$i=1;
foreach ($tab3 as $val) {
   $tabid[]=$val->id;
   $main->assign("domainID", $val->id);
   $main->assign("domain_price1", $val->price." TL");
   $main->assign("domainName1", $val->name."."."info");
   $main->assign("domainNameLink", strtolower($val->name."."."info").".htm");
   ($i%2==0)?$main->assign("bgcolor1", "bgcolor='#f3f2eb'"):$main->assign("bgcolor1", "bgcolor='#e7e5d8'");

   $main->parse("main.tab3_rows");
   $i++;
}
*/
$query="select * from kategori where state=1 order by name";
$result=$db->get_rows($query);
$total_count = count($result);
$first_block = ceil($total_count / 3);
$second_block = intval(($total_count - $first_block) / 2);
$third_block = $total_count - ($first_block + $second_block);
$counter = 1;
foreach ($result as $kategori) {
	//echo "<pre>" . print_r($kategori,TRUE). "</pre>";
    $main->assign("kategorilerID", $kategori->id);
    $main->assign("kategoriler", $kategori->name);
	if($counter <= $first_block) $main->parse("main.first_cat_block");
	elseif($counter <= ($first_block + $second_block)) $main->parse("main.second_cat_block");
	else $main->parse("main.third_cat_block");
	$counter++;
}



$main->parse("main");
$index->assign("MAIN", $main->text("main"));

$search->parse("main");
$index->assign("domain_search", $search->text("main"));

$footer->parse("main");
$index->assign("FOOTER", $footer->text("main"));


$index->assign("HEADER", header_q());

$index->assign("domname", $domain);
$index->assign("description", $domain." jenerik alan adlarını hemen tescil edebilirsiniz");
$index->assign("keywords", "alan adı, domain, adi, adı, populer, premium, uygun, fiyat, ozel, uzantı, alanadiara, alanadiara.com, AlanAdiAra");

$index->parse('main');
$index->out('main');
?>
