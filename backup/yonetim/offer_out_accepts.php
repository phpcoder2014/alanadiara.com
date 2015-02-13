<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set('error_reporting', E_ALL & ~E_NOTICE);
session_start();
if($_SESSION['netadmin'] !=true) {
    header("location:login.php");
}
include_once('../inc/xtpl.php');
include_once('../inc/dbMysql.php');
include_once('../inc/func.php');


$index = new XTemplate('temp/index.tpl');
$header = new XTemplate('temp/header.tpl');
$footer = new XTemplate('temp/footer.tpl');
$right = new XTemplate('temp/right.tpl');
$left = new XTemplate('temp/offer_left.tpl');
$center= new XTemplate('temp/offer_out.tpl');

$db=new dbMysql();
$center->assign("status", "(Kabul edilenler)");

$aktif_sayfa = intval($_GET['aktif_sayfa']);
if($aktif_sayfa=="") {
    $aktif_sayfa=1;
}
$center->assign("aktif_sayfa", $aktif_sayfa);

$center->parse("main.top_nav");
$query="select * from reoffers where status=1";
$sayfa_basina_kayit = 10;
$son_kayit = ($aktif_sayfa - 1) * $sayfa_basina_kayit;
$sorgu_cumlesi = $query;
$limit = " order by time desc LIMIT ".$son_kayit.", ".$sayfa_basina_kayit."";
$num=$db->num_rows($query);
if($num>0) {
    $row=$db->get_rows($query.$limit);
    $i=1;
    foreach ($row as $rows) {
        ($i%2==0)?$center->assign("bg", "bg"):$center->assign("bg", " ");
        $center->assign("sira", $i);
        $center->assign("ipno", $rows->ipno);
        $center->assign("price", $rows->total_cost);
        $center->assign("offerid", $rows->id);

        $user=$db->get_row("select * from netadmin where id='".$rows->user_id."'");
        $center->assign("costumer_id", $user->name."<br/>".$user->email);
        $domNAME = getDomainName($rows->domain_id);
        $center->assign("domName",$domNAME);

        $center->assign("time", date('d-m-Y H.i.s',$rows->time));
        $center->parse("main.rows");
        $i++;
    }
    $toplam_sayfa = ceil(($num/$sayfa_basina_kayit));
    for($i=1;$i<=$toplam_sayfa;$i++) {
        ($aktif_sayfa==$i)?$center->assign("selected_num", "selected"):$center->assign("selected_num", " ");
        $center->assign("num", $i);
        $center->assign("num_value", $i);
        $center->parse("main.t_num");
    }

    
    $center->assign("t_num", $num);
    $center->assign("t_price", $tprice);

}
$center->parse("main");
$index->assign("MAIN", $center->text("main"));


$left->parse("main.orders");
$left->parse("main");
$index->assign("LEFT", $left->text("main"));

$right->parse("main");
$index->assign("RIGHT", $right->text("main"));
$footer->parse("main");
$index->assign("FOOTER", $footer->text("main"));
$header->assign("cls_teklif", 'active');
$header->parse("main");
$index->assign("HEADER", $header->text("main"));

$index->parse('main');
$index->out('main');
?>