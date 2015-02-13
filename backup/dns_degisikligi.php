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
$param['domainName']=$row->name.".info";
if($_POST['gönder']=="Gönder") {
    //print_r($_POST);
    $param['DNS1']=$_POST['dns1'];
    $param['DNS2']=$_POST['dns2'];
    $param['DNS3']=$_POST['dns3'];
    $row=$db->get_row("select name from domain where id='".$_GET['domainID']."'");
    $param['domainName']=$row->name.".info";
    /*$_SESSION['net_users']['name']=$user->name;
         $_SESSION['net_users']['email']=$user->email;*/
    $param['email']=$_SESSION['net_users']['email'];
    $param['name']=$_SESSION['net_users']['name'];
    //print_r($param);
    if(sendDNSRegisterMail($param)) {
        $mesaj="* DNS değişikliği talabiniz alınmıştır. Teşekkür ederiz.";
    }else {
        $mesaj="* DNS değişikliği alınamamıştır. Lütfen daha sonra tekrar deneyiniz.";
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>infopazari.info</title>
        <script type="text/javascript" src="js/jquery-1.4.min.js"></script>
        <script type="text/javascript" src="js/jquery.numeric.pack.js"></script>
        <style type="text/css">
            td{background: #ffffff}
            body {font: bold 12px Arial;}
            input {height: 27px;width: 100%;border:1px solid #dddddd;}
            .btn {height: 25px;width: 80px}
            th {background: #f1f1f1;color:#333333;text-align: center;font: bold 14px Arial;padding: 10px 0;}
        </style>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#dns_degisikligi').submit(function(){
                    if($("input[name$='dns1']").val()==""){
                        alert("Birinci DNS alan\u0131nı giriniz.");
                        return false;
                    }else if($("input[name$='dns2']").val()==""){
                        alert("\u0130kinci DNS alanını giriniz.");
                        return false;
                    }else {
                        return true;
                    }
                    return false;
                });
            });
            /*$(
            function()
            {
                $("input.dns1").numeric(".");
                $("input.dns2").numeric(".");
                $("input.dns3").numeric(".");
            }

        );*/
        </script>
    </head>
    <body>
        <form name="dns_degisikligi" id="dns_degisikligi" method="POST" enctype="multipart/form-data" action="?domainID=<?php echo $_GET['domainID'];?>">
            <table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#cccccc">
                <thead>
                    <tr>
                        <th colspan="2"><?php echo $param['domainName'];?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="150" >Birinci DNS <font color="red">*</font></td>
                        <td><input type="text" name="dns1" value="" class="dns1"/></td>
                    </tr>
                    <tr>
                        <td>İkinci DNS <font color="red">*</font></td>
                        <td><input type="text" name="dns2" value="" class="dns2"/></td>
                    </tr>
                    <tr>
                        <td>Üçüncü DNS</td>
                        <td><input type="text" name="dns3" value="" class="dns3"/></td>
                    </tr>
                    <tr>
                        <td colspan="2" height="5"></td>
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

