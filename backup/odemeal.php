<?php 
session_start();

include_once('inc/dbMysql.php');
include_once('inc/func.php');


if(!isset ($_SESSION['domBasket'])) {
    echo "Hata";
    exit ();
}
$db=new dbMysql();

$currency = $db->get_row("SELECT currency FROM currency_cron ORDER BY ctime DESC LIMIT 1");

$user_id=$_SESSION['net_users']['id'];
$billID=$db->insert("bill", array('user_id'=>$user_id,'firm_name'=>$firm_name,'firm_adress'=>$firm_adress,'tax_office'=>$tax_office,'tax_code'=>$tax_code));
$tprice=0;
foreach ($_SESSION['domBasket'] as $domID) {
	$res=$db->get_row("select price from domain where id=".$domID." and orderid=0");
	$row_offer = $db->get_row("select total_cost from offers where domain_id=".$domID." and status=1");
	$row_reoffer = $db->get_row("select ro.total_cost from reoffers ro, offers o where ro.domain_id=".$domID." and ro.status=1 and o.status=3");
	if(count($row_offer) > 0 && $row_offer->total_cost > 0) $price = $row_offer->total_cost;
	elseif (count($row_reoffer) > 0 && $row_reoffer->total_cost > 0) $price = $row_reoffer->total_cost;
	else $price = $res->price;
    if ($price > 0) $tprice += number_format($price * $currency->currency, 2, '.', '');
    else {
        echo "Seçtiğiniz domain alınmıştır.";
        exit ();
    }
}

//$tprice=1;
if (!is_numeric($tprice) || $tprice<=0) {
    echo "Ödeme sayfasında hata oluştu.";
    exit ();
}
if($action=="bank") {
    $order_result=PutOrder("bank",$tprice,$billID,0,$_POST['bank_info']);
    if(!$order_result) {
        echo "Ödeme sayfasında hata oluştu.";
        exit ();
    }else {
        $order_code=$order_result['ordercode'];
        $order_id=$order_result['orderid'];
        //echo "Sipariş numaranız <b>".$order_code."</b><br><br>Siparisininizin onaylanması için 3 (üç) gün içerisinde seçtiniz bankaya Ödemenizi iletmeniz gerekmektedir.";
        echo $order_code;
        sendOrderBankMail(array('bankid'=>$_POST['bank_info'],'code'=>$order_code,'email'=>$_SESSION['net_users']['email'],'name'=>$_SESSION['net_users']['name'],'tprice'=>$tprice));
    }

}else if($action=="mail_order") {
    $order_result=PutOrder("mail_order",$tprice,$billID,0,0);
    if(!$order_result) {
        echo "Ödeme sayfasında hata oluştu.";
        exit ();
    }else {
        $order_code=$order_result['ordercode'];
        $order_id=$order_result['orderid'];
        //echo "Sipariş numaranız <b>".$order_code."</b><br><br>Siparişinizin onaylanması için 3 (üç) gün içerisinde mailorder formunuzu imzalı ve kaşeli olarak ya da kimlik fotokopinizle birlikte <b>0224 224 95 20</b> no.'lu faksa iletmeniz gerekmektedir.";
        echo $order_code;
        sendOrderMailOrderMail(array('code'=>$order_code,'name'=>$_SESSION['net_users']['name'],'email'=>$_SESSION['net_users']['email'],'tprice'=>$tprice));
    }
}else if($action=="ccard" && $banktype=="FORTIS" ) {
    echo $action;echo $banktype;exit();
    include_once("inc/virtualpos.inc");
    $executement=ExecutePayment($_POST['ccardno'],$_POST['ccexpmonth'],$_POST['ccexpyear'],$_POST['cvv2'],$tprice);
    if ($executement['response']) {
        $order_result=PutOrder("ccard",$tprice,$billID,1,VP_CURRENT);

        if(!$order_result) {
            echo "Ödeme sayfasında hata oluştu.";
            exit ();
        }else {
            $order_code=$order_result['ordercode'];
            $order_id=$order_result['orderid'];
            $sql="INSERT INTO vpos (id, order_id, auth_id, trans_id, cost, ff_digits, lf_digits, time, orderin_id)
            VALUES (NULL, '".$executement['order_id']."', '".$executement['auth_id']."', '".$executement['trans_id']."', '".$tprice."', '".substr($_POST['ccardno'],0,4)."', '".substr($_POST['ccardno'],12)."', '".time()."', '".$order_id."')";
            //echo "Sipariş numaranız <b>".$order_code."</b><br><br>Siparişinizin onaylanması için 3 (üç) gün içerisinde mailorder formunuzu imzalı ve kaşeli olarak ya da kimlik fotokopinizle birlikte <b>0224 224 95 20</b> no.'lu faksa iletmeniz gerekmektedir.";
            $db->updateSql($sql);
            echo $order_code;
            sendOrderCcardMail(array('code'=>$order_code,'name'=>$_SESSION['net_users']['name'],'email'=>$_SESSION['net_users']['email'],'tprice'=>$tprice,'domain'=>$_SESSION['domBasket']));
            foreach ($_SESSION['domBasket'] as $valll) {
                gkupon(array('name'=>$_SESSION['net_users']['name'],'email'=>$_SESSION['net_users']['email'],'userID'=>$_SESSION['net_users']['id'],'domain'=>getDomainName($valll),'domID'=>$valll));
            }
             
        }
    }
    else {
        echo "Kart bakiyesi geçersiz ya da yanlış kart no girdiniz. Lütfen başka bir kredi kartı ile deneyiniz.";
        exit();
    }
    // echo $tprice."____".$_POST['ccardno'].",".$_POST['ccexpmonth'].",".$_POST['ccexpyear'].",".$_POST['cvv2'];
}
else if($action=="ccard" && $banktype=="GAR" ) {//GARANTİ BANKASI
    //$executement=ExecutePayment($_POST['ccardno'],$_POST['ccexpmonth'],$_POST['ccexpyear'],$_POST['cvv2'],$tprice);
    //echo $action;echo $banktype;exit();
    $executement=GAR_payment($user_id, 0, $billID, $tprice, $_POST['ccardno'], $_POST['ccexpmonth'], $_POST['ccexpyear'], $_POST['cvv2'],array());
    if ($executement['success']) {
        $bankcode=$executement['strReasonOrderValue'];
        $order_result=PutOrder("ccard",$tprice,$billID,1,2,$bankcode);
        //PutOrder($payment_type, $total_cost, $billing, $status, $willpay, $bankcode)
        //print_r($executement);
        //$order_result=false;
        if(!$order_result) {
            echo "Ödeme sayfasında hata oluştu.";
            exit ();
        }else {
            //echo "OK".$executement['strReasonOrderValue'];
            $order_code=$order_result['ordercode'];
            $order_id=$order_result['orderid'];
			$sql="INSERT INTO vpos (id, order_id, auth_id, trans_id, cost, ff_digits, lf_digits, time, orderin_id)
            VALUES (NULL, '".$executement['strReasonOrderValue']."', '".$executement['strReasonAuthcodeValue']."', '', '".$tprice."', '".substr($_POST['ccardno'],0,4)."', '".substr($_POST['ccardno'],12)."', '".time()."', '".$order_id."')";
            $db->updateSql($sql);
			echo $order_code;
            //echo "Sipariş numaranız <b>".$order_code."</b><br><br>Siparişinizin onaylanması için 3 (üç) gün içerisinde mailorder formunuzu imzalı ve kaşeli olarak ya da kimlik fotokopinizle birlikte <b>0224 224 95 20</b> no.'lu faksa iletmeniz gerekmektedir.";            
            /*
			echo $order_code;
            sendOrderCcardMail(array('code'=>$order_code,'name'=>$_SESSION['net_users']['name'],'email'=>$_SESSION['net_users']['email'],'tprice'=>$tprice,'domain'=>$_SESSION['domBasket']));
            foreach ($_SESSION['domBasket'] as $valll) {
                gkupon(array('name'=>$_SESSION['net_users']['name'],'email'=>$_SESSION['net_users']['email'],'userID'=>$_SESSION['net_users']['id'],'domain'=>getDomainName($valll),'domID'=>$valll));
            }*/

        }
    }
    else {
        echo "Kart bakiyesi geçersiz ya da yanlış kart no girdiniz. Lütfen başka bir kredi kartı ile deneyiniz.".$executement['errcode']." => ".$executement['errmsg'];
        exit();
    }
    // echo $tprice."____".$_POST['ccardno'].",".$_POST['ccexpmonth'].",".$_POST['ccexpyear'].",".$_POST['cvv2'];
}

if(is_numeric($order_id)) {
    unset ($_SESSION['domBasket']);
}
function PutOrder($payment_type,$total_cost,$billing,$status=0,$willpay=0,$bankcode=0) {

    if (!is_numeric($total_cost) || $total_cost<=0) return false;
    $db=new dbMysql();
    //$code=GetUniqueOrderCode();
    $code=time();

    switch ($payment_type) {
        case "ccard":
            $recieved_payment=$total_cost;
            $ordacttime=time();
			$status = 1;
            break;
        case "bank":
            $recieved_payment=0.00;
            $ordacttime=null;
			$status = 0;
            break;
        case "mail_order":
            $recieved_payment=0.00;
            $ordacttime=null;
			$status = 0;
            break;
    }
	$serialDomainID=serialize($_SESSION['domBasket']);
	$table=array('costumer_id'=>$_SESSION['net_users']['id'], 'order_code'=>$code, 'payment_type'=>$payment_type, 'total_cost'=>$total_cost,'status'=>$status, 'willpay'=>$willpay, 'billing'=>$billing, 'time'=>time(), 'act_time'=>$ordacttime, 'recieved_payment'=>$recieved_payment,'domainID'=>$serialDomainID);
	$orderID=$db->insert("orders", $table);
	//$sql="INSERT INTO orders () VALUES ('','".$_SESSION['net_users']['id']."','$code','$payment_type','$total_cost','$recieved_payment','$status','$willpay','$billing','".time()."', '$ordacttime', '', '', '', '$serialDomainID','');";
    //$orderID=mysql_query($sql);
    if(!$orderID) {
		echo "Ödeme sayfasında bir hata oluştu.";
        return false;
    } 
    else {
		foreach ($_SESSION['domBasket'] as $domID) {
			$result = mysql_query("update domain set orderid=".$orderID." where id=".$domID);
		}
        return array("orderid"=>$orderID,"ordercode"=>$code);
    }
}
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

function GAR_payment($id_firm, $numshop=0, $order_num="", $sum="", $cc_number="", $exp_month="", $exp_year="", $cvno="", $extparams = array()) {
    /*echo $id_firm ." = ". $numshop." = ". $order_num." = ". $sum." = ". $cc_number ." = ".$exp_month. " = ".$exp_year. " = ".$cvno;
    return;*/
    $bankid = "GAR";
    $exp_month = str_pad($exp_month, 2, "0", STR_PAD_LEFT);
    $exp_year = substr($exp_year, -2);
    $shops = array(
        array('Name' => 'PROVAUT', 'Password' => 'aSy24Bm5', 'ClientId' => '1617477', 'terminalId' => '10000277'),
        array('Name' => 'PROVAUT', 'Password' => '5mB24ySa', 'ClientId' => '1626952', 'terminalId' => '10001734'),
    );
    if ($extparams['instalment'] !== false && ($extparams['instalment']['inst'] > 1 || $extparams['ortak_kart'])) {
        $taksit = str_pad($extparams['instalment']['inst'], 2, '0', STR_PAD_LEFT);
        //$taksit = $extparams['instalment']['inst'];
    } else {
        $taksit = '';
    }
    
    //10000277
    $strMode = "PROD";
    $strVersion = "v0.01";
    $strTerminalID = $shops[$numshop]['terminalId'];
    $strTerminalID_ = "0" . $shops[$numshop]['terminalId']; //Başına 0 eklenerek 9 digite tamamlanmalıdır.
    $strProvUserID = "PROVAUT";
    $strProvisionPassword = $shops[$numshop]['Password']; //Terminal UserID şifresi
    $strUserID = $shops[$numshop]['ClientId'];
    $strMerchantID = $shops[$numshop]['ClientId']; //Üye İşyeri Numarası
    $strCustomerName = substr(replaceTurkishCharactersInOrderContact($_SESSION['net_users']['name']), 0, 32);
    $strIPAddress = "192.168.1.1";
    $strEmailAddress = $_SESSION['net_users']['email'];
    $strOrderID = "";
    $strInstallmentCnt = $taksit; //Taksit Sayısı. Boş gönderilirse taksit yapılmaz
    $strNumber = $cc_number;

    $strExpireDate = $exp_month . $exp_year;
    $strCVV2 = $cvno;
    $strAmount = $sum * 100; //İşlem Tutarı
    $strType = "sales";
    $strCurrencyCode = "949";
    $strCardholderPresentCode = "0";
    $strMotoInd = "N";
    $strHostAddress = "https://sanalposprov.garanti.com.tr/VPServlet";
    $SecurityData = strtoupper(sha1($strProvisionPassword . $strTerminalID_));
    $HashData = strtoupper(sha1($strOrderID . $strTerminalID . $strNumber . $strAmount . $SecurityData));
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <GVPSRequest>
        <Mode>$strMode</Mode><Version>$strVersion</Version>
        <Terminal><ProvUserID>$strProvUserID</ProvUserID><HashData>$HashData</HashData><UserID>$strUserID</UserID><ID>$strTerminalID</ID><MerchantID>$strMerchantID</MerchantID></Terminal>
        <Customer><IPAddress>$strIPAddress</IPAddress><EmailAddress>$strEmailAddress</EmailAddress></Customer>
        <Card><Number>$strNumber</Number><ExpireDate>$strExpireDate</ExpireDate><CVV2>$strCVV2</CVV2></Card>
        <Order><OrderID>$strOrderID</OrderID><GroupID></GroupID><AddressList><Address><Type>S</Type><Name></Name><LastName></LastName><Company></Company><Text></Text><District></District><City></City><PostalCode></PostalCode><Country></Country><PhoneNumber></PhoneNumber></Address></AddressList></Order><Transaction><Type>$strType</Type><InstallmentCnt>$strInstallmentCnt</InstallmentCnt><Amount>$strAmount</Amount><CurrencyCode>$strCurrencyCode</CurrencyCode><CardholderPresentCode>$strCardholderPresentCode</CardholderPresentCode><MotoInd>$strMotoInd</MotoInd><Description></Description><OriginalRetrefNum></OriginalRetrefNum></Transaction>
        </GVPSRequest>";
    //echo "<pre>".$xml."</pre>";exit();
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $strHostAddress);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . $xml);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $xml_parser = xml_parser_create();
    
    if (xml_parse_into_struct($xml_parser, $response, $vals, $index)) {
        xml_parser_free($xml_parser);
        //echo "<pre>";print_r($vals);echo "</pre>";
        $strReasonCodeValue = $vals[$index['REASONCODE'][0]]['value'];
        $strReasonOrderValue = $vals[$index['ORDERID'][0]]['value'];
        $strReasonAuthcodeValue = $vals[$index['AUTHCODE'][0]]['value'];
        $strReasonErrormsgValue = $vals[$index['ERRORMSG'][0]]['value'];
		
        if ($strReasonCodeValue == "00") {
            $result = array('success' => true, 'errcode' => '', 'errmsg' => '', 'shopid' => $shops[$numshop]['ClientId'], 'bankid' => $bankid, 'dresp' => $response, 'dreq' => $str,'strReasonOrderValue'=>$strReasonOrderValue,'strReasonAuthcodeValue'=>$strReasonAuthcodeValue);
        } else {
            $result = array('success' => false, 'errcode' => $strReasonCodeValue, 'errmsg' => $strReasonErrormsgValue, 'shopid' => $shops[$numshop]['ClientId'], 'bankid' => $bankid, 'dresp' => $response, 'dreq' => $str);
            @mail('abdullah.cakmak@turkticaret.net', 'alanadiara kart hatasi ' . $shops[$numshop]['ClientId'], $response."<br />".$xml, "From: info@turkticaret.net\r\nContent-type: text/plain\r\n");
        }
    } else {
        $result = array('success' => false, 'errcode' => '', 'errmsg' => 'response is no XML Document', 'shopid' => $shops[$numshop]['ClientId'], 'bankid' => $bankid, 'dresp' => $response, 'dreq' => $str);
        @mail('abdullah.cakmak@turkticaret.net', 'alanadiara cc connection hata' . $shops[$numshop]['ClientId'], $response . "<br />" . $xml, "From: info@turkticaret.net\r\nContent-type: text/plain\r\n");
    }
    return $result;
}
class PaymentOrdersMapManager {

    var $con;

    function PaymentOrdersMapManager($con = '') {
        $this->con = $con;
    }

    function relateOrderMap($id, $map) {
        $rupd = pg_exec($this->con, "update gm_order_maps set order_num_local='" . $map . "' where id='" . $id . "'");
        if ($rupd) {
            return true;
        }
        return false;
    }

    function addOrderMap($id_firm, $localOrderNumber, $remoteOrderNumber='', $bank_account='', $sum=0, $kindpay, $indate=0, $descr='', $bankid='') {
        if (!is_numeric($id_firm))
            $id_firm = 0;
        $remote_addr = substr($_SERVER['HTTP_REMOTE_ADDR'], 0, 16);
        $rins = pg_exec($this->con, "insert into gm_order_maps (id_firm, order_num_local, order_num_remote, sum, kindpay, indate, descr, bank_account, bankid, remote_addr) values ('" . $id_firm . "', '" . $localOrderNumber . "', '" . $remoteOrderNumber . "', '" . $sum . "', '" . $kindpay . "', '" . $indate . "', '" . $descr . "', '" . $bank_account . "', '" . $bankid . "', '" . $remote_addr . "')");
        if ($rins) {
            $loid = pg_getlastoid($rins);
            $rsid = pg_exec($this->con, "select id from gm_order_maps where oid='" . $loid . "'");
            if (pg_numrows($rsid)) {
                $osid = pg_fetch_object($rsid, 0);
            }
            return $osid->id;
        }
        return false;
    }

    function getRandomMappedOrderNumber($prefix, $query = '', $length = 10) {
        $remainLength = $length - strlen($prefix);
        if (($this->con != '') && ($query != '')) {
            do {
                $randval = $this->getRandomNumber($remainLength);
                $rch = pg_exec($this->con, str_replace('[orderNumber]', $randval, $query));
            } while ((pg_numrows($rch) > 0) || (pg_numrows(pg_exec($this->con, "select id from gm_order_maps where order_num_local='" . $prefix . str_pad((string) $randval, $remainLength, '0', STR_PAD_LEFT) . "' limit 1")) > 0));
        } else {
            $randval = $this->getRandomNumber($remainLength);
        }
        $orderNumber = $prefix . str_pad((string) $randval, $remainLength, '0', STR_PAD_LEFT);
        return array('orderNumber' => $orderNumber, 'randomNumber' => (string) $randval);
    }

    function isAvailableMapID($id) {
        $rch = pg_exec($this->con, "select order_num_local from gm_order_maps where id='" . $id . "'");
        if (pg_numrows($rch) > 0) {
            $och = pg_fetch_object($rch, 0);
            if ($och->order_num_local == '') {
                return true;
            } else {
                return 2;
            }
        } else {
            return 1;
        }
    }

    // private
    function getRandomNumber($length) {
        $max_val = pow(10, $length) - 1;
        mt_srand();
        return mt_rand(0, $max_val);
    }

}
function replaceTurkishCharactersInOrderContact($string) {
	//delete control characters too
	return str_replace(array('ı', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ş', 'Ş', 'ö', 'Ö', 'ç', 'Ç', '&', chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8), chr(9), chr(10), chr(11), chr(12), chr(13), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19), chr(20), chr(21), chr(22), chr(23), chr(24), chr(25), chr(26), chr(27), chr(28), chr(29), chr(30), chr(31) ), array('i', 'I', 'g', 'G', 'u', 'U', 's', 'S', 'o', 'O', 'c', 'C', '', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.'), $string);
}
?>
