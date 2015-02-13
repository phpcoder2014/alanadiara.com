<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
unset ($_SESSION['domBasket']);
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');

$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/order_complate.tpl');

/**/
$main->assign("siteDomName", $siteDomName);
$main->assign("siteDomEmail", $siteDomEmail);

$db=new dbMysql();
$row=$db->get_row("select * from orders where order_code='".$ordercode."'");

if($kindpay==1) {
    $main->assign("price", $row->total_cost);

    $rows=$db->get_row("select * from bank_account where id='".$row->willpay."'");
    $main->assign("bankName", $rows->bank_name);
    $main->assign("bankoffice_code", $rows->office_code);
    $main->assign("bankaccount_code", $rows->account_code);
    $main->assign("bankaccount_IBAN", $rows->IBAN);
    $main->assign("bankaccount_type", $rows->account_type);

    $main->parse("main.havale_thanks.bank_info");
    $main->parse("main.havale_thanks");
}else if($kindpay==2) {
    $main->assign("price", $row->total_cost);
    $main->parse("main.mailorder_thanks");
}else {
    $main->assign("cctutar", $row->total_cost);
    $main->assign("ccIP", $_SERVER['REMOTE_ADDR']);
    $main->parse("main.kredi_thanks");
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
