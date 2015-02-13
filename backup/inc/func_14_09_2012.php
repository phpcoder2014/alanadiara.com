<?php
$siteDomName="alanadiara.com";
$siteDomEmail="info@alanadiara.com";
//$siteDomEmail="info@alanadiara.com";

$dom_status=array("Ödeme bekleniyor","Aktif","İptal", "Taşıma tamamlandı");
$kindpay_type=array("bank"=>"Banka Havalesi","mail_order"=>"Mail Order","ccard"=>"Kredi Kartı");
$premium=7;
$quantityArray=array(10,20,50,100);
$orderArray = array(
    1 => "Pahalı - Ucuz",
    2 => "Ucuz - Pahalı",
    3 => "Eklenme (Son)",
    4 => "Eklenme (İlk)",
    5 => "Premium alan adları",
    6 => "Populer alan adları",
);
function createReqCode() {
    $t=md5("aa"+time());
    return $t;
}
function filled_out($form_vars) {
    // test that each variable has a value
    foreach ($form_vars as $key => $value) {
        if (!isset($key) || ($value === ''))
            return false;
    }
    return true;
}
function banner300X250($param="") {
    $banner = new XTemplate('temp/300x250.tpl');

    $banner->parse("main");
    return $banner->text("main");
}
function header_q($param="") {
    $header = new XTemplate('temp/header.tpl');
    if(isset ($_SESSION['net_users']['name'])) {
        $header->assign("user_name", $_SESSION['net_users']['name']);
        $header->parse("main.start");
    }else {
        $header->parse("main.form");
    }

    if(isset ($_SESSION['net_users'])) {
        $header->parse("main.login_sessionON");
    }

    $header->parse("main");
    return $header->text("main");
}
function GenRandOrderCode() {
    $generate_code=mt_rand(10000000,99999999);
    if (substr($generate_code,0,1)!=0)
        return $generate_code;
    else GenRandOrderCode();
}
function GetUniqueOrderCode() {
    global $db;
    $code=GenRandOrderCode();
    $sql="SELECT order_code FROM orders";
    $cmd=$db->get_rows($sql);
    foreach ($cmd as $row) {
        $ccodes[]=$row->order_code;
    }
    $code_generated=false;
    while ($code_generated==false) {
        if (is_array($ccodes) && in_array($code,$ccodes)==false) {
            $code_generated=true;
            return $code;
        }
        else {
            $code_generated=false;
            $code=GenRandOrderCode();
        }
    }
}
function userChangePassword($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Bu maili alanadiara ".$param['email']." hesabınıza ait kullanıcı bilgilerinde değişiklik";
    $metin .=" yapıldığını bildirmek için gönderiyoruz. Lütfen bilgileri kontrol etmek için <a href='http://www.alanadiara.com/login.php'>tıklayınız</a>.</p>";
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com </p>";
    sendMail($metin,$param['email'],$param['name'],"Şifreniz Değiştirildi");
}
function userSendReqCode($param) {
    //Req Code Yeni Üye
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>alanadiara.com üyelik işlemleriniz başarı ile gerçekleştirimiştir. </p>";
    $metin .="<p>InfoPazarı  hizmetlerine erişebilmek için bu maildeki onay linkini tıklayarak veya aşağıdaki aktivasyon kodunu yazarak üyelik hesabınızı aktif etmeniz gerekmektedir.</p>";
    $metin .="<p>Bir kez doğrulama yaparak hesap yönetiminize erişilebilir, çok cazip fiyatlarla size en uygun alan adlarına sahip olabilirsiniz.</p>";
    $metin .="<p>Aktivasyon Kodu: ".$param['code']."</p>";
    $metin .="<p><a href='http://www.alanadiara.com/activate.php?code=".$param['code']."'>http://www.alanadiara.com/activate.php?code=".$param['code']."</a></p>";
    //$metin .="<a href='http://www.alanadiara.com/activate.php?code=".$param['code']."'>http://www.alanadiara.com/activate.php?code=".$param['code']."</a><br><br>";
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com Takımı </p>";
    sendMail($metin,$param['email'],$param['name'],"Yeni Başvuru");
}
function userSendForgetCode($param) {
    //şifremi unuttum
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Siteye girmenizi sağlayacak şifreniz aşağıda yer almaktadır:</p>";
    $metin .="<p>Yeni Şifreniz: ".$param['pass']."<br/>";
    //$metin .="Aktivasyon Kodu: ".$param['code']."</p>";
    $metin .="<p>Şifrenizle siteye girerek istediğiniz işlemi gerçekleştirebilirsiniz. Şifre değiştirme sayfasına giderek şifrenizi güncelleyebilirsiniz.</p>";
    $metin .="<p><a href='http://www.alanadiara.com/activate.php?code=".$param['code']."'>http://www.alanadiara.com/activate.php?code=".$param['code']."</a></p>";
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com </p>";
    sendMail($metin,$param['email'],$param['name'],"Yeni Şifre Başvurusu");
}
function sendOrderBankMail($param) {
    //Banka Havalesi sipariş
    global $db;
    $res=$db->get_row("select * from bank_account where id='".$param['bankid']."'");
    $bank ="<table width='100%' border=0 cellpadding='2' cellspancing='0'>
       <tr>
       <td>Banka adı</td>
       <td>Banka kodu</td>
       <td>Hesap kodu</td>
       <td>Hesap türü</td>
       </tr>";
    $bank .="<tr>";
    $bank .="<td>".$res->bank_name."</td>";
    $bank .="<td>".$res->office_code."</td>";
    $bank .="<td>".$res->account_code."</td>";
    $bank .="<td>".$res->account_type."</td>";
    $bank .="</tr>";
    $bank .="</table>";
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>alanadiara siparişiniz alınmıştır. Siparişinizin tamamlanabilmesi için 3(üç) gün içerisinde ";
    $metin .="aşağıdaki banka hesap numarasına ödeme yapmanız gerekmektedir. ";
    $metin .="</p> ";
    $metin .="<p>Sipariş numaranız: ".$param['code']."<br/> ";
    $metin .="Yatırmanız gereken tutar: ".$param['tprice']."<br/> ";
    $metin .="Yatıracağınız banka hesap bilgileri:<br/> ".$bank;//".$param['tprice']."<br/> ";

    $metin .="</p> ";

    $metin .="Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com ";
    sendMail($metin,$param['email'],$param['name'],"Yeni Sipariş");
}
function sendOrderMailOrderMail($param) {
    //Mail order sipariş
    $link="http://www.alanadiara.com/";
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>alanadiara siparişiniz alınmıştır. Siparişinizin tamamlanabilmesi için ";
    $metin .="aşağıdaki linkten indirebileceğiniz mail order formu doldurup, kimlik fotokopiniz ile birlikte ";
    $metin .="faxlamanız gerekmektedir.</p> ";
    $metin .="<p>Sipariş numaranız: ".$param['code']."<br/> ";
    $metin .="Yatırmanız gereken tutar: ".$param['tprice']."<br/> ";
    $metin .='<table width="500" border="0" cellspacing="1" >';
    $metin .='<tr>';
    $metin .='<td width="55" height="50" bgcolor="#FBFBFB" style="padding-left:10px;" >';
    $metin .='<img src="'.$link.'images/mailorderIco.gif" width="43" height="43" /></td>';
    $metin .='<td style="padding-left:10px;" height="30" bgcolor="#FBFBFB" >';
    $metin .='<strong class="redLink"><a href="'.$link.'Mail_Order_Talimati.doc" target="_blank">';
    $metin .='alanadiara.com Mail Order Talimatı &raquo;</a></strong></td>';
    $metin .='</tr></table>';
    $metin .="</p> ";

    $metin .="Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com ";
    sendMail($metin,$param['email'],$param['name'],"Yeni Sipariş");
}
function sendOrderCcardMail($param) {
    //Mail order sipariş
    $link="http://www.alanadiara.com/";
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Ödediğiniz gereken tutar ".$param['tprice']." $'dır. Bu tutar kredi kartınızdan çekilmiştir. İşlemleriniz en kısa sürede tamamlanacaktır.  İşlemlerinizin durumunu “Alan Adı Merkezi” sekmesinden takip edebilirsiniz</p>";
    $metin .="<p>Sipariş numaranız: ".$param['code']."<br/> ";
    $domName="";
    foreach ($param['domain'] as $value) {
        $domName .=getDomainName($value).", ";
    }
    $metin .="Alınan domainler: ".$domName."<br/> ";
    $metin .="Yatırmanız gereken tutar: ".$param['tprice']." $<br/> </p>";
    sendMail($metin,$param['email'],$param['name'],"Yeni Sipariş");
}
function sendDNSRegisterMail($param) {
    //DNS DEĞİŞİKLİĞİ
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Aşağıdaki domain bilginiz için DNS değişikliği talebiniz alınmıştır.<p>";
    $metin .="<p>İşlemlerinizin tamamlanmasının ardından size en kısa zamanda bilgi verilecektir.<p>";
    $metin .="<p>Domain Name: ".$param['domainName']."<br />";
    $metin .="Dns1 : ".$param['DNS1']."<br />";
    $metin .="Dns2 : ".$param['DNS2']."<br />";
    $metin .="Dns3 : ".$param['DNS3']."<br /><p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com Takımı </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"DNS değişikliği","true")) {
        return false;
    }else return true;
}
function sendOdemeBildirim($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Ödeme bildirim talebiniz alınmıştır.<p>";
    $metin .="<p>İşlemlerinizin tamamlanmasının ardından size en kısa zamanda bilgi verilecektir.<p>";
    $metin .=$param['metin'];
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com Takımı </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Ödeme Bildirim Formu","true")) {
        return false;
    }else return true;
}
function sendDNSUpdateDomInf($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Aşağıdaki domaininiz için bilgi güncelleme talebiniz alınmıştır.<p>";
    $metin .="<p>İşlemlerinizin tamamlanmasının ardından size en kısa zamanda bilgi verilecektir.<p>";
    $metin .=$param['metin'];
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com Takımı </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Domain Bilgi Güncelleme","true")) {
        return false;
    }else return true;
}
//DOMAIN TRANSFER
function sendDomainTransferAdmin($param) {
    $metin="Merhaba <br/><br/>";
    $metin .="<p>Aşağıdaki müşteri transfer kodu talebinde bulunmuştur. <p>";
    $metin .="<p>Alan adı:".$param['domain'];
    $metin .="<br>Kayıtla mail:".$param['email']."<br>";
    $metin .="<br>Kullanıcı Adı:".$param['name']."<br><p>";
    
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com Takımı </p>";
    if(!sendMail($metin,"info@alanadiara.com","Administration","Transfer Kodu Talebi")) {
        return false;
    }else return true;
}
function sendDomainTransfer($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>".$param['domain']." alan adı transfer kodu talebiniz tarafımıza ulaşmıştır.  ";
    $metin .="Kodunuz 24 saat içerisinde kayıtlı mail adresinize gönderilecektir. <p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com Takımı </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Transfer Kodu Talebi")) {
        return false;
    }else return true;
}
function sendDomainTransferComplate($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>".$param['domain']." alan adı transfer kodunuz aşağıda yer almaktadır. ";
    $metin .="Transfer kodunuzun geçerliliği 3 gündür. 3 gün içersinde  ";
    $metin .="alan adınızı transfer etmek istediğiniz diğer registrar firmaya ";
    $metin .="iletmeniz gerekmektedir. Aksi takdirde yeniden ";
    $metin .="transfer kodu talebinde bulunmanız gerekecektir. ";
    $metin .="Transfer işleminizi diğer register firma takip edecektir. <p>";
    $metin .="<p>Transfer kodu : ".$param['code']."</p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com Takımı </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Transfer Kodu Talebi")) {
        return false;
    }else return true;
}
function sendMail($metin,$email,$name,$mesaj_name="alanadiara.com",$sendTeknik="false") {

    include_once("mailtpl/general.php");
    include_once("mailtpl/class.phpmailer.php");
    $mail = new PHPMailer();
      $mail->Host		= "89.106.12.24"; // SMTP server
      $mail->Port		= 587;
      $mail->Mailer  	= "smtp";
      $mail->SMTPAuth = true;
      $mail->Username = "info@alanadiara.com";
      $mail->Password = "TAZm421";

    $mail->From = "info@alanadiara.com";
    $mail->FromName = "Alanadiara";
    $mail->AddAddress($email, $name);
    $mail->AddBCC("ahmet.basaran@turkticaret.net", $mesaj_name);
    if($sendTeknik=="true") {
        $mail->AddBCC("info@alanadiara.com", $mesaj_name);
    }
    $mail->AddReplyTo("info@alanadiara.com", "alanadiara.com");
    $mail->IsHTML(true);     // set email format to HTML
    $mail->Subject = $mesaj_name;
    $mail->Body    = GenelMailTaslak("alanadiara.com",$metin);
    $mail->AltBody = "Üyelik aktivasyon";
    if(!$mail->Send()) return false;else return true;
}
function getDomainName($id){
    global $db;
    $row=$db->get_row("select name from domain where id='".$id."'");
    return $row->name;
}

function getDomainCat($id,$kat="") {
    global $db;
    if($kat!="") {
        $kosul =" and kategori.id in(".implode(',', $kat).")";
    }
    $sql="SELECT kategori.name AS catname,kategori.id FROM dom_cat Inner Join kategori ON dom_cat.id_cat = kategori.id WHERE dom_cat.id_dom =  '".$id."' $kosul ORDER BY kategori.id ASC";
    if($db->num_rows($sql)>0) {
        $res=$db->get_rows($sql);
        $countt=count($res);
        $i=0;
        foreach ($res as $value) {
            $i++;
            $sperator=" | ";
            $er .="<a href=?btn=Send&categori=".$value->id.">".$value->catname."</a>";
            if($countt!=$i) {
                $er .=$sperator;
            }
        }
        return $er;
    }else return "";
}
function getDomainCatFull($id){
    global $db;
    $sql="SELECT kategori.name AS catname,kategori.id FROM dom_cat Inner Join kategori ON dom_cat.id_cat = kategori.id WHERE dom_cat.id_dom =  '".$id."' ORDER BY kategori.id ASC";
    if($db->num_rows($sql)>0) {
        $res=$db->get_rows($sql);
        $countt=count($res);
        $i=0;
        foreach ($res as $value) {
            $i++;
            $sperator=" | ";
            $er .="<a href=kategoriler.htm?btn=Send&categori=".$value->id.">".$value->catname."</a>";
            if($countt!=$i) {
                $er .=$sperator;
            }
        }
        return $er;
    }else return false;
}

//ADMIN FUNCTION
function adminSendOrderAct($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>alanadiara dan aşağıdaki siparişiniz için ödemeniz onaylanmıştır.</p>";
    $metin .="<p>Sipariş kodu: <b>".$param['orderCode']."</b></p>";
    $metin .="<p>Alınan domain: <b>".$param['domain']."</b></p>";

    $metin .="<p>Hesap yönetim panelinize erişmek için <a href='http:www.alanadiara.com/account_management.php'>tıklayınız</a></p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Sipariş durumunuz")) {
        return false;
    }else {

        //gkupon(array('name'=>$param['name'],'email'=>$param['email'],'name'=>$param['name'],'userID'=>$_SESSION['net_users']['id'],'domID'=>$param['name']));
        return true;
    }
}
function adminSendOrderCancel($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>alanadiara’da yapmış olduğunuz alan adı siparişiniz iptal edilmiştir.</p>";
    $metin .="<p>Sipariş kodu: <b>".$param['orderCode']."</b></p>";
    $metin .="<p>Alınan domain(ler): <b>".$param['domain']."<b></p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Sipariş durumunuz")) {
        return false;
    }else return true;
}
function adminSendOrderComplate($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>alanadiara dan aşağıdaki siparişinizin taşıma işlemi tamamlanmıştır.</p>";
    $metin .="<p>Sipariş kodu: <b>".$param['orderCode']."</b></p>";
    $metin .="<p>Alınan domain: <b>".$param['domain']."</b></p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Sipariş durumunuz")) {
        return false;
    }else return true;
}
function gkupon($param) {
    return true;
    $db=new dbMysql();
    $sqlg="SELECT id FROM gkpn WHERE userID is NULL and used='0' limit 1";
    $numg=$db->num_rows($sqlg);
    if ($numg>0) {
        $rowg=$db->get_row($sqlg);
        $db->updateSQL("UPDATE gkpn SET userID='".$param['userID']."',  domID='".$param['domID']."', time='".time()."' WHERE id='".$rowg->id."'");

        $metin="Merhaba ".$param['name']."<br/><br/>";
        $metin .="<p>alanadiara.com'dan 100 TL değerinde Google AdWords deneme kuponu kazanmış bulunuyorsunuz.</p>";
        $metin .="<p>Hediye kupon kazandığınız alan adınız : ".$param['domain']."</p>";
        $metin .="<p>Hediye kuponunuzu kullanmak için <a href='http://www.alanadiara.com/account_management.php?state=3'>tıklayınız.</a></p>";
        $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>alanadiara.com </p>";

        
        if(!sendMail($metin,$param['email'],$param['name'],"GOOGLE ADWORDS HEDİYENİZ")) {
            return false;
        }else return true;
    }
    else return false;

}
function gresult_count($word){
	$data = file_get_contents("http://www.google.com.tr/search?sclient=psy-ab&hl=tr&source=hp&q=".$word."&pbx=1&oq=".$word."&aq=f&aqi=&aql=1&gs_sm=s&gs_upl=0l0l3l742753l0l0l0l0l0l0l0l0ll1l0&bav=on.2,or.r_gc.r_pw.,cf.osb&fp=8f364004aba46997&biw=1680&bih=352&tch=1&ech=1&psi=i5-yTrWxEo-E-wak_bSHBA.1320329099513.5");
	$exploder = explode('\\x3eYaklaşık', $data);
	$expo = explode('sonu', $exploder[1]);
	return str_replace('.', ',', substr($expo[0], 3, strlen($expo[0])));
}
?>