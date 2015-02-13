<?php 
session_start();
include_once('inc/dbMysql.php');
include_once('inc/func.php');
//
//deleteBasket
if($action=="addToBasket") {
    if(!in_array($domainID, $_SESSION['domBasket'])) {
        $_SESSION['domBasket'][]=$domainID;
		var_dump($_SESSION);
    }
    $totalItems=count($_SESSION['domBasket']);
    echo $totalItems;
}else if($action=="deleteBasket") {
    unset($_SESSION['domBasket']);
    echo "OK";
}else if($action=="getToBasket") {
    if(isset ($_SESSION['domBasket'])) {
        $totalItems=count($_SESSION['domBasket']);
        echo $totalItems;
    }else echo "0";
}else if($action=="delToBasket") {
    foreach ($_SESSION['domBasket'] as $key => $val) {
         echo $key." ".$val;
        if($val==$domainID) {
            unset ($_SESSION['domBasket'][$key]);
        }
    }
    $totalItems=count($_SESSION['domBasket']);
    echo $totalItems;
}else if($action=="add_fovori") {
    $db=new dbMysql();
    $id_user=$_SESSION['net_users']['id'];
    $id_dom=$domainID;
    $num=$db->num_rows("select id from fav_domain where id_dom='".$id_dom."' and id_user='".$id_user."'");
    if($num <= 0) {
        $db->insert("fav_domain",array("id_dom"=>$id_dom,"id_user"=>$id_user,"add_time"=>time()));
    }
    $numt=$db->num_rows("select id from fav_domain where id_user='".$id_user."'");
    echo  $numt;
}else if($action=="delToFavori") {
    $db=new dbMysql();
    $id_user=$_SESSION['net_users']['id'];
    $id_dom=$domainID;
    $db->delete("fav_domain", array('id'=>$domainID));
}else if($action=="getTofavori") {//
    $db=new dbMysql();
    $id_user=$_SESSION['net_users']['id'];
    $numt=$db->num_rows("select id from fav_domain where id_user='".$id_user."'");
    echo  $numt;
}else if($action=="addAramaKaydet") {
    $db=new dbMysql();
    $id_user=$_SESSION['net_users']['id'];
    $db->insert("save_search",array("name"=>$name,"search"=>$domainID,"id_user"=>$id_user,"time"=>time()));
    echo "OK";
}else if($action=="delAramaKaydet") {
    $db=new dbMysql();
    $id_user=$_SESSION['net_users']['id'];
    $db->delete("save_search", array('id'=>$domainID));
    echo "OK";
}else if($action=="domainHit") {
    $db=new dbMysql();
    $db->updateSql("update domain set hit=hit+1 where id='".$domainID."'");
    echo "OK";
}else if($action=="searchDomain") {

    $db=new dbMysql();
    print_r($_POST);
    $arama=$_SESSION['arama'];


    $spe="WHERE";
    $searchvalue=$arama['searchvalue'];
    if($searchvalue !="") {
        $kosul .=$spe." domain.name like '%".$searchvalue."%' ";
    }

    if($arama['categori']!="") {
        foreach ($_POST['categori'] as $cat) {
           $kosul .=" kategori.id in (6,7,8)";
        }
        $kosul .=" and kategori.id in (".implode(',', $arama['categori']).")";
    }
    $order=$arama['order'];


    if($order !="") {
        if($order==1) {
            $order=" order by domain.price desc ";
        }else if($order==2) {
            $order=" order by domain.price ";
        }else if($order==3) {
            $order=" order by kategori.id desc ";
        }else if($order==4) {
            $order=" order by kategori.id ";
        }else if($order==5) {
            $order=" order by domain.price desc ";
            $main->assign("selected5", "selected");
        }else if($order==6) {
            $order=" order by domain.hit ";
            $main->assign("selected6", "selected");
        }else {
            $order=" order by kategori.id ";
        }
    }else {
        $order=" order by kategori.id ";
    }
    $start=($number-1)*10;
    $query="SELECT kategori.name AS catname, kategori.id AS catid, domain.id, domain.name, domain.price FROM domain Inner Join dom_cat ON dom_cat.id_dom = domain.id Inner Join kategori ON kategori.id = dom_cat.id_cat ".$kosul." group by domain.name ".$order." ";
    $res=$db->get_rows($query." limit ".$start.",10");
    $i=1;
    $table ="";
    foreach ($res as $row) {
        ($i%2==0)?$bgcolor="#f1f1f1":$bgcolor="#ffffff";
        $table .="
<tr bgcolor='".$bgcolor."'>
    <td align='left'  class='icerikTd favori'>
        <a href='add_fovarite.php?action=addfovarite&domainID=".$row->id."' onClick='return false;'>
            <img src='images/favStar.png' width='12' height='13' title='Favorilerime Ekle' alt='Favorilerime Ekle' id='imgFav_".$row->id."'/>
        </a>
    </td>
    <td height='30'  class='icerikTd'><a href='#' onClick=\"return false;\"><U>".$row->name."</U></a></td>
    <td align='center'  class='icerikTd'><span class='redLink'>".getDomainCat($row->id,$arama['categori'])."</span></td>
    <td align='center'  class='icerikTd'><p>".$row->price." $<strong></strong></p></td>
    <td colspan='2' align='center'  class='icerikTd'>
        <div class='productPriceWrapRight'>
            <a href='add_basket.php?action=addToBasket&domainID=".$row->id."' onClick='return false;'><img src='images/sepeteEkle.gif' alt='' id=\"imgSepet_".$row->id."\"/></a>
        </div>
    </td>
</tr>
";
        $i++;
    }
    echo $table;
    return;
}else if($action=="getCounty") {
    //echo $_POST['sehirID'];
    $db=new dbMysql();
    $row=$db->get_rows("select id,name,code from county where id_par='".$sehirID."'");
    echo "<option value='0'>Merkez</option>\n";
    foreach ($row as $val) {
        echo "<option value='".$val->id."'>".$val->name."</option>\n";
    }

}else if($action=="google_act") {
    $db=new dbMysql();
    $id_user=$_SESSION['net_users']['id'];
    $id_dom=$domainID;
    $db->updateSql("update gkpn set used=1 where id='".$id_dom."'");
    echo "Aktive edildi.";

}else if($action=="addToTransfer") {
    $db=new dbMysql();
    if($_SESSION['net_users']['id']=="") {
        echo "-1";
        return;
    }
    $userID=$_SESSION['net_users']['id'];
    $domID=$domainID;
    $sql="select * from domain_transfer where domID='".$domID."'";
    $num=$db->num_rows($sql);
    $param['name']=$_SESSION['net_users']['name'];
    $param['email']=$_SESSION['net_users']['email'];
    $param['domain']=getDomainName($domID);
    if($num>0) {
        $row=$db->get_row($sql);
        

        if($row->status==0) {
            if($row->add_time < mktime(date('H'),date('i'), date('s'), date('m'), date('j'), date('Y'))-(3*3600*24)) {
                sendDomainTransfer($param);
                sendDomainTransferAdmin($param);
                echo "0";
                //mktime($hour, $minute, $second, $month, $day, $year)
            }else {
                $db->updateSql("update domain_transfer set status=0, add_time='".time()."' where id='".$row->id."'");
                sendDomainTransfer($param);
                sendDomainTransferAdmin($param);
                echo "1";
            }
            //echo "0";
        }else if($row->status==1) {
            sendDomainTransfer($param);
            sendDomainTransferAdmin($param);
            $db->updateSql("update domain_transfer set status=0, add_time='".time()."' where id='".$row->id."'");
            echo "1";
        }

    }else {
        sendDomainTransfer($param);
        sendDomainTransferAdmin($param);
        $db->updateSql("insert into domain_transfer (domID,status,add_time) values ('".$domID."','0','".time()."')");
        echo "1";
    }

}
//searchDomain
?>
