<?php

/* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');
include_once('inc/class.nusoap.php');

$index = new XTemplate('temp/index_domain.tpl');
$footer = new XTemplate('temp/footer.tpl');

$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/domain_info.tpl');
$right = new XTemplate('temp/arama.tpl');
$main->assign("300x250", banner300X250());
$db = new dbMysql();

$domkisa = explode(".", $domain);
$query = "select * from domain where state = 0 AND name='".$domain."'";
$result = $db->get_row($query);
if(!isset($result->id))
	{
	header("Location: /");
	exit;
	}
//echo $result->name."ere";
/*
include 'inc/phpwhois/whois.main.php';
include 'inc/phpwhois/whois.utils.php';
$whois = new Whois();

$whois->deep_whois = false;
$whois_data = $whois->Lookup($domain);

if ($whois_data["regrinfo"]["registered"] == "yes") {
    //echo "<pre>";print_r($whois_data);
    $datacre = $whois_data["regrinfo"]["domain"]['created'];
    $dataexp = $whois_data["regrinfo"]["domain"]['expires'];
    $datacre2 = explode("-", $datacre);
    $dataexp2 = explode("-", $dataexp);
    $datayears = $dataexp2[0] - $datacre2[0];

    $main->assign("datacre", $datacre);
    $main->assign("dataexp", $dataexp);
    $main->assign("datayears", $datayears);
}
*/
//$main->assign("domainPageRank", GooglePageRank("ok.net"));
$main->assign("domainName", strtoupper($domain));
if(isset($_SESSION['net_users']['id']) && $_SESSION['net_users']['id'] > 0) $main->assign("idDom", $result->id);
else $main->assign("idDom", 'doregister');
if(isset($_GET['fiyat']))
{
    $referer = htmlentities(strip_tags($_GET['referer']));
    $fiyat = htmlentities(strip_tags($_GET['fiyat']));
    $gelen_bilgi_d = "Turkticaret tarafından gelindi.";
    $main->assign("gelen_deger", $fiyat);
    $main->assign("gelen_bilgi", $gelen_bilgi_d);
}
if(isset($teklif) && $teklif == 'evet') {
	$main->parse("main.offer_first");
}
$bolunmus = explode(".", $domain);
$bolunmus = $bolunmus[0];
$main->assign("bolunmus", $bolunmus);
$main->assign("domainName", strtoupper($domain));
$main->assign("domainID", $result->id);
$main->assign("domainNamePrice", $result->price);
$main->assign("domainNamePuan", $result->puan);
$main->assign("domainWordRelation", $result->info);
$main->assign("dataexp", date("d-m-Y", $result->enddate));
$main->assign("domainNameCat", getDomainCatFull($result->id));
$main->assign("domainteklifver", strtolower($result->name.$rresultows->suffix).".teklif");
$main->assign("domainNamePriceasd",$result->price);

$config_title = $result->info . ", " . $domain;
$config_desc = $result->info . " yazıldığında sizin siteniz çıksın istiyorsanız, " . $domain . " alan adına hemen sahip olabilirsiniz.";
$config_meta = $result->info . ", " . $domain;

if($result->offer == 0) {
	$main->parse("main.buttons.order_button");
}
$main->parse("main.buttons.offer_button");
$main->parse("main.buttons");
$main->parse("main.offer_div");

/*
if($result->google_rank > 0)
	$main->assign("domainPageRank", $result->google_rank);
else
	{
	$client = new nusoap_client('http://89.106.14.247/turkticaret_test.php?wsdl');

	$data = $client->call('KeywordGoogleCountSearch', array(
		'username' => 'alanadiara',			// Username
		'password' => 'iYop87Ygs',			// Password
		"domain_name" => $domain
		));
	
	if($data["status"] == 1)
		{
		$domains_std = json_decode($data["data"]);
		
		$db->updateSql("update domain set google_rank=".$domains_std->count." where id='" . $result->id . "'");
		$main->assign("domainPageRank", $domains_std->count);
		}
	}
*/
	
$main->assign("domainSearchRank", $result->monthly_search);
$db->updateSql("update domain set hit=hit+1 where id='" . $result->id . "'");

$hitrows = $db->get_rows("select id,name from domain where orderid !=0 AND state = 0 order by hit desc limit 10");
foreach ($hitrows as $hitrows2) {
    $main->assign("hitNameLink", "" . $hitrows2->name . ".htm");
    $main->assign("hitName", $hitrows2->name);
    $main->parse("main.hit_dom");
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

$index->assign("domname", $config_title);
$index->assign("description", $config_desc);
$index->assign("keywords", $config_meta);

$index->parse('main');
$index->out('main');

function GooglePageRank($url) {
    $fp = @fsockopen("toolbarqueries.google.com", 80, $errno, $errstr, 30);
    if (!$fp) {
        return 0;
    } else {
        $out = "GET /search?client=navclient-auto&ch=" . CheckHash(HashURL($url)) . "&features=Rank&q=info:" . $url . "&num=100&filter=0 HTTP/1.1\r\n";
        $out .= "Host: toolbarqueries.google.com\r\n";
        $out .= "User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);

        while (!feof($fp)) {
            $data = fgets($fp, 128);
            //print_r($data);
            $pos = strpos($data, "Rank_");
            if ($pos === false) {
                //
            } else {
                $pagerank = substr($data, $pos + 9);
            }
        }

        fclose($fp);
        return $pagerank;
    }
}

function StrToNum($Str, $Check, $Magic) {
    $Int32Unit = 4294967296; // 2^32
    $length = strlen($Str);
    for ($i = 0; $i < $length; $i++) {
        $Check *= $Magic;
        if ($Check >= $Int32Unit) {
            $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
            $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
        }
        $Check += ord($Str{$i});
    }
    return $Check;
}

function HashURL($String) {
    $Check1 = StrToNum($String, 0x1505, 0x21);
    $Check2 = StrToNum($String, 0, 0x1003F);
    $Check1 >>= 2;
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) << 2 ) | ($Check2 & 0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
    return ($T1 | $T2);
}

function CheckHash($Hashnum) {
    $CheckByte = 0;
    $Flag = 0;
    $HashStr = sprintf('%u', $Hashnum);
    $length = strlen($HashStr);
    for ($i = $length - 1; $i >= 0; $i--) {
        $Re = $HashStr{$i};
        if (1 === ($Flag % 2)) {
            $Re += $Re;
            $Re = (int) ($Re / 10) + ($Re % 10);
        }
        $CheckByte += $Re;
        $Flag++;
    }
    $CheckByte %= 10;
    if (0 !== $CheckByte) {
        $CheckByte = 10 - $CheckByte;
        if (1 === ($Flag % 2)) {
            if (1 === ($CheckByte % 2)) {
                $CheckByte += 9;
            }
            $CheckByte >>= 1;
        }
    }
    return '7' . $CheckByte . $HashStr;
}

?>
