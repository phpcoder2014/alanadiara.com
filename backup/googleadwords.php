<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');

$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/googleadwords.tpl');

$db=new dbMysql();


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
