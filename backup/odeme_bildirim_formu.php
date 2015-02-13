<?php
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');
if($_SESSION['net_users']['id']=="") {
    header('Location:login.php?git=odeme_bildirim_formu.php');
}
$db=new dbMysql();
$row=$db->get_row("select name from domain where id='".$_GET['domainID']."'");
$param['domainName']=$row->name;
if($_POST['gönder']=="Gönder") {
    //print_r($_POST);
    if($_POST['name']=="" || $_POST['tutar']=="" || $_POST['atm']=="" || $_POST['cep']=="" || $_POST['type']=="") {
        $mesaj="Lütfen zorunlu alanları eksiksiz doldurunuz.";
    }else {
        /*$_SESSION['net_users']['name']=$user->name;
         $_SESSION['net_users']['email']=$user->email;*/
        if($_POST['type']==1){
            $bank="Banka Havalesi";
        }else $bank="Mail Order";
        $param['metin']='
<table border="0" cellpadding="3" cellspacing="1" width="500" bgcolor="#cccccc">
    <tr bgcolor="#f1f1f1">
        <td width="130">Tutar</td>
        <td>'.$_POST['tutar'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>Ad Soyad</td>
        <td>'.$_POST['name'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>ATM</td>
        <td>'.$_POST['atm'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>Cep</td>
        <td>'.$_POST['cep'].'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>Ödeme tipi</td>
        <td>'.$bank.'</td>
    </tr>
    <tr bgcolor="#ffffff">
        <td>NOT</td>
        <td>'.$_POST['not'].'</td>
    </tr>
</table>
';
        $param['email']=$_SESSION['net_users']['email'];
        $param['name']=$_SESSION['net_users']['name'];
        //print_r($param);
        if(sendOdemeBildirim($param)) {
            $mesaj="* Talabiniz alınmıştır. Teşekkür ederiz.";
        }else {
            $mesaj="* HATA! Lütfen daha sonra tekrar deneyiniz.";
        }
    }


}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>alanadiara.com</title>
        <script type="text/javascript" src="js/jquery-1.4.min.js"></script>
        <script type="text/javascript" src="js/jquery.numeric.pack.js"></script>
        <style type="text/css">
            td{background: #ffffff;font: bold 12px Arial;}
            body {font: bold 12px Arial;}
            .input {height: 27px;width: 100%;border:1px solid #dddddd;}
            textarea {width: 100%;border:1px solid #dddddd;}
            input {height: 27px;}
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
                    }else if($("input[name$='tutar']").val()==""){
                        alert("Lütfen odeme tutar\u0131nı giriniz.");
                        return false;
                    }else if($("input[name$='cep']").val()==""){
                        alert("Lütfen cep telefon numaran\u0131zı giriniz.");
                        return false;
                    }else if($("input[name$='atm']").val()==""){
                        alert("Lütfen ATM numaras\u0131nı giriniz.");
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
                $("input.atm").numeric();
                $("input.cep").numeric();
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
        <form name="Bilgi Güncelle" id="dns_degisikligi" method="POST" enctype="multipart/form-data" action="">
            <table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#cccccc">
                <thead>
                    <tr>
                        <th colspan="3">Lütfen hangi ürün için ödeme yaptınızı belirtin. Ürünün süresi diğer özellikleri varsa yazınız.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 110px" width="110">Ödeme Tutarı <font color="red">*</font></td>
                        <td width="400"><input type="text" name="tutar" value="<?=$_POST['tutar']?>" class="name input" maxlength="60"/></td>
                        <td width="10"></td>
                    </tr>
                    <tr>
                        <td>Ad Soyad <font color="red">*</font></td>
                        <td><input type="text" name="name" value="<?=$_POST['name']?>" class="name input" maxlength="60"/></td>
                        <td width="10"></td>
                    </tr>
                    <tr>
                        <td>ATM Numarası <font color="red">*</font></td>
                        <td><input type="text" name="atm" value="<?=$_POST['atm']?>" class="atm input"/></td>
                        <td width="10"></td>
                    </tr>
                    <tr>
                        <td>Cep Telefonu Numarası <font color="red">*</font></td>
                        <td><input type="text" name="cep" value="<?=$_POST['cep']?>" class="cep input" maxlength="11"/></td>
                        <td width="10"></td>
                    </tr>
                    <tr>
                        <td>Hangi Yolla Ödeme Yaptınız <font color="red">*</font></td>
                        <td>
                            <label>Banka Havalesi<input type="radio" name="type" value="1" checked/></label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label>Mail Order<input type="radio" name="type" value="2" /></label>
                        </td>
                        <td width="10"></td>
                    </tr>
                    <tr>
                        <td>Not <font color="red">*</font><br>
                        <strong>Lütfen kullanıcı adınızı (mail adresi ) ve satın aldığınız alan ad(ları)ını yazınız.</strong>
                        </td>
                        <td>
                            <textarea name="not" rows="4" cols="20"><?=$_POST['not']?></textarea>
                        </td>
                        <td width="10"></td>
                    </tr>
                    
                    

                    <tr>
                        <td colspan="3"><input type="submit" value="Gönder" name="gönder" class="btn"/>
                            &nbsp;&nbsp;&nbsp;<font color="red"> <?php echo $mesaj;?></font>
                        </td>
                    </tr>


                </tbody>
            </table>

        </form>
    </body>
</html>