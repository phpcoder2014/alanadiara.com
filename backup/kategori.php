<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');
$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');

$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/kategori.tpl');
/**/
$main->assign("siteDomName", $siteDomName);
$main->assign("siteDomEmail", $siteDomEmail);

$aktif_sayfa = intval($_GET['aktif_sayfa']);
if($aktif_sayfa=="") {
    $aktif_sayfa=1;
}
$list= intval($_GET['list']);
if($list==""){
    $sayfa_basina_kayit=100;
}else {
    $sayfa_basina_kayit = $list;
}

$categori = intval($categori);
if($categori=="") {
    $categori=10;
}
if($_SESSION['net_users']['id']=="") {
    $main->assign("usersession", "0");
}else $main->assign("usersession", "1");

$son_kayit = ($aktif_sayfa - 1) * $sayfa_basina_kayit;
$limit = " LIMIT ".$son_kayit.", ".$sayfa_basina_kayit."";

$main->assign("QUERY_STRING", $_SERVER['QUERY_STRING']);
$main->assign("list", $list);
$main->assign("categori", $categori);
$db=new dbMysql();
$dsql="SELECT
domain.*,
kategori.name AS catName
FROM
kategori
Inner Join dom_cat ON dom_cat.id_cat = kategori.id
Inner Join domain ON dom_cat.id_dom = domain.id
WHERE
kategori.id =  '".$categori."' AND domain.state = 0 AND
domain.orderid =  '0' ".$kosul;
if($sort =="sbname" and $sort !=""){
   $siralama=" order by domain.name ";
   $siralama .=$sort." ";
}
if($sort =="sbprice" and $sort !=""){
   $siralama=" order by domain.price ";
   $siralama .=$sort." ";
}


$top=$db->num_rows($dsql);
$main->assign("tnum", "Toplam <strong>".$top."</strong> kayıt bulunmuştur.");
$i=1;
if($top>0) {
    $drow=$db->get_rows($dsql.$siralama.$limit);
    foreach ($drow as $drows) {
        ($i%2==0)?$main->assign("bgcolor", "#f1f1f1"):$main->assign("bgcolor", "#ffffff");
        $main->assign("aciklama", "-");
        $main->assign("domainID", $drows->id);
        $main->assign("domName", $drows->name.$drows->suffix);
        $main->assign("domNameLink", strtolower($drows->name.$drows->suffix).".htm");
		$main->assign("domainOfferLink", strtolower($drows->name.$drows->suffix).".teklif");
        $main->assign("catName", $drows->catName);
		$main->assign("price", '');
//        $main->assign("price", $drows->price);
		if($drows->offer == 1) $main->parse("main.kat.rows.offer");
		else $main->parse("main.kat.rows.order");
        $main->parse("main.kat.rows");
        $i++;
    }
    
}else {
    
    $main->parse("main.kat.no_rows");
}

$x = 3; // aktif sayfadan önceki/sonraki sayfa gösterim sayısı
$toplam_kayit = $top;
$toplam_sayfa = ceil(($toplam_kayit/$sayfa_basina_kayit));
if($toplam_sayfa > 1) {
    if($toplam_kayit > $sayfa_basina_kayit) {
        // sayfa 1'i yazdır
        if($aktif_sayfa == 1) {
            $sayfa=("<b>1</b>&nbsp;");
        }
        else {
            $sayfa=("<a href='".$_SERVER['PHP_SELF']."?categori=$categori&list=$list&btnz=Ara&aktif_sayfa=1'>1</a>&nbsp;");
        }

        // "..." veya direkt 2
        if($aktif_sayfa - $x > 2) {
            $sayfa .=("...&nbsp;");
            $i = $aktif_sayfa - $x;
        }
        else {
            $i = 2;
        }

        // +/- $x sayfaları yazdır
        for($i; $i <= $aktif_sayfa + $x; $i++) {
            if($i == $aktif_sayfa) {
                $sayfa .=("<b>".$i."</b>&nbsp;");
            }
            else {
                $sayfa .= "<a href='".$_SERVER['PHP_SELF']."?categori=$categori&list=$list&btnz=Ara&aktif_sayfa=".$i."'>$i</a>&nbsp;";
            }

            if($i == $toplam_sayfa) {
                break;
            }
        }

        // "..." veya son sayfa
        if($aktif_sayfa + $x < $toplam_sayfa - 1) {
            $sayfa .= "...&nbsp;";
            $sayfa .= "<a href='".$_SERVER['PHP_SELF']."?categori=$categori&list=$list&btnz=Ara&aktif_sayfa=".$toplam_sayfa."'>$toplam_sayfa</a>&nbsp;";
        }
        elseif($aktif_sayfa + $x == $lastP - 1) {
            $sayfa .= "<a href='".$_SERVER['PHP_SELF']."?categori=$categori&list=$list&btnz=Ara&aktif_sayfa=".$toplam_sayfa."'>$toplam_sayfa</a>";
        }
    }
    $main->assign("sayfalama",$sayfa);
}
$main->parse("main.kat");
////////////////////////


// SAĞ ARAMA
$query="select * from kategori where state=1 order by name";
$result=$db->get_rows($query);
$total_count = count($result);
$second_block = intval($total_count / 2);
$counter = 1;
foreach ($result as $kategori) {
	$main->assign("kategorilerID", $kategori->id);
	$main->assign("kategoriler", $kategori->name);
	if($counter <= $second_block) $main->parse("main.aramaSag.first_cat_block");
	else $main->parse("main.aramaSag.second_cat_block");
	$counter++;
}
$main->parse("main.aramaSag");

$listarray=array("10","25","50","75","100","250","500");
foreach ($listarray as $list_val) {
    ($sayfa_basina_kayit==$list_val)?$main->assign("list_selected", "selected"):$main->assign("list_selected", " ");
    $main->assign("list_name", $list_val);
    $main->parse('main.list_array');
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
