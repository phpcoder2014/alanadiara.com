<?php
$dom_status=array("Ödeme bekleniyor","Aktif","İptal", "Taşıma tamamlandı");
$kindpay_type=array("bank"=>"Banka Havalesi","mail_order"=>"Mail Order","ccard"=>"Kredi Kartı");
$premium=7;
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
    if(isset ($_SESSION['net_users'])) {
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
    $metin .="<p>Bu maili infopazariinfo ".$param['email']." hesabınıza ait kullanıcı bilgilerinde değişiklik";
    $metin .=" yapıldığını bildirmek için gönderiyoruz. Lütfen bilgileri kontrol etmek için <a href='http://www.infopazari.info/login.php'>tıklayınız</a>.</p>";
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info </p>";
    sendMail($metin,$param['email'],$param['name'],"Şifreniz Değiştirildi");
}
function userSendReqCode($param) {
    //Req Code Yeni Üye
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>infopazari.info üyelik işlemleriniz başarı ile gerçekleştirimiştir. </p>";
    $metin .="<p>InfoPazarı  hizmetlerine erişebilmek için bu maildeki onay linkini tıklayarak veya aşağıdaki aktivasyon kodunu yazarak üyelik hesabınızı aktif etmeniz gerekmektedir.</p>";
    $metin .="<p>Bir kez doğrulama yaparak hesap yönetiminize erişilebilir, çok cazip fiyatlarla size en uygun .info uzantılı alan adlarına sahip olabilirsiniz.</p>";
    $metin .="<p>Aktivasyon Kodu: ".$param['code']."</p>";
    $metin .="<p><a href='http://www.infopazari.info/activate.php?code=".$param['code']."'>http://www.infopazari.info/activate.php?code=".$param['code']."</a></p>";
    //$metin .="<a href='http://www.infopazari.info/activate.php?code=".$param['code']."'>http://www.infopazari.info/activate.php?code=".$param['code']."</a><br><br>";
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info Takımı </p>";
    sendMail($metin,$param['email'],$param['name'],"Yeni Başvuru");
}
function userSendForgetCode($param) {
    //şifremi unuttum
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Siteye girmenizi sağlayacak şifreniz aşağıda yer almaktadır:</p>";
    $metin .="<p>Yeni Şifreniz: ".$param['pass']."<br/>";
    //$metin .="Aktivasyon Kodu: ".$param['code']."</p>";
    $metin .="<p>Şifrenizle siteye girerek istediğiniz işlemi gerçekleştirebilirsiniz. Şifre değiştirme sayfasına giderek şifrenizi güncelleyebilirsiniz.</p>";
    $metin .="<p><a href='http://www.infopazari.info/activate.php?code=".$param['code']."'>http://www.infopazari.info/activate.php?code=".$param['code']."</a></p>";
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info </p>";
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
    $metin .="<p>InfoPazaryeri siparişiniz alınmıştır. Siparişinizin tamamlanabilmesi için 3(üç) gün içerisinde ";
    $metin .="aşağıdaki banka hesap numarasına ödeme yapmanız gerekmektedir. ";
    $metin .="</p> ";
    $metin .="<p>Sipariş numaranız: ".$param['code']."<br/> ";
    $metin .="Yatırmanız gereken tutar: ".$param['tprice']."<br/> ";
    $metin .="Yatıracağınız banka hesap bilgileri:<br/> ".$bank;//".$param['tprice']."<br/> ";

    $metin .="</p> ";

    $metin .="Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info ";
    sendMail($metin,$param['email'],$param['name'],"Yeni Sipariş");
}
function sendOrderMailOrderMail($param) {
    //Mail order sipariş
    $link="http://www.infopazari.info/";
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>InfoPazaryeri siparişiniz alınmıştır. Siparişinizin tamamlanabilmesi için ";
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
    $metin .='infopazari.info Mail Order Talimatı &raquo;</a></strong></td>';
    $metin .='</tr></table>';
    $metin .="</p> ";

    $metin .="Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info ";
    sendMail($metin,$param['email'],$param['name'],"Yeni Sipariş");
}
function sendOrderCcardMail($param) {
    //Mail order sipariş
    $link="http://www.infopazari.info/";
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Ödediğiniz gereken tutar ".$param['tprice']." TL'dir. Bu tutar kredi kartınızdan çekilmiştir. İşlemleriniz en kısa sürede tamamlanacaktır.  İşlemlerinizin durumunu “Alan Adı Merkezi” sekmesinden takip edebilirsiniz</p>";
    $metin .="<p>Sipariş numaranız: ".$param['code']."<br/> ";
    $domName="";
    foreach ($param['domain'] as $value) {
        $domName .=getDomainName($value).".info,";
    }
    $metin .="Alınan domainler: ".$domName."<br/> ";
    $metin .="Yatırmanız gereken tutar: ".$param['tprice']." TL<br/> </p>";
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

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info Takımı </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"DNS değişikliği","true")) {
        return false;
    }else return true;
}
function sendOdemeBildirim($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Ödeme bildirim talebiniz alınmıştır.<p>";
    $metin .="<p>İşlemlerinizin tamamlanmasının ardından size en kısa zamanda bilgi verilecektir.<p>";
    $metin .=$param['metin'];
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info Takımı </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Ödeme Bildirim Formu","true")) {
        return false;
    }else return true;
}
function sendDNSUpdateDomInf($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>Aşağıdaki domaininiz için bilgi güncelleme talebiniz alınmıştır.<p>";
    $metin .="<p>İşlemlerinizin tamamlanmasının ardından size en kısa zamanda bilgi verilecektir.<p>";
    $metin .=$param['metin'];
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info Takımı </p>";
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
    
    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info Takımı </p>";
    if(!sendMail($metin,"info@infopazari.info","Administration","Transfer Kodu Talebi")) {
        return false;
    }else return true;
}
function sendDomainTransfer($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>".$param['domain']." alan adı transfer kodu talebiniz tarafımıza ulaşmıştır.  ";
    $metin .="Kodunuz 24 saat içerisinde kayıtlı mail adresinize gönderilecektir. <p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info Takımı </p>";
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

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info Takımı </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Transfer Kodu Talebi")) {
        return false;
    }else return true;
}
function sendMail($metin,$email,$name,$mesaj_name="Infopazari.info",$sendTeknik="false") {

    include_once("mailtpl/general.php");
    include_once("mailtpl/class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->From = "info@infopazari.info";
    $mail->FromName = "InfoPazari";
    $mail->AddAddress($email, $name);
    $mail->AddBCC("erden.gencer@turkticaret.net", $mesaj_name);
    if($sendTeknik=="true") {
        $mail->AddBCC("info@infopazari.info", $mesaj_name);
    }
    $mail->AddReplyTo("info@infopazari.info", "infopazari.info");
    $mail->IsHTML(true);     // set email format to HTML
    $mail->Subject = $mesaj_name;
    $mail->Body    = GenelMailTaslak("InfoPazari.info",$metin);
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
            $er .="<a href=?btn=Send&categori%5B%5D=".$value->id.">".$value->catname."</a>";
            if($countt!=$i) {
                $er .=$sperator;
            }
        }
        return $er;
    }else return "";
}

//ADMIN FUNCTION
function adminSendOrderAct($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>InfoPazaryeri nden aşağağıdaki siparişiniz için ödemeniz onaylanmıştır.</p>";
    $metin .="<p>Sipariş kodu: <b>".$param['orderCode']."</b></p>";
    $metin .="<p>Alınan domain: <b>".$param['domain']."</b></p>";

    $metin .="<p>Hesap yönetim panelinize erişmek için <a href='http:www.infopazari.info/account_management.php'>tıklayınız</a></p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Sipariş durumunuz")) {
        return false;
    }else {

        //gkupon(array('name'=>$param['name'],'email'=>$param['email'],'name'=>$param['name'],'userID'=>$_SESSION['net_users']['id'],'domID'=>$param['name']));
        return true;
    }
}
function adminSendOrderCancel($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>InfoPazaryeri’nde yapmış olduğunuz alan adı siparişiniz iptal edilmiştir.</p>";
    $metin .="<p>Sipariş kodu: <b>".$param['orderCode']."</b></p>";
    $metin .="<p>Alınan domain(ler): <b>".$param['domain']."<b></p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Sipariş durumunuz")) {
        return false;
    }else return true;
}
function adminSendOrderComplate($param) {
    $metin="Merhaba ".$param['name']."<br/><br/>";
    $metin .="<p>InfoPazaryeri nden aşağağıdaki siparişinizin taşıma işlemi tamamlanmıştır.</p>";
    $metin .="<p>Sipariş kodu: <b>".$param['orderCode']."</b></p>";
    $metin .="<p>Alınan domain: <b>".$param['domain']."</b></p>";

    $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info </p>";
    if(!sendMail($metin,$param['email'],$param['name'],"Sipariş durumunuz")) {
        return false;
    }else return true;
}
function gkupon($param) {
    $db=new dbMysql();
    $sqlg="SELECT id FROM gkpn WHERE userID is NULL and used='0' limit 1";
    $numg=$db->num_rows($sqlg);
    if ($numg>0) {
        $rowg=$db->get_row($sqlg);
        $db->updateSQL("UPDATE gkpn SET userID='".$param['userID']."',  domID='".$param['domID']."', time='".time()."' WHERE id='".$rowg->id."'");

        $metin="Merhaba ".$param['name']."<br/><br/>";
        $metin .="<p>infopazari.info'dan 100 TL değerinde Google AdWords deneme kuponu kazanmış bulunuyorsunuz.</p>";
        $metin .="<p>Hediye kupon kazandığınız alan adınız : ".$param['domain']."</p>";
        $metin .="<p>Hediye kuponunuzu kullanmak için <a href='http://www.infopazari.info/account_management.php?state=3'>tıklayınız.</a></p>";
        $metin .="<p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.<br>infopazari.info </p>";

        
        if(!sendMail($metin,$param['email'],$param['name'],"GOOGLE ADWORDS HEDİYENİZ")) {
            return false;
        }else return true;
    }
    else return false;

}

?>