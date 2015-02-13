<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');

$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/main.tpl');

$db=new dbMysql();

$tabid=array();
$tab1=$db->get_rows("select * from domain where orderid=0 order by rand() limit 6");
$i=1;
foreach ($tab1 as $val) 
{
echo "<pre>" . print_r($val,TRUE). "</pre>";	
   $tabid[]=$val->id;
   $main->assign("domainID", $val->id);
   $main->assign("domain_price1", $val->price."$");
   $main->assign("domainName1", $val->name."."."info");
   ($i%2==0)?$main->assign("bgcolor1", "bgcolor='#f3f2eb'"):$main->assign("bgcolor1", "bgcolor='#e7e5d8'");

   $main->parse("main.tab1_rows");
   $i++;
}
//echo "select * from domain where type=1 and id not in ('".implode(",", $tabid)."') order by rand() limit 6";
$tab2=$db->get_rows("select * from domain where orderid=0 and id not in (".implode(",", $tabid).") order by hit desc limit 6");
$i=1;
foreach ($tab2 as $val) {
   $tabid[]=$val->id;
   $main->assign("domainID", $val->id);
   $main->assign("domain_price1", $val->price."$");
   $main->assign("domainName1", $val->name."."."info");
   ($i%2==0)?$main->assign("bgcolor1", "bgcolor='#f3f2eb'"):$main->assign("bgcolor1", "bgcolor='#e7e5d8'");

   $main->parse("main.tab2_rows");
   $i++;
}
$tab3=$db->get_rows("select * from domain where orderid=0 and id not in (".implode(",", $tabid).") order by rand() limit 6");
$i=1;
foreach ($tab3 as $val) {
   $tabid[]=$val->id;
   $main->assign("domainID", $val->id);
   $main->assign("domain_price1", $val->price."$");
   $main->assign("domainName1", $val->name."."."info");
   ($i%2==0)?$main->assign("bgcolor1", "bgcolor='#f3f2eb'"):$main->assign("bgcolor1", "bgcolor='#e7e5d8'");

   $main->parse("main.tab3_rows");
   $i++;
}
$price_row=$db->get_rows("select * from domain where orderid=0 and id not in (".implode(",", $tabid).") order by rand() limit 7");
$i=1;
foreach ($price_row as $val) {
    $tabid[]=$val->id;
    $main->assign("price_dom", $val->name."."."info");
    $main->parse("main.price_row1");
    $i++;
}
$price_row2=$db->get_rows("select * from domain where orderid=0 and id not in (".implode(",", $tabid).") order by rand() limit 7");
$i=1;
foreach ($price_row2 as $val) {
    $tabid[]=$val->id;
    $main->assign("price_dom", $val->name."."."info");
    $main->parse("main.price_row2");
    $i++;
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
