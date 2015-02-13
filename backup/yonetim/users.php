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
$center= new XTemplate('temp/users.tpl');
$left->parse("main.domain");

$db=new dbMysql();
$aktif_sayfa = intval($_GET['aktif_sayfa']);
if($aktif_sayfa=="") {
    $aktif_sayfa=1;
}
$center->assign("aktif_sayfa", $aktif_sayfa);

$kosul="";
$sayfa_basina_kayit = 20;
$son_kayit = ($aktif_sayfa - 1) * $sayfa_basina_kayit;
$sorgu_cumlesi = "select * from user ";
$limit = " ORDER BY id DESC LIMIT ".$son_kayit.", ".$sayfa_basina_kayit."";
//$center->parse("main.table_last");
if($domName !="") {
	$kosul=" where name like '%".$domName."%' ";
	$center->assign("domName", $domName);
}
$sorgu_cumlesi =$sorgu_cumlesi.$kosul;
//echo $sorgu_cumlesi;,
$num=$db->num_rows($sorgu_cumlesi);
$center->assign("toplamKayit", $num);
$rows=$db->get_rows($sorgu_cumlesi.$limit);
$i=1;
foreach ($rows as $val) {
    $center->assign("domain_ID", $val->id);
	if($val->f_user_id > 0) $center->assign("user_name", '<a href="javascript:void(0);" onclick="javascript:wopen('.$val->f_user_id.')" style="color:blue;">'.$val->name.'</a>');
    else $center->assign("user_name", $val->name);
    $center->assign("email", $val->email);
	if($val->sex == 'm') $center->assign("sex", "Erkek");
	elseif($val->sex == 'f') $center->assign("sex", "Bayan");
	else $center->assign("sex", "");
    $center->assign("phone", $val->phone);
	$center->assign("status", $val->status);

	$catrows=$db->get_rows("SELECT dom_cat.id, dom_cat.id_dom, dom_cat.id_cat, kategori.name,kategori.id as catid FROM dom_cat Inner Join kategori ON kategori.id = dom_cat.id_cat where id_dom='".$val->id."' order by id");
	if(is_array($catrows)) {
        foreach ($catrows as $v) {
            $kat .="<a href='#' onClick='return false;'><span id='domcat_".$v->id."'>".$v->name."</span></a>, ";
        }
    }else $kat="";
    $center->assign("domain_cat", $kat);
    ($i%2==0)?$center->assign("bg", " "):$center->assign("bg", "bg");
    $center->parse("main.table.rows");
    $i++;
}
$aralik = 3; // aktif sayfadan önceki/sonraki sayfa gösterim sayısı
$toplam_kayit = $db->num_rows($sorgu_cumlesi);

$toplam_sayfa = ceil(($toplam_kayit/$sayfa_basina_kayit));
for($i=1;$i<=$toplam_sayfa;$i++) {
    ($aktif_sayfa==$i)?$center->assign("selected_num", "selected"):$center->assign("selected_num", " ");
    $center->assign("num", $i);
    $center->assign("num_value", $i);
    $center->parse("main.table.t_num");
}
for($i=1;$i<=$toplam_sayfa;$i++) {
    ($aktif_sayfa==$i)?$center->assign("selected_num", "selected"):$center->assign("selected_num", " ");
    $center->assign("num", $i);
    $center->assign("num_value", $i);
    $center->parse("main.table.t_num2");
}
if(($aktif_sayfa <= $toplam_sayfa) && $aktif_sayfa > 1) $center->assign("geri_sayfa",($aktif_sayfa-1));
else $center->assign("geri_sayfa",($aktif_sayfa));
if($aktif_sayfa < $toplam_sayfa) $center->assign("ileri_sayfa",($aktif_sayfa+1));
else $center->assign("ileri_sayfa",($aktif_sayfa));
$arama=explode("&", $_SERVER['QUERY_STRING']);
$center->assign("request_URI","?".$arama[0]);
$center->parse("main.table");

$center->parse("main.top_nav");
$center->parse("main");
$index->assign("MAIN", $center->text("main"));


$left->parse("main");
$index->assign("LEFT", $left->text("main"));
$right->parse("main");
$index->assign("RIGHT", $right->text("main"));
$footer->parse("main");
$index->assign("FOOTER", $footer->text("main"));
$header->assign("cls_userlist", 'active');
$header->parse("main");
$index->assign("HEADER", $header->text("main"));

$index->parse('main');
$index->out('main');
?>