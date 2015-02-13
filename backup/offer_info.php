<?php
session_start();
if($_SESSION['net_users']['id'] !=true) {
    header("location:login.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Admin</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="js/jquery-1.4.min.js"></script>
        <style type="text/css">
            td { background: #f1f1f1}
            td.baslik { font-weight: bold }
        </style>
		<script type="text/javascript">
			function init() {
		  		parent.location.reload();
			}
			window.onunload = init;

			function offer_actions(id, act){
				if(act=='delete_out' && confirm("Teklifi iptal etmek istediğinize emin misiniz?")){
					$.ajax({
		        	    type: "POST",
		        	    url: "offer_actions.php",
		        	    data: {offerid: id, action: act},
		        	    success: function(data) {
							alert(data);
		        	    }
		        	});
				}
			}
			function add_basket(offerid,domainIDVal) {
				if(domainIDVal != '' && domainIDVal > 0) {
					$.ajax({
		        	    type: "POST",
		        	    url: "offer_actions.php",
		        	    data: {offerid: offerid, action: 'accept_out'},
		        	    success: function(data) {
							alert(data);
		        	    }
		        	});
					$.ajax({
						type: "POST",
			            url: "add_basket.php",
			            data: { domainID: domainIDVal, action: "addToBasket"},
			            success: function(data) {

						}
					});
					opener.location = opener.location.href;
					self.close();
				}
			}
			function make_offer(domainIDVal) {
				if(domainIDVal == 'doregister') window.location.href='register.php';
				price_offer = $('#price_offer').val();
				ps = $('#ps').val();
				offerid = $('#offerid').val();
				if((domainIDVal != '' && domainIDVal > 0) && (price_offer != '' && price_offer > 0)) {
					$.ajax({
						type: "POST",
			            url: "add_offer_back.php",
			            data: { domainid: domainIDVal, price: price_offer, offerid: offerid, user_ps: ps},
			            success: function(data) {
							alert(data);
						}
					});
				}
			}
		</script>
    </head>
    <body>
        <?php
        include_once('inc/xtpl.php');
        include_once('inc/dbMysql.php');
        include_once('inc/func.php');
        $db=new dbMysql();
		if(isset($offerid)) {
			$row=$db->get_row("select * from offers where id=".$offerid);
			$user=$db->get_row("select * from user where id=".$row->customer_id);
			$id = $offerid;
		}
        if(isset($reofferid)) {
			$row=$db->get_row("select * from reoffers where id=".$reofferid);
			$row_offer=$db->get_row("select * from offers where id=".$row->offer_id);
			$user=$db->get_row("select * from user where id=".$row_offer->customer_id);
			$id = $reofferid;
		}
        ?>
        <div>
			<div style="float: left; min-height: 250px; padding: 12px 16px 0 13px; width: 600px;">
                <div class="top-bar">
                    <h1>Teklif Detay</h1>
                </div><br />
                <div class="table" height="300">
                    <table  cellpadding="3" cellspacing="2" border="0" bgcolor="#ffffff" width="100%">
                        <tr>
                            <td>Teklif Sahibi</td>
                            <td><?php
                                echo $user->name."<br/>".$user->email;
                                ?></td>
                        </tr>
                        <tr>
                            <td>Domain</td>
                            <td>
                                <?php
                                $rowww=$db->get_row("select name from domain where id='".$row->domain_id."'");
                                echo $rowww->name;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Teklif Tarihi</td>
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
	                            <td colspan="2" align="center">
									<input type="button" name="accept" value="Sepete Ekle" onclick="add_basket(<?php echo $id;?>, <?php echo $row->domain_id;?>)" />
									<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" name="reject" value="İptal Et" onclick="offer_actions(<?php echo $id;?>, 'delete_out')" />-->
								</td>
	                        </tr>
                        <tr>
                            <td colspan="2"><input type="button" name="close" value="Kapat" onclick="javascript:self.close();"/></td>
                        </tr>
						<tr>
							<div id="teklifver_div" style="display:none;">
								<table width="100%" border="0" cellpadding="5" cellspacing="2" class="yable">
									<tr bgcolor="#f1f1f1">
										<td align="center" colspan="3"><?=$rowww->name;?> için <strong>Teklif Ver</strong></td>
									</tr>
									<tr>
										<td align="right" width="165">Teklifiniz: </td>
										<td><input type="text" name="price_offer" id="price_offer"> $</td>
										<td>
											<input type="hidden" id="offerid" value="<?php echo $id; ?>">
											<input type="button" name="send_offer" value="Teklif Ver" onclick="make_offer('<?php echo $row->domain_id;?>');">
										</td>
									</tr>
									<tr>
										<td align="right" width="165">Not: </td>
										<td colspan="2"><textarea name="ps" id="ps" cols="36" rows="2"></textarea></td>
									</tr>
								</table>
							</div>
						</tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
