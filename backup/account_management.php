<?php /* Create by ErdenGENCER  15.02.2010 Pazartesi */
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');

$index = new XTemplate('temp/index.tpl');
$footer = new XTemplate('temp/footer.tpl');
$search = new XTemplate('temp/domain_search.tpl');
$main = new XTemplate('temp/account_management.tpl');
//$main = new XTemplate('temp/search_result.tpl');

/**/
$main->assign("siteDomName", $siteDomName);
$main->assign("siteDomEmail", $siteDomEmail);

if($_SESSION['net_users']['id']=="") {
    header('Location:login.php?git=account_management.php');
}
$db=new dbMysql();
$main->assign("300x250", banner300X250());
if(!isset($_SESSION['net_users']['facebook'])) {
$main->parse("main.hesap_ayalari_tab");
}
$main->assign("userName", $_SESSION['net_users']['name']);
$state = $_GET['state'];
if($state=="") {
    $state=3;
}

$main->assign("liclass".$state, "class='selected'");
switch ($state) {
    case "1":
        //$main->parse("main.state1");
        break;
    case "2":
        $islem=1;
		if(!isset($_SESSION['net_users']['facebook'])) {
        	if($btn=="Send") {
        	    $num=$db->num_rows("select soru from user where id='".$_SESSION['net_users']['id']."' and cevap='".$cevap."'");
        	    if($num<=0) {
        	        $main->assign("MESAJ", "Gizli soru cevabınız yanlış.");
        	        $main->parse("main.state2.gizli_soru.mesaj");
        	        $islem=1;
        	    }else {
        	        $islem=2;
        	
        	    }
        	}else if($btn=="Degis") {
        	    print_r($_POST);
        	    $num=$db->num_rows("select id from user where id='".$_SESSION['net_users']['id']."' and pass='".md5($epass)."'");
        	    if($num<=0) {
        	        $main->assign("MESAJ", "Eski şifreniz hatalı girdiniz. Lütfen kontrol ederek yeniden deneyiniz.");
        	        $main->parse("main.state2.sifre_degistir.mesaj");
        	    }else if($newpass !=$newpass2) {
        	        $main->assign("MESAJ", "Yeni şifreniz ile onay şifreniz birbiri ile aynı değildir. Lütfen  kontrol ederek yeniden giriniz.");
        	        $main->parse("main.state2.sifre_degistir.mesaj");
        	    }else if(strlen($newpass)<6) {
        	        $main->assign("MESAJ", "Yeni şifreniz 6 (altı) karekterden az 20 (yirmi) karekterden çok olamaz.");
        	        $main->parse("main.state2.sifre_degistir.mesaj");
        	    }else {
        	        $db->updateSql("update user set pass='".md5($newpass)."' where id='".$_SESSION['net_users']['id']."'");
        	        $param['name']=$_SESSION['net_users']['name'];
        	        $param['email']=$_SESSION['net_users']['email'];
        	        userChangePassword($param);
        	        $main->assign("MESAJ", "ŞİFRENİZ DEĞİŞTİRİLDİ.");
        	        $main->parse("main.state2.sifre_degistir.mesaj");
        	    }
        	    $islem=2;
        	}
        	if($islem==1) {
        	    $main->assign("NOTE", "Hesap ayarlarınızı değiştirebilmeniz için gizli sorunuza doğru cevap vermeniz gerekmektedir.");
        	    $res=$db->get_row("select soru from user where id='".$_SESSION['net_users']['id']."'");
        	    $main->assign("soru", $res->soru);
        	    $main->assign("cevap", $cevap);
        	
        	    $main->parse("main.state2.gizli_soru");
        	}else if($islem==2) {
        	    $main->assign("NOTE", "Şifrenizi değiştirebilmek için aşağıdaki alanları eksiksiz giriniz.");
        	    $main->parse("main.state2.sifre_degistir");
        	}
        	$main->parse("main.state2");
		}
        break;
    case "3":
        $status=1;
        $sql="SELECT domain.id, domain.name,domain.suffix, domain.price, orders.payment_type, orders.total_cost, orders.`status`, orders.willpay, orders.billing, orders.order_code,orders.id as orderin_id, orders.act_time, orders.`time` FROM domain Inner Join orders ON orders.id = domain.orderid WHERE orders.costumer_id =  '".$_SESSION['net_users']['id']."' and orders.`status`=".$status." ORDER BY orders.`time` ASC ";
        $tnum=$db->num_rows($sql);

        if($tnum>0) {
            $res=$db->get_rows($sql);
            foreach ($res as $row) {
                $main->assign("domID", $row->id);
                $main->assign("domain_state", $dom_status[$row->status]);
                $main->assign("kindpay_type", $kindpay_type[$row->payment_type]);
                /*if($row->payment_type=="ccard"){
                    $ccno=$db->get_row("select ff_digits,lf_digits from vpos where orderin_id='".$row->orderin_id."'");
                    $main->assign("ccard_no", $ccno->ff_digits."****".$ccno->lf_digits);
                }*/
                $main->assign("domain_name", $row->name.$row->suffix);
                $main->assign("order_code",$row->order_code);
                $main->assign("order_time", date('d.m.Y',$row->time));
                $main->assign("order_actTime", date('d.m.Y',$row->act_time));
                $main->assign("domain_price", $row->price);
                $main->parse("main.state3.alan_adlarim.order_domain");
            }
            $main->parse("main.state3.alan_adlarim");
        }else {
            $main->parse("main.state3.no-record");
        }
        /*
        $gsql="SELECT gkpn.id, gkpn.code, gkpn.used, gkpn.domID, gkpn.userID FROM gkpn WHERE gkpn.userID =  '".$_SESSION['net_users']['id']."'";
        $gnum=$db->num_rows($gsql);
        if($gnum>0) {
            $grow=$db->get_rows($gsql);
            foreach ($grow as $grows) {
                $main->assign("g_domain", getDomainName($grows->domID).".info");
                if($grows->used==0){
                $main->assign("g_pass", "**** **** **** ****");
                $main->assign("g_id",$grows->id);
                $main->parse("main.state3.google.g1.grow.gaktif_et");
                }else {
                    $main->assign("g_pass", $grows->code);
                    $main->parse("main.state3.google.g1.grow.gaktif_et2");
                }

                
                $main->parse("main.state3.google.g1.grow");
            }
            $main->parse("main.state3.google.g1");
        }else {
            $main->parse("main.state3.google.g2");
        }
        $main->parse("main.state3.google");
        */
        $main->parse("main.state3.googleFin");
        //Odeme beleniyor
        $status=0;
        $sql="SELECT domain.id, domain.name,domain.suffix, domain.price, orders.payment_type, orders.total_cost, orders.`status`, orders.willpay, orders.billing, orders.order_code, orders.act_time, orders.`time` FROM domain Inner Join orders ON orders.id = domain.orderid WHERE orders.costumer_id =  '".$_SESSION['net_users']['id']."' and orders.`status`=".$status." ORDER BY orders.`time` ASC ";


        $tnum=$db->num_rows($sql);
        if($tnum>0) {
            $res=$db->get_rows($sql);
            foreach ($res as $row) {
                $main->assign("domain_state", $dom_status[$row->status]);
                $main->assign("kindpay_type", $kindpay_type[$row->payment_type]);
                $main->assign("domain_name", $row->name.$row->suffix);
                $main->assign("order_code",$row->order_code);
                $main->assign("order_time", date('d.m.Y',$row->time));
                if($row->act_time!="0")$main->assign("order_actTime", date('d.m.Y',$row->act_time));
                else $main->assign("order_actTime"," - ");
                $main->assign("domain_price", $row->price);
                $main->parse("main.state3.odeme_bekleniyor.order_domain");
            }
            $main->parse("main.state3.odeme_bekleniyor");
        }else {
            $main->parse("main.state3.no-record2");
        }

        $main->parse("main.state3");
        break;
    case "4":
		$pastWeek = time() - (7 * 24 * 60 * 60);
        $sql="SELECT * FROM offers WHERE customer_id = '".$_SESSION['net_users']['id']."' and status<>-2 and(time = ".$pastWeek." or time > ".$pastWeek.") order by time desc";
    	if($db->num_rows($sql)>0) {
            $res=$db->get_rows($sql);
            foreach ($res as $rows) {
				$query = "SELECT id FROM domain WHERE orderid=0 and id=".$rows->domain_id;
				if($db->num_rows($query)>0){
			   		$txt = '';
               		$main->assign("domainID", $rows->id);
			   		$price = 0;
			   		$row_offer = $db->get_row("select total_cost from offers where domain_id=".$val." and status=1");
			   		$row_reoffer = $db->get_row("select ro.total_cost from reoffers ro, offers o where ro.domain_id=".$val." and ro.status=1 and o.status=3");
			   		if(count($row_offer) > 0 && $row_offer->total_cost > 0) $price = $row_offer->total_cost;
			   		elseif (count($row_reoffer) > 0 && $row_reoffer->total_cost > 0) $price = $row_reoffer->total_cost;
			   		else $price = $rows->total_cost;
			   		$price = $rows->total_cost;
               		$main->assign("domain_price", $price);
			   		if($rows->status == -2) $txt = 'Teklif iptal edildi.';
			   		if($rows->status == -1) $txt = 'Teklif kabul edilmedi.';
			   		if($rows->status == 0) $txt = 'Teklif beklemede.';
			   		if($rows->status == 1) $txt = 'Teklif kabul edildi. <a href="javascript:void(0);" onclick="add_basket('.$rows->domain_id.');">Sepete Eklemek için tıklayınız.';
			   		if($rows->status == 3) {
			   			$row = $db->get_row("select * from reoffers where offer_id='".$rows->id."' and status<>-1 and domain_id=".$rows->domain_id."");
			   			if($row) $txt = 'Size özel bir teklif yapıldı. <a href="javascript:void(0);" onclick="popup(\'offer_info.php?reofferid='.$row->id.'\', \'Teklif Detay\');">Teklifi Görmek için tıklayınız.';	else $txt = 'Teklif beklemede.';
			   		}
			   		$main->assign("offer_status", $txt);
			   		$dom = $db->get_row("select name from domain where id=".$rows->domain_id);
			   		$main->assign("domainNameLink", strtolower($dom->name).".htm");
			   		$main->assign("domain_name", $dom->name);
               		$main->parse("main.state4.sepet.rows");
				}
            }
            $main->assign("totalPrice", $totalPrice."");
            $main->parse("main.state4.sepet.total");
            $main->parse("main.state4.sepet");
        }
        $main->parse("main.state4");
        break;
    case "5":
        if($_SESSION['net_users']['id']!="") {
            $save_sql="select * from save_search where id_user='".$_SESSION['net_users']['id']."' order by time desc";
            $s_num=$db->num_rows($save_sql);
            $main->assign("num_save", $s_num);
            if($s_num > 0) {
                $s_res=$db->get_rows($save_sql);
                $i=1;
                foreach ($s_res as $s_val) {
                    ($i%2==0)?$main->assign("sbg", "#f1f1f1"):$main->assign("sbg", "#ffffff");
                    $main->assign("sil_link", $s_val->id);
                    $main->assign("save_link", $s_val->search);
                    $main->assign("save_name", $s_val->name);
                    $main->parse("main.aramaSag.save_search");
                    $i++;
                }
                $main->parse("main.state5.favori");
            }else {
                $main->parse("main.state5.no_record");
            }
        }
        $main->parse("main.state5");
        break;
    default:
        $main->parse("main.state1");
        break;
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
