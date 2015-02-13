<?php
session_start();
if($_SESSION['netadmin'] !=true) {
    header("location:login.php");
}
if($id =="") {
    header("location:login.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Admin</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <style media="all" type="text/css">@import "css/all.css";</style>
        <script type="text/javascript" src="../js/jquery-1.4.min.js"></script>
        <style type="text/css"> </style>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#transfer').submit(function(){
                    if($('#code').val()=="") {
                        alert('Kod Giriniz.');
                    }else {
                        return true;
                    }
                    return false;
                });
            });
        </script>
    </head>
    <body>
        <?php
        include_once('../inc/xtpl.php');
        include_once('../inc/dbMysql.php');
        include_once('../inc/func.php');
        $db=new dbMysql();
        $row=$db->get_row("select * from domain_transfer where id='".$id."'");
        $userSQL="SELECT
`user`.name, `user`.email FROM
domain Inner Join orders ON orders.id = domain.orderid
Inner Join `user` ON `user`.id = orders.costumer_id WHERE domain.id =  '".$row->domID."'";
    $userrow=$db->get_row($userSQL);
        ?>
        <div >
            <div id="center-column">
                <div class="top-bar">
                    <h1>Transfer Kodu Gönder</h1>
                </div><br />
                <?php
                if($send=="Gönder"){
                    $db->updateSql("update domain_transfer set status=1, act_time='".time()."' where id='".$id."'");
                    $param['name']=$userrow->name;
                    $param['email']=$userrow->email;
                    //$param['domain']=getDomainName($row->domID).".info";
					$param['domain']=getDomainName($row->domID);
                    $param['code']=$_POST['code'];
                    sendDomainTransferComplate($param);
                    echo "<font color=red>Transfer Kodu Gönderildi...</font>";
                }
                ?>
                <div class="table">
                    <form name="ee" action="?id=<?=$id?>" method="POST" id="transfer">

                    <table  cellpadding="0" cellspacing="0" border="0" class="listing">
                        <tr class="">
                            <td width="150" height="30">Domain</td>
                            <td align="left">
                                <?php 
                                echo getDomainName($row->domID);
                                ?>.info
                            </td>
                        </tr>
                        <tr class="bg">
                            <td width="150" height="30">Müşteri Bilgileri</td>
                            <td align="left">
                                <?php
    echo $userrow->name ."<br>".$userrow->email;
                                ?>
                            </td>
                        </tr>
                        <tr class="">
                            <td width="150" height="30">Transfer Kodu</td>
                            <td align="left"><input type="text" name="code" value="" id="code"/></td>
                        </tr>
                        <tr class="bg">
                            <td colspan="2" height="30">
                                <input type="submit" name="send" value="Gönder" />
                                <input type="button" name="kapat" value="Kapat" onclick="self.close()"/>
                            </td>
                        </tr>
                    </table>
                        </form>
                </div>
            </div>
        </div>
    </body>
</html>
