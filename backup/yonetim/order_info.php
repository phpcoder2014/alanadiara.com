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
            td { background: #f1f1f1}
            td.baslik { font-weight: bold }
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
                    <h1>Sipariş Detay</h1>
                </div><br />
                <div class="table">
                    <table  cellpadding="3" cellspacing="2" border="0" bgcolor="#ffffff" width="100%">
                        <tr>
                            <td width="150">Order Code</td>
                            <td align="left"><?php echo $row->order_code;?></td>
                        </tr>
                        <tr>
                            <td>Müşteri bilgi</td>
                            <td><?php 
                                $user=$db->get_row("select * from user where id='".$row->costumer_id."'");
                                echo $user->name."<br/>".$user->email
                                ?></td>
                        </tr>
                        <tr>
                            <td>Domain</td>
                            <td>
                                <?php
                                $domName ="";
                                $domID=unserialize($row->domainID);
                                foreach ($domID as $value) {
                                $rowww=$db->get_row("select name from domain where id='".$value."'");
                                    $domName .=$rowww->name.", ";
                                }
                                echo $domName;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Şipariş Tarihi</td>
                            <td><?php echo date('d-m-Y H.i.s',$row->time);?></td>
                        </tr>
                        <tr>
                            <td>Ödeme tipi</td>
                            <td>
                                <?php
                                echo "<strong>".$kindpay_type[$row->payment_type]."</strong>";
                                if($row->payment_type=="bank") {
                                    $willpay=$db->get_row("select * from bank_account where id='".$row->willpay."'");
                                    echo "<br/>".$willpay->bank_name." ".$willpay->office_code."<br>";
                                    echo $willpay->account_code." ".$willpay->account_type;
                                }else if($row->payment_type=="ccard") {
                                    $ccno=$db->get_row("select order_id,ff_digits,lf_digits from vpos where orderin_id='".$row->id."'");
                                    echo "<br>".$ccno->ff_digits."****".$ccno->lf_digits;
                                    echo "<br>".$ccno->order_id;
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Ödenecek Fiyat</td>
                            <td><?php echo $row->total_cost;?> TL</td>
                        </tr>
                        <tr>
                            <td>Ödenen Fiyat</td>
                            <td><?php echo $row->recieved_payment;?> TL</td>
                        </tr>
                        <tr>
                            <td>Durum</td>
                            <td><?php echo $dom_status[$row->status];?></td>
                        </tr>
                        <tr>
                            <td class="baslik" colspan="2"> Fatura bilgileri</td>
                        </tr>
                        <tr>
                            <td>Firma - Şahıs adı </td>
                            <td> <?php echo $bill->firm_name;?></td>
                        </tr>
                        <tr>
                            <td>Adres </td>
                            <td> <?php echo $bill->firm_name;?></td>
                        </tr>
                        <tr>
                            <td>Vergi Dairesi </td>
                            <td> <?php echo $bill->tax_office;?></td>
                        </tr>
                        <tr>
                            <td>Vergi No </td>
                            <td> <?php echo $bill->tax_code;?></td>
                        </tr>
                        <tr>
                            <td class="baslik" colspan="2"> GOOGLE</td>
                        </tr>
                        <tr>
                            <td>Google Adwords Keys </td>
                            <td>
                                <?php
                                foreach ($domID as $value) {
                                $rowwww=$db->get_row("select code,used from gkpn where domID='".$value."'");
                                    echo $rowwww->code." ";
                                    if($rowwww->used==0) {
                                        echo "(Kullanılmadı)";
                                    }else echo "(Kullanıldı)";
                                }
                                //echo $domGoogle;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="button" name="close" value="Kapat" onclick="javascript:self.close();"/></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
