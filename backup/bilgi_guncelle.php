<?php
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');
if($_SESSION['net_users']['id']=="") {
    header('Location:login.php?git=dns_degisikligi.php');
}
$db=new dbMysql();
$row=$db->get_row("select name from domain where id='".$_GET['domainID']."'");
$param['domainName']=$row->name;
if($_POST['gönder']=="Gönder") {
    //print_r($_POST);
    if($_POST['name']=="" || $_POST['email']=="" || $_POST['adres']=="" || $_POST['pkod']=="" || $_POST['il']=="" || $_POST['ilce']=="" || $_POST['tel']=="") {
        $mesaj="Lütfen zorunlu alanları eksiksiz doldurunuz.";
    }else {
        /*$_SESSION['net_users']['name']=$user->name;
         $_SESSION['net_users']['email']=$user->email;*/
        $param['metin']='
<table border="0" cellpadding="3" cellspacing="1" width="500" bgcolor="#cccccc">
    <tr bgcolor="#f1f1f1">
        <td width="130">Domain</td>
        <td>'.$param['domainName'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>Ad Soyad</td>
        <td>'.$_POST['name'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>E-Posta</td>
        <td>'.$_POST['email'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>Adres</td>
        <td>'.$_POST['adres'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>İl /İlçe</td>
        <td>'.$_POST['il'].' | '.$_POST['ilce'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>Telefon</td>
        <td>'.$_POST['tel'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>Fax</td>
        <td>'.$_POST['fax'].'</td>
    </tr>
</table>
';
        $param['email']=$_SESSION['net_users']['email'];
        $param['name']=$_SESSION['net_users']['name'];
        //print_r($param);
        if(sendDNSUpdateDomInf($param)) {
            $mesaj="* Bilgi güncelleme talabiniz alınmıştır. Teşekkür ederiz.";
        }else {
            $mesaj="* Bilgi güncelleme alınamamıştır. Lütfen daha sonra tekrar deneyiniz.";
        }
    }


}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Alanadiara.com</title>
        <script type="text/javascript" src="js/jquery-1.4.min.js"></script>
        <script type="text/javascript" src="js/jquery.numeric.pack.js"></script>
        <style type="text/css">
            td{background: #ffffff;font: bold 12px Arial;}
            body {font: bold 12px Arial;}
            input {height: 27px;width: 100%;border:1px solid #dddddd;}
            .btn {height: 25px;width: 80px}
            th {background: #f1f1f1;color:#333333;text-align: center;font: bold 14px Arial;padding: 10px 0;}
        </style>
        <script type="text/javascript">
            function isValidEmailAddress(emailAddress) {
                var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
                return pattern.test(emailAddress);
            }
            $(document).ready(function(){
                $('#dns_degisikligi').submit(function(){
                    if($("input[name$='name']").val()==""){
                        alert("Lütfen ad soyad giriniz.");
                        return false;
                    }else if($("input[name$='firm_name']").val()==""){
                        alert("Lütfen firma adını giriniz.");
                        return false;
                    }else if(!isValidEmailAddress($("input[name$='email']").val())){
                        alert("Lütfen email adresinizi giriniz.");
                        return false;
                    }else if($("input[name$='adres']").val()==""){
                        alert("Lütfen adres giriniz.");
                        return false;
                    }else if($("input[name$='pkod']").val()==""){
                        alert("Lütfen posta kodu giriniz.");
                        return false;
                    }else if($("select#ctlCounty").val()=="sec"){
                        alert("Lütfen il seçiniz.");
                        return false;
                    }else if($("select#ctlPov").val()=="sec"){
                        alert("Lütfen il seçiniz.");
                        return false;
                    }else if($("input[name$='tel']").val()==""){
                        alert("Lütfen telefon giriniz.");
                        return false;
                    }else {
                        return true;
                    }
                    return false;
                    //$("select#ctlCounty")
                });
            });
            $(
            function()
            {
                $("input.pkod").numeric();
                $("input.tel").numeric();
                $("input.fax").numeric();
            }

        );
        </script>
        <script type="text/javascript" >
            $(function(){
                $("select#ctlCounty").change(function(){
                    var sehir=($(this).val());
                    $.ajax({
                        type: "POST",
                        url: "add_basket.php",
                        data: { sehirID: sehir, action: "getCounty"},
                        success: function(data) {
                            //alert(data);
                            $('select#ctlPov').html(data);
                        }
                    });
                });
            })
        </script>
    </head>
    <body>
        <form name="Bilgi Güncelle" id="dns_degisikligi" method="POST" enctype="multipart/form-data" action="?domainID=<?php echo $_GET['domainID'];?>">
            <table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#cccccc">
                <thead>
                    <tr>
                        <th colspan="2"><?php echo $param['domainName'];?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="150" >Ad Soyad <font color="red">*</font></td>
                        <td><input type="text" name="name" value="<?=$_POST['name']?>" class="name" maxlength="60"/></td>
                    </tr>
                    <tr>
                        <td width="150" >Firma İsmi <font color="red">*</font></td>
                        <td><input type="text" name="firm_name" value="<?=$_POST['firm_name']?>" class="firm_name"/></td>
                    </tr>
                    <tr>
                        <td width="150" >E-posta <font color="red">*</font></td>
                        <td><input type="text" name="email" value="<?=$_POST['email']?>" class="email" maxlength="60"/></td>
                    </tr>
                    <tr>
                        <td width="150" >Adres <font color="red">*</font></td>
                        <td><input type="text" name="adres" value="<?=$_POST['adres']?>" class="adres" maxlength="300"/></td>
                    </tr>
                    <tr>
                        <td width="150" >Posta kodu <font color="red">*</font></td>
                        <td><input type="text" name="pkod" value="<?=$_POST['pkod']?>" class="pkod" maxlength="6"/></td>
                    </tr>
                    <tr>
                        <td width="150" >İl <font color="red">*</font></td>
                        <td>
                            <select name="il" id="ctlCounty">
                                <option value="sec">Seçiniz</option>
                                <?php
                                $row=$db->get_rows("select id,name,code from county where id_par=0 order by code");
                                foreach ($row as $rows) {
                                    ?>
                                <option value="<?php echo $rows->id?>"><?php echo $rows->name?></option>
                                    <?php }?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="150" >İlçe <font color="red">*</font></td>
                        <td>
                            <select name="ilce" id="ctlPov">
                                <option value="sec">Seçiniz</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="150" >Telefon<font color="red">*</font></td>
                        <td><input type="text" name="tel" value="<?=$_POST['tel']?>" class="tel" maxlength="11"/></td>
                    </tr>
                    <tr>
                        <td width="150" >Fax</td>
                        <td><input type="text" name="fax" value="<?=$_POST['fax']?>" class="fax" maxlength="11"/></td>
                    </tr>

                    <tr>
                        <td colspan="2"><input type="submit" value="Gönder" name="gönder" class="btn"/>
                            &nbsp;&nbsp;&nbsp;<font color="red"> <?php echo $mesaj;?></font>
                        </td>
                    </tr>


                </tbody>
            </table>

        </form>
    </body>
</html>