<?php 
session_start();
include_once('../inc/dbMysql.php');
include_once('../inc/func.php');
if($_SESSION['netadmin'] !=true) {
    header("location:login.php");
}
$param=array();

if($action=="delToBasket") {
   $db=new dbMysql();
   $domainID=$_POST['domainID'];
   $numt=$db->delete("dom_cat", array('id'=>$domainID));//("delete from dom_cat where id='".$domainID."'");
   echo "OK";
}else if($action=="fiyatUpdate") {
   $db=new dbMysql();
   $domainID=$_POST['domainID'];
   $price=$_POST['price'];
   $price_min=$_POST['price_min'];
   $tip=$_POST['tip'];
   //echo ($price ."==".$domainID);return;
   $numt=$db->updateSql("update domain set price=".$price.", price_min=".$price_min.", offer=".$tip." where id='".$domainID."'");
   echo "OK";
}else if($action=="order_act") {
    $db=new dbMysql();
	$domainID=$_POST['domainID'];     
    $res=$db->updateSql("update orders set status=1,act_time=".time()." where id='".$orderID."'"); 
	$sql="SELECT user.name AS username, user.email AS useremail,user.id AS userID, orders.order_code, orders.act_time,orders.domainID FROM user Inner Join orders ON user.id = orders.costumer_id WHERE orders.id = '".$orderID."'";
    $row=$db->get_row($sql);
    $param['name']=$row->username;
    $param['email']=$row->useremail;
    $param['userID']=$row->userID;
    $param['orderCode']=$row->order_code;
    
    $dom_ID=unserialize($row->domainID);
    $dname="";
    $d_row=$db->get_rows("select name from domain where id in (".implode(",", $dom_ID).")");
    ///echo "select name from domain where id in (".implode(",", $dom_ID).")";
    foreach ($d_row as $value) {
        $dname .=$value->name.".info,";
        //$db->updateSql("update domain set orderid='".$orderID."' where name='".$value->name."' ");
    }
	$tarih = time();
    	/*$res2=$db->insert("INSERT INTO domain_transfer (domID,status,add_time,act_time) VALUES ('".$dom_ID[0]."','1','".$tarih."','".$tarih."')");*/
		$res3 =  mysql_query("INSERT INTO domain_transfer (domID,status,add_time,act_time) VALUES ('".$dom_ID[0]."','1','".$tarih."','".$tarih."')");
    $param['domain']=$dname;
    //echo $param;
    
    if(!adminSendOrderAct($param)) {

        echo "Siparis Aktiflenemedi.";
    }else {
        foreach ($dom_ID as $valll) {
           gkupon(array('name'=>$row->username,'email'=>$row->useremail,'domain'=>$dname,'userID'=>$row->userID,'domID'=>$valll));
        }	
        echo "Siparis Aktiflendi.";
    }
    return;
    //if()return true;else return false;
}else if($action=="order_cancel") {
    //echo "ok".$orderID;
    $db=new dbMysql();
    $res=$db->updateSql("update orders set status=2 where id='".$orderID."'");
	$res2=$db->insert("update domain_transfer set status=2 where order_id='".$orderID."'");
    $sql="SELECT user.name AS username, user.email AS useremail, orders.order_code, orders.act_time,orders.domainID FROM user Inner Join orders ON user.id = orders.costumer_id WHERE orders.id = '".$orderID."'";
    $row=$db->get_row($sql);
    $param['name']=$row->username;
    $param['email']=$row->useremail;
    $param['orderCode']=$row->order_code;
    $dom_ID=unserialize($row->domainID);
    $d_row=$db->get_rows("select name from domain where id  in (".implode(",", $dom_ID).")");
    $dname="";
    foreach ($d_row as $value) {
        $dname .=$value->name." ";
        $db->updateSql("update domain set orderid=0 where name='".$value->name."' ");
    }
    foreach ($dom_ID as $domIDD) {
        $db->updateSql("update gkpn set userID=null where domID='".$domIDD."'");
    }
    $param['domain']=$dname;
    if(!adminSendOrderCancel($param)){
        echo "HATA";
    }else {
        echo "OK";
    }
    echo $res;return;
    //if()return true;else return false;
}else if($action=="order_odemebekleniyor") {
    //echo "ok".$orderID;
    $db=new dbMysql();
    $res=$db->updateSql("update orders set status=0 where id='".$orderID."'");
	$res2=$db->updateSql("update domain_transfer set status=0 where domID='".$orderID."'");
    /* 
     $sql="SELECT user.name AS username, user.email AS useremail, orders.order_code, orders.act_time FROM user Inner Join orders ON user.id = orders.costumer_id WHERE orders.id = '".$orderID."'";
    $row=$db->get_row($sql);
    $param['name']=$row->username;
    $param['email']=$row->useremail;
    $param['orderCode']=$row->order_code;
    $dom_ID=unserialize($row->domainID);
    $d_row=$db->get_rows("select name from domain where id not in (".implode(",", $dom_ID).")");
    foreach ($d_row as $d){
        $dname .=$d.name.",";
    }
    $param['domain']=$dname;
     */
    echo $res;return;
    //if()return true;else return false;
}

?>
