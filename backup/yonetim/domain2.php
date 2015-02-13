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
$center= new XTemplate('temp/domain2.tpl');
$left->parse("main.domain");

$db=new dbMysql();
$aktif_sayfa = intval($_GET['aktif_sayfa']);
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
      }
      header('Location: domain2.php?z=list&aktif_sayfa='.$aktif_sayfa);
   }
   $query="SELECT domain.price, domain.puan, domain.name, domain.id FROM
domain
WHERE domain.id =  '".$id."'";
   $dom=$db->get_rows($query);
   foreach ($dom as $value) {
      $name=$value->name;

   }
   $center->assign("domain_name", $name.".info");
   $center->assign("domain_ID", $id);
   $center->assign("z", $z);
   $center->assign("aktif_sayfa", $aktif_sayfa);

   $catrows=$db->get_rows("select * from kategori order by id");
   foreach ($catrows as $v) {
      $center->assign("selected", "");
      $center->assign("cat_ID", $v->id);
      $center->assign("cat_name",  $v->name);
      $center->parse("main.cat_duzelt.sel_rows");

   }
   $center->parse("main.cat_duzelt");
}else if($z=="list") {
    
   $sayfa_basina_kayit = 20;
   $son_kayit = ($aktif_sayfa - 1) * $sayfa_basina_kayit;
   $sorgu_cumlesi = "SELECT
kategori.name AS catname,
kategori.id AS catid,
domain.id,
domain.name,
domain.price
FROM
domain
Inner Join dom_cat ON dom_cat.id_dom = domain.id
Inner Join kategori ON kategori.id = dom_cat.id_cat
WHERE  kategori.id in(10)
group by domain.name
";
   $limit = " ORDER BY id LIMIT ".$son_kayit.", ".$sayfa_basina_kayit."";
//$center->parse("main.table_last");
   $rows=$db->get_rows($sorgu_cumlesi.$limit);
   $i=1;
   foreach ($rows as $val) {
      $kat="";
      $center->assign("domain_ID", $val->id);
      $center->assign("domain_price", $val->price);
      $center->assign("domain_puan", $val->puan);
      $center->assign("domain_name", "($val->id) ".$val->name);

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
   $center->assign("geri_sayfa",($aktif_sayfa-1));
   $center->assign("ileri_sayfa",($aktif_sayfa+1));
   $arama=explode("&", $_SERVER['QUERY_STRING']);
   $center->assign("request_URI","?".$arama[0]);
   $center->parse("main.table");
}
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