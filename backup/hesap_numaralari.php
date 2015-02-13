<?php
session_start();
include_once('inc/xtpl.php');
include_once('inc/dbMysql.php');
include_once('inc/func.php');
$db=new dbMysql();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>alanadiara.com</title>
      <script type="text/javascript" src="js/jquery-1.4.min.js"></script>
      <script type="text/javascript" src="js/jquery.numeric.pack.js"></script>
      <style type="text/css">
         td{}
         body {font: bold 12px Arial;}
         th {background: #f1f1f1;color:#333333;text-align: center;font: bold 14px Arial;padding: 10px 0;}
         table{font: bold 12px Arial;background: #cccccc;}
         .f5{background: #f5f5f5;}
         .ff{background: #fff;}
      </style>
      
   </head>
   <body>
      <div id="cc_bank" class="odemeSecenekleri">
         <div class="havaleBg">
            <table border="0" cellspacing="1" class="tablo2">
               <tr>
                  <th class="brdBottom" style="padding-left:10px;" width="200" height="30" bgcolor="#FBFBFB" ><strong>Banka Adı</strong></th>
                  <th class="brdBottom" width="120" align="center" bgcolor="#FBFBFB"><strong>Şube Kodu</strong></th>
                  <th class="brdBottom" width="330" align="center" bgcolor="#FBFBFB"><strong>Hesap Numarası</strong></th>
                  <th class="brdBottom" width="130" align="center" bgcolor="#FBFBFB"><strong>Hesap T&uuml;r&uuml;</strong></th>
               </tr>
               <?php
               $bank=$db->get_rows("select * from bank_account where status=1");
               $i=1;
               foreach ($bank as $banka) {

               ?>
               <tr <?php if($i%2==0){ ?>class="f5"<?php }else { ?> class="ff"<?php }?>>
                  <td class="brdBottom" style="padding-left:10px;" height="30" ><?=$banka->bank_name?></td>
                  <td class="brdBottom" align="center"><?=$banka->office_code?></td>
                  <td class="brdBottom" align="left"><?=$banka->account_code?><br><?php if($banka->IBAN !="")echo "IBAN: ".$banka->IBAN;?></td>
                  <td class="brdBottom" align="center"><?=strtoupper($banka->account_type)?></td>
               </tr>
               <?php $i++;}?>
            </table>
         </div>
      </div>
   </body>
</html>