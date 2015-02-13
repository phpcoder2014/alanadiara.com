<?php
session_start();
error_reporting(0);
include_once("includes/func.general.php");
include_once("includes/variables.php");
include_once("includes/classes/virtualpos.inc");
include_once("includes/func.db.php");
include_once("includes/configuration.inc");
include_once("includes/classes/plesk.class.php");

header("Content-Type: text/html; charset=ISO-8859-9");

if (!$_SESSION['hst_usr_logged_in']) {
    echo "Lütfen Giriş Yapınız.";
    exit();
}
if (count($_SESSION['quantity'])<=0) { //session yok
    echo "******Geçersiz işlem yaptınız.Lütfen tekrar deneyiniz.";
    exit();
}

openDB();
$userinfo=split("[|]+", $_SESSION['hst_usr_logged_in']);

function ExecutePayment ($ccardno,$exp_m,$exp_y,$cvv2,$total) { //returns true or false

    if (strlen($exp_y)!=2) $exp_y=substr($exp_y,-2);
    if (strlen($exp_m)!=2) $exp_m=str_repeat("0",(2-strlen($exp_m))).$exp_m;

    $data="DATA=<?xml version=\"1.0\" encoding=\"ISO-8859-9\"?>
<CC5Request>
<Name>".VP_FORTIS_NAME."</Name>
<Password>".VP_FORTIS_PASSWORD."</Password>
<ClientId>".VP_FORTIS_CLIENTID."</ClientId>
<IPAddress>".$_SERVER['REMOTE_ADDR']."</IPAddress>
<Email></Email>
<Mode>".VP_FORTIS_MODE."</Mode>
<OrderId></OrderId>
<GroupId></GroupId>
<TransId></TransId>
<UserId></UserId>
<Type>".VP_FORTIS_TYPE."</Type>
<Number>$ccardno</Number>
<Expires>$exp_m/$exp_y</Expires>
<Cvv2Val>$cvv2</Cvv2Val>
<Total>$total</Total>
<Currency>".VP_FORTIS_CURRENCY."</Currency>
<Taksit></Taksit>
<BillTo>
<Name></Name>
<Street1></Street1>
<Street2></Street2>
<Street3></Street3>
<City></City>
<StateProv></StateProv>
<PostalCode></PostalCode>
<Country></Country>
<Company></Company>
<TelVoice></TelVoice>
</BillTo>
<ShipTo>
<Name></Name>
<Street1></Street1>
<Street2></Street2>
<Street3></Street3>
<City></City>
<StateProv></StateProv>
<PostalCode></PostalCode>
<Country></Country>
</ShipTo>
<Extra></Extra>
</CC5Request>";



    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_URL, VP_FORTIS_URL);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 90);
    $result = curl_exec($ch);
    curl_close($ch);


    $response_tag="Response";
    $posf = strpos (  $result, ("<" . $response_tag . ">") );
    $posl = strpos (  $result, ("</" . $response_tag . ">") ) ;
    $posf = $posf+ strlen($response_tag) +2 ;
    $Response = substr (  $result, $posf, $posl - $posf) ;

    $response_tag="OrderId";
    $posf = strpos (  $result, ("<" . $response_tag . ">") );
    $posl = strpos (  $result, ("</" . $response_tag . ">") ) ;
    $posf = $posf+ strlen($response_tag) +2 ;
    $OrderId = substr (  $result, $posf , $posl - $posf   ) ;

    $response_tag="AuthCode";
    $posf = strpos (  $result, "<" . $response_tag . ">" );
    $posl = strpos (  $result, "</" . $response_tag . ">" ) ;
    $posf = $posf+ strlen($response_tag) +2 ;
    $AuthCode = substr (  $result, $posf , $posl - $posf   ) ;

    $response_tag="TransId";
    $posf = strpos (  $result, "<" . $response_tag . ">" );
    $posl = strpos (  $result, "</" . $response_tag . ">" ) ;
    $posf = $posf+ strlen($response_tag) +2 ;
    $TransId = substr (  $result, $posf , $posl - $posf   ) ;

    if ($Response=="Approved") {
        return array("response"=>true,"order_id"=>$OrderId,"auth_id"=>$AuthCode,"trans_id"=>$TransId);
    }
    else {
        return array("response"=>false);
    }
}

function PutOrder($payment_type,$total_cost,$billing,$status=0,$willpay=0) {
    if (!is_numeric($total_cost) || $total_cost<=0) return false;
    openDB();
    $userinfo=split("[|]+", $_SESSION['hst_usr_logged_in']);
    $code=GenRandOrderCode();
    $sql="SELECT order_code FROM ".TABLE_PREFIX."orders";
    $cmd=mysql_query($sql);
    while ($row=mysql_fetch_array($cmd)) {
        $ccodes[]=$row['order_code'];
    }
    if (!is_array($ccodes)) $ccodes[]=0;
    $code_generated=false;
    while ($code_generated==false) {
        if (in_array($code,$ccodes)==false) {
            switch ($payment_type) {
                case "ccard":
                    $recieved_payment=$total_cost;
                    break;
                case "hstcredit":
                    $recieved_payment=$total_cost;
                    break;
                case "mtransfer":
                    $recieved_payment=0.00;
                    break;
                case "mailorder":
                    $recieved_payment=0.00;
                    break;
            }
            $sql="INSERT INTO ".TABLE_PREFIX."orders (id, costumer_id, order_code, payment_type, total_cost, status, willpay, billing, time, recieved_payment) VALUES ('','".$userinfo[0]."','$code','$payment_type','$total_cost','$status','$willpay','$billing','".time()."', '$recieved_payment');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - Order Kaydedilemedi","payment_finish.php-Put Order-154.satir<br>".$sql);
                return false;
            }
            else {
                $inserted=mysql_insert_id();
                $code_generated=true;
                return array("orderid"=>$inserted,"ordercode"=>$code);
            }
        }
        else {
            $code_generated=false;
            $code=GenRandOrderCode();
        }
    }
}

if ($_POST['payment_type']=="ccard") {
    $total=CalculateTotal()*GetCurrency();
    if (is_numeric($total) && $total>0) {
        $executement=ExecutePayment($_POST['ccardno'],$_POST['ccexpmonth'],$_POST['ccexpyear'],$_POST['cvv2'],$total);
        if ($executement['response']) {
            $order_result=PutOrder("ccard",$total,$_POST['billing'],14,VP_CURRENT);
            if ($order_result==false) {
                sendNewMail("Ok.Net - PutOrder=false","payment_finish.php-PutOrder false döndü -177.satir");
                echo "292278#Ödeme sayfasında problem oluştu.";
            }
            else {
                $order_code=$order_result['ordercode'];
                $order_id=$order_result['orderid'];

                $sql="INSERT INTO ".TABLE_PREFIX."vpos_fortis (id, order_id, auth_id, trans_id, cost, ff_digits, lf_digits, time, orderin_id) VALUES (NULL, '".$executement['order_id']."', '".$executement['auth_id']."', '".$executement['trans_id']."', '".$total."', '".substr($_POST['ccardno'],0,4)."', '".substr($_POST['ccardno'],12)."', '".time()."', '".$order_id."')";
                $cmd=mysql_query($sql);
                if(!$cmd) {
                    sendNewMail("Ok.Net - vpos_fortis Kaydedilemedi","payment_finish.php-ödeme alındı ama kaydedilmedi-178.satir<br>".$sql);
                }
                echo "292277#Sipariş numaranız <b>".$order_code."</b><br><br>Ödemeniz için teşekkür ederiz siparişlerinizi hesap yönetiminizden kontrol edebilirsiniz.";
                //mail start
                $sql2="SELECT id, username, email, name, surname FROM ".TABLE_PREFIX."users WHERE id='".$userinfo[0]."'";
                $cmd2=mysql_query($sql2);
                $row2=mysql_fetch_array($cmd2);
                include_once("/usr/local/www/www.ok.net/include/mailtpl/general.php");
                include_once("/usr/local/www/www.ok.net/include/mailtpl/class.phpmailer.php");
                $mail = new PHPMailer();
                $mail->From = "bilgi@ok.net";
                $mail->FromName = "Ok.Net";
                if ($row2['email']) $mail->AddAddress($row2['email'], $row2['name']." ".$row2['surname']);
                $mail->AddAddress($row2['username']."@ok.net", $row2['name']." ".$row2['surname']);
                $mail->AddReplyTo("bilgi@ok.net", "Ok.Net");
                $mail->IsHTML(true);
                $mail->Subject = "Ok.Net Yeni Siparis";
                $mail->Body    = GenelMailTaslak("Ok.Net Yeni Siparis","<span class=\"ortaBaslik\">Sayın ".$row2['name']." ".$row2['surname']."</span><br>
<br><span class=\"yazi\">
<b>$order_code</b> numaralı siparişiniz kredi kartı ödeme seçenekli olarak tarafımıza ulaşmıştır.<br>
Siparişiniz Tutarı : <b>".number_format($total,2,'.',',')." $</b><br>
<br>
Yönetim panelinize giriş yapmak için <a href=\"http://internet.ok.net/yonetim.php?siparis\" target=\"_blank\"><span class=\"linkBold\">tıklayınız</span></a>.</span>");
                $mail->AltBody = "Sayın ".$row2['name']." ".$row2['surname']."\n
\n
                        $order_code numaralı siparişiniz kredi kartı ödeme seçenekli olarak tarafımıza ulaşmıştır.\n
Siparişiniz Tutarı : ".$total." $\n
\n
Yönetim panelinize giriş yapmak için http://internet.ok.net/yonetim.php?siparis adresine tıklayınız.";

                $mail->Send();
                //mail finish
                $tmp_status=14;
            }
        }
        else echo "292278#Kart bakiyesi geçersiz ya da yanlış kart no girdiniz. Lütfen başka bir kredi kartı ile deneyiniz.";
    }
    else {
        sendNewMail("Ok.Net - tutar sıfır","payment_finish.php-tutar =0 -224.satir<br>");
        echo "292278#Sepetinizle ilgili bir sorun var.Sepeti boşaltıp tekrar oluşturunuz.";
    }
}
else if ($_POST['payment_type']=="mtransfer") {
    $total=CalculateTotal()*GetCurrency();
    if (is_numeric($total) && $total>0) {
        $order_result=PutOrder("mtransfer",$total,$_POST['billing'],0,$_POST['bankcode']);
        if ($order_result==false) {
            sendNewMail("Ok.Net - putorder==false","payment_finish.php-putoder==false -234.satir<br>");
            echo "292278#Ödeme sayfasında problem oluştu.";
        }
        else {
            $order_code=$order_result['ordercode'];
            $order_id=$order_result['orderid'];
            echo "Sipariş numaranız <b>".$order_code."</b><br><br>Siparişinizin onaylanması için 3 (üç) gün içerisinde seçtiğiniz bankaya ödemenizi iletmeniz gerekmektedir.";

            //mail start
            $sql2="SELECT id, username, email, name, surname FROM ".TABLE_PREFIX."users WHERE id='".$userinfo[0]."'";
            $cmd2=mysql_query($sql2);
            $row2=mysql_fetch_array($cmd2);
            include_once("/usr/local/www/www.ok.net/include/mailtpl/general.php");
            include_once("/usr/local/www/www.ok.net/include/mailtpl/class.phpmailer.php");
            $mail = new PHPMailer();
            $mail->From = "bilgi@ok.net";
            $mail->FromName = "Ok.Net";
            if ($row2['email']) $mail->AddAddress($row2['email'], $row2['name']." ".$row2['surname']);
            $mail->AddAddress($row2['username']."@ok.net", $row2['name']." ".$row2['surname']);
            $mail->AddReplyTo("bilgi@ok.net", "Ok.Net");
            $mail->IsHTML(true);
            $mail->Subject = "Ok.Net Yeni Siparis";
            $mail->Body    = GenelMailTaslak("Ok.Net Yeni Siparis","<span class=\"ortaBaslik\">Sayın ".$row2['name']." ".$row2['surname']."</span><br>
<br><span class=\"yazi\">
<b>$order_code</b> numaralı siparişiniz banka havalesi ödeme seçenekli olarak tarafımıza ulaşmıştır. 3 (üç) gün içerisinde ödemenizi iletmemeniz durumunda siparişiniz iptal edilecektir.<br>
Siparişiniz Tutarı : <b>".number_format($total,2,'.',',')." $</b><br>
<br>
Yönetim panelinize giriş yapmak için <a href=\"http://internet.ok.net/yonetim.php?siparis\" target=\"_blank\"><span class=\"linkBold\">tıklayınız</span></a>.</span>");
            $mail->AltBody = "Sayın ".$row2['name']." ".$row2['surname']."\n
\n
                    $order_code numaralı siparişiniz banka havalesi ödeme seçenekli olarak tarafımıza ulaşmıştır. 3 (üç) gün içerisinde ödemenizi iletmemeniz durumunda siparişiniz iptal edilecektir.\n
Siparişiniz Tutarı : ".number_format($total,2,'.',',')." $\n
\n
Yönetim panelinize giriş yapmak için http://internet.ok.net/yonetim.php?siparis adresine tıklayınız.";

            $mail->Send();
            //mail finish
            $tmp_status=0;
        }
    }
    else {
        sendNewMail("Ok.Net - tutar sıfır","payment_finish.php-tutar =0 -275.satir<br>");
        echo "292278#Sepetinizle ilgili bir sorun var.Sepeti boşaltıp tekrar oluşturunuz.";
    }
}
else if ($_POST['payment_type']=="mailorder") {
    $total=CalculateTotal()*GetCurrency();
    if (is_numeric($total) && $total>0) {
        $order_result=PutOrder("mailorder",$total,$_POST['billing']);
        if ($order_result==false) {
            sendNewMail("Ok.Net - putorder==false","payment_finish.php-putorder==false -284.satir<br>");
            echo "292278#Ödeme sayfasında problem oluştu.";
        }
        else {
            $order_code=$order_result['ordercode'];
            $order_id=$order_result['orderid'];
            echo "Sipariş numaranız <b>".$order_code."</b><br><br>Siparişinizin onaylanması için 3 (üç) gün içerisinde mailorder formunuzu imzalı ve kaşeli olarak ya da kimlik fotokopinizle birlikte <b>0224 224 95 20</b> no.'lu faksa iletmeniz gerekmektedir.";
            //mail start
            $sql2="SELECT id, username, email, name, surname FROM ".TABLE_PREFIX."users WHERE id='".$userinfo[0]."'";
            $cmd2=mysql_query($sql2);
            $row2=mysql_fetch_array($cmd2);
            include_once("/usr/local/www/www.ok.net/include/mailtpl/general.php");
            include_once("/usr/local/www/www.ok.net/include/mailtpl/class.phpmailer.php");
            $mail = new PHPMailer();
            $mail->From = "bilgi@ok.net";
            $mail->FromName = "Ok.Net";
            if ($row2['email']) $mail->AddAddress($row2['email'], $row2['name']." ".$row2['surname']);
            $mail->AddAddress($row2['username']."@ok.net", $row2['name']." ".$row2['surname']);
            $mail->AddReplyTo("bilgi@ok.net", "Ok.Net");
            $mail->IsHTML(true);
            $mail->Subject = "Ok.Net Yeni Siparis";
            $mail->Body    = GenelMailTaslak("Ok.Net Yeni Siparis","<span class=\"ortaBaslik\">Sayın ".$row2['name']." ".$row2['surname']."</span><br>
<br><span class=\"yazi\">
<b>$order_code</b> numaralı siparişiniz mailorder ödeme seçenekli olarak tarafımıza ulaşmıştır. Siparişinizin onaylanması için 3 (üç) gün içerisinde mailorder formunuzu imzalı ve kaşeli olarak ya da kimlik fotokopinizle birlikte <b>0224 224 95 20</b> no.'lu faksa iletmeniz gerekmektedir.<br>
Siparişiniz Tutarı : <b>".number_format($total,2,'.',',')." $</b><br>
<br>
Yönetim panelinize giriş yapmak için <a href=\"http://internet.ok.net/yonetim.php?siparis\" target=\"_blank\"><span class=\"linkBold\">tıklayınız</span></a>.</span>");
            $mail->AltBody = "Sayın ".$row2['name']." ".$row2['surname']."\n
\n
                    $order_code numaralı siparişiniz mailorder ödeme seçenekli olarak tarafımıza ulaşmıştır. Siparişinizin onaylanması için 3 (üç) gün içerisinde mailorder formunuzu imzalı ve kaşeli olarak ya da kimlik fotokopinizle birlikte 0224 224 95 20 no.'lu faksa iletmeniz gerekmektedir.\n
Siparişiniz Tutarı : ".number_format($total,2,'.',',')." $\n
\n
Yönetim panelinize giriş yapmak için http://internet.ok.net/yonetim.php?siparis adresine tıklayınız.";

            $mail->Send();
            //mail finish
            $tmp_status=0;
        }
    }
    else {
        sendNewMail("Ok.Net - tutar sıfır","payment_finish.php-tutar =0 -324.satir<br>");
        echo "292278#Sepetinizle ilgili bir sorun var.Sepeti boşaltıp tekrar oluşturunuz.";
    }
}
else if ($_POST['payment_type']=="hstcredit") {
    $tmp_total=CalculateTotal();
    $total=$tmp_total*GetCurrency();
    if (is_numeric($total) && $total>0) {
        $query_total = mysql_query("SELECT hst_credit FROM ".TABLE_PREFIX."users WHERE id='".$userinfo[0]."';");
        $mt_tmp_total = mysql_fetch_array($query_total);
        if($mt_tmp_total["hst_credit"] >= $tmp_total) {
            $order_result=PutOrder("hstcredit",$total,$_POST['billing'],14);
            if ($order_result==false) {
                sendNewMail("Ok.Net - putorder==false","payment_finish.php-putorder==false -337.satir<br>");
                echo "292278#Ödeme sayfasında problem oluştu.";
            }
            else {
                $order_code=$order_result['ordercode'];
                $order_id=$order_result['orderid'];

                echo "Sipariş numaranız <b>".$order_code."</b><br><br>Ok.Net Kredi'nizden ".$tmp_total." kredi düşülmüştür.";
                $sql="UPDATE ".TABLE_PREFIX."users SET hst_credit=hst_credit-$tmp_total WHERE id='".$userinfo[0]."';";
                $cmd=mysql_query($sql);
                if(!$cmd) {
                    sendNewMail("Ok.Net - users credit güncellenemedi","payment_finish.php-kredi düşülmedi-304.satir<br>".$sql);
                }
                //mail start
                $sql2="SELECT id, username, email, name, surname FROM ".TABLE_PREFIX."users WHERE id='".$userinfo[0]."'";
                $cmd2=mysql_query($sql2);
                $row2=mysql_fetch_array($cmd2);
                include_once("/usr/local/www/www.ok.net/include/mailtpl/general.php");
                include_once("/usr/local/www/www.ok.net/include/mailtpl/class.phpmailer.php");
                $mail = new PHPMailer();
                $mail->From = "bilgi@ok.net";
                $mail->FromName = "Ok.Net";
                if ($row2['email']) $mail->AddAddress($row2['email'], $row2['name']." ".$row2['surname']);
                $mail->AddAddress($row2['username']."@ok.net", $row2['name']." ".$row2['surname']);
                $mail->AddReplyTo("bilgi@ok.net", "Ok.Net");
                $mail->IsHTML(true);
                $mail->Subject = "Ok.Net Yeni Siparis";
                $mail->Body    = GenelMailTaslak("Ok.Net Yeni Siparis","<span class=\"ortaBaslik\">Sayın ".$row2['name']." ".$row2['surname']."</span><br>
<br><span class=\"yazi\">
<b>$order_code</b> numaralı siparişiniz Ok.Net Kredi ödeme seçenekli olarak tarafımıza ulaşmıştır.<br>Ok.Net Kredi'nizden $tmp_total kredi düşülmüştür.<br>
Siparişiniz Tutarı : <b>".number_format($total,2,'.',',')." $</b><br>
<br>
Yönetim panelinize giriş yapmak için <a href=\"http://internet.ok.net/yonetim.php?siparis\" target=\"_blank\"><span class=\"linkBold\">tıklayınız</span></a>.</span>");
                $mail->AltBody = "Sayın ".$row2['name']." ".$row2['surname']."\n
\n
                        $order_code numaralı siparişiniz kredi Ok.Net Kredi seçenekli olarak tarafımıza ulaşmıştır.\nOk.Net Kredi'nizden $tmp_total kredi düşülmüştür.\n
Siparişiniz Tutarı : ".number_format($total,2,'.',',')." $\n
\n
Yönetim panelinize giriş yapmak için http://internet.ok.net/yonetim.php?siparis adresine tıklayınız.";

                $mail->Send();
                //mail finish
                $tmp_status=14;
            }
        }
        else echo "292278#Ödemenizi Ok.Net Kredi\'nizi kullanarak gerçekleştirmek için yeterli krediniz bulunmamaktadır.";
    }
    else {
        sendNewMail("Ok.Net - tutar sıfır","payment_finish.php-tutar =0 -385.satir<br>");
        echo "292278#Sepetinizle ilgili bir sorun var.Sepeti boşaltıp tekrar oluşturunuz.";
    }
}


if ($order_id) {
    if (count($_SESSION['basket_prods']['domain']['tld'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Uluslararası Alan Adı</b></td></tr>";
        foreach ($_SESSION['basket_prods']['domain']['tld'] as $value) {
            $relax1=explode("-",$_SESSION['quantity'][$value]);
            $relax2=explode("-",$_SESSION['domain_contacts'][$value]);

            $cost=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$relax1[1])*$relax1[1];
            if ($relax1[0]=="p") $cost+=2;
            $sql="INSERT INTO ".TABLE_PREFIX."pending_domain (id, order_id, type, domain, years, privacy, contact_registrant, contact_admin, contact_technical, contact_billing, status, own_cost, time) VALUES (NULL, '$order_id','tld','".$_SESSION['added_domains'][$value]."','".$relax1[1]."','".$relax1[0]."','".$relax2[0]."','".$relax2[1]."','".$relax2[2]."','".$relax2[3]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_domain kaydedilemedi","payment_finish.php-pending_domain kaydedilemedi-356.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".strtoupper($_SESSION['added_domains'][$value])."</td><td>".$relax1[1]."</td><td>".$cost."</td></tr>";
            if ($_SESSION['domain_offer'][$value]) {
                $value=$_SESSION['domain_offer'][$value];
                $relax1=explode("-",$_SESSION['quantity'][$value]);
                foreach ($_SESSION['basket_prods']['domain']['offer'][$value] as $ovalue) {
                    $relax2=explode("-",$_SESSION['domain_contacts'][$ovalue]);
                    $cost=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$ovalue])),$relax1[1])*$relax1[1];
                    if ($relax1[0]=="p") $cost+=2;
                    $cost=$cost*0.9;
                    $sql="INSERT INTO ".TABLE_PREFIX."pending_domain (id, order_id, type, domain, years, privacy, contact_registrant, contact_admin, contact_technical, contact_billing, status, own_cost, time) VALUES (NULL, '$order_id','offer','".$_SESSION['added_domains'][$ovalue]."','".$relax1[1]."','".$relax1[0]."','".$relax2[0]."','".$relax2[1]."','".$relax2[2]."','".$relax2[3]."','$tmp_status','".$cost."','".time()."');";
                    $cmd=mysql_query($sql);
                    if(!$cmd) {
                        sendNewMail("Ok.Net - pending_domain kaydedilemedi","payment_finish.php-pending_domain kaydedilemedi-370.satir<br>".$sql);
                    }
                    $mail_body.="<tr><td>".strtoupper($_SESSION['added_domains'][$value])."</td><td>".$relax1[1]."</td><td>".$cost."</td></tr>";
                }
            }
        }
    }

    if (count($_SESSION['basket_prods']['domain']['trf'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Alan Adı Transferi</b></td></tr>";
        foreach ($_SESSION['basket_prods']['domain']['trf'] as $value) {
            $relax1=explode("-",$_SESSION['quantity'][$value]);
            $relax2=explode("-",$_SESSION['domain_contacts'][$value]);

            $cost=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$relax1[1])*$relax1[1];
            if ($relax1[0]=="p") $cost+=2;
            $sql="INSERT INTO ".TABLE_PREFIX."pending_domain (id, order_id, type, domain, years, privacy, contact_registrant, contact_admin, contact_technical, contact_billing, status, own_cost, time) VALUES (NULL, '$order_id','trf','".$_SESSION['added_domains'][$value]."','".$relax1[1]."','".$relax1[0]."','".$relax2[0]."','".$relax2[1]."','".$relax2[2]."','".$relax2[3]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_domain kaydedilemedi","payment_finish.php-pending_domain kaydedilemedi-389.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".strtoupper($_SESSION['added_domains'][$value])."</td><td>".$relax1[1]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['domain']['trtrf'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>.TR Uzantılı Alan Adı Transferi</b></td></tr>";
        foreach ($_SESSION['basket_prods']['domain']['trtrf'] as $value) {
            //$relax1=explode("-",$_SESSION['quantity'][$value]);
            $relax2=explode("-",$_SESSION['domain_contacts'][$value]);

            $cost=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$_SESSION['quantity'][$value])*$_SESSION['quantity'][$value];
            $sql="INSERT INTO ".TABLE_PREFIX."pending_domain (id, order_id, type, domain, years, privacy, contact_registrant, contact_admin, contact_technical, contact_billing, status, own_cost, time) VALUES (NULL, '$order_id','trtrf','".$_SESSION['added_domains'][$value]."','".$_SESSION['quantity'][$value]."','t','".$relax2[0]."','".$relax2[1]."','".$relax2[2]."','".$relax2[3]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_domain kaydedilemedi","payment_finish.php-pending_domain kaydedilemedi-405.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".strtoupper($_SESSION['added_domains'][$value])."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['domain']['tr'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>.TR Uzantılı Alan Adı</b></td></tr>";
        foreach ($_SESSION['basket_prods']['domain']['tr'] as $value) {
            $relax2=explode("-",$_SESSION['domain_contacts'][$value]);
            $cost=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$_SESSION['quantity'][$value])*$_SESSION['quantity'][$value];
            $sql="INSERT INTO ".TABLE_PREFIX."pending_domain (id, order_id, type, domain, years, privacy, contact_registrant, contact_admin, contact_technical, contact_billing, status, own_cost, time) VALUES (NULL, '$order_id','tr','".$_SESSION['added_domains'][$value]."','".$_SESSION['quantity'][$value]."','t','".$relax2[0]."','".$relax2[1]."','".$relax2[2]."','".$relax2[3]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_domain kaydedilemedi","payment_finish.php-pending_domain kaydedilemedi-418.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".strtoupper($_SESSION['added_domains'][$value])."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['domain']['rnw'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Alan Adı Yenileme</b></td></tr>";
        foreach ($_SESSION['basket_prods']['domain']['rnw'] as $value) {
            $relax=explode("-",$_SESSION['renew_domain'][$value]);
            if ($relax[0]=="d") $tmp_table="pending_domain";
            else if ($relax[0]=="e") $tmp_table="pending_emaild";
            $domain=strtoupper(GetMasterInfo($tmp_table,"id",$relax[1],"domain"));
            if (strstr($domain,".TR")) $tmp_type="trrnw";
            else $tmp_type="tldrnw";
            $cost=GenerateDomainCost(GenerateDomainKey(GetDomExt($domain)),$_SESSION['quantity'][$value])*$_SESSION['quantity'][$value];
            $sql="INSERT INTO ".TABLE_PREFIX."pending_domrenew (id, order_id, type, prod_type, prod_id, years, status, own_cost, time) VALUES (NULL, '$order_id','$tmp_type','".$relax[0]."','".$relax[1]."', '".$_SESSION['quantity'][$value]."', '$tmp_status', '".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_domrenew kaydedilemedi","payment_finish.php-pending_domrenew kaydedilemedi-438.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".strtoupper($domain)."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['hosting'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Web Alanı</b></td></tr>";
        foreach ($_SESSION['basket_prods']['hosting'] as $value) {
            $relax1=explode("-",$_SESSION['added_hostings'][$value]);
            $relax2=explode("-",$_SESSION['quantity'][$value]);
            if ($relax1[0]=="manual") {
                $relax3=explode("-",$_SESSION['added_manhostings'][$value]);
                $cost=GenerateTmpManualHostingCost($relax3[0],$relax3[1],$relax3[2],$relax3[3],$relax3[4],$relax2[0],$relax2[1]);
                $sql="INSERT INTO ".TABLE_PREFIX."pending_mhosting (id, order_id, conf_web, conf_traffic, conf_mails, conf_mailbox, conf_db, server_type, pay_period, quantity, status, own_cost, time) VALUES (NULL,'$order_id','".$relax3[0]."','".$relax3[1]."','".$relax3[2]."','".$relax3[3]."','".$relax3[4]."','".$relax1[1]."','".$relax2[0]."','".$relax2[1]."','$tmp_status','".$cost."','".time()."');";
                $cmd=mysql_query($sql);
                if(!$cmd) {
                    sendNewMail("Ok.Net - pending_mhosting kaydedilemedi","payment_finish.php-pending_mhosting kaydedilemedi-455.satir<br>".$sql);
                }
                $mail_body.="<tr><td>Kendi Paketini Oluştur<br>".
                        $relax3[0]."Mb Web Alanı<br>".$relax3[1]." Gb Trafik<br>".$relax3[2]." Adet E-posta<br>".$relax3[3]."Mb E-Posta Alanı";
                if ($relax3[4]>0) $mail_body.="<br>".$relax3[4]." Adet Veritabanı";
                $mail_body.="<br>".$var_server_types[$relax1[1]]." Sunucu</td><td>".$relax2[1]."</td><td>".$cost."</td></tr>";
            }
            else {
                $cost=GenerateTmpHostingCost($relax1[0],$relax2[0],$relax2[1]);
                $sql="INSERT INTO ".TABLE_PREFIX."pending_rhosting (id, order_id, packet_id, server_type, pay_period, quantity, status, own_cost, time ) VALUES (NULL, '$order_id','".$relax1[0]."','".$relax1[1]."','".$relax2[0]."','".$relax2[1]."','$tmp_status','".$cost."','".time()."');";
                $cmd=mysql_query($sql);
                if(!$cmd) {
                    sendNewMail("Ok.Net - pending_rhosting kaydedilemedi","payment_finish.php-pending_rhosting kaydedilemedi-466.satir<br>".$sql);
                }
                $mail_body.="<tr><td>".$var_active_packages[$relax1[0]]."<br>".$var_server_types[$relax1[1]]." Sunucu</td><td>".$relax2[1]."</td><td>".$cost."</td></tr>";
            }

        }
    }

    if (count($_SESSION['basket_prods']['hstextra'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Web Alanı Ek Özellik</b></td></tr>";
        foreach ($_SESSION['basket_prods']['hstextra'] as $value) {
            $relax1=explode("-",$_SESSION['renew_domain'][$value]);
            $relax2=explode("-",$_SESSION['quantity'][$value]);
            if ($relax1[0]=="m") {
                $tmp_table="pending_mhosting";
            }
            else if ($relax1[0]=="r") {
                $tmp_table="pending_rhosting";
            }
            $sql="SELECT plesk_id, plesk_domain FROM ".TABLE_PREFIX.$tmp_table." WHERE id='".$relax1[1]."'";
            $cmd=mysql_query($sql);
            $num=mysql_num_rows($cmd);
            if ($num==1) {
                $row=mysql_fetch_array($cmd);
                $domain_id=$row['plesk_id'];
                $domain_name=$row['plesk_domain'];
            }
            $details=PleskDomainDetails($domain_id,1,0,0,0,1,0);
            $expiration=$details->limits->limit[1]->value;
            $oneyear=365*24*60*60;
            $diff=$expiration-time();

            $cost=(GenerateManualHostingCost($relax2[0],$relax2[1],$relax2[2],$relax2[3],$relax2[4])*$diff)/$oneyear;

            $sql="INSERT INTO ".TABLE_PREFIX."hosting_extra (id, order_id, hst_type, hst_id, conf_web, conf_traffic, conf_mails, conf_mailbox, conf_db, status, own_cost, time ) VALUES ( NULL, '$order_id','".$relax1[0]."','".$relax1[1]."','".$relax2[0]."','".$relax2[1]."','".$relax2[2]."','".$relax2[3]."','".$relax2[4]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - hosting_extra kaydedilemedi","payment_finish.php-hosting_extra kaydedilemedi-503.satir<br>".$sql);
            }
            $mail_body.="<tr><td>Ek Özellik</td><td>";
            if ($relax2[0]>0) $mail_body.=$relax2[0]."MB Web Alanı<br>";
            if ($relax2[1]>0) $mail_body.=$relax2[1]."GB Trafik<br>";
            if ($relax2[2]>0) $mail_body.=$relax2[2]." Adet E-Posta<br>";
            if ($relax2[3]>0) $mail_body.=$relax2[3]."MB E-Posta Alanı<br>";
            if ($relax2[4]>0) $mail_body.=$relax2[4]." Adet Veritabanı";
            $mail_body.="</td><td>".number_format($cost,2,'.',',')."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['hstrenew'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Web Alanı Yenileme</b></td></tr>";
        foreach ($_SESSION['basket_prods']['hstrenew'] as $value) {
            $relax1=explode("-",$_SESSION['renew_domain'][$value]);

            if ($relax1[0]=="m") {
                $sql="SELECT id, conf_web, conf_traffic, conf_mails, conf_mailbox, conf_db, plesk_domain FROM ".TABLE_PREFIX."pending_mhosting WHERE id='".$relax1[1]."'";
                $cmd=mysql_query($sql);
                $num=mysql_num_rows($cmd);
                if ($num==1) {
                    $row=mysql_fetch_array($cmd);
                    $cost=GenerateTmpManualHostingCost($row['conf_web'],$row['conf_traffic'],$row['conf_mails'],$row['conf_mailbox'],$row['conf_db'],"year",$_SESSION['quantity'][$value]);
                    $sql2="SELECT * FROM ".TABLE_PREFIX."hosting_extra WHERE hst_type='m' AND hst_id='".$row['id']."' AND status='1'";
                    $cmd2=mysql_query($sql2);
                    $num2=mysql_num_rows($cmd2);
                    if ($num2>0) {
                        while ($row2=mysql_fetch_array($cmd2)) {
                            $cost+=GenerateTmpManualHostingCost($row2['conf_web'],$row2['conf_traffic'],$row2['conf_mails'],$row2['conf_mailbox'],$row2['conf_db'],"year",$_SESSION['quantity'][$value]);
                        }
                    }
                }
            }
            else if ($relax1[0]=="r") {
                $sql="SELECT id, packet_id, plesk_domain FROM ".TABLE_PREFIX."pending_rhosting WHERE id='".$relax1[1]."'";
                $cmd=mysql_query($sql);
                $num=mysql_num_rows($cmd);
                if ($num==1) {
                    $row=mysql_fetch_array($cmd);
                    $cost=GenerateTmpHostingCost($row['packet_id'],"year",$_SESSION['quantity'][$value]);
                    $sql2="SELECT * FROM ".TABLE_PREFIX."hosting_extra WHERE hst_type='r' AND hst_id='".$row['id']."' AND status='1'";
                    $cmd2=mysql_query($sql2);
                    $num2=mysql_num_rows($cmd2);
                    if ($num2>0) {
                        while ($row2=mysql_fetch_array($cmd2)) {
                            $cost+=GenerateTmpManualHostingCost($row2['conf_web'],$row2['conf_traffic'],$row2['conf_mails'],$row2['conf_mailbox'],$row2['conf_db'],"year",$_SESSION['quantity'][$value]);

                        }
                    }
                }
            }
            $mail_body.="<tr><td>".strtoupper($row['plesk_domain'])."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";

            $sql="INSERT INTO ".TABLE_PREFIX."pending_hstrenew (id, order_id, hst_type, hst_id, quantity, status, own_cost, time) VALUES (NULL, '$order_id','".$relax1[0]."','".$relax1[1]."', '".$_SESSION['quantity'][$value]."', '$tmp_status', '".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_hstrenew kaydedilemedi","payment_finish.php-556.satir<br>".$sql);
            }
        }
    }

    if (count($_SESSION['basket_prods']['vendor'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Bayi Paketi</b></td></tr>";
        foreach ($_SESSION['basket_prods']['vendor'] as $value) {
            $relax1=explode("-",$_SESSION['added_vndpacks'][$value]);
            $cost=GetVendorCost($relax1[0],$_SESSION['quantity'][$value]);
            $mail_body.="<tr><td>".$var_vendor_packages[$relax1[0]]."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
            $sql="INSERT INTO ".TABLE_PREFIX."pending_vendor (id, order_id, packet_id, server_type, quantity, status, own_cost, time) VALUES (NULL,'$order_id','".$relax1[0]."','".$relax1[1]."','".$_SESSION['quantity'][$value]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_vendor kaydedilemedi","payment_finish.php-570.satir<br>".$sql);
            }
        }
    }

    if (count($_SESSION['basket_prods']['vndextra'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Bayi Paketi Ek Özellik</b></td></tr>";
        foreach ($_SESSION['basket_prods']['vndextra'] as $value) {
            $client_id=GetMasterInfo("pending_vendor","id",$_SESSION['renew_domain'][$value],"plesk_id");
            $details=PleskClientDetails($client_id,1,0,0,1,0);
            $expiration=$details->limits->limit[1]->value;
            $oneyear=365*24*60*60;
            $diff=$expiration-time();
            $relax1=explode("-",$_SESSION['quantity'][$value]);
            $cost=(GenerateManualVendorCost($relax1[0],$relax1[1],$relax1[2],$relax1[3],$relax1[4],1,false)*$diff)/$oneyear;
            $sql="INSERT INTO ".TABLE_PREFIX."vendor_extra (id, order_id, hst_id, conf_dom, conf_web, conf_traffic, conf_mails, conf_db, status, own_cost, time ) VALUES ( NULL, '$order_id','".$_SESSION['renew_domain'][$value]."','".$relax1[0]."','".$relax1[1]."','".$relax1[2]."','".$relax1[3]."','".$relax1[4]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - vendor_ kaydedilemedi","payment_finish.php-588.satir<br>".$sql);
            }
            $mail_body.="<tr><td>Ek Özellik</td><td>";
            if ($relax1[0]>0) $mail_body.=$relax1[0]." Adet Alan Adı<br>";
            if ($relax1[1]>0) $mail_body.=$relax1[1]."MB Web Alanı<br>";
            if ($relax1[2]>0) $mail_body.=$relax1[2]."GB Trafik<br>";
            if ($relax1[3]>0) $mail_body.=$relax1[3]." Adet E-Posta<br>";
            if ($relax1[4]>0) $mail_body.=$relax1[4]." Adet Veritabanı";
            $mail_body.="</td><td>$cost</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['vndrenew'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Bayi Paketi Yenileme</b></td></tr>";
        foreach ($_SESSION['basket_prods']['vndrenew'] as $value) {

            $packet_id=GetMasterInfo("pending_vendor","id",$_SESSION['renew_domain'][$value],"packet_id");

            $cost=GetVendorCost($packet_id,$_SESSION['quantity'][$value]);
            $sql2="SELECT * FROM ".TABLE_PREFIX."vendor_extra WHERE hst_id='".$_SESSION['renew_domain'][$value]."' AND status='1'";
            $cmd2=mysql_query($sql2);
            $num2=mysql_num_rows($cmd2);
            if ($num2>0) {
                while ($row2=mysql_fetch_array($cmd2)) {
                    $cost+=GenerateManualVendorCost($row2['conf_dom'],$row2['conf_web'],$row2['conf_traffic'],$row2['conf_mails'],$row2['conf_db'],$_SESSION['quantity'][$value],false);
                }
            }
            $sql="INSERT INTO ".TABLE_PREFIX."pending_vndrenew (id, order_id, hst_id, quantity, status, own_cost, time) VALUES (NULL, '$order_id','".$_SESSION['renew_domain'][$value]."', '".$_SESSION['quantity'][$value]."', '$tmp_status', '".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_vndrenew kaydedilemedi","payment_finish.php-614.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".$var_vendor_packages[$packet_id]."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['email'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>E-Posta</b></td></tr>";
        foreach ($_SESSION['basket_prods']['email'] as $value) {
            if (strstr($_SESSION['quantity'][$value],"-")) {
                $relax1=explode("-",$_SESSION['quantity'][$value]);
                $relax2=explode("-",$_SESSION['domain_contacts'][$value]);
                $cost=GetEmailCost($_SESSION['added_emails'][$value],$relax1[1]);
                $cost+=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$relax1[1])*$relax1[1];
                if ($relax1[0]=="p") $cost+=2;
                if (strstr($_SESSION['added_domains'][$value],".TR")) $type="tr";
                else $type="tld";
                $sql="INSERT INTO ".TABLE_PREFIX."pending_emaild (id, order_id, packet_id, domain, years, privacy, contact_registrant, contact_admin, contact_technical, contact_billing, status, own_cost, time, type) VALUES (NULL, '$order_id','".$_SESSION['added_emails'][$value]."','".$_SESSION['added_domains'][$value]."','".$relax1[1]."','".$relax1[0]."','".$relax2[0]."','".$relax2[1]."','".$relax2[2]."','".$relax2[3]."','$tmp_status','".$cost."','".time()."','$type');";
                $cmd=mysql_query($sql);
                if(!$cmd) {
                    sendNewMail("Ok.Net - pending_emaild kaydedilemedi","payment_finish.php-634.satir<br>".$sql);
                }
                $mail_body.="<tr><td>".$var_email_packages[$_SESSION['added_emails'][$value]]." + ".strtoupper($_SESSION['added_domains'][$value])."</td><td>".$relax1[1]."</td><td>".$cost."</td></tr>";
            }
            elseif (strstr($_SESSION['quantity'][$value],"|")) {
                $relax1=explode("|",$_SESSION['quantity'][$value]);
                $relax2=explode("-",$_SESSION['domain_contacts'][$value]);
                if(strstr($_SESSION['added_domains'][$value], ".COM.TR"))
                    $cost=24.00 * $relax1[1];
                else
                    $cost=12.00 * $relax1[1];
                if ($relax1[0]=="p") $cost+=2;
                if (strstr($_SESSION['added_domains'][$value],".TR")) $type="pck1-tr";
                else $type="pck1";
                $sql="INSERT INTO ".TABLE_PREFIX."pending_emaild (id, order_id, packet_id, domain, years, privacy, contact_registrant, contact_admin, contact_technical, contact_billing, status, own_cost, time, type) VALUES (NULL, '$order_id','".$_SESSION['added_emails'][$value]."','".$_SESSION['added_domains'][$value]."','".$relax1[1]."','".$relax1[0]."','".$relax2[0]."','".$relax2[1]."','".$relax2[2]."','".$relax2[3]."','$tmp_status','".$cost."','".time()."','$type');";
                $cmd=mysql_query($sql);
                if(!$cmd) {
                    sendNewMail("Ok.Net - pending_emaild kaydedilemedi","payment_finish.php-651.satir<br>".$sql);
                }
                $mail_body.="<tr><td>Google Adwords Hediyeli Alan Adı + E-Posta ".strtoupper($_SESSION['added_domains'][$value])."</td><td>".$relax1[1]."</td><td>".$cost."</td></tr>";
            }
            else {
                $cost=GetEmailCost($_SESSION['added_emails'][$value],$_SESSION['quantity'][$value]);
                $sql="INSERT INTO ".TABLE_PREFIX."pending_emailr (id, order_id, packet_id, quantity, status, own_cost, time) VALUES (NULL,'$order_id','".$_SESSION['added_emails'][$value]."','".$_SESSION['quantity'][$value]."','$tmp_status','".$cost."','".time()."');";
                $cmd=mysql_query($sql);
                if(!$cmd) {
                    sendNewMail("Ok.Net - pending_emailr kaydedilemedi","payment_finish.php-660.satir<br>".$sql);
                }
                $mail_body.="<tr><td>".$var_email_packages[$_SESSION['added_emails'][$value]]."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
            }

        }
    }

    if (count($_SESSION['basket_prods']['emlextra'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>E-Posta Ek Özellik</b></td></tr>";
        foreach ($_SESSION['basket_prods']['emlextra'] as $value) {
            $relax1=explode("-",$_SESSION['renew_domain'][$value]);
            $relax2=explode("-",$_SESSION['quantity'][$value]);

            $cost=GenerateManualEmailCost($relax2[0],$relax2[1]);
            $sql="INSERT INTO ".TABLE_PREFIX."email_extra (id, order_id, hst_type, hst_id, conf_mails, conf_mailbox, status, own_cost, time ) VALUES ( NULL, '$order_id','".$relax1[0]."','".$relax1[1]."','".$relax2[0]."','".$relax2[1]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - email_extra kaydedilemedi","payment_finish.php-678.satir<br>".$sql);
            }
            $mail_body.="<tr><td>Ek Özellik</td><td>";
            if ($relax2[0]>0) $mail_body.=$relax2[0]." Adet E-Posta<br>";
            if ($relax2[1]>0) $mail_body.=$relax2[1]."MB E-Posta Alanı";
            $mail_body.="</td><td>$cost</td></tr>";

        }
    }

    if (count($_SESSION['basket_prods']['emlrenew'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>E-Posta Yenileme</b></td></tr>";
        foreach ($_SESSION['basket_prods']['emlrenew'] as $value) {
            $relax1=explode("-",$_SESSION['renew_domain'][$value]);

            if ($relax1[0]=="d") {
                $sql="SELECT id, packet_id, domain FROM ".TABLE_PREFIX."pending_emaild WHERE id='".$relax1[1]."'";
                $cmd=mysql_query($sql);
                $num=mysql_num_rows($cmd);
                if ($num==1) {
                    $row=mysql_fetch_array($cmd);
                    $domain=$row['domain'];
                    $cost=GetEmailCost($row['packet_id'],$_SESSION['quantity'][$value],true);
                    $sql2="SELECT * FROM ".TABLE_PREFIX."email_extra WHERE hst_type='d' AND hst_id='".$row['id']."' AND status='1'";
                    $cmd2=mysql_query($sql2);
                    $num2=mysql_num_rows($cmd2);
                    if ($num2>0) {
                        while ($row2=mysql_fetch_array($cmd2)) {
                            $cost+=GetEmailCost($row2['conf_mails']."-".$row2['conf_mailbox'],$_SESSION['quantity'][$value],true);
                        }
                    }
                }
            }
            else if ($relax1[0]=="r") {
                $sql="SELECT id, packet_id, plesk_domain FROM ".TABLE_PREFIX."pending_emailr WHERE id='".$relax1[1]."'";
                $cmd=mysql_query($sql);
                $num=mysql_num_rows($cmd);
                if ($num==1) {
                    $row=mysql_fetch_array($cmd);
                    $domain=$row['plesk_domain'];
                    $total+=GetEmailCost($row['packet_id'],$_SESSION['quantity'][$value],true);
                    $sql2="SELECT * FROM ".TABLE_PREFIX."email_extra WHERE hst_type='r' AND hst_id='".$row['id']."' AND status='1'";
                    $cmd2=mysql_query($sql2);
                    $num2=mysql_num_rows($cmd2);
                    if ($num2>0) {
                        while ($row2=mysql_fetch_array($cmd2)) {
                            $total+=GetEmailCost($row2['conf_mails']."-".$row2['conf_mailbox'],$_SESSION['quantity'][$value],true);
                        }
                    }
                }
            }
            $sql="INSERT INTO ".TABLE_PREFIX."pending_emlrenew (id, order_id, hst_type, hst_id, quantity, status, own_cost, time) VALUES (NULL, '$order_id','".$relax1[0]."','".$relax1[1]."', '".$_SESSION['quantity'][$value]."', '$tmp_status', '".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_emlrenew kaydedilemedi","payment_finish.php-731.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".strtoupper($domain)."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['ssl'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>SSL Sertifikaları</b></td></tr>";
        foreach ($_SESSION['basket_prods']['ssl'] as $value) {
            $cost=GetSslCost($_SESSION['added_sslcerts'][$value]);
            $sql="INSERT INTO ".TABLE_PREFIX."pending_ssl (id, order_id, packet_id, quantity, status, own_cost, time) VALUES (NULL,'$order_id','".$_SESSION['added_sslcerts'][$value]."','".$_SESSION['quantity'][$value]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_ssl kaydedilemedi","payment_finish.php-744.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".$var_ssl_certs[$_SESSION['added_sslcerts'][$value]]."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['secrd'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Arama Motoru Kayıt Kredileri</b></td></tr>";
        foreach ($_SESSION['basket_prods']['secrd'] as $value) {
            $cost=GetSeCreditCost($_SESSION['added_secrds'][$value]);
            $sql="INSERT INTO ".TABLE_PREFIX."pending_secrd (id, order_id, packet_id, quantity, status, own_cost, time) VALUES (NULL,'$order_id','".$_SESSION['added_secrds'][$value]."','".$_SESSION['quantity'][$value]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_secrd kaydedilemedi","payment_finish.php-757.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".$var_se_credits[$_SESSION['added_secrds'][$value]]."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['server']['col'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Sunucu Bulundurma</b></td></tr>";
        foreach ($_SESSION['basket_prods']['server']['col'] as $value) {
            $cost=GetColocationCost($_SESSION['added_servers'][$value],$_SESSION['quantity'][$value]);
            $sql="INSERT INTO ".TABLE_PREFIX."pending_scolocation (id, order_id, packet_id, period, status, own_cost, time) VALUES (NULL,'$order_id','".$_SESSION['added_servers'][$value]."','".$_SESSION['quantity'][$value]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_scolo kaydedilemedi","payment_finish.php-770.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".$var_server_colocation[$_SESSION['added_servers'][$value]]."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['server']['lea'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Sunucu Kiralama</b></td></tr>";
        foreach ($_SESSION['basket_prods']['server']['lea'] as $value) {
            $cost=GetLeasingCost($_SESSION['added_servers'][$value],$_SESSION['quantity'][$value]);
            $sql="INSERT INTO ".TABLE_PREFIX."pending_sleasing (id, order_id, packet_id, period, status, own_cost, time) VALUES (NULL,'$order_id','".$_SESSION['added_servers'][$value]."','".$_SESSION['quantity'][$value]."','$tmp_status','".$cost."','".time()."');";
            $cmd=mysql_query($sql);
            if(!$cmd) {
                sendNewMail("Ok.Net - pending_slea kaydedilemedi","payment_finish.php-783.satir<br>".$sql);
            }
            $mail_body.="<tr><td>".$var_server_leasing[$_SESSION['added_servers'][$value]]."</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
        }
    }

    if (count($_SESSION['basket_prods']['crd'])>0) {
        $mail_body.="<tr><td colspan=\"3\"><b>Ok.Net Kredileri</b></td></tr>";
        $cost=(0.96*$_SESSION['quantity'][$_SESSION['basket_prods']['crd']]);
        $sql="INSERT INTO ".TABLE_PREFIX."pending_credit (id, order_id, quantity, status, own_cost, time) VALUES (NULL,'$order_id','".$_SESSION['quantity'][$_SESSION['basket_prods']['crd']]."','$tmp_status','".$cost."','".time()."');";
        $cmd=mysql_query($sql);
        if(!$cmd) {
            sendNewMail("Ok.Net - pending_credit kaydedilemedi","payment_finish.php-795.satir<br>".$sql);
        }
        $mail_body.="<tr><td>Ok.Net Kredi</td><td>".$_SESSION['quantity'][$value]."</td><td>".$cost."</td></tr>";
    }

    $mail2 = new PHPMailer();
    $mail2->From = "siparis@ok.net";
    $mail2->FromName = "Ok.Net Siparis";
    if ($userinfo[0]=='1') $mail2->AddAddress("ersin.gencer@turkticaret.net");
    else {
        $mail2->AddAddress("pinar@turkticaret.net");
        $mail2->AddAddress("agokhan@turkticaret.net");
        $mail2->AddAddress("murat.yaniklar@turkticaret.net");
        $mail2->AddAddress("serap.tasdelen@turkticaret.net");
        $mail2->AddAddress("duygu.barikan@turkticaret.net");
        $mail2->AddAddress("siparis@ok.net");
        $mail2->AddAddress("aker.canguzel@turkticaret.net");
        $mail2->AddAddress("hulya.capali@turkticaret.net");
    }
    $mail2->IsHTML(false);
    $mail2->Subject = "Ok.Net Yeni Siparis #$order_code";

    $mail2->Body = "<b>Yeni Sipariş</b><br><br>
<b>Üyelik Numarası:</b> ".$userinfo[2]."<br>
<b>Kullanıcı Adı:</b> ".$userinfo[1]."<br>
<b>Adı:</b> ".$userinfo[3]."<br>
<b>Soyadı:</b> ".$userinfo[4]."<br><br>
<b>Sipariş Ayrıntıları</b><br>
<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">
            $mail_body
</table>
<br><br>
<b>Ödeme Tipi :</b> ".$var_payment_types[$_POST['payment_type']];
    if ($_POST['payment_type']=="mtransfer") $mail2->Body .= " - [".BankIdToProperty($_POST['bankcode'])." / ".$var_account_types[BankIdToProperty($_POST['bankcode'],"account_type")]." / ".BankIdToProperty($_POST['bankcode'],"office_code")."-".BankIdToProperty($_POST['bankcode'],"account_code")."]";
    $mail2->Body .= "<br>
<b>Toplam Tutar :</b> ".number_format($total,2,'.',',')." $<br>
<b>Tarihi :</b> ".date("d/m/Y H:i:s")."<br>
<b>IP :</b> ".$_SERVER['REMOTE_ADDR'];

    $mail2->AltBody = "Yeni Sipariş\n\n
Üyelik Numarası: ".$userinfo[2]."\n
Kullanıcı Adı: ".$userinfo[1]."\n
Adı: ".$userinfo[3]."\n
Soyadı: ".$userinfo[4]."\n\n
            $mail_body
\n\n
Ödeme Tipi : ".$var_payment_types[$_POST['payment_type']];
    if ($_POST['payment_type']=="mtransfer") $mail2->AltBody .= " - [".BankIdToProperty($_POST['bankcode'])." / ".$var_account_types[BankIdToProperty($_POST['bankcode'],"account_type")]." / ".BankIdToProperty($_POST['bankcode'],"office_code")."-".BankIdToProperty($_POST['bankcode'],"account_code")."]";
    $mail2->AltBody .="\n
Toplam Tutar : ".number_format($total,2,'.',',')." $\n
Tarihi : ".date("d/m/Y H:i:s")."\n
IP : ".$_SERVER['REMOTE_ADDR'];

    $mail2->Send();

}

?>
