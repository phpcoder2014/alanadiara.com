<?php
session_start();
if($_SESSION['netadmin'] !=true) {
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
        <style type="text/css">
            
        </style>
    </head>
    <body>
        <?php
        include_once('../inc/xtpl.php');
        include_once('../inc/dbMysql.php');
        include_once('../inc/func.php');
        $db=new dbMysql();
        $row=$db->get_row("select * from orders where id='".$orderid."'");
        $bill=$db->get_row("select * from bill where id='".$row->billing."'");
        ?>
        <div >
            <div id="center-column">
                <div class="top-bar">
                    <h1>Sipariş Onay</h1>
                </div><br />
                <div class="table">
                    <table  cellpadding="0" cellspacing="0" border="0" class="listing">
                        <tr class="">
                            <td width="150">Order Code</td>
                            <td align="left"><?php echo $row->order_code;?></td>
                        </tr>
                        <tr class="bg">
                            <td>Müşteri bilgi</td>
                            <td><?php 
                            $user=$db->get_row("select * from user where id='".$row->costumer_id."'");
                            echo $user->name."<br/>".$user->email
                            ?></td>
                        </tr>
                        <tr class="">
                            <td>Domain</td>
                            <td><?php echo $row->domainID;?></td>
                        </tr>
                        <tr class="bg">
                            <td>Şipariş Tarihi</td>
                            <td><?php echo date('d-m-Y H.i.s',$row->time);?></td>
                        </tr>
                        <tr>
                            <td>Ödeme tipi</td>
                            <td>
                            <?php
                                echo "<strong>".$row->payment_type."</strong>";
                                if($row->payment_type=="bank"){
                                    $willpay=$db->get_row("select * from bank_account where id='".$row->willpay."'");
                                    echo "<br/>".$willpay->bank_name." ".$willpay->office_code;
                                    echo $willpay->account_code." ".$willpay->account_type;
                                }
                            ?>
                            </td>
                        </tr>
                        <tr class="bg">
                            <td>Ödenecek Fiyat</td>
                            <td><?php echo $row->total_cost;?> $</td>
                        </tr>
                        <tr>
                            <td>Ödenen Fiyat</td>
                            <td><?php echo $row->recieved_payment;?> $</td>
                        </tr>
                        <tr class="bg">
                            <td>Durum</td>
                            <td><?php echo $dom_status[$row->status];?></td>
                        </tr>
                        <tr>
                            <td class="baslik" colspan="2"> <strong>Fatura bilgileri</strong></td>
                        </tr>
                        <tr class="bg">
                            <td>Firma - Şahıs adı </td>
                            <td> <?php echo $bill->firm_name;?></td>
                        </tr>
                        <tr>
                            <td>Adres </td>
                            <td> <?php echo $bill->firm_name;?></td>
                        </tr>
                        <tr class="bg">
                            <td>Vergi Dairesi </td>
                            <td> <?php echo $bill->tax_office;?></td>
                        </tr>
                        <tr>
                            <td>Vergi No </td>
                            <td> <?php echo $bill->tax_code;?></td>
                        </tr>
                        <tr class="bg">
                            <td colspan="2"><input type="button" name="close" value="Kapat" onclick="javascript:self.close();"/></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
