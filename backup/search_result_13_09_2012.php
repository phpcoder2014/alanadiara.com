<?php
/* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');
$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/search_result.tpl');
$right = new XTemplate('temp/arama.tpl');

if($_SESSION['net_users']['id']=="") {
    $main->assign("usersession", "0");
}else $main->assign("usersession", "1");
if($searchvalue !="") {
    $rt=explode(".", $searchvalue);
    $searchvalue=$rt[0];
}
//print_r($_GET);
$db=new dbMysql();
//print_r($_POST);
$kosul=" where true ";

if($aktif_sayfa==""){
    $aktif_sayfa=1;
}
if($quantity==""){
    $quantity=10;
}
if($order==""){
    $order=3;
}

switch ($order) {
    case 1:
        $orders=" order by domain.price desc ";
        break;
    case 2:
        $orders=" order by domain.price ";
        break;
    case 3:
        $orders=" order by kategori.id desc ";
        break;
    case 4:
        $orders=" order by kategori.id ";
        break;
    case 5:
        $orders=" order by domain.price desc ";
        break;
    case 6:
        $orders=" order by domain.hit ";
        break;
    default:
        $orders=" order by domain.price desc ";
        break;
}
if(!isset($_SESSION['net_users']['id'])) $kosul .=" and offer=1 ";
if($domain !=""){
    //$dom=explode(".", $domain);
    //$domain=$dom[0];
    $kosul .=" and domain.name like '%".$domain."%' ";
}
if(count($categori)>0){
    $kosul .=" and kategori.id in(".implode(",", $categori).") ";
    foreach ($categori as $key => $value) {
        $sayfaliste[]="categori%5B%5D=".$value;
    }
}
//&quantity=10&order=3&aktif_sayfa=1&ara=Ara
$sayfaliste[]="order=".$order;
$sayfaliste[]="domain=".$domain;
$sayfaliste[]="quantity=".$quantity;
//echo implode("&", $sayfaliste);
//$orders="";

$query="SELECT
kategori.name AS catname,
kategori.id AS catid,
domain.*
FROM
domain
Inner Join dom_cat ON dom_cat.id_dom = domain.id
Inner Join kategori ON kategori.id = dom_cat.id_cat
".$kosul."
 and domain.state = 0 group by domain.name". $orders;


$sayfa_basina_kayit = 15;
$son_kayit = ($aktif_sayfa - 1) * $sayfa_basina_kayit;

$num = $db->num_rows($query);

/*if(($num-1*$sayfa_basina_kayit) <$son_kayit){
   $son_kayit = (1 - 1) * $sayfa_basina_kayit; 
}*/
$limit = " limit " . $son_kayit . "," . $sayfa_basina_kayit. " ";
$main->assign("arama_num", $num);
//echo $query.$limit;
if ($num > 0) {
    //echo $query.$limit;
    $result=$db->get_rows($query.$limit);
    $i=1;
    foreach ($result as $rows) {
        ($i%2==0)?$main->assign("trbgcolor", "#f1f1f1"):$main->assign("trbgcolor", "#ffffff");
        $main->assign("domainID", $rows->id);
        $main->assign("domname", $rows->name);
        $main->assign("domnameLink", $rows->name.".htm");
		$main->assign("domainOfferLink", strtolower($rows->name.$rows->suffix).".teklif");
        $main->assign("hediye", "-");
        $main->assign("kategori", getDomainCat($rows->id,$categori));
		$main->assign("domain_price", " - ");
        //$main->assign("domain_price", $rows->price. " $");
        if($srow->orderid==0){
			if($rows->offer == 1) $main->parse("main.list.rows.offer");
			else $main->parse("main.list.rows.order");
        }
        $main->parse("main.list.rows");
        $i++;
    }
    $toplam_kayit=ceil($num/$sayfa_basina_kayit);
    if ($toplam_kayit > 1) {
        for ($j = ($aktif_sayfa-5); $j <= $toplam_kayit; $j++) {
            if ($j >= 1) {
                $main->assign("page", $j);
                $main->assign("pageLink", "?".implode("&", $sayfaliste)."&aktif_sayfa=" . $j);
                if ($aktif_sayfa == $j) {
                    $main->assign("pgCurrent", "pgCurrent");
                } else {
                    $main->assign("pgCurrent", "");
                }
                $main->parse("main.list.pager.rows");
                if ($aktif_sayfa + 4 == $j) {
                    break;
                }
            }

        }
        $firstPageLink = "?".implode("&", $sayfaliste)."&aktif_sayfa=1";
        $lastPageLink = "?".implode("&", $sayfaliste)."&aktif_sayfa=" . $toplam_kayit;
        $main->assign("first_page", $firstPageLink);
        $main->assign("last_page", $lastPageLink);
        $main->assign("topnum", $toplam_kayit);

        $main->parse("main.list.pager");
    }
    $main->parse("main.list");
}

//SAĞ ALAN
$query="select * from kategori where state=1 order by name";
$result=$db->get_rows($query);
$total_count = count($result);
$second_block = intval($total_count / 2);
$counter = 1;
foreach ($result as $kategori) {
	$right->assign("kategorilerID", $kategori->id);
	$right->assign("kategoriler", $kategori->name);
	if($counter <= $second_block) $right->parse("main.first_cat_block");
	else $right->parse("main.second_cat_block");
	$counter++;
}
$right->parse("main");
// END SAĞ

$main->assign("right", $right->text("main"));

$main->parse("main");
$index->assign("MAIN", $main->text("main"));


$search->parse("main");
$index->assign("domain_search", $search->text("main"));

$footer->parse("main");
$index->assign("FOOTER", $footer->text("main"));
$index->assign("HEADER", header_q());

$index->assign("domname", "Popüler alan Adları");
$index->assign("description", $domain." alan adını hemen tescil edebilirsiniz");
$index->assign("keywords", $domain.",domain, alanadi, adi, adı, populer, premium, uygun, fiyat, ozel, uzantı, alanadiara, alanadiara.com, AlanAdıAra");


$index->parse('main');
$index->out('main');
?>
