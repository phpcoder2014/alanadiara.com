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
    <body onunload="window.opener.location.reload();">
        <?php
        include_once('../inc/xtpl.php');
        include_once('../inc/dbMysql.php');
        include_once('../inc/func.php');
        $db=new dbMysql();
		if(isset($offerid)) { $row=$db->get_row("select * from offers where id='".$offerid."'"); $domain_id = $row->domain_id;}
        if(isset($reofferid)) { $row=$db->get_row("select * from reoffers where id='".$reofferid."'"); $domain_id = $row->domain_id;}
		
        ?>
        <div>
			<div style="background: url('img/bg-center-column.jpg') no-repeat scroll left top transparent; float: left; min-height: 250px; padding: 12px 16px 0 13px; width: 600px;">
                <div class="top-bar">
                    <h1>Teklif Detay</h1>
                </div><br />
                <div class="table" height="300">
                    <table  cellpadding="3" cellspacing="2" border="0" bgcolor="#ffffff" width="100%">
						<?php if(isset($reofferid)) { ?>
						<tr>
                            <td>Müşteri bilgi</td>
                            <td><?php 
								$row2=$db->get_row("select * from offers where id='".$row->offer_id."'");
								$user=$db->get_row("select * from user where id=".$row2->customer_id);
                                echo $user->name."<br/>".$user->email."<br/>".$user->phone;
                                ?></td>
                        </tr>
						<tr>
                            <td>Admin bilgi</td>
                            <td><?php 
								$user=$db->get_row("select * from netadmin where id=".$row->user_id);
                                echo $user->name."<br/>".$user->email;
                                ?></td>
                        </tr>
						<?php } else { ?>
						 <tr>
                            <td>Müşteri bilgi</td>
                            <td><?php 
								$user=$db->get_row("select * from user where id=".$row->customer_id);
								echo $user->name."<br/>".$user->email."<br/>".$user->phone;
                                ?></td>
                        </tr>
						<?php } ?>
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
				<br><br>--Önceki Teklifler--
				<div class="table" height="300">
                    <table  cellpadding="3" cellspacing="2" border="0" bgcolor="#ffffff" width="100%">
						<tr>
                            <td>Müşteri bilgi</td>
                            <td>Admin bilgi</td>
							<td>Teklif Tarihi</td>
							<td>Teklif</td>
							<td>Not</td>
                        </tr>
						<?php
						$query = mysql_query("SELECT u.name as m_name, u.email as m_email, u.phone as m_phone, n.name as a_name, n.email as a_email, a1.* FROM ((SELECT customer_id, 0 as admin_id, ps, total_cost, status, time FROM offers WHERE domain_id = " . $domain_id . ") UNION (SELECT o.customer_id, r.user_id as admin_id, r.ps, r.total_cost, r.status, r.time FROM offers o, reoffers r WHERE r.offer_id = o.id AND r.domain_id = " . $domain_id . ")) as a1 LEFT JOIN netadmin n ON (n.id = a1.admin_id), user u WHERE u.id = a1.customer_id ORDER BY a1.time DESC") or die(mysql_error());
						if(mysql_num_rows($query) > 0)
							{
							while($data = mysql_fetch_object($query))
								{
						?>
						<tr>
                            <td><?php echo $data->m_name."<br/>".$data->m_email."<br/>".$data->m_phone;?></td>
                            <td><?php echo $data->a_name."<br/>".$data->a_email;?></td>
                            <td><?php echo date('d-m-Y H.i.s',$data->time);?></td>
                            <td><?php echo $data->total_cost;?> $</td>
                            <td><?php echo $data->ps;?></td>
                        </tr>
						<?php } } ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
