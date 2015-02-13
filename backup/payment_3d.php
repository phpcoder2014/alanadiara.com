<?php
session_start();
error_reporting(0);

include_once('inc/dbMysql.php');
include_once('inc/func.php');

$db=new dbMysql();
$user_id=$_SESSION['net_users']['id'];

define("POS_3D_URL", "https://vpos.est.com.tr/servlet/cc5ApiServer");
define("POS_3D_GATEURL", "https://vpos.est.com.tr/servlet/est3Dgate");

define("POS_3D_OKURL", "https://www.alanadiara.com/payment_3d.php");
define("POS_3D_FAILURL", "https://www.alanadiara.com/payment_3d.php");

define("POS_3D_CLIENTID", "105312710"); // //722041010
define("POS_3D_CURRENCY", "949");
define("POS_3D_NAME", "KO1212api"); // //adhood
define("POS_3D_PASS", "34KO08yt"); // //yUtZ123y
define("POS_3D_STOREKEY", "3DKo254e"); //  //zU12Tt43
define("POS_3D_STORETYPE", "3d");

$place = "https://www.alanadiara.com/register.php";//register.php olacak

header("Content-Type: text/html; charset=UTF8");

if (!isset($_SESSION['domBasket'])) { //session yok
    $_SESSION["3d_error"] = 1;
    $_SESSION["3d_message"] = "Geçersiz işlem yaptınız.Lütfen tekrar deneyiniz.";
    header("Location: " . $place);
    exit();
}
if (empty($user_id)) {
    $_SESSION["3d_error"] = 1;
    $_SESSION["3d_message"] = "Lütfen Giriş Yapınız.";
    header("Location: " . $place);
    exit();
}


function PutOrder($payment_type,$total_cost,$billing,$status=0,$willpay=0) {

    if (!is_numeric($total_cost) || $total_cost<=0) return false;
    $db=new dbMysql();
    //$code=GetUniqueOrderCode();
    $code=time();

    switch ($payment_type) {
        case "ccard":
            $recieved_payment=$total_cost;
            $ordacttime=time();
            break;
        case "bank":
            $recieved_payment=0.00;
            $ordacttime=null;
            break;
        case "mail_order":
            $recieved_payment=0.00;
            $ordacttime=null;
            break;
    }
    $serialDomainID=serialize($_SESSION['domBasket']);
    $table=array('costumer_id'=>$_SESSION['net_users']['id'], 'order_code'=>$code, 'payment_type'=>$payment_type, 'total_cost'=>$total_cost,'status'=>$status, 'willpay'=>$willpay, 'billing'=>$billing, 'time'=>time(), 'act_time'=>$ordacttime, 'recieved_payment'=>$recieved_payment,'domainID'=>$serialDomainID);
    $orderID=$db->insert("orders", $table);
    //$sql="INSERT INTO orders () VALUES ('','".$_SESSION['net_users']['id']."','$code','$payment_type','$total_cost','$status','$willpay','$billing','".time()."', '$recieved_payment');";
    //$cmd=mysql_query($sql);
    if(!$orderID) {
        echo "Siparişiniz Kaydedilemedi.";
        return false;
    }
    else {
        return array("orderid"=>$orderID,"ordercode"=>$code);
    }
}

/////// Burdan sonrası değişmez.
$clientId = POS_3D_CLIENTID;
$oid = "";

$name = POS_3D_NAME;
$password = POS_3D_PASS;

$okUrl = POS_3D_OKURL;
$failUrl = POS_3D_FAILURL;

$rnd = microtime();

$storekey = POS_3D_STOREKEY;
$storetype = POS_3D_STORETYPE;

if(isset($_POST["mdStatus"])) {
        //sendMail(print_r($_POST, true),"erden.gencer@turkticaret.net","erden");
    $mdStatus = $_POST["mdStatus"];
    $_SESSION["3d_error"] = 0;

    if($mdStatus == 1) {
        // Hash Check
        $hashparams = $_POST["HASHPARAMS"];
        $hashparamsval = $_POST["HASHPARAMSVAL"];
        $hashparam = $_POST["HASH"];
        $paramsval = "";
        $index1 = 0;
        $index2 = 0;
        
        while($index1 < strlen($hashparams)) {
            $index2 = strpos($hashparams, ":", $index1);
            $vl = $_POST[substr($hashparams, $index1, $index2 - $index1)];
            if($vl == null)
                $vl = "";
            $paramsval = $paramsval . $vl;
            $index1 = $index2 + 1;
        }
        
        $hashval = $paramsval.$storekey;
        $hash = base64_encode(pack('H*',sha1($hashval)));
        
        if($paramsval != $hashparamsval || $hashparam != $hash) {
            $_SESSION["3d_error"] = 1;
            $_SESSION["3d_message"] = "Geçersiz işlem yaptınız.Lütfen tekrar deneyiniz.";
            header("Location: " . $place);
            exit;
        }
        elseif (!is_numeric($_POST["amount"]) || $_POST["amount"]<=0) {
            $_SESSION["3d_error"] = 1;
            $_SESSION["3d_message"] = "Geçersiz işlem yaptınız.Lütfen tekrar deneyiniz.";
            header("Location: " . $place);
            exit;
        }
        else {
            // 3D Ok. Ödeme Al
            $mode = "P";
            $type = "Auth";
            $expires = $_POST["Ecom_Payment_Card_ExpDate_Month"] . "/" . $_POST["Ecom_Payment_Card_ExpDate_Year"];
            $cv2 = $_POST['cv2'];
            $tutar = $_POST["amount"];
            $taksit = "";
            $oid = $_POST['oid'];
            $lip = GetHostByName($REMOTE_ADDR);
            $email = "";
            
            $mdStatus = $_POST['mdStatus'];
            $xid = $_POST['xid'];
            $eci = $_POST['eci'];
            $cavv = $_POST['cavv'];
            $md = $_POST['md'];
            
            // XML request sablonu
            $request = "DATA=<?xml version=\"1.0\" encoding=\"ISO-8859-9\"?>".
                    "<CC5Request>".
                    "<Name>{NAME}</Name>".
                    "<Password>{PASSWORD}</Password>".
                    "<ClientId>{CLIENTID}</ClientId>".
                    "<IPAddress>{IP}</IPAddress>".
                    "<Email>{EMAIL}</Email>".
                    "<Mode>P</Mode>".
                    "<OrderId>{OID}</OrderId>".
                    "<GroupId></GroupId>".
                    "<TransId></TransId>".
                    "<UserId></UserId>".
                    "<Type>{TYPE}</Type>".
                    "<Number>{MD}</Number>".
                    "<Expires></Expires>".
                    "<Cvv2Val></Cvv2Val>".
                    "<Total>{TUTAR}</Total>".
                    "<Currency>949</Currency>".
                    "<Taksit>{TAKSIT}</Taksit>".
                    "<PayerTxnId>{XID}</PayerTxnId>".
                    "<PayerSecurityLevel>{ECI}</PayerSecurityLevel>".
                    "<PayerAuthenticationCode>{CAVV}</PayerAuthenticationCode>".
                    "<CardholderPresentCode>13</CardholderPresentCode>".
                    "<BillTo>".
                    "<Name></Name>".
                    "<Street1></Street1>".
                    "<Street2></Street2>".
                    "<Street3></Street3>".
                    "<City></City>".
                    "<StateProv></StateProv>".
                    "<PostalCode></PostalCode>".
                    "<Country></Country>".
                    "<Company></Company>".
                    "<TelVoice></TelVoice>".
                    "</BillTo>".
                    "<ShipTo>".
                    "<Name></Name>".
                    "<Street1></Street1>".
                    "<Street2></Street2>".
                    "<Street3></Street3>".
                    "<City></City>".
                    "<StateProv></StateProv>".
                    "<PostalCode></PostalCode>".
                    "<Country></Country>".
                    "</ShipTo>".
                    "<Extra></Extra>".
                    "</CC5Request>";
            
            $request = str_replace("{NAME}", $name, $request);
            $request = str_replace("{PASSWORD}", $password, $request);
            $request = str_replace("{CLIENTID}", $clientId, $request);
            $request = str_replace("{IP}", $lip, $request);
            $request = str_replace("{OID}", $oid, $request);
            $request = str_replace("{TYPE}", $type, $request);
            $request = str_replace("{XID}", $xid, $request);
            $request = str_replace("{ECI}", $eci, $request);
            $request = str_replace("{CAVV}", $cavv, $request);
            $request = str_replace("{MD}", $md, $request);
            $request = str_replace("{TUTAR}", $tutar, $request);
            $request = str_replace("{TAKSIT}", $taksit, $request);
            
            $url = POS_3D_URL;
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
            curl_setopt($ch, CURLOPT_SSLVERSION, 3);
            
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 90);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            
            $result = curl_exec($ch);
            
            if(curl_errno($ch)) {
                $_SESSION["3d_error"] = 1;
                $_SESSION["3d_message"] = "Geçersiz işlem yaptınız.Lütfen tekrar deneyiniz.";
                header("Location: " .$place);
                exit;
            }
            else {
                curl_close($ch);
            }
            //sendMail(print_r($result, true),"erden.gencer@turkticaret.net","erden");
            $response_tag = "Response";
            $posf = strpos($result, ("<" . $response_tag . ">"));
            $posl = strpos($result, ("</" . $response_tag . ">"));
            $posf = $posf + strlen($response_tag) + 2 ;
            $Response = substr($result, $posf, $posl - $posf);
            
            $response_tag = "OrderId";
            $posf = strpos($result, ("<" . $response_tag . ">"));
            $posl = strpos($result, ("</" . $response_tag . ">"));
            $posf = $posf + strlen($response_tag) + 2;
            $OrderId = substr($result, $posf , $posl - $posf);
            
            $response_tag = "AuthCode";
            $posf = strpos($result, ("<" . $response_tag . ">"));
            $posl = strpos($result, ("</" . $response_tag . ">"));
            $posf = $posf + strlen($response_tag) + 2;
            $AuthCode = substr($result, $posf , $posl - $posf);
            
            $response_tag = "TransId";
            $posf = strpos($result, ("<" . $response_tag . ">"));
            $posl = strpos($result, ("</" . $response_tag . ">"));
            $posf = $posf + strlen($response_tag) + 2;
            $TransId = substr($result, $posf , $posl - $posf);
            
            if($Response == "Approved") {
                   // Burda işlem tamamlandı sistemine kayıt et.
                $executement = array("order_id" => $OrderId, "auth_id" => $AuthCode, "trans_id" => $TransId);
                
                $_SESSION = unserialize($_SESSION["all_bucket"]);

                $order_result=PutOrder("ccard",$_SESSION["3d_cost"],$_POST['billing'],1,2);

                    if(!$order_result) {
                        $_SESSION["3d_error"] = 1;
                $_SESSION["3d_message"] = "Ödeme işleminizde hata oluştu."; // mtc
                header("Location: " .$place);
                    }else {
                        $order_code=$order_result['ordercode'];
                        $order_id=$order_result['orderid'];
                        $sql="INSERT INTO vpos (id, order_id, auth_id, trans_id, cost, ff_digits, lf_digits, time, orderin_id)
                            VALUES (NULL, '".$executement['order_id']."', '".$executement['auth_id']."', '".$executement['trans_id']."', '".$_SESSION["3d_cost"]."', '".substr($_POST['ccardno'],0,4)."', '".substr($_POST['ccardno'],12)."', '".time()."', '".$order_id."')";
                        //echo "Sipariş numaranız <b>".$order_code."</b><br><br>Siparişinizin onaylanması için 3 (üç) gün içerisinde mailorder formunuzu imzalı ve kaşeli olarak ya da kimlik fotokopinizle birlikte <b>0224 224 95 20</b> no.'lu faksa iletmeniz gerekmektedir.";
                        $db->updateSql($sql);

                        $_SESSION["3d_error"] = -1;
                        $_SESSION["3d_message"] = $order_code;

                        sendOrderCcardMail(array('code'=>$order_code,'name'=>$_SESSION['net_users']['name'],'email'=>$_SESSION['net_users']['email'],'tprice'=>$_SESSION["3d_cost"],'domain'=>$_SESSION['domBasket']));
                        foreach ($_SESSION['domBasket'] as $valll) {
                            gkupon(array('name'=>$_SESSION['net_users']['name'],'email'=>$_SESSION['net_users']['email'],'userID'=>$_SESSION['net_users']['id'],'domain'=>getDomainName($valll),'domID'=>$valll));
                        }
                        header("Location: " .$place);

                    }
            }
            else {
                $_SESSION["3d_error"] = 1;
                $_SESSION["3d_message"] = "Geçersiz işlem yaptınız.Lütfen tekrar deneyiniz."; // mtc
                header("Location: " .$place);
                exit;
            }
        }
    }
    elseif($mdStatus == 2) {
        $_SESSION["3d_error"] = 1;
        $_SESSION["3d_message"] = "Kart sahibi ya da banka sisteme kayıtlı değil.";
        header("Location: " .$place);
    }
    elseif($mdStatus == 3) {
        $_SESSION["3d_error"] = 1;
        $_SESSION["3d_message"] = "Kredi kartının ait olduğu banka sisteme kayıtlı değil.";
        header("Location: " .$place);
    }
    elseif($mdStatus == 4) {
        $_SESSION["3d_error"] = 1;
        $_SESSION["3d_message"] = "İşleminizin tamamlanması için 3D uygulamasını geçmeniz gerekmektedir.";
        header("Location: " .$place);
    }
    elseif($mdStatus == 7) {
        $_SESSION["3d_error"] = 1;
        $_SESSION["3d_message"] = "İşleminizin tamamlanması için 3D uygulamasını geçmeniz gerekmektedir.";
        header("Location: " .$place);
    }
    else {
        $_SESSION["3d_error"] = 1;
        $_SESSION["3d_message"] = "İşleminizin tamamlanması için 3D uygulamasını geçmeniz gerekmektedir.";
        header("Location: " .$place);
        exit;
    }
}
else {
    $billID=$db->insert("bill", array('user_id'=>$user_id,'firm_name'=>$firm_name,'firm_adress'=>$firm_adress,'tax_office'=>$tax_office,'tax_code'=>$tax_code));
    $tprice=0;
    foreach ($_SESSION['domBasket'] as $domID) {
        $res=$db->get_row("select price from domain where id='".$domID."' and orderid=0");
        if ($res->price) $tprice +=$res->price;
        else {
            echo "Seçtiğiniz domain alınmıştır.";//DÜZELT
            exit ();
        }
    }
    // Ödeme ekranından buraya geliyor ilk.
    $total = $tprice;
    
    $_SESSION["3d_cost"] = $total;
    
    $temp = serialize($_SESSION);
    $_SESSION["all_bucket"] = $temp;
    
    $oid = "";
    
    $amount = $total;
    $taksit = "";
    $billing = $_REQUEST["bill"];
    
    if($_REQUEST["cardtype"] == "visa")
        $cardType = 1;
    else
        $cardType = 2;
    
    if($_REQUEST["ccexpmonth"] < 10)
        $_REQUEST["ccexpmonth"] = "0" . $_REQUEST["ccexpmonth"];
    
    $rnd = microtime();
    
    $hashstr = $clientId . $oid . $amount . $okUrl . $failUrl . $islemtipi . $taksit  . $rnd . $storekey;
    $hash = base64_encode(pack('H*',sha1($hashstr)));
    ?>
<html>
    <body>
        <form name="credit_3d" id="credit_3d" method="POST" action="<?php echo POS_3D_GATEURL; ?>">
            <input type="hidden" name="pan" id="pan" value="<?php echo $_REQUEST["ccardno"]; ?>" />
            <input type="hidden" name="cv2" id="cv2" value="<?php echo $_REQUEST["cvv2"]; ?>" />
            <input type="hidden" name="Ecom_Payment_Card_ExpDate_Year" id="Ecom_Payment_Card_ExpDate_Year" value="<?php echo $_REQUEST["ccexpyear"]; ?>" />
            <input type="hidden" name="Ecom_Payment_Card_ExpDate_Month" id="Ecom_Payment_Card_ExpDate_Month" value="<?php echo $_REQUEST["ccexpmonth"]; ?>" />
            <input type="hidden" name="cardType" id="cardType" value="<?php echo $cardType; ?>" />
    
            <input type="hidden" name="clientid" value="<?php echo $clientId; ?>" />
            <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
            <input type="hidden" name="oid" value="<?php echo $oid; ?>" />
            <input type="hidden" name="okUrl" value="<?php echo $okUrl; ?>" />
            <input type="hidden" name="failUrl" value="<?php echo $failUrl; ?>" />
            <input type="hidden" name="rnd" id="rnd" value="<?php echo $rnd; ?>" />
            <input type="hidden" name="hash" id="hash" value="<?php echo $hash; ?>" />
            <input type="hidden" name="storetype" value="3d" />
            <input type="hidden" name="lang" value="en" />
    
            <input type="hidden" name="payment_type" value="ccard_3d" />
            <input type="hidden" name="billing" value="<?php echo $billID; ?>" />
        </form>
        <script type="text/javascript">
            document.credit_3d.submit();
        </script>
    </body>
</html>
    <?php
}
?>