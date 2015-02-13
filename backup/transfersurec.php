<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');

$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/transfersurec.tpl');
/**/
$main->assign("siteDomName", $siteDomName);
$main->assign("siteDomEmail", $siteDomEmail);
if($_SESSION['net_users']['id']=="") {
    header('Location:login.php?git=transfersurec.php?domainID='.$domainID);
}
if($domainID=="") {
    header('Location:login.php?git=account_management.php');
}
$db=new dbMysql();
$main->assign("domID", ($domainID));
$main->assign("domain", getDomainName($domainID));

//domainTransferstatus

$sql="select id,status from domain_transfer where domID='".$domainID."' and status in(0,1)";
$num=$db->num_rows($sql);
if($num > 0) {
    $res=$db->get_row($sql);
    if($res->status==0){
        $main->assign("transfer_status", "Transfer Kodu bekleniyor.");
    }else if($res->status==1){
        $main->assign("transfer_status", "Transfer Kodu gönderildi.");
    }
    $main->parse("main.domainTransferstatus");
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
