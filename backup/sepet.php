<?php
session_start();

if (!$_SESSION['hst_usr_logged_in']) {
	header("Location: http://www.ok.net/login.php?git=http://internet.ok.net/sepet.php");
	exit();
}
$userinfo=split("[|]+", $_SESSION['hst_usr_logged_in']);

$title_suffix="Sepetiniz - ";
$okih_desc_cont2="Sepetinizi Olu򴵲un. ";
$okih_key_cont2="Sepet 򤥭e 򥫬i, fatura adresi, ";

$conf['pagestyle']="lite";
if ($_POST['basket_empty']) {
	unset($_SESSION['basket_prods']);
	unset($_SESSION['added_domains']);
	unset($_SESSION['domain_contacts']);
	unset($_SESSION['domain_offer']);
	unset($_SESSION['renew_domain']);
	unset($_SESSION['added_hostings']);
	unset($_SESSION['added_emails']);
	unset($_SESSION['added_emaildoms']);
	unset($_SESSION['added_servers']);
	unset($_SESSION['added_manhostings']);
	unset($_SESSION['added_sslcerts']);
	unset($_SESSION['added_secrds']);
	unset($_SESSION['added_vndpacks']);
	unset($_SESSION['quantity']);
	if ($_POST['basket_empty']=="inline") header("Location: sepet.php");
	else if ($_POST['basket_empty']=="outline") header("Location: http://www.ok.net/usr_prf_rmd.php?git=http://internet.ok.net/yonetim.php&intsep=".time()); // header("Location: yonetim.php");
}
include_once("includes/variables.php");
include_once("includes/classes/plesk.class.php");

$is_domain_exist = 0;

include ("header.php");
?>

<script type="text/javascript">
var loaderimg='<table border="0" cellpadding="2" cellspacing="0"><tr><td><img src="images/arrows3.gif"></td><td class="normal" style="font-size:9px;"><b>G𮣥lleniyor...</b></td></tr></table>';
var loadednbsp='&nbsp';
function BasketUpdate(action,pid,amount) {
	if (action=="quantity-dom" || action=="quantity-domo" || action=="quantity-domtr") {
		var updaterloader=$('loader_dom');
		new Ajax.Request("basket_update.php",{
			parameters:{action:action,pid:pid,amount:amount},
			onLoading: function() {
				updaterloader.update(loaderimg);
			},
			onSuccess: function(transport) {
				var totalcostupdate=$('dom-cost-total-'+pid);
				totalcostupdate.update(transport.responseText);
				new Effect.Highlight('dom-cost-total-container-'+pid);
				BasketCostUpdate('dom');
				BasketCostUpdate('total');
				BasketCostUpdate('totaltr');
			},
			onComplete: function() {
				updaterloader.update(loadednbsp);
			},
			onFailure:function BasketError(){}
		}
		);
	}
	else if (action=="quantity-domrnw") {
		var updaterloader=$('loader_dom');
		new Ajax.Request("basket_update.php",{
			parameters:{action:action,pid:pid,amount:amount},
			onLoading: function() {
				updaterloader.update(loaderimg);
			},
			onSuccess: function(transport) {
				var totalcostupdate=$('dom-cost-total-'+pid);
				totalcostupdate.update(transport.responseText);
				new Effect.Highlight('dom-cost-total-container-'+pid);
				BasketCostUpdate('dom');
				BasketCostUpdate('total');
				BasketCostUpdate('totaltr');
			},
			onComplete: function() {
				updaterloader.update(loadednbsp);
			},
			onFailure:function BasketError(){}
		}
		);
	}
	else if (action=="privacy" || action=="privacyo") {
		//var element='dom-privacy-'+pid;
		if (document.basket4546.elements['dom-privacy-'+pid][0].checked) tmpvalue='o';
		if (document.basket4546.elements['dom-privacy-'+pid][1].checked) tmpvalue='p';
		//if (tmpvalue!=amount) {
		var updaterloader=$('loader_dom');
		new Ajax.Request("basket_update.php",{
			parameters:{action:action,pid:pid,amount:amount},
			onLoading: function() {
				updaterloader.update(loaderimg);
			},
			onSuccess: function(transport) {
				var totalcostupdate=$('dom-cost-total-'+pid);
				totalcostupdate.update(transport.responseText);
				new Effect.Highlight('dom-cost-total-container-'+pid);
				BasketCostUpdate('dom');
				BasketCostUpdate('total');
				BasketCostUpdate('totaltr');
			},
			onComplete: function() {
				updaterloader.update(loadednbsp);
			},
			onFailure:function BasketError(){}
		}
		);
		//}
	}
	else if (action=="quantity-hst" || action=="quantity-hstrenew") {
		var updaterloader=$('loader_hst');
		new Ajax.Request("basket_update.php",{
			parameters:{action:action,pid:pid,amount:amount},
			onLoading: function() {
				updaterloader.update(loaderimg);
			},
			onSuccess: function(transport) {
				var totalcostupdate=$('hst-cost-total-'+pid);
				totalcostupdate.update(transport.responseText);
				new Effect.Highlight('hst-cost-total-container-'+pid);
				BasketCostUpdate('hst');
				BasketCostUpdate('total');
				BasketCostUpdate('totaltr');
			},
			onComplete: function() {
				updaterloader.update(loadednbsp);
			},
			onFailure:function BasketError(){}
		}
		);
	}
	else if (action=="quantity-vnd" || action=="quantity-vndrenew") {
		var updaterloader=$('loader_vnd');
		new Ajax.Request("basket_update.php",{
			parameters:{action:action,pid:pid,amount:amount},
			onLoading: function() {
				updaterloader.update(loaderimg);
			},
			onSuccess: function(transport) {
				var totalcostupdate=$('vnd-cost-total-'+pid);
				totalcostupdate.update(transport.responseText);
				new Effect.Highlight('vnd-cost-total-container-'+pid);
				BasketCostUpdate('vnd');
				BasketCostUpdate('total');
				BasketCostUpdate('totaltr');
			},
			onComplete: function() {
				updaterloader.update(loadednbsp);
			},
			onFailure:function BasketError(){}
		}
		);
	}
	else if (action=="quantity-email" || action=="quantity-emlrenew") {
		var updaterloader=$('loader_eml');
		new Ajax.Request("basket_update.php",{
			parameters:{action:action,pid:pid,amount:amount},
			onLoading: function() {
				updaterloader.update(loaderimg);
			},
			onSuccess: function(transport) {
				var totalcostupdate=$('email-cost-total-'+pid);
				totalcostupdate.update(transport.responseText);
				new Effect.Highlight('email-cost-total-container-'+pid);
				BasketCostUpdate('eml');
				BasketCostUpdate('total');
				BasketCostUpdate('totaltr');
			},
			onComplete: function() {
				updaterloader.update(loadednbsp);
			},
			onFailure:function BasketError(){}
		}
		);
	}
	OkKrediKontrol();
}

function BasketCostUpdate(trigger) {
	new Ajax.Request("basket_update.php",{
		parameters:{action:'total',trigger:trigger},
		onLoading: function() {
			var updaterloader=$('loader_'+trigger);
			updaterloader.update(loaderimg);
		},
		onSuccess: function(transport) {
			var totalcostupdate=$(trigger+'-section-cost');
			totalcostupdate.update(transport.responseText);
			new Effect.Highlight(trigger+'-section-container');

		},
		onComplete: function() {
			var updaterloader=$('loader_'+trigger);
			updaterloader.update(loadednbsp);
		}
	}
	);
}
function RemoveItem (trigger,item) {
	new Ajax.Request("basket_update.php",{
		parameters:{action:'remove',trigger:trigger,item:item},
		onLoading: function() {
			var updaterloader=$('loader_'+trigger);
			updaterloader.update(loaderimg);
		},
		onSuccess: function(transport) {
			var returnstring=transport.responseText;

			$(returnstring.substr(6,38)).setStyle({display:'none'});
			if (returnstring.substr(0,6)=="#sect#") $(trigger+'-all-container').setStyle({display:'none'});
			if (returnstring.substr(0,6)=="#dest#") {
				$(trigger+'-all-container').setStyle({display:'none'});
				$('basket_full').setStyle({display:'none'});
				for (var i=0;i<5;i++)
				$('basket_empty-'+i).setStyle({display:'block'});
			}
			if (trigger!="crd" && returnstring.substr(0,6)=="#item#") { BasketCostUpdate(trigger); }
			if (returnstring.substr(0,6)!="#dest#") {
				BasketCostUpdate('total');
				BasketCostUpdate('totaltr');
			}
		},
		onComplete: function() {
			var updaterloader=$('loader_'+trigger);
			updaterloader.update(loadednbsp);
		}
	}
	);
	OkKrediKontrol();
}

function BasketError() {
	Ajax.Responders.register({
		onCreate: function() {
			Ajax.activeRequestCount++;
		},
		onComplete: function() {
			Ajax.activeRequestCount--;
		}
	});
	alert('Sepet g𮣥lleme i\u015flemi ba򡲽s񺠯ldu, l𴦥n sayfay񠹥nileyiniz!');
}

function OkKrediKontrol() {
	if ($F('payment_hstcredit')) {
			$('payment_area').update('');
			$('payment_hstcredit').checked=false;
	}
}
</script>

 	<?php
 	// echo count($_SESSION['quantity']);
 	if (count($_SESSION['quantity'])>0) {
 		$basket_empty_style['full']=" style=\"display:block;\"";
 		$basket_empty_style['empty']=" style=\"display:none;\"";
 	}
 	else {
 		$basket_empty_style['full']=" style=\"display:none;\"";
 		$basket_empty_style['empty']=" style=\"display:block;\"";
 	}
	?>
  <tr>
    <td colspan="2" align="left" valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">	
<tr>
  <td height="10" valign="top"></td>
</tr>
<tr id="basket_full" <?=$basket_empty_style['full'];?>>
  <td align="center" valign="top"><table width="770" border="0" cellspacing="0" cellpadding="0">
      <tr valign="top">
        <td height="100%"><table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td colspan="2" class="normal12"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr valign="bottom">
                    <td width="11%"><span class="fiyat">Sepetiniz</span></td>
                    <td width="89%" align="left"><img src="images/sepeticon.gif" width="28" height="22" /></td>
                  </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="11" valign="bottom"><table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#efefef">
                        <tr>
                          <td></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
           
            <tr>
              <td colspan="2" class="ort-bsl2" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="5%"><span class="fiyat"><img src="images/sepet_1ico.gif" width="35" height="35" /></span></td>
                    <td width="47%" class="basliklar">Al񾶥ri򠓥petiniz</td>
                    <td width="48%" align="right" class="basliklar"><table border="0" cellpadding="3" cellspacing="0">
                        <form name="domainsearch" action="alanadi2.php" method="POST" id="domainsearch" onsubmit="return checkDomain(this);">
                          <tbody>
                            <tr>
                              <td align="right" class="fiyat">&nbsp;</td>
                              <td><input id="domainquery" name="domainquery" size="34" title="Alan ad񮽠yaz񮽺" maxlength="63" style="width: 150px;" type="text" onkeypress="return K_Validate.checkAlphaNum(event)" /></td>
                              <td width="66"><select name="domain_suffix">
                                  <?php
                                  foreach ($var_tlds as $tldkey=>$domkey) {
                                  	foreach ($domkey as $key=>$value) {
                                  		$strech=str_replace(".","_",$value);
                                  		echo "<option value=\"".$tldkey."-".$strech."\">$value</option>\n";
                                  	}
                                  }
                                  foreach ($var_trds as $value) {
                                  	echo "<option value=\"domtr-".$value."\">$value.tr</option>\n";
                                  }
                                        	?>
                                </select>
                              </td>
                              <td width="69"><input type="image" src="images/sorgulab.gif" /></td>
                            </tr>
                          </tbody>
                        </form>
                      </table></td>
                  </tr>
                  <tr>
                    <td height="11" colspan="3"><table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#efefef">
                        <tr>
                          <td></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td colspan="2" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                  <form name="basket4546" id="basket4546" method="POST" action="">
                    <tr class="host-sag-baslik">
                      <td height="25" width="28%"><strong>ݲ𮠁d񼯳trong></td>
                      <td height="25" width="40%"><strong>Miktar</strong></td>
                      <td height="25" width="16%" align="center" ><strong>Birim Fiyat</strong></td>
                      <td height="25" width="16%" align="center"><strong>TOPLAM</strong></td>
                    </tr>
                    <?php
                    if (count($_SESSION['basket_prods']['domain']['tld'])>0 || count($_SESSION['basket_prods']['domain']['tr'])>0 || count($_SESSION['basket_prods']['domain']['trf'])>0 || count($_SESSION['basket_prods']['domain']['trtrf'])>0 || count($_SESSION['basket_prods']['domain']['rnw'])>0) {
                          	?>
                    <tr bgcolor="#FAF9F8" class="normal" id="dom-all-container">
                      <td valign="top" class="sepettbl3" height="35"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="12%"><a href="#" onclick="return false;"><img src="images/alanadi.gif" border="0" /></a></td>
                            <td width="88%" class="normal"><b>Alan Adlar񼯢></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top" class="sepettbl3">&nbsp;</td>
                      <td valign="top" class="sepettbl3" id="loader_dom">&nbsp;</td>
                      <td valign="top" class="sepettbl3" style="border-right: solid 1px #d1cec9;" id="dom-section-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td width="30%" align="right"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                            <div id="dom-section-cost">
                              <?=number_format(CalculateTotal("dom"), 2, '.', '')?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    if (count($_SESSION['basket_prods']['domain']['tld'])>0) {
                    	foreach ($_SESSION['basket_prods']['domain']['tld'] as $value) {
							$is_domain_exist = 1;
                    		$relax=explode("-",$_SESSION['quantity'][$value]);
                    		$perform_tld=$_SESSION['added_domains'][$value];
                    		$perform_tld_dext=substr($perform_tld,strpos($perform_tld,".")+1);
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl">.<?=strtoupper((GetDomExt($_SESSION['added_domains'][$value])));?> uzant񬽠alan ad񠫡yd񼢲>
                        <br>
                        Alan ad񠼳pan><b>
                        <?php echo $_SESSION['added_domains'][$value];
                        if ($_SESSION['domain_contacts'][$value]=="") echo "<br><br><a href=\"alanadi4.php\" class=\"ort-bsl\">ެeti򩭠bilgisi eksik!</a> <input type=\"hidden\" name=\"missing_contact\" id=\"missing_contact\" value=\"$value\">";
                        else echo "<br><br><a href=\"alanadi4.php?d=$value\" class=\"krm-link\" style=\"font-size:10px;\">ެeti򩭠bilgisi d𺥮le</a>";
                        ?></b>
                        </span></td>
                      <td align="left" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" height="25" valign="top" class="normal3">1 alan ad񠦮bsp;
                              <select name="dom-quantity-<?=$value;?>" id="dom-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-dom','<?=$value;?>',this.value);">
                                <?php
                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($relax[1]==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                    ?>
                              </select></td>
                            <td class="normal"><input type="radio" name="dom-privacy-<?=$value;?>" value="o"<?php if ($relax[0]=="o") echo "checked";?> onclick="BasketUpdate('privacy','<?=$value;?>','o');" /> A辫 Whois<br>
                              <input type="radio" name="dom-privacy-<?=$value;?>" value="p"<?php if ($relax[0]=="p") echo "checked";?> onclick="BasketUpdate('privacy','<?=$value;?>','p');" /> Gizli Whois <span class="krm-link">Yaln񺣡 <b>$2</b> !</span></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="90%" height="18" class="normal"></td>
                            <td align="right"><a href="javascript:RemoveItem('dom','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),1);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="dom-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="dom-cost-total-<?=$value;?>">
                                <?php
                                $tmp_total=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$relax[1])*$relax[1];
                                if ($relax[0]=="p") $tmp_total+=2;
                                echo number_format($tmp_total, 2, '.', '');
                                ?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    if ($_SESSION['domain_offer'][$value]) {
                    	$regular_total=0;
                    	$tmp_total=0;
                    	$value=$_SESSION['domain_offer'][$value];
                    	$relax=explode("-",$_SESSION['quantity'][$value]);
                    	?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl">׮erilen Alan Adlar񼢲>
                        <br>
                        <span><b>
                        <?php 
                        $j=1;
                        foreach ($_SESSION['basket_prods']['domain']['offer'][$value] as $ovalue) {
							$is_domain_exist = 1;
                        	echo $_SESSION['added_domains'][$ovalue];
                        	if ($_SESSION['domain_contacts'][$ovalue]=="") echo "<br><a href=\"alanadi4.php\" class=\"ort-bsl\">ެeti򩭠bilgisi eksik!</a> <input type=\"hidden\" name=\"missing_contact\" id=\"missing_contact\" value=\"$ovalue\">";
                        	else echo "<br><a href=\"alanadi4.php?d=$ovalue\" class=\"krm-link\" style=\"font-size:10px;\">ެeti򩭠bilgisi d𺥮le</a>";
                        	if ($j!=count($_SESSION['basket_prods']['domain']['offer'][$value])) echo "<hr>";
                        	$regular_total+=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$ovalue])),1);
                        	$tmp_total+=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$ovalue])),$relax[1])*$relax[1]*0.9;
                        	//$tmp_total=$tmp_total*0.9;
                        	if ($relax[0]=="p") $tmp_total+=2;
                        	$j++;
                        }
                        ?></b>
                        </span></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" height="25" valign="top" class="normal3"><?=count($_SESSION['basket_prods']['domain']['offer'][$value]);?> alan ad񠦮bsp;
                              <select name="dom-quantity-<?=$value;?>" id="dom-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-domo','<?=$value;?>',this.value);">
                                <?php
                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($relax[1]==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                ?>
                              </select></td>
                            <td class="normal"><input type="radio" name="dom-privacy-<?=$value;?>" value="o"<?php if ($relax[0]=="o") echo "checked";?> onclick="BasketUpdate('privacyo','<?=$value;?>','o');" /> A辫 Whois<br>
                              <input type="radio" name="dom-privacy-<?=$value;?>" value="p"<?php if ($relax[0]=="p") echo "checked";?> onclick="BasketUpdate('privacyo','<?=$value;?>','p');" /> Gizli Whois <span class="krm-link">Yaln񺣡 <b>$2</b> !</span></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="90%" height="18" class="normal"></td>
                            <td align="right"><a href="javascript:RemoveItem('domoffer','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong><strike>
                              <?=$regular_total;?>
                              </strike></strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="dom-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="dom-cost-total-<?=$value;?>">
                                <?php
                                echo number_format($tmp_total, 2, '.', '');
                                ?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    }
                    	}
                    }
                    if (count($_SESSION['basket_prods']['domain']['trf'])>0) {
                    	foreach ($_SESSION['basket_prods']['domain']['trf'] as $value) {
							$is_domain_exist = 1;
                    		$relax=explode("-",$_SESSION['quantity'][$value]);
                    		$perform_tld=$_SESSION['added_domains'][$value];
                    		$perform_tld_dext=substr($perform_tld,strpos($perform_tld,".")+1);
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl">.<?=strtoupper(GetDomExt($_SESSION['added_domains'][$value]));?> uzant񬽠alan ad񠴲ansferi<br>
                        <br>
                        Alan ad񠼳pan><b>
                        <?php echo $_SESSION['added_domains'][$value];
                        if ($_SESSION['domain_contacts'][$value]=="") echo "<br><br><a href=\"alanadi4.php\" class=\"ort-bsl\">ެeti򩭠bilgisi eksik!</a> <input type=\"hidden\" name=\"missing_contact\" id=\"missing_contact\" value=\"$value\">";
                        else echo "<br><br><a href=\"alanadi4.php?d=$value\" class=\"krm-link\" style=\"font-size:10px;\">ެeti򩭠bilgisi d𺥮le</a>";
                        ?></b>
                        </span></td>
                      <td align="left" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" height="25" valign="top" class="normal3">1 alan ad񠦮bsp;
                              <select name="dom-quantity-<?=$value;?>" id="dom-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-dom','<?=$value;?>',this.value);">
                                <?php
                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($relax[1]==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                    ?>
                              </select></td>
                            <td class="normal"><input type="radio" name="dom-privacy-<?=$value;?>" value="o"<?php if ($relax[0]=="o") echo "checked";?> onclick="BasketUpdate('privacy','<?=$value;?>','o');" /> A辫 Whois<br>
                              <input type="radio" name="dom-privacy-<?=$value;?>" value="p"<?php if ($relax[0]=="p") echo "checked";?> onclick="BasketUpdate('privacy','<?=$value;?>','p');" /> Gizli Whois <span class="krm-link">Yaln񺣡 <b>$2</b> !</span></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="90%" height="18" class="normal"></td>
                            <td align="right"><a href="javascript:RemoveItem('dom','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),1);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="dom-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="dom-cost-total-<?=$value;?>">
                                <?php
                                $tmp_total=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$relax[1])*$relax[1];
                                if ($relax[0]=="p") $tmp_total+=2;
                                echo number_format($tmp_total, 2, '.', '');
                                ?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    if (count($_SESSION['basket_prods']['domain']['tr'])>0) {
                    	foreach ($_SESSION['basket_prods']['domain']['tr'] as $value) {
							$is_domain_exist = 1;
                    		$perform_tld=$_SESSION['added_domains'][$value];
                    		$perform_tld_dext=substr($perform_tld,strpos($perform_tld,".")+1);
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl">.<?=strtoupper(GetDomExt($_SESSION['added_domains'][$value]));?>
                        uzant񬽠alan ad񠫡yd񼢲>
                        <br>
                        Alan ad񠼳pan><b>
                        <?php echo $_SESSION['added_domains'][$value];
                        if ($_SESSION['domain_contacts'][$value]=="") echo "<br><br><a href=\"alanadi4.php\" class=\"ort-bsl\">ެeti򩭠bilgisi eksik!</a> <input type=\"hidden\" name=\"missing_contact\" id=\"missing_contact\" value=\"$value\">";
                        else echo "<br><br><a href=\"alanadi4.php?d=$value\" class=\"krm-link\" style=\"font-size:10px;\">ެeti򩭠bilgisi d𺥮le</a>";
                        ?></b>
                        </span></td>
                      <td align="left" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" height="25" valign="top" class="normal3">1 alan ad񠦮bsp;
                            <select name="dom-quantity-<?=$value;?>" id="dom-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-domtr','<?=$value;?>',this.value);">
                                <?php
                                for ($i=1;$i<=5;$i++) {
                                	echo "<option value=\"$i\""; if ($_SESSION['quantity'][$value]==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                    ?>
                              </select></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="90%" height="18" class="normal"></td>
                            <td align="right"><a href="javascript:RemoveItem('dom','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),1);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="dom-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="dom-cost-total-<?=$value;?>">
                                <?php
                                $tmp_total=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$_SESSION['quantity'][$value])*$_SESSION['quantity'][$value];
                                echo number_format($tmp_total, 2, '.', '');
                                ?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    // tr alan ad񠴲ansferi basla
                    if (count($_SESSION['basket_prods']['domain']['trtrf'])>0) {
                    	foreach ($_SESSION['basket_prods']['domain']['trtrf'] as $value) {
							$is_domain_exist = 1;
                    		$perform_tld=$_SESSION['added_domains'][$value];
                    		$perform_tld_dext=substr($perform_tld,strpos($perform_tld,".")+1);
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl">.<?=strtoupper(GetDomExt($_SESSION['added_domains'][$value]));?>
                        uzant񬽠alan ad񠴲ansferi<br>
                        <br>
                        Alan ad񠼳pan><b>
                        <?php echo $_SESSION['added_domains'][$value];
                        if ($_SESSION['domain_contacts'][$value]=="") echo "<br><br><a href=\"alanadi4.php\" class=\"ort-bsl\">ެeti򩭠bilgisi eksik!</a> <input type=\"hidden\" name=\"missing_contact\" id=\"missing_contact\" value=\"$value\">";
                        else echo "<br><br><a href=\"alanadi4.php?d=$value\" class=\"krm-link\" style=\"font-size:10px;\">ެeti򩭠bilgisi d𺥮le</a>";
                        ?></b>
                        </span></td>
                      <td align="left" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" height="25" valign="top" class="normal3">1 alan ad񠦮bsp;
                            <select name="dom-quantity-<?=$value;?>" id="dom-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-domtr','<?=$value;?>',this.value);">
                                <?php
                                for ($i=1;$i<=5;$i++) {
                                	echo "<option value=\"$i\""; if ($_SESSION['quantity'][$value]==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                    ?>
                              </select></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="90%" height="18" class="normal"></td>
                            <td align="right"><a href="javascript:RemoveItem('dom','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),1);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="dom-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="dom-cost-total-<?=$value;?>">
                                <?php
                                $tmp_total=GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$_SESSION['quantity'][$value])*$_SESSION['quantity'][$value];
                                echo number_format($tmp_total, 2, '.', '');
                                ?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }                    
                    //tr alan ad񠴲ansferi bitti
                    if (count($_SESSION['basket_prods']['domain']['rnw'])>0) {
                    	foreach ($_SESSION['basket_prods']['domain']['rnw'] as $value) {
							$is_domain_exist = 1;
                    		$relax=explode("-",$_SESSION['renew_domain'][$value]);
                    		if ($relax[0]=="d") $tmp_table="pending_domain";
                    		else if ($relax[0]=="e") $tmp_table="pending_emaild";
                    		$domain=strtoupper(GetMasterInfo($tmp_table,"id",$relax[1],"domain"));
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl">.<?=strtoupper((GetDomExt($domain)));?> uzant񬽠alan ad񠹥nileme<br>
                        <br>
                        Alan ad񠼢><?=$domain;?></b></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" height="25" valign="top" class="normal3">1 alan ad񠦮bsp;
                              <select name="dom-quantity-<?=$value;?>" id="dom-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-domrnw','<?=$value;?>',this.value);">
                                <?php
                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($_SESSION['quantity'][$value]==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                    ?>
                              </select></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="90%" height="18" class="normal"></td>
                            <td align="right"><a href="javascript:RemoveItem('dom','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GenerateDomainCost(GenerateDomainKey(GetDomExt($domain)),1);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="dom-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="dom-cost-total-<?=$value;?>">
                                <?php
                                $tmp_total=GenerateDomainCost(GenerateDomainKey(GetDomExt($domain)),$_SESSION['quantity'][$value])*$_SESSION['quantity'][$value];
                                echo number_format($tmp_total, 2, '.', '');
                                ?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    }
                    $sub_total=0;
                    if (count($_SESSION['basket_prods']['hosting'])>0 || count($_SESSION['basket_prods']['hstextra'])>0 || count($_SESSION['basket_prods']['hstrenew'])>0) {
                          	?>
                    <tr bgcolor="#FAF9F8" class="normal" id="hst-all-container">
                      <td valign="top" class="sepettbl3" height="35"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="12%"><a href="#" onclick="return false;"><img src="images/hosting.gif" border="0" /></a></td>
                            <td width="88%" class="normal"><b>Web Alan񼯢></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top" class="sepettbl3">&nbsp;</td>
                      <td class="sepettbl3" align="center" id="loader_hst">&nbsp;</td>
                      <td valign="top" class="sepettbl3" style="border-right: solid 1px #d1cec9;" id="hst-section-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td width="30%" align="right"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="hst-section-cost">
                                <?=number_format(CalculateTotal("hst"), 2, '.', '')?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    if (count($_SESSION['basket_prods']['hosting'])>0) {
                    	foreach ($_SESSION['basket_prods']['hosting'] as $value) {
                    		$relax=explode("-",$_SESSION['added_hostings'][$value]);
                    		$relax2=explode("-",$_SESSION['quantity'][$value]);
                    		if ($relax[0]=="manual") {
                    			$relax3=explode("-",$_SESSION['added_manhostings'][$value]);
                    			$calculate['perunit']=GenerateTmpManualHostingCost($relax3[0],$relax3[1],$relax3[2],$relax3[3],$relax3[4],$relax2[0],1);
                    			$calculate['unittotal']=GenerateTmpManualHostingCost($relax3[0],$relax3[1],$relax3[2],$relax3[3],$relax3[4],$relax2[0],$relax2[1]);
                    		}
                    		else {
                    			$calculate['perunit']=GenerateTmpHostingCost($relax[0],$relax2[0],1);
                    			$calculate['unittotal']=GenerateTmpHostingCost($relax[0],$relax2[0],$relax2[1]);
                    		}
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><?php if ($relax[0]=="manual") echo "Kendi Paketini Olu򴵲"; else echo $var_active_packages[$relax[0]]; echo "<br>".ucfirst($relax[1])." Sunucu";?></td>
                      <td align="left" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php if ($relax[0]=="manual") { echo $relax3[0]."Mb Web Alan񼢲>".$relax3[1]." Gb Trafik<br>".$relax3[2]." Adet E-posta<br>".$relax3[3]."Mb E-Posta Alan񢻠if ($relax3[4]>0) echo "<br>".$relax3[4]." Adet Veritaban񢻠} else echo "1 hosting";?>
                              &nbsp;
                              <select name="hst-quantity-<?=$value;?>" id="hst-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-hst','<?=$value;?>',this.value);">
                                <?php
                                if ($relax2[0]=="month") $select_suffix="AY";
                                else $select_suffix="YIL";
                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($relax2[1]==$i) echo " selected"; echo ">$i $select_suffix</option>";
                                }
                                    ?>
                              </select></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('hst','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=number_format($calculate['perunit'], 2, '.', '');?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="hst-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="hst-cost-total-<?=$value;?>">
                                <?=number_format($calculate['unittotal'], 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    if (count($_SESSION['basket_prods']['hstextra'])>0) {
                    	openDB();
                    	foreach ($_SESSION['basket_prods']['hstextra'] as $value) {
                    		$relax=explode("-",$_SESSION['renew_domain'][$value]);
                    		if ($relax[0]=="r") $tmp_table="pending_rhosting";
                    		else if ($relax[0]=="m") $tmp_table="pending_mhosting";
                    		$sql="SELECT plesk_id, plesk_domain FROM ".TABLE_PREFIX.$tmp_table." WHERE id='".$relax[1]."'";
                    		$cmd=mysql_query($sql);
                    		$num=mysql_num_rows($cmd);
                    		if ($num==1) {
                    			$row=mysql_fetch_array($cmd);
                    			$domain_id=$row['plesk_id'];
                    			$domain_name=$row['plesk_domain'];
                    		}
                    		$details=PleskDomainDetails($domain_id,1,0,0,0,1,0);
                    		$expiration=$details->limits->limit[1]->value;
                    		$oneyear=365*24*60*60;
                    		$diff=$expiration-time();
                    		$relax2=explode("-",$_SESSION['quantity'][$value]);
                    		$calculate['unit']=GenerateManualHostingCost($relax2[0],$relax2[1],$relax2[2],$relax2[3],$relax2[4]);
                    		$calculate['total']=(GenerateManualHostingCost($relax2[0],$relax2[1],$relax2[2],$relax2[3],$relax2[4])*$diff)/$oneyear;
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><b><?=strtoupper($domain_name);?></b><br><br>Web Alan񠅫 ׺ellik</td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php if ($relax2[0]>0) echo $relax2[0]."MB Web Alan񼢲>"; if ($relax2[1]>0) echo $relax2[1]."GB Trafik<br>"; if ($relax2[2]>0) echo $relax2[2]." Adet E-Posta<br>"; if ($relax2[3]>0) echo $relax2[3]."MB E-Posta Alan񼢲>"; if ($relax2[4]>0) echo $relax2[4]." Adet Veritaban񼢲>"; ?></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('hst','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=number_format($calculate['unit'], 2, '.', '');?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="hst-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="hst-cost-total-<?=$value;?>">
                                <?=number_format($calculate['total'], 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    if (count($_SESSION['basket_prods']['hstrenew'])>0) {
                    	foreach ($_SESSION['basket_prods']['hstrenew'] as $value) {
                    		unset($tmp_extra);
                    		$relax=explode("-",$_SESSION['renew_domain'][$value]);
                    		//$relax2=explode("-",$_SESSION['quantity'][$value]);
                    		if ($relax[0]=="m") {
                    			$sql="SELECT id, conf_web, conf_traffic, conf_mails, conf_mailbox, conf_db, plesk_domain FROM ".TABLE_PREFIX."pending_mhosting WHERE id='".$relax[1]."'";
                    			$cmd=mysql_query($sql);
                    			$num=mysql_num_rows($cmd);
                    			if ($num==1) {
                    				$row=mysql_fetch_array($cmd);
                    				$calculate['perunit']=GenerateTmpManualHostingCost($row['conf_web'],$row['conf_traffic'],$row['conf_mails'],$row['conf_mailbox'],$row['conf_db'],"year",1);
                    				$calculate['unittotal']=GenerateTmpManualHostingCost($row['conf_web'],$row['conf_traffic'],$row['conf_mails'],$row['conf_mailbox'],$row['conf_db'],"year",$_SESSION['quantity'][$value]);
                    				$sql2="SELECT * FROM ".TABLE_PREFIX."hosting_extra WHERE hst_type='m' AND hst_id='".$row['id']."' AND status='1'";
                    				$cmd2=mysql_query($sql2);
                    				$num2=mysql_num_rows($cmd2);
                    				if ($num2>0) {
                    					while ($row2=mysql_fetch_array($cmd2)) {
                    						$tmp_extra['web']+=$row2['conf_web'];
                    						$tmp_extra['bw']+=$row2['conf_traffic'];
                    						$tmp_extra['mails']+=$row2['conf_mails'];
                    						$tmp_extra['mailbox']+=$row2['conf_mailbox'];
                    						$tmp_extra['db']+=$row2['conf_db'];
                    						$calculate['perunit']+=GenerateTmpManualHostingCost($row2['conf_web'],$row2['conf_traffic'],$row2['conf_mails'],$row2['conf_mailbox'],$row2['conf_db'],"year",1);
                    						$calculate['unittotal']+=GenerateTmpManualHostingCost($row2['conf_web'],$row2['conf_traffic'],$row2['conf_mails'],$row2['conf_mailbox'],$row2['conf_db'],"year",$_SESSION['quantity'][$value]);
                    					}
                    				}
                    			}
                    		}
                    		else if ($relax[0]=="r") {
                    			$sql="SELECT id, packet_id, plesk_domain FROM ".TABLE_PREFIX."pending_rhosting WHERE id='".$relax[1]."'";
                    			$cmd=mysql_query($sql);
                    			$num=mysql_num_rows($cmd);
                    			if ($num==1) {
                    				$row=mysql_fetch_array($cmd);
                    				$calculate['perunit']=GenerateTmpHostingCost($row['packet_id'],"year",1);
                    				$calculate['unittotal']=GenerateTmpHostingCost($row['packet_id'],"year",$_SESSION['quantity'][$value]);
                    				$sql2="SELECT * FROM ".TABLE_PREFIX."hosting_extra WHERE hst_type='r' AND hst_id='".$row['id']."' AND status='1'";
                    				$cmd2=mysql_query($sql2);
                    				$num2=mysql_num_rows($cmd2);
                    				if ($num2>0) {
                    					while ($row2=mysql_fetch_array($cmd2)) {
                    						$tmp_extra['web']+=$row2['conf_web'];
                    						$tmp_extra['bw']+=$row2['conf_traffic'];
                    						$tmp_extra['mails']+=$row2['conf_mails'];
                    						$tmp_extra['mailbox']+=$row2['conf_mailbox'];
                    						$tmp_extra['db']+=$row2['conf_db'];
                    						$calculate['perunit']+=GenerateTmpManualHostingCost($row2['conf_web'],$row2['conf_traffic'],$row2['conf_mails'],$row2['conf_mailbox'],$row2['conf_db'],"year",1);
                    						$calculate['unittotal']+=GenerateTmpManualHostingCost($row2['conf_web'],$row2['conf_traffic'],$row2['conf_mails'],$row2['conf_mailbox'],$row2['conf_db'],"year",$_SESSION['quantity'][$value]);
                    					}
                    				}
                    			}
                    		}
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><?php if ($relax[0]=="m") echo "Kendi Paketini Olu򴵲"; else echo $var_active_packages[$row['packet_id']]; echo "<br><br>Web Alan񠙥nileme";?></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php if ($relax[0]=="m") { echo $row['conf_web']."Mb Web Alan񬠢.$row['conf_traffic']."Gb Trafik, ".$row['conf_mails']." Adet E-posta, ".$row['conf_mailbox']."Mb E-Posta Alan񢻠if ($row['conf_db']>0) echo ", ".$row['conf_db']." Adet Veritaban񢻠} else echo "1 hosting";
                            if ($tmp_extra) { echo "<br>Ek ׺ellikler : "; if ($tmp_extra['web']) echo $tmp_extra['web']."Mb Web Alan񠢻 if ($tmp_extra['bw']) echo $tmp_extra['bw']."Gb Trafik "; if ($tmp_extra['mails']) echo $tmp_extra['mails']." Adet E-posta "; if ($tmp_extra['mailbox']) echo $tmp_extra['mailbox']." Mb E-Posta Alan񠢻 if ($tmp_extra['db']) echo $tmp_extra['db']." Adet Veritaban񢻠}
                            ?>
                              &nbsp;<br><br>
                              <select name="hst-quantity-<?=$value;?>" id="hst-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-hstrenew','<?=$value;?>',this.value);">
                                <?php
                                if ($row['pay_period']=="month") $select_suffix="AY";
                                else $select_suffix="YIL";
                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($_SESSION['quantity'][$value]==$i) echo " selected"; echo ">$i $select_suffix</option>";
                                }
                                    ?>
                              </select></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('hst','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=number_format($calculate['perunit'], 2, '.', '');?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="hst-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="hst-cost-total-<?=$value;?>">
                                <?=number_format($calculate['unittotal'], 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    }

                    $sub_total=0;
                    if (count($_SESSION['basket_prods']['crd'])>0) {
                          	?>
                    <tr bgcolor="#FAF9F8" class="normal" id="crd-all-container">
                      <td valign="top" class="sepettbl3" height="35"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="12%"><a href="#" onclick="return false;"><img src="images/kredi.gif" border="0" /></a></td>
                            <td width="88%" class="normal"><b>Ok.Net Kredileri</b></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top" class="sepettbl3">&nbsp;</td>
                      <td valign="top" class="sepettbl3" id="loader_crd">&nbsp;</td>
                      <td valign="top" class="sepettbl3" style="border-right: solid 1px #d1cec9;" id="crd-section-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td width="30%" align="right"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                            <div id="crd-section-cost">
                              <?=number_format(CalculateTotal("crd"), 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr class="normal" id="<?=$_SESSION['basket_prods']['crd'];?>">
                      <td valign="top" class="sepettbl">Ok.Net Kredi</td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?=$_SESSION['quantity'][$_SESSION['basket_prods']['crd']];?> kredi &nbsp;</td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('crd','<?=$_SESSION['basket_prods']['crd'];?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>1</strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="crd-cost-total-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="crd-cost-total">
                                <?=number_format(CalculateTotal("crd"), 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    }
                    $sub_total=0;
                    if (count($_SESSION['basket_prods']['ssl'])>0) {
                          	?>
                    <tr bgcolor="#FAF9F8" class="normal" id="ssl-all-container">
                      <td valign="top" class="sepettbl3" height="35"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="12%"><a href="#" onclick="return false;"><img src="images/ssl.gif" border="0" /></a></td>
                            <td width="88%" class="normal"><b>SSL Sertifikalar񼯢></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top" class="sepettbl3">&nbsp;</td>
                      <td valign="top" class="sepettbl3" id="loader_ssl">&nbsp;</td>
                      <td valign="top" class="sepettbl3" style="border-right: solid 1px #d1cec9;" id="ssl-section-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td width="30%" align="right"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                            <div id="ssl-section-cost">
                              <?=number_format(CalculateTotal("ssl"), 2, '.', '')?>
                              </div>
                              </strong>
                              </td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php foreach ($_SESSION['basket_prods']['ssl'] as $key=>$value) {?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><?=$var_ssl_certs[$_SESSION['added_sslcerts'][$value]];?></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3">1 adet
                              <?=$var_ssl_certs[$_SESSION['added_sslcerts'][$value]];?>
                              </td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('ssl','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GetSslCost($_SESSION['added_sslcerts'][$value]);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="ssl-cost-total-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="ssl-cost-total">
                                <?=GetSslCost($_SESSION['added_sslcerts'][$value]);?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    }
                    }
                    $sub_total=0;
                    if (count($_SESSION['basket_prods']['secrd'])>0) {
                          	?>
                    <tr bgcolor="#FAF9F8" class="normal" id="secrd-all-container">
                      <td valign="top" class="sepettbl3" height="35"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="12%"><a href="#" onclick="return false;"><img src="images/search.gif" border="0" /></a></td>
                            <td width="88%" class="normal"><b>Arama Motorlar񮡠Kay񴠋redisi</b></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top" class="sepettbl3">&nbsp;</td>
                      <td valign="top" class="sepettbl3" id="loader_secrd">&nbsp;</td>
                      <td valign="top" class="sepettbl3" style="border-right: solid 1px #d1cec9;" id="secrd-section-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td width="30%" align="right"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                            <div id="secrd-section-cost">
                              <?=number_format(CalculateTotal("secrd"), 2, '.', '')?>
                              </div>
                              </strong>
                              </td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php foreach ($_SESSION['basket_prods']['secrd'] as $key=>$value) {?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><?=$var_se_credits[$_SESSION['added_secrds'][$value]];?></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3">1 Adet</td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('secrd','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GetSeCreditCost($_SESSION['added_secrds'][$value]);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="secrd-cost-total-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="secrd-cost-total">
                                <?=GetSeCreditCost($_SESSION['added_secrds'][$value]);?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    }
                    }
                    $sub_total=0;
                    if (count($_SESSION['basket_prods']['server']['col'])>0 || count($_SESSION['basket_prods']['server']['lea'])>0) {
                          	?>
                    <tr bgcolor="#FAF9F8" class="normal" id="srv-all-container">
                      <td valign="top" class="sepettbl3" height="35"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="12%"><a href="#" onclick="return false;"><img src="images/sunucu.gif" border="0" /></a></td>
                            <td width="88%" class="normal"><b>Sunucular</b></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top" class="sepettbl3">&nbsp;</td>
                      <td valign="top" class="sepettbl3" id="loader_srv">&nbsp;</td>
                      <td valign="top" class="sepettbl3" style="border-right: solid 1px #d1cec9;" id="srv-section-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td width="30%" align="right"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                            <div id="srv-section-cost">
                              <?=number_format(CalculateTotal("srv"), 2, '.', '')?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php if (count($_SESSION['basket_prods']['server']['col'])>0) { 
                        	foreach ($_SESSION['basket_prods']['server']['col'] as $value) {?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl">Sunucu Bulundurma</td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php if ($_SESSION['quantity'][$value]=="m") echo "Taksitli "; else echo "Pe򩮠"; echo $var_server_colocation[$_SESSION['added_servers'][$value]];?>
                              Sunucu Bulundurma</td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('srv','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GetColocationCost($_SESSION['added_servers'][$value],$_SESSION['quantity'][$value]);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="ser-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="ser-cost-total-<?=$value;?>">
                                <?=GetColocationCost($_SESSION['added_servers'][$value],$_SESSION['quantity'][$value]);?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                        	}
                    }
                    if (count($_SESSION['basket_prods']['server']['lea'])>0) {
                        	foreach ($_SESSION['basket_prods']['server']['lea'] as $value) {?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl">Sunucu Kiralama</td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php if ($_SESSION['quantity'][$value]=="month") echo "1 ayl񫠢; else echo "1 y񬬽k "; echo $var_server_leasing[$_SESSION['added_servers'][$value]];?>
                              Sunucu Kiralama</td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('srv','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GetLeasingCost($_SESSION['added_servers'][$value],$_SESSION['quantity'][$value]);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="ser-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="ser-cost-total-<?=$value;?>">
                                <?=GetLeasingCost($_SESSION['added_servers'][$value],$_SESSION['quantity'][$value]);?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                        	}
                    }
                    }
                    // email start
                    $sub_total=0;
                    if (count($_SESSION['basket_prods']['email'])>0 || count($_SESSION['basket_prods']['emlextra'])>0 || count($_SESSION['basket_prods']['emlrenew'])>0) {
                          	?>
                    <tr bgcolor="#FAF9F8" class="normal" id="eml-all-container">
                      <td valign="top" class="sepettbl3" height="35"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="12%"><a href="#" onclick="return false;"><img src="images/eposta.gif" border="0" /></a></td>
                            <td width="88%" class="normal"><b>E-Posta</b></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top" class="sepettbl3">&nbsp;</td>
                      <td valign="top" class="sepettbl3" id="loader_eml">&nbsp;</td>
                      <td valign="top" class="sepettbl3" style="border-right: solid 1px #d1cec9;" id="eml-section-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td width="30%" align="right"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                            <div id="eml-section-cost">
                              <?=number_format(CalculateTotal("eml"), 2, '.', '')?>
                              </div>
                              </strong>
                              </td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php if (count($_SESSION['basket_prods']['email'])>0) {
                    	foreach ($_SESSION['basket_prods']['email'] as $key=>$value) {
                    		if (strstr($_SESSION['quantity'][$value],"-")) {
								$is_domain_exist = 1;
                    			$email_pack_type="domemail";
                    			$relax=explode("-",$_SESSION['quantity'][$value]);
                    			$email_pack_quantity=$relax[1];
                    		}
							elseif (strstr($_SESSION['quantity'][$value],"|")) {
								$is_domain_exist = 1;
                    			$email_pack_type="domemailadwords";
                    			$relax=explode("|",$_SESSION['quantity'][$value]);
                    			$email_pack_quantity=$relax[1];
                    		}
                    		else {
                                $email_pack_type="email";
                    			$email_pack_quantity=$_SESSION['quantity'][$value];
                    		}
                    	?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><?php 
                      if ($email_pack_type=="domemail") echo "Alan Ad񠫠E-Posta<br><br>";
					  if ($email_pack_type=="domemailadwords") echo "Google Adwords Hediyeli<br>Alan Ad񠫠E-Posta<br><br>";
                      if (strstr($_SESSION['added_emails'][$value],"-")) echo "Kendi E-Posta Paketini Olu򴵲";
                      else if (strstr($_SESSION['added_emails'][$value],"h")) echo $var_email_hc_packages[$_SESSION['added_emails'][$value]];
                      else echo $var_email_packages[$_SESSION['added_emails'][$value]];
                      if ($email_pack_type=="domemail") { ?>
                      <br><br>Alan ad񠼳pan class="krm-link">
                        <?php echo $_SESSION['added_domains'][$value];
                        if ($_SESSION['domain_contacts'][$value]=="") echo "<br><a href=\"alanadi4.php\" class=\"ort-bsl\">ެeti򩭠bilgisi eksik!</a> <input type=\"hidden\" name=\"missing_contact\" id=\"missing_contact\" value=\"$value\">";
                        else echo "<br><a href=\"alanadi4.php?d=$value\" class=\"krm-link\" style=\"font-size:10px;\">ެeti򩭠bilgisi d𺥮le</a>";
                        ?>
                        </span>
                       <?php } ?>
					   <?php if ($email_pack_type=="domemailadwords") { ?>
                      <br><br>Alan ad񠼳pan class="krm-link">
                        <?php echo $_SESSION['added_domains'][$value];
                        if ($_SESSION['domain_contacts'][$value]=="") echo "<br><a href=\"alanadi4.php?d=$value\" class=\"ort-bsl\">ެeti򩭠bilgisi eksik!</a> <input type=\"hidden\" name=\"missing_contact\" id=\"missing_contact\" value=\"$value\">";
                        else echo "<br><a href=\"alanadi4.php?d=$value\" class=\"krm-link\" style=\"font-size:10px;\">ެeti򩭠bilgisi d𺥮le</a>";
                        ?>
                        </span>
                       <?php } ?>
					 </td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php if (strstr($_SESSION['added_emails'][$value],"-")) { $relax=explode("-",$_SESSION['added_emails'][$value]); echo $relax[0]." adet e-posta<br>".$relax[1]." MB toplam alan"; } else if (strstr($_SESSION['added_emails'][$value],"h")) echo "1 adet ".$var_email_hc_packages[$_SESSION['added_emails'][$value]]; else echo "1 adet ".$var_email_packages[$_SESSION['added_emails'][$value]]; 
                            if ($email_pack_type=="domemail") echo " + .".strtoupper(GetDomExt($_SESSION['added_domains'][$value]))." uzant񬽠alan ad񠫡yd񼢲><br>";
                            elseif ($email_pack_type=="domemailadwords") echo " + .".strtoupper(GetDomExt($_SESSION['added_domains'][$value]))." uzant񬽠alan ad񠫡yd񠫠Google Adwords<br><br>";
							else echo "&nbsp;&nbsp;";
                            ?>
                              <select name="email-quantity-<?=$value;?>" id="email-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-email','<?=$value;?>',this.value);">
                                <?php
                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($email_pack_quantity==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                    ?>
                              </select></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('eml','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?php if ($email_pack_type=="domemail") echo GetEmailCost($_SESSION['added_emails'][$value])+GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])));
								elseif ($email_pack_type=="domemailadwords") { if(strstr($_SESSION['added_domains'][$value], ".COM.TR")) echo "24.00"; else echo "12.00"; }
								else echo GetEmailCost($_SESSION['added_emails'][$value]);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="email-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="email-cost-total-<?=$value;?>">
                                <?php if ($email_pack_type=="domemail") { echo number_format(floatval(GetEmailCost($_SESSION['added_emails'][$value],$email_pack_quantity,true))+floatval(GenerateDomainCost(GenerateDomainKey(GetDomExt($_SESSION['added_domains'][$value])),$email_pack_quantity,true)*$email_pack_quantity),2,'.',''); }
								elseif ($email_pack_type=="domemailadwords") { if(strstr($_SESSION['added_domains'][$value], ".COM.TR")) echo number_format(floatval(24.00 * $email_pack_quantity),2,'.',''); else echo number_format(floatval(12.00 * $email_pack_quantity),2,'.',''); }
								else { echo GetEmailCost($_SESSION['added_emails'][$value],$email_pack_quantity); }?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    if (count($_SESSION['basket_prods']['emlextra'])>0) {
                    	openDB();
                    	foreach ($_SESSION['basket_prods']['emlextra'] as $value) {
                    		$relax=explode("-",$_SESSION['renew_domain'][$value]);
                    		if ($relax[0]=="r") {
                    			$domain_id=GetMasterInfo("pending_emailr","id",$relax[1],"plesk_id");
                    			$domain_name=GetMasterInfo("pending_emailr","id",$relax[1],"plesk_domain");
                    		}
                    		else if ($relax[0]=="d") {
                    			$domain_id=GetMasterInfo("pending_emaild","id",$relax[1],"plesk_id");
                    			$domain_name=GetMasterInfo("pending_emaild","id",$relax[1],"domain");
                    		}
                    		$details=PleskDomainDetails($domain_id,1,0,0,0,1,0);
                    		$expiration=$details->limits->limit[1]->value;
                    		$oneyear=365*24*60*60;
                    		$diff=$expiration-time();
                    		$relax2=explode("-",$_SESSION['quantity'][$value]);
                    		$calculate['unit']=GenerateManualEmailCost($relax2[0],$relax2[1]);
                    		$calculate['total']=(GenerateManualEmailCost($relax2[0],$relax2[1])*$diff)/$oneyear;
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><b><?=strtoupper($domain_name);?></b><br><br>E-Posta Ek ׺ellik</td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php if ($relax2[0]>0) echo $relax2[0]." Adet E-Posta<br>"; if ($relax2[1]>0) echo $relax2[1]."MB E-Posta Alan񢻠?></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('eml','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=number_format($calculate['unit'], 2, '.', '');?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="eml-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="hst-cost-total-<?=$value;?>">
                                <?=number_format($calculate['total'], 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    if (count($_SESSION['basket_prods']['emlrenew'])>0) {
                    	foreach ($_SESSION['basket_prods']['emlrenew'] as $value) {
                    		unset($tmp_extra);
                    		$relax=explode("-",$_SESSION['renew_domain'][$value]);
                    		//$relax2=explode("-",$_SESSION['quantity'][$value]);
                    		if ($relax[0]=="d") {
                    			$sql="SELECT id, packet_id, domain FROM ".TABLE_PREFIX."pending_emaild WHERE id='".$relax[1]."'";
                    			$cmd=mysql_query($sql);
                    			$num=mysql_num_rows($cmd);
                    			if ($num==1) {
                    				$row=mysql_fetch_array($cmd);
                    				$domain_name=strtoupper($row['domain']);
                    				$calculate['perunit']=GetEmailCost($row['packet_id'],1,true);
                    				$calculate['unittotal']=GetEmailCost($row['packet_id'],$_SESSION['quantity'][$value],true);
                    				$sql2="SELECT * FROM ".TABLE_PREFIX."email_extra WHERE hst_type='d' AND hst_id='".$row['id']."' AND status='1'";
                    				$cmd2=mysql_query($sql2);
                    				$num2=mysql_num_rows($cmd2);
                    				if ($num2>0) {
                    					while ($row2=mysql_fetch_array($cmd2)) {
                    						$tmp_extra['mails']+=$row2['conf_mails'];
                    						$tmp_extra['mailbox']+=$row2['conf_mailbox'];
                    						$calculate['perunit']+=GetEmailCost($row2['conf_mails']."-".$row2['conf_mailbox'],1,true);
                    						$calculate['unittotal']+=GetEmailCost($row2['conf_mails']."-".$row2['conf_mailbox'],$_SESSION['quantity'][$value],true);
                    					}
                    				}
                    			}
                    		}
                    		else if ($relax[0]=="r") {
                    			$sql="SELECT id, packet_id, plesk_domain FROM ".TABLE_PREFIX."pending_emailr WHERE id='".$relax[1]."'";
                    			$cmd=mysql_query($sql);
                    			$num=mysql_num_rows($cmd);
                    			if ($num==1) {
                    				$row=mysql_fetch_array($cmd);
                    				$domain_name=strtoupper($row['plesk_domain']);
                    				$calculate['perunit']=GetEmailCost($row['packet_id'],1,true);
                    				$calculate['unittotal']=GetEmailCost($row['packet_id'],$_SESSION['quantity'][$value],true);
                    				$sql2="SELECT * FROM ".TABLE_PREFIX."email_extra WHERE hst_type='r' AND hst_id='".$row['id']."' AND status='1'";
                    				$cmd2=mysql_query($sql2);
                    				$num2=mysql_num_rows($cmd2);
                    				if ($num2>0) {
                    					while ($row2=mysql_fetch_array($cmd2)) {
                    						$tmp_extra['mails']+=$row2['conf_mails'];
                    						$tmp_extra['mailbox']+=$row2['conf_mailbox'];
                    						$calculate['perunit']+=GetEmailCost($row2['conf_mails']."-".$row2['conf_mailbox'],1,true);
                    						$calculate['unittotal']+=GetEmailCost($row2['conf_mails']."-".$row2['conf_mailbox'],$_SESSION['quantity'][$value],true);
                    					}
                    				}
                    			}
                    		}
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><?php if (strstr($row['packet_id'],"-")) echo "Kendi Paketini Olu򴵲"; else echo $var_email_packages[$row['packet_id']]; echo "<br><br>E-Posta Yenileme<br><br><b>".$domain_name."</b>";?></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php if (strstr($row['packet_id'],"-")) { $relax2=explode("-",$row['packet_id']); echo $relax2[0]." Adet E-posta, ".$relax2[1]."Mb E-Posta Alan񢻠} else echo "1 e-posta paketi";
                            if ($tmp_extra) { echo "<br>Ek ׺ellikler : "; if ($tmp_extra['mails']) echo $tmp_extra['mails']." Adet E-posta "; if ($tmp_extra['mailbox']) echo $tmp_extra['mailbox']." Mb E-Posta Alan񠢻 }
                            ?>
                              &nbsp;<br><br>
                              <select name="eml-quantity-<?=$value;?>" id="eml-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-emlrenew','<?=$value;?>',this.value);">
                                <?php
                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($_SESSION['quantity'][$value]==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                    ?>
                              </select></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('eml','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=number_format($calculate['perunit'], 2, '.', '');?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="email-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="email-cost-total-<?=$value;?>">
                                <?=number_format($calculate['unittotal'], 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    }
                    // emails finish
                    //vendor start
                    $sub_total=0;

                    if (count($_SESSION['basket_prods']['vendor'])>0 || count($_SESSION['basket_prods']['vndextra'])>0 || count($_SESSION['basket_prods']['vndrenew'])>0) {
                          	?>
                    <tr bgcolor="#FAF9F8" class="normal" id="vnd-all-container">
                      <td valign="top" class="sepettbl3" height="35"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="12%"><a href="#" onclick="return false;"><img src="images/bayi.gif" border="0" /></a></td>
                            <td width="88%" class="normal"><b>Bayi Paketleri</b></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top" class="sepettbl3">&nbsp;</td>
                      <td valign="top" class="sepettbl3" id="loader_vnd">&nbsp;</td>
                      <td valign="top" class="sepettbl3" style="border-right: solid 1px #d1cec9;" id="vnd-section-container"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td width="30%" align="right"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                            <div id="vnd-section-cost">
                              <?=number_format(CalculateTotal("vnd"), 2, '.', '')?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    if (count($_SESSION['basket_prods']['vendor'])>0) {
                    	foreach ($_SESSION['basket_prods']['vendor'] as $value) {
                    		$relax=explode("-",$_SESSION['added_vndpacks'][$value]);
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><?=$var_vendor_packages[$relax[0]]."<br>".$var_server_types[$relax[1]]." Sunucu";?></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3">1 bayi paketi &nbsp;
                              <select name="vnd-quantity-<?=$value;?>" id="vnd-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-vnd','<?=$value;?>',this.value);">
                                <?php
                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($_SESSION['quantity'][$value]==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                    ?>
                              </select></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('vnd','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=GetVendorCost($relax[0]);?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="vnd-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="vnd-cost-total-<?=$value;?>">
                                <?=number_format(GetVendorCost($relax[0],$_SESSION['quantity'][$value]), 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    if (count($_SESSION['basket_prods']['vndextra'])>0) {
                    	openDB();
                    	foreach ($_SESSION['basket_prods']['vndextra'] as $value) {

                    		$client_id=GetMasterInfo("pending_vendor","id",$_SESSION['renew_domain'][$value],"plesk_id");
                    		$details=PleskClientDetails($client_id,1,0,0,1,0);
                    		$client_name=$details->gen_info->pname." / ".$details->gen_info->login;
                    		$expiration=$details->limits->limit[1]->value;
                    		$oneyear=365*24*60*60;
                    		$diff=$expiration-time();
                    		$relax2=explode("-",$_SESSION['quantity'][$value]);
                    		$calculate['unit']=GenerateManualVendorCost($relax2[0],$relax2[1],$relax2[2],$relax2[3],$relax2[4],1,false);
                    		$calculate['total']=(GenerateManualVendorCost($relax2[0],$relax2[1],$relax2[2],$relax2[3],$relax2[4],1,false)*$diff)/$oneyear;
                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><b><?=$client_name;?></b><br><br>Bayi Paketi Ek ׺ellik</td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php if ($relax2[0]>0) echo $relax2[0]." Adet Alan Ad񼢲>"; if ($relax2[1]>0) echo $relax2[1]."MB Web Alan񼢲>"; if ($relax2[2]>0) echo $relax2[2]."GB Trafik<br>"; if ($relax2[3]>0) echo $relax2[3]." Adet E-Posta<br>"; if ($relax2[4]>0) echo $relax2[4]." Adet Veritaban񼢲>"; ?></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('vnd','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=number_format($calculate['unit'], 2, '.', '');?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="vnd-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="vnd-cost-total-<?=$value;?>">
                                <?=number_format($calculate['total'], 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    if (count($_SESSION['basket_prods']['vndrenew'])>0) {
                    	foreach ($_SESSION['basket_prods']['vndrenew'] as $value) {
                    		unset($tmp_extra);


                    		$packet_id=GetMasterInfo("pending_vendor","id",$_SESSION['renew_domain'][$value],"packet_id");
                    		$client_id=GetMasterInfo("pending_vendor","id",$_SESSION['renew_domain'][$value],"plesk_id");



                    		$details=PleskClientDetails($client_id,1,0,0,0,0);
                    		$client_name=$details->gen_info->pname." / ".$details->gen_info->login;
                    		$cost['unit']=GetVendorCost($packet_id,1);
                    		$cost['total']=GetVendorCost($packet_id,$_SESSION['quantity'][$value]);
                    		$sql2="SELECT * FROM ".TABLE_PREFIX."vendor_extra WHERE hst_id='".$_SESSION['renew_domain'][$value]."' AND status='1'";
                    		$cmd2=mysql_query($sql2);
                    		$num2=mysql_num_rows($cmd2);
                    		if ($num2>0) {
                    			while ($row2=mysql_fetch_array($cmd2)) {
                    				$tmp_extra['dom']+=$row2['conf_dom'];
                    				$tmp_extra['web']+=$row2['conf_web'];
                    				$tmp_extra['bw']+=$row2['conf_traffic'];
                    				$tmp_extra['mails']+=$row2['conf_mails'];
                    				$tmp_extra['db']+=$row2['conf_db'];
                    				$cost['unit']+=GenerateManualVendorCost($row2['conf_dom'],$row2['conf_web'],$row2['conf_traffic'],$row2['conf_mails'],$row2['conf_db'],1,false);
                    				$cost['total']+=GenerateManualVendorCost($row2['conf_dom'],$row2['conf_web'],$row2['conf_traffic'],$row2['conf_mails'],$row2['conf_db'],$_SESSION['quantity'][$value],false);
                    			}
                    		}

                          		?>
                    <tr class="normal" id="<?=$value;?>">
                      <td valign="top" class="sepettbl"><?=$var_vendor_packages[$packet_id]."<br><br>Bayi Paketi Yenileme";?></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="25" valign="top" class="normal3"><?php echo "1 bayi paketi";
                            if ($tmp_extra) { echo "<br>Ek ׺ellikler : "; if ($tmp_extra['dom']) echo $tmp_extra['dom']." Adet Alan Ad񠢻 if ($tmp_extra['web']) echo $tmp_extra['web']."Mb Web Alan񠢻 if ($tmp_extra['bw']) echo $tmp_extra['bw']."Gb Trafik "; if ($tmp_extra['mails']) echo $tmp_extra['mails']." Adet E-posta "; if ($tmp_extra['db']) echo $tmp_extra['db']." Adet Veritaban񢻠}
                            ?>
                              &nbsp;<br><br>
                              <select name="vnd-quantity-<?=$value;?>" id="vnd-quantity-<?=$value;?>" class="ortaform2" onchange="BasketUpdate('quantity-vndrenew','<?=$value;?>',this.value);">
                                <?php

                                for ($i=1;$i<=10;$i++) {
                                	echo "<option value=\"$i\""; if ($_SESSION['quantity'][$value]==$i) echo " selected"; echo ">$i YIL</option>";
                                }
                                    ?>
                              </select></td>
                          </tr>
                        </table>
                        <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="right"><a href="javascript:RemoveItem('vnd','<?=$value;?>');"><img src="images/kaldirbt.gif" alt="Bu 𲼮𠳥petten kald񲢠border="0" width="62" height="20" /></a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top" class="sepettbl"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <?=number_format($cost['unit'], 2, '.', '');?>
                              </strong></td>
                          </tr>
                        </table></td>
                      <td valign="top" class="sepettbl2" id="vnd-cost-total-container-<?=$value;?>"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr valign="top" class="normal">
                            <td align="right" width="30%"><b>$</b></td>
                            <td height="18" align="left" width="70%"><strong>
                              <div id="vnd-cost-total-<?=$value;?>">
                                <?=number_format($cost['total'], 2, '.', '');?>
                              </div>
                              </strong></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
                    	}
                    }
                    }
                    //vendor finish
                          ?>
                    <tr class="normal">
                      <td height="35" colspan="4" class="sepettbl2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%">&nbsp;</td>
                            <td align="right"><table border="0" cellspacing="0" cellpadding="3">
                                <tr id="total-section-container">
                                <td id="loader_total" height="30">&nbsp;</td>
                                  <td class="host-sag-baslik">TOPLAM ݃RET ($)</td>
                                  <td width="90" align="right"><table border="0" cellpadding="1" cellspacing="0"><tr><td class="host-sag-baslik"><strong><font color="#D82682">$</font></strong></td><td class="host-sag-baslik"><strong><font color="#D82682"><div id="total-section-cost"><?=number_format(CalculateTotal(), 2, '.', ',');?></div></font></strong></td></tr></table></td>
                                </tr>
                                <tr id="totaltr-section-container">
                                <td id="loader_totaltr" height="30">&nbsp;</td>
                                  <td class="host-sag-baslik">TOPLAM ݃RET (TL)<br>KDV DAHތ</td>
                                  <td width="90" align="right" valign="top"><table border="0" cellpadding="1" cellspacing="0"><tr><td class="host-sag-baslik"><strong><font color="#D82682"><div id="totaltr-section-cost"><?=number_format(CalculateTotal()*GetCurrency(), 2, '.', ',');?></div></font></strong></td><td class="host-sag-baslik"> <strong><font color="#D82682"> TL</font></strong></td></tr></table></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </form>
                </table></td>
            </tr>
            <script type="text/javascript">
            function BasketEmpty (method) {
            	if (method=="inline") {
            		if (confirm('Sepetinizdeki b𴼮 𲼮ler silicek. Bu i\u015flemi ger覫le򴩲mek istedi𩮩zden emin misiniz?')) {
            			document.basket4252.basket_empty.value=method;
            			document.basket4252.submit();
            			return true;
            		}
            	}
            	else {
            		document.basket4252.basket_empty.value=method;
            		document.basket4252.submit();
            		return true;
            	}
            }
                   </script>
            <tr>
              <td colspan="2" class="grs-yazi"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="payment_types_top">
                  <tr>
                    <td width="5%"><span class="fiyat"><img src="images/sepet_2ico.gif" width="35" height="35" /></span></td>
                    <td width="25%" class="basliklar" align="left">פeme ߥklini Se誮iz :</td>
                    <td align="left" width="45%"><div id="payment_types-loader" style="display:none;" class="normal"><img src="images/arrows.gif" /> <b>L𴦥n Bekleyiniz...</b></div></td>
                    <td width="25%" align="right" valign="top" class="basliklar" rowspan="3"><a href="javascript:window.location='<?php if (strstr($_SERVER['HTTP_REFERER'],"alanadi4.php")) echo "alanadi.php"; else echo $_SERVER['HTTP_REFERER'];?>';" onMouseOver="window.status='Al\u0131򶥲i򥠄evam';return true;" onMouseOut="window.status='';return true;"><img src="images/keep.gif" width="168" height="40" border="0" /></a></td>
                  </tr>
                  <tr>
                    <td height="11" colspan="3"><table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#efefef">
                        <tr>
                          <td></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td height="11">&nbsp;</td>
                    <td height="11" colspan="2" align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="3%" height="18"><input type="radio" name="payment" id="payment_ccard" value="ccard" onclick="new Ajax.Updater('payment_area','payment_types.php',{parameters:{ptype:'ccard'},evalScripts:true,onLoading:function(){$('payment_types-loader').setStyle({display:'block'});},onComplete:function(){$('payment_types-loader').setStyle({display:'none'});new Effect.ScrollTo('payment_area');new Effect.Highlight('payment_area');}});" /></td>
                          <td width="97%" height="18" class="normal3"><strong>
                            <label for="payment_ccard">Kredi Kart񼯬abel>
                            </strong></td>
                        </tr>
                        <tr>
                          <td height="18"><input type="radio" name="payment" id="payment_mtransfer" value="mtransfer" onclick="new Ajax.Updater('payment_area','payment_types.php',{parameters:{ptype:'mtransfer'},evalScripts:true,onLoading:function(){$('payment_types-loader').setStyle({display:'block'});},onComplete:function(){$('payment_types-loader').setStyle({display:'none'});new Effect.ScrollTo('payment_area');new Effect.Highlight('payment_area');}});" /></td>
                          <td height="18" class="normal3"><strong>
                            <label for="payment_mtransfer">Banka Havalesi</label>
                            </strong></td>
                        </tr>
                        <tr>
                          <td height="18"><input type="radio" name="payment" id="payment_mailorder" value="mailorder" onclick="new Ajax.Updater('payment_area','payment_types.php',{parameters:{ptype:'mailorder'},evalScripts:true,onLoading:function(){$('payment_types-loader').setStyle({display:'block'});},onComplete:function(){$('payment_types-loader').setStyle({display:'none'});new Effect.ScrollTo('payment_area');new Effect.Highlight('payment_area');}});" /></td>
                          <td height="18" class="normal3"><strong>
                            <label for="payment_mailorder">Mail Order</label>
                            </strong></td>
                        </tr>
                        <tr>
                          <td height="18"><input type="radio" name="payment" id="payment_hstcredit" value="hstcredit" onclick="new Ajax.Updater('payment_area','payment_types.php',{parameters:{ptype:'hstcredit'},evalScripts:true,onLoading:function(){$('payment_types-loader').setStyle({display:'block'});},onComplete:function(){$('payment_types-loader').setStyle({display:'none'});new Effect.ScrollTo('payment_area');new Effect.Highlight('payment_area');}});" /></td>
                          <td height="18" class="normal3"><strong>
                            <label for="payment_hstcredit">Ok.Net Kredi</label>
                            </strong></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td align="left"><div id="payment_area"></div></td>
            </tr>
            <tr align="center">
              <td colspan="2" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="100%" height="21"><table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#efefef">
                        <tr>
                          <td></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td colspan="2" class="grs-yazi" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="payment_types_top">
                  <tr>
                    <td width="5%"><span class="fiyat"><img src="images/sepet_3ico.gif" width="35" height="35" /></span></td>
                    <td width="25%" class="basliklar" align="left">Fatura Adresinizi Se誮iz :</td>
                    <td align="left" width="45%"><div id="billing_addrs-loader" style="display:none;" class="normal"><img src="images/arrows.gif" /> <b>L𴦥n Bekleyiniz...</b></div></td>
                  </tr>
                  <tr>
                    <td height="11" colspan="3"><table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#efefef">
                        <tr>
                          <td></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td height="11">&nbsp;</td>
                    <td height="11" colspan="2" align="left">
                    <script type="text/javascript">
                    function Reload () {
                    	new Ajax.Request('fatura_adresi.php',{
                    		parameters:{action:'reload',type:'input'},
                    		onSuccess:function(transport){
                    			$('billing_addrs').update(transport.responseText);
                    		}
                    	});
                    }
    	</script>
                    <div id="billing_addrs" class="normal">
	    <?php
	    $sql="SELECT id, name, firm FROM ".TABLE_PREFIX."billing_addrs WHERE costumer_id='".$userinfo[0]."' ORDER BY id ASC";
	    $cmd=mysql_query($sql);
	    $num=mysql_num_rows($cmd);
	    if ($num>0) {
	    	echo '<form name="billing_addr_form" id="billing_addr_form" action="#" method="POST">
		<table border="0" cellspacing="0" cellpadding="3" class="normal">';
	    	while ($row=mysql_fetch_array($cmd)) {
	    		echo "<tr><td><input type=\"radio\" name=\"billing_address\" value=\"".$row['id']."\"></td><td><span id=\"tooltip-".$row['id']."\" style=\"cursor:pointer;\">".$row['name']; if ($row['firm']!="") echo " - ".$row['firm']; echo "</span> <script> TooltipManager.addAjax('tooltip-".$row['id']."', {url: 'fatura_adresi.php?action=get&id=".$row['id']."', options: {method: \"get\"}}); </script></td></tr>\n";
	    	}
	    	echo '</table></form>';
	    }
	    else echo "Hi硦atura adresi tan񭬡mam񾳽n񺮠<input type=\"hidden\" name=\"billing_addr_null\" id=\"billing_addr_null\">";
	    ?>
	    </div>
	    <div style="padding-top:5px;"><a href="fatura_adresi.php" class="krm-link" onclick="return hs.htmlExpand(this, { contentId: 'newbilling', objectType: 'iframe', objectWidth: 655, objectHeight: 600} )" target="_blank"><img src="images/arti5.gif" id="addbilling_address_img" border="0" /> <b>Yeni Fatura Adresi Tan񭬡</b></a></div>
    	<div class="highslide-html-content" id="newbilling" style="width: 655px">
	      <div class="highslide-move" style="border: 0; height: 18px; padding: 2px;"> <a href="javascript:void(0);" onclick="hs.close(this); Reload();" class="control">Kapat</a> </div>
	      <div class="highslide-body"></div>
	      <div style="text-align: center; border-top: 1px solid silver; padding: 5px 0"> <small>Yeni Fatura Adresi Tan񭬡ma</small> </div>
	    </div>
                    </td>
                  </tr>
                </table></td>
            </tr>
            <tr align="center">
              <td colspan="2" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="100%" height="21"><table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#efefef">
                        <tr>
                          <td></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td colspan="2" class="grs-yazi" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="5%" valign="top"><span class="fiyat"><img src="images/sepet_4ico.gif" width="35" height="35" /></span></td>
                    <td width="95%" class="basliklar"><table width="91%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="3%" height="18"><input type="checkbox" name="user_aggreement" id="user_aggreement" value="true" /></td>
                          <td width="97%" height="18" class="normal3"><a href="https://www.ok.net/sozlesme.html" onclick="return hs.htmlExpand(this, { contentId: 'aggreement', objectType: 'iframe', objectWidth: 655, objectHeight: 600} )" class="krm-link">S򺬥򭥹i</a>
                            <div class="highslide-html-content" id="aggreement" style="width: 655px">
                              <div class="highslide-move" style="border: 0; height: 18px; padding: 2px;"> <a href="#" onclick="return hs.close(this)" class="control">Kapat</a> </div>
                              <div class="highslide-body"></div>
                              <div style="text-align: center; border-top: 1px solid silver; padding: 5px 0"> <small>Kullan񭠓򺬥򭥳i</small> </div>
                            </div>
                            <label for="user_aggreement">okudum kabul ediyorum</label></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td height="11" colspan="2">&nbsp;</td>
                  </tr>
                </table></td>
            </tr>
<script type="text/javascript" src="js/creditcard.js"></script>            
<script type="text/javascript">
function PaymentControl () {
	var checkloaderimg='<table border="0" cellpadding="2" cellspacing="0"><tr><td><img src="images/arrows3.gif"></td><td class="normal" style="font-size:9px;"><b>L𴦥n Bekleyiniz...</b></td></tr></table>';
	var bill=false;
	if ($('billing_addr_form')) {
		var form = $('billing_addr_form');
		var buttons = form.getInputs('radio', 'billing_address');
		var count=buttons.length;
		for (var i=0;i < count;i++) {
			if (buttons[i].checked==true) bill=buttons[i].value;
		}
	}
	if (!$F('payment_ccard') && !$F('payment_mtransfer') && !$F('payment_mailorder') && !$F('payment_hstcredit')) {
		alert('פeme \u015feklinizi se讥lisiniz!');
		new Effect.ScrollTo('payment_types_top');
		return false;
	}
	else if (!$('billing_addr_form')) {
		alert('Fatura adresi tan\u0131mlamal񳽮񺡧);
		new Effect.ScrollTo('billing_addrs');
		return false;
	}
	else if (bill==false) {
		alert('Fatura adresini se讥lisiniz!');
		new Effect.ScrollTo('billing_addrs');
		return false;
	}
	else if (!$F('user_aggreement')) {
		alert('Kullan\u0131m s򺬥򭥳ini kabul etmelisiniz!');
		new Effect.ScrollTo('user_aggreement');
		return false;
	}
	else if ($('missing_contact') && $F('missing_contact')) {
		alert('Sepetinizde ileti\u015fim bilgisi eklenmemi򠡬an ad񠢵lunmaktad񲡧);
		new Effect.ScrollTo('missing_contact');
		return false;
	}
	else {
		if ($F('payment_ccard')) {
			cvv2codeErr = false;
			cvv2code = $F('cvv2').replace (/\s/g, "");
			var cvv2Code = cvv2code
			var cvv2exp = /^[0-9]{3}$/;
			if (!cvv2exp.exec(cvv2Code))  {
				cvv2codeErr = true;
			}

			if (!$F('ccardname')) {
				alert('Kredi kart\u0131n񺽮 𺥲indeki ismi yazmal񳽮񺡧);
				$('ccardname').focus();
				return false;
			}
			else if (!checkCreditCard($F('ccardno'),$F('ccardtype'))) {
				alert(ccErrors[ccErrorNo]);
				$('ccardno').focus();
				return false;
			}
			else if ($F('cvv2').length == 0)  {
				alert('Kredi kart\u0131n񺽮 g𶥮lik numaras񮽠yazmal񳽮񺡧);
				$('cvv2').focus();
				return false;
			}
			else if (cvv2codeErr)  {
				alert('Kredi kart\u0131n񺽮 g𶥮lik numaras񮽠ge覲siz!');
				$('cvv2').focus();
				return false;
			}
			else {
				$('checkout_button').update(checkloaderimg);
				<?php if($is_domain_exist == 1) {?>
				document.location = 'payment_3d.php?ccardno=' + $F('ccardno') + '&cvv2=' + $F('cvv2') + '&bill=' + bill + '&ccexpyear=' + $F('ccexpyear') + '&ccexpmonth=' + $F('ccexpmonth') + '&cardtype=' + $F('ccardtype');
				<?php } else { ?>
				new Ajax.Request('payment_finish.php', {
					parameters:{payment_type:'ccard',billing:bill,ccardname:$F('ccardname'),ccardtype:$F('ccardtype'),ccardno:$F('ccardno'),ccexpmonth:$F('ccexpmonth'),ccexpyear:$F('ccexpyear'),cvv2:$F('cvv2')},
					onLoading: function() {
						Dialog.info('פemeniz kontrol ediliyor. L𴦥n bekleyiniz.',{className: "alphacube", showProgress:true});
					},
					onSuccess: function(t) {
						var response=t.responseText;
						if (response.substr(0,6)=="292277") {
							$('checkout_button').update();
							Windows.closeAllModalWindows();
							Dialog.alert(response.substr(7,response.length-7),{className: "alphacube", okLabel: "Tamam", ok:function(win){ Dialog.info('Y򮥴im Paneli\'nize y򮬥ndiriliyorsunuz. L𴦥n bekleyiniz.',{className: "alphacube",showProgress:true}); BasketEmpty('outline'); return true;}});
						}
						else {
							$('checkout_button').update('<a href="javascript:void(0);" onclick="return PaymentControl();" onMouseOver="window.status=\'פeme Yap\';return true;" onMouseOut="window.status=\'\';return true;"><img src="images/checkout.gif" width="168" height="40" border="0" id="finish_order_button"></a>');
							Windows.closeAllModalWindows();
							Dialog.alert(response.substr(7,response.length-7),{className: "alphacube", okLabel: "Tamam", ok:function(win){ Windows.closeAllModalWindows(); return true;}});
						}
					}
				}
				);
				<?php } ?>
			}
		}
		else if ($F('payment_mtransfer')) {
			var result=false;

			var form = $('mtrans_bank_form');
			var buttons = form.getInputs('radio', 'mtrans_bank');
			var count=buttons.length;
			for (var i=0;i < count;i++) {
				if (buttons[i].checked==true) result=buttons[i].value;
			}

			if (result) {
				$('checkout_button').update(checkloaderimg);
				new Ajax.Request('payment_finish.php', {
					parameters:{payment_type:'mtransfer',billing:bill,bankcode:result},
					onLoading: function() {
						Dialog.info('\u0130򬥭iniz ger覫le򴩲iliyor. L𴦥n bekleyiniz.',{className: "alphacube", showProgress:true});
					},
					onSuccess: function(t) {
						$('checkout_button').update();
						var response=t.responseText;
						Windows.closeAllModalWindows();
						Dialog.alert(response,{className: "alphacube", okLabel: "Tamam", ok:function(win){ Dialog.info('Y򮥴im Paneli\'nize y򮬥ndiriliyorsunuz. L𴦥n bekleyiniz.',{className: "alphacube", showProgress:'true'}); BasketEmpty('outline'); return true;}});
					}
				}
				);
			}
			else {
				alert('Havaleyi hangi bankaya yapaca\u011f񮽺񠩾aretlemelisiniz!');
				new Effect.ScrollTo('payment_area');
				return false;
			}
		}
		else if ($F('payment_mailorder')) {
			$('checkout_button').update(checkloaderimg);
			new Ajax.Request('payment_finish.php', {
				parameters:{payment_type:'mailorder',billing:bill},
				onLoading: function() {
					var tmp_window=getId();
					Dialog.info('\u0130򬥭iniz ger覫le򴩲iliyor. L𴦥n bekleyiniz.',{className: "alphacube", showProgress:true});
				},
				onSuccess: function(t) {
					$('checkout_button').update();
					var response=t.responseText;
					Windows.closeAllModalWindows();
					Dialog.alert(response,{className: "alphacube", okLabel: "Tamam", ok:function(win){ Dialog.info('Y򮥴im Paneli\'nize y򮬥ndiriliyorsunuz. L𴦥n bekleyiniz.',{className: "alphacube", showProgress:true}); BasketEmpty('outline'); return true;}});
				}
			}
			);
		}
		else if ($F('payment_hstcredit')) {
			if ($('pay_via_credit')) {
				if ($F('pay_via_credit')=="true") {
					$('checkout_button').update(checkloaderimg);
					new Ajax.Request('payment_finish.php', {
						parameters:{payment_type:'hstcredit',billing:bill},
						onLoading: function() {
							var tmp_window=getId();
							Dialog.info('\u0130򬥭iniz ger覫le򴩲iliyor. L𴦥n bekleyiniz.',{className: "alphacube", showProgress:true});
						},
						onSuccess: function(t) {
							var response=t.responseText;
							if (response.substr(0,6)=="292278") {
								$('checkout_button').update('<a href="javascript:void(0);" onclick="return PaymentControl();" onMouseOver="window.status=\'פeme Yap\';return true;" onMouseOut="window.status=\'\';return true;"><img src="images/checkout.gif" width="168" height="40" border="0" id="finish_order_button"></a>');
								Windows.closeAllModalWindows();
								Dialog.alert(response.substr(7,response.length-7),{className: "alphacube", okLabel: "Tamam", ok:function(win){ Windows.closeAllModalWindows(); return true;}});
							}
							else {
								$('checkout_button').update();
								Windows.closeAllModalWindows();
								Dialog.alert(response,{className: "alphacube", okLabel: "Tamam", ok:function(win){ Dialog.info('Y򮥴im Paneli\'nize y򮬥ndiriliyorsunuz. L𴦥n bekleyiniz.',{className: "alphacube", showProgress:true}); BasketEmpty('outline'); return true;}});
							}
						}
					}
					);
				}
				else {
					alert('פemenizi Ok.Net Kredi\'nizi kullanarak ger覫le򴩲mek i誮 onay kutucu𵮵 t񫬡y񮽺.');
					return false;
					new Effect.ScrollTo('payment_hstcredit');
				}
			}
			else {
				alert('פemenizi Ok.Net Kredi\'nizi kullanarak ger覫le򴩲mek i誮 yeterli krediniz bulunmamaktad񲮧);
				return false;
				new Effect.ScrollTo('payment_hstcredit');
			}

		}
	}

}
</script>

            <tr align="right">
              <td height="18" colspan="2" align="right" class="ahd-menu"><table border="0" cellspacing="0" cellpadding="0">
                  <tr align="center">
                    <td valign="top"><form name="basket4252" id="basket4252" method="POST" action="">
                        <a href="javascript:BasketEmpty('inline');" onMouseOver="window.status='Sepeti Bo\u015falt';return true;" onMouseOut="window.status='';return true;"><img src="images/updatebut.gif" border="0" /></a>
                        <input type="hidden" name="basket_empty" id="basket_empty" value="false" />
                        <input type="hidden" name="onduty" id="onduty" value="false" />
                      </form></td>
                    <td valign="top" id="checkout_button"><a href="javascript:void(0);" onclick="return PaymentControl();" onMouseOver="window.status='פeme Yap';return true;" onMouseOut="window.status='';return true;"><img src="images/checkout.gif" width="168" height="40" border="0" id="finish_order_button" /></a></td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
    </table></td>
</tr>
<tr id="basket_empty-0" <?=$basket_empty_style['empty'];?>>
              <td class="normal12" width="770"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr valign="bottom">
                    <td width="11%"><span class="fiyat">Sepetiniz</span></td>
                    <td width="89%"><img src="images/sepeticon.gif" width="28" height="22" /></td>
                  </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="11" valign="bottom"><table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#efefef">
                        <tr>
                          <td></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
<tr id="basket_empty-1" <?=$basket_empty_style['empty'];?>>
  <td height="30">&nbsp;</td>
</tr>
<tr id="basket_empty-2" <?=$basket_empty_style['empty'];?>>
  <td align="center" class="normal" width="770"><font size="6" face="Arial, Helvetica, sans-serif"><strong>SEPETގޚDE ݒݎ<br>
    <font color="#D82682">BULUNMAMAKTADIR</font></strong></font></td>
</tr>
<tr id="basket_empty-3" <?=$basket_empty_style['empty'];?>>
  <td height="30">&nbsp;</td>
</tr>
<tr id="basket_empty-4" <?=$basket_empty_style['empty'];?>>
  <td align="center" width="770"><table width="530" height="100" border="0" cellpadding="0" cellspacing="0" class="host-alt-ort-tbl">
      <tr>
        <td align="center"><table width="500" height="70" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="basliklar" align="left">Bir alan ad񠡲ayarak ba򬡹abilirsiniz</td>
            </tr>
            <tr>
              <td><table border="0" cellpadding="1" cellspacing="0">
                  <form name="domainsearch2" action="alanadi2.php" method="POST" id="domainsearch2" onsubmit="return checkDomain(this);">
                    <tbody>
                      <tr>
                        <td><input id="domainquery" name="domainquery" size="34" title="Alan ad񮽠yaz񮽺" maxlength="63" style="width: 270px;" type="text" onkeypress="return K_Validate.checkAlphaNum(event)" /></td>
                        <td><select name="domain_suffix">
                            <?php
                            foreach ($var_tlds as $tldkey=>$domkey) {
                            	foreach ($domkey as $key=>$value) {
                            		$strech=str_replace(".","_",$value);
                            		echo "<option value=\"".$tldkey."-".$strech."\">$value</option>\n";
                            	}
                            }
                            foreach ($var_trds as $value) {
                            	echo "<option value=\"domtr-".$value."\">$value.tr</option>\n";
                            }
                                        	?>
                          </select></td>
                        <td><input type="image" src="images/host_sorgula.gif" /></td>
                        <td class="subText" valign="top">&nbsp;&nbsp;&nbsp;</td>
                      </tr>
                    </tbody>
                    <input type="hidden" name="smart_search" id="smart_search" value="false" />
                  </form>
                </table></td>
            </tr>
            <tr>
              <td class="normal" align="left"><?php foreach ($var_tlds as $tldkey=>$domkey) {
              	foreach ($domkey as $key=>$value) {
              		$strech=str_replace(".","_",$value);
              		echo "$value, ";
              	}
              }
              foreach ($var_trds as $key=>$value) {
              	if ($var_trds[$key+1]) echo "$value.tr, ";
              	else echo $value.".tr";
                                        	}?></td>
            </tr>
          </table></td>
      </tr>
    </table></td>
</tr>
<tr>
  <td height="10">&nbsp;</td>
</tr>
<tr>
  <td height="10" valign="top"></td>
</tr>
<tr>
  <td align="center" valign="top">&nbsp;</td>
</tr>
</table>

</td>
  </tr>
  <tr>
    <td height="10" colspan="2"> </td>
  </tr>
<?php include("footer.php");?>
<script type="text/javascript">
var aa = 1;
<?php
if(isset($_SESSION["3d_error"]))
	{
	if($_SESSION["3d_error"] == -1)
		{
		echo 'Dialog.alert("' . $_SESSION["3d_message"] . '",{className: "alphacube", okLabel: "Tamam", ok:function(win){BasketEmpty("outline"); return true;}});' . "\n";
		}
	if($_SESSION["3d_error"] == 1)
		{
		echo 'Dialog.alert("' . $_SESSION["3d_message"] . '",{className: "alphacube", okLabel: "Tamam", ok:function(win){return true;}});' . "\n";
		}
	}
unset($_SESSION["3d_error"]);
unset($_SESSION["3d_message"]);
unset($_SESSION["3d_cost"]);
unset($_SESSION["3d_cost_kdv"]);
?>
</script>