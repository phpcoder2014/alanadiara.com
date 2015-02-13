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
$left = new XTemplate('temp/left.tpl');
$center= new XTemplate('temp/domain_transfer.tpl');

$db=new dbMysql();
$center->assign("status", "(Transfer Başvuruları)");

$aktif_sayfa = intval($_GET['aktif_sayfa']);
if($aktif_sayfa=="") {
    $aktif_sayfa=1;
}
$center->assign("aktif_sayfa", $aktif_sayfa);

$center->parse("main.top_nav");
if($status=="0"){
    $kosul=" where status=0 ";
}else if($status=="1") {
    $kosul=" where status=1 ";
}else {
    $kosul=" where status=0 ";
}
//$query="select * from domain_transfer ".$kosul;
$query ="select * from domain_transfer where status=1 or status=0";
$sayfa_basina_kayit = 10;
$son_kayit = ($aktif_sayfa - 1) * $sayfa_basina_kayit;
$sorgu_cumlesi = $query;
$limit = " order by add_time desc LIMIT ".$son_kayit.", ".$sayfa_basina_kayit."";

$num=$db->num_rows($query);
if($num>0) {
    $row=$db->get_rows($query.$limit);
    $tprice=0.00;
    $i=1;
    foreach ($row as $rows) {
        ($i%2==0)?$center->assign("bg", "bg"):$center->assign("bg", " ");
        $center->assign("sira", $i);
        $center->assign("orderid", $rows->id);
        //$center->assign("domID", getDomainName($rows->domID).".info");
        $center->assign("domID", getDomainName($rows->domID));
		
        $userSQL="SELECT
`user`.name, `user`.email FROM
domain Inner Join orders ON orders.id = domain.orderid
Inner Join `user` ON `user`.id = orders.costumer_id WHERE domain.id =  '".$rows->domID."'";
    $userrow=$db->get_row($userSQL);
        $center->assign("costumer_id",$userrow->name ."<br>".$userrow->email);
        $center->assign("time", date('d-m-Y H.i.s',$rows->add_time));
        $center->parse("main.rows");
        $tprice +=$rows->total_cost;
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

    
}else {
    $center->assign("t_num", 0);
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
$header->parse("main");
$index->assign("HEADER", $header->text("main"));

$index->parse('main');
$index->out('main');
?>