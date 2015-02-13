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
		<script>
			function make_offer(domainIDVal) {
				if(domainIDVal == 'doregister') window.location.href='register.php';
				price_offer = $('#price_offer').val();
				ps = $('#ps').val();
				offerid = $('#offerid').val();
				if((domainIDVal != '' && domainIDVal > 0) && (price_offer != '' && price_offer > 0)) {
					$.ajax({
						type: "POST",
			            url: "add_offer.php",
			            data: { domainID: domainIDVal, price: price_offer, user_ps: ps, offer: offerid},
			            success: function(data) {
							$('#info_box').html("<center style='color:red;'>"+data+"</center>");
							window.location.reload();
						}
					});
				}
			}
		</script>
    </head>
    <body onunload="window.opener.location.reload();">
        <?php
        include_once('../inc/xtpl.php');
        include_once('../inc/dbMysql.php');
        include_once('../inc/func.php');
        $db=new dbMysql();
        $row=$db->get_row("select * from offers where id='".$offerid."'");
		$rowww=$db->get_row("select name from domain where id='".$row->domain_id."'");
        $dname = $rowww->name;
        ?>
        <div>
			<div style="background: url('img/bg-center-column.jpg') no-repeat scroll left top transparent; float: left; min-height: 250px; padding: 12px 16px 0 13px; width: 600px;">
                <div class="top-bar">
                    <h1>Teklif Detay</h1>
                </div><br />
				<div id="info_box"></div>
                <div class="table" height="300">
                    <table width="100%" border="0" cellpadding="5" cellspacing="2" class="yable">
						<tr bgcolor="#f1f1f1">
							<td align="center" colspan="3"><?= $dname; ?> için <strong>Teklif Ver</strong></td>
						</tr>
						<tr>
							<td align="right" width="165">Teklifiniz: </td>
							<td>
								<input type="text" name="price_offer" id="price_offer"> $
								<input type="hidden" name="offerid" id="offerid" value="<?=$offerid;?>">
							</td>
							<td><input type="button" name="send_offer" value="Teklif Ver" onclick="make_offer('<?= $row->domain_id; ?>');"></td>
						</tr>
						<tr>
							<td align="right" width="165">Not: </td>
							<td colspan="2"><textarea name="ps" id="ps" cols="36" rows="2"></textarea></td>
						</tr>
					</table>
					
					<br />
					
					<table  cellpadding="3" cellspacing="2" border="0" bgcolor="#ffffff" width="100%">
                        <tr>
                            <td>Müşteri bilgi</td>
                            <td><?php 
                                $user=$db->get_row("select * from user where id='".$row->customer_id."'");
                                echo $user->name."<br/>".$user->email
                                ?></td>
                        </tr>
                        <tr>
                            <td>Domain</td>
                            <td>
                               <?= $dname; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Şipariş Tarihi</td>
                            <td><?php echo date('d-m-Y H.i.s',$row->time);?></td>
                        </tr>
                        <tr>
                            <td>Teklif</td>
                            <td><?php echo $row->total_cost;?> $</td>
                        </tr>
                        <tr>
                            <td>Not</td>
                            <td><?php echo $row->ps;?></td>
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
