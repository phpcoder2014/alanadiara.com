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
$center= new XTemplate('temp/domain.tpl');
$left->parse("main.domain");

$db=new dbMysql();
$aktif_sayfa = intval($_GET['aktif_sayfa']);
if($_REQUEST['search'] == 1){
	$_SESSION['dom_name'] = $_REQUEST['domName'];
	$_SESSION['yes_cat'] = $_REQUEST['yes_cat'];
	$_SESSION['no_cat'] = $_REQUEST['no_cat'];	
}

if($aktif_sayfa=="") {
    $aktif_sayfa=1;
}
$center->assign("aktif_sayfa", $aktif_sayfa);
if($z=="cat") {
    if($send=="Gönder") {
        //print_r($_POST);
        foreach ($dom_cat as $l) {
            //echo $l;
            $res=$db->get_row("select id from dom_cat where id_dom='".$id."' and id_cat='".$l."'");
            if($res->id =="") {
                $db->insert("dom_cat", array("id_dom"=>$id,"id_cat"=>$l));
            }
            $update=$db->update("domain", array('info'=>$info), array('id'=>$id));
        }
        header('Location: domain.php?z=list&aktif_sayfa='.$aktif_sayfa);
    }
    $query="SELECT * FROM domain WHERE id =".$id;
    $dom=$db->get_rows($query);
    foreach ($dom as $value) {
        $name=$value->name;
        $dom_info=$value->info;
        $dom=$value->name;

    }
    $domkategorileri=$db->get_rows("select id_cat from dom_cat where id_dom=".$id);
    $domCAT[]=0;
    foreach ($domkategorileri as $domvalue) {
        $domCAT[]=$domvalue->id_cat;
    }
    $center->assign("domain_name", $name);
    $center->assign("domain_ID", $id);
    $center->assign("domain_info", $dom_info);
    $center->assign("z", $z);
    $center->assign("aktif_sayfa", $aktif_sayfa);
	
	
	$center->assign("category", $category);
    $center->assign("domName",  $_SESSION['dom_name']);

    $catrows=$db->get_rows("select * from kategori order by id");
    foreach ($catrows as $v) {
        if(in_array($v->id, $domCAT)){
            $center->assign("selected", "selected");
        }else {
            $center->assign("selected", "");
        }
        $center->assign("cat_ID", $v->id);
        $center->assign("cat_name",  $v->name);
        $center->parse("main.cat_duzelt.sel_rows");
    }

    $center->parse("main.cat_duzelt");
}else if($z=="list") {
    $kosul="";
    $sayfa_basina_kayit = 20;
    $son_kayit = ($aktif_sayfa - 1) * $sayfa_basina_kayit;
    $sorgu_cumlesi = "select * from domain ";
    $limit = " ORDER BY id LIMIT ".$son_kayit.", ".$sayfa_basina_kayit."";
//$center->parse("main.table_last");
    if(isset($_SESSION['dom_name']) && $_SESSION['dom_name'] !="") {
        $kosul=" where name like '%".$_SESSION['dom_name']."%' ";
        $center->assign("domName", $_SESSION['dom_name']);
    }
	if((isset($_SESSION['yes_cat']) && $_SESSION['yes_cat'] == 1) || (isset($_SESSION['no_cat']) && $_SESSION['no_cat'] == 1)){
		if((isset($_SESSION['yes_cat']) && $_SESSION['yes_cat'] == 1)) $act = " ";
		if((isset($_SESSION['no_cat']) && $_SESSION['no_cat'] == 1)) $act = " not ";
		if(isset($_SESSION['dom_name']) && $_SESSION['dom_name'] !="") {
			$kosul = $kosul." and id".$act."in(select id_dom from dom_cat)";
		} else {
			$kosul = "where id".$act."in(select id_dom from dom_cat)";
		}
	}
    $sorgu_cumlesi =$sorgu_cumlesi.$kosul;
    //echo $sorgu_cumlesi;,
    $num=$db->num_rows($sorgu_cumlesi);
    $center->assign("toplamKayit", $num);
    $rows=$db->get_rows($sorgu_cumlesi.$limit);
    $i=1;
    foreach ($rows as $val) {
        $kat="";
        $center->assign("domain_ID", $val->id);
		$center->assign("domain_min_price", $val->price_min);
        $center->assign("domain_price", $val->price);
        $center->assign("domain_puan", $val->puan);
        $center->assign("domain_name", "($val->id) ".$val->name);
		if($val->offer == 0) $center->assign("selected0", "selected");
		else $center->assign("selected0", "");
		if($val->offer == 1) $center->assign("selected1", "selected");
		else $center->assign("selected1", "");
        $catrows=$db->get_rows("SELECT dom_cat.id, dom_cat.id_dom, dom_cat.id_cat, kategori.name,kategori.id as catid FROM dom_cat Inner Join kategori ON kategori.id = dom_cat.id_cat where id_dom='".$val->id."' order by id");
        if(is_array($catrows)) {
            foreach ($catrows as $v) {
                $kat .="<a href='#' onClick='return false;'><span id='domcat_".$v->id."'>".$v->name."</span></a>, ";
            }
        } else $kat = "Kategori Ekle";
	
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

    $center->assign("geri_sayfa",($aktif_sayfa-1)."&domName=".$_SESSION['dom_name']);
    $center->assign("ileri_sayfa",($aktif_sayfa+1)."&domName=".$_SESSION['dom_name']);
    $arama=explode("&", $_SERVER['QUERY_STRING']);
    $center->assign("request_URI","?".$arama[0]);
    $center->parse("main.table");
}
if($_SESSION['yes_cat'] == 1) $center->assign("yes_cat_select", "checked=checked");
if($_SESSION['no_cat'] == 1) $center->assign("no_cat_select", "checked=checked");
$center->parse("main.top_nav");
$center->parse("main");
$index->assign("MAIN", $center->text("main"));


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