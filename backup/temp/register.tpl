<!-- BEGIN: main -->
<script type="text/javascript" src="js/jquery.numeric.pack.js"></script>
<script src="js/creditcard.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(
        function()
        {
            $("input#ccardno").numeric();
            $("input#cvv2").numeric();
            $("input#tax_code").numeric();
        }
    );
        $('#cc_cart').hide();
        $('#cc_bank').hide();
        $('#cc_mailOrder').hide();
        $("input[name$='kindpay']").click(function() {
            if($('#cc_open3').is(':checked')){
                $('#cc_bank').hide();
                $('#cc_mailOrder').hide();
                $('#cc_cart').show();
            }else if($('#cc_open4').is(':checked')){
                $('#cc_bank').hide();
                $('#cc_mailOrder').hide();
                $('#cc_cart').show();
            }else if($('#cc_open1').is(':checked')){
                $('#cc_cart').hide();
                $('#cc_mailOrder').hide();
                $('#cc_bank').show();
            }else {
                $('#cc_cart').hide();
                $('#cc_bank').hide();
                $('#cc_mailOrder').show();
            }
        });
        $('form#order').submit(function(){
            if($("form#order input[name$='firm_name']").val()=="") {
                alert("Fatura ad\u0131nı giriniz.");
                return false;
            }else if($("form#order input[name$='firm_adress']").val()=="") {
                alert("Fatura adresini giriniz.");
                return false;
            }else if($("form#order input[name$='tax_office']").val()=="") {
                alert("Vergi dairesini giriniz.");
                return false;
            }else if($("form#order input[name$='tax_code']").val()=="") {
                alert("Vergi numaras\u0131nı giriniz.");
                return false;
            }
            if(!$("input[name$='kindpay']").is(':checked')){
                alert("Ödeme tipini seçiniz.");
                return false;
            }
            firm_name=$("form#order input[name$='firm_name']").val();
            firm_adress=$("form#order input[name$='firm_adress']").val();
            tax_office=$("form#order input[name$='tax_office']").val();
            tax_code=$("form#order input[name$='tax_code']").val();
            if($("input[name$='kindpay']:checked").val()==1){
                if(!$("input[name$='bank_info']").is(':checked')){
                    alert("Havele yi hangi bankaya yapaca\u011fınızı seçiniz.");
                    return false;
                }else {
                    bank_info=$("input[name$='bank_info']:checked").val();
                    $("input[name$='send']").attr('disabled', 'disabled');
                    $.ajax({
                        type: "POST",
                        url: "odemeal.php",
                        data: { action: "bank",
                            firm_name : firm_name,
                            firm_adress: firm_adress,
                            tax_office : tax_office,
                            tax_code : tax_code,
                            bank_info : bank_info
                        },
                        success: function(data) {
                            //$('#basketWrap').html("("+data+")");
                            //alert(data);
                            if(isNaN(data)) {
                                alert(data);
                                $("input[name$='send']").attr('disabled', '');
                            }else {
                                window.location='order_complate.php?kindpay=1&ordercode='+data;
                            }
                            
                        }
                    });
                }
            }else if($("input[name$='kindpay']:checked").val()==2){
                $("input[name$='send']").attr('disabled', 'disabled');
                $.ajax({
                    type: "POST",
                    url: "odemeal.php",
                    data: { action: "mail_order",
                        firm_name : firm_name,
                        firm_adress: firm_adress,
                        tax_office : tax_office,
                        tax_code : tax_code
                    },
                    success: function(data) {
                        //$('#basketWrap').html("("+data+")");
                        //alert(data);
                        if(isNaN(data)) {
                            alert(data);
                            $("input[name$='send']").attr('disabled', '');
                        }else {
                            window.location='order_complate.php?kindpay=2&ordercode='+data;
                        }
                    }
                });
            }else if($("input[name$='kindpay']:checked").val()==3){
                //alert("erere");return false;
                if($("form#order input[name$='ccardname']").val()=="") {
                    alert("Kredi kart\u0131 üzerindeki ismi yazınız.");
                    return false;
                }else if($("form#order input[name$='cvv2']").val()=="") {
                    alert("Kredi kart\u0131nın güvenlik numarasını yazınız.");
                    return false;
                }else if (!checkCreditCard ($('#ccardno').val(),$('#ccardtype').val())){
                    alert (ccErrors[ccErrorNo]);
                }else {
                    //alert("dara");
                    $("input[name$='send']").attr('disabled', 'disabled');
                    //return false;
                    $.ajax({
                        type: "POST",
                        url: "odemeal.php",
                        data: {
                            action: "ccard",
                            banktype: "GAR",
                            firm_name : firm_name,
                            firm_adress: firm_adress,
                            tax_office : tax_office,
                            tax_code : tax_code,
                            ccardname : $("form#order input[name$='ccardname']").val(),
                            ccardtype : $("form#order input[name$='ccardtype']").val(),
                            ccardno : $("form#order input[name$='ccardno']").val(),
                            ccexpmonth : $("#ccexpmonth option:selected").val(),
                            ccexpyear : $("#ccexpyear option:selected").val(),
                            cvv2 : $("form#order input[name$='cvv2']").val()

                        },
                        success: function(data) {
                            //$('#basketWrap').html("("+data+")");
                            if(isNaN(data)) {
                                //alert(data);
                                $("input[name$='send']").attr('disabled', '');
                            }else {
                                window.location='order_complate.php?kindpay=3&ordercode='+data;
                            }
                        }
                    });
                    //alert("Ba\u011flantı Kurulamıyor.");
                    return false;
                }
            }else if($("input[name$='kindpay']:checked").val()==4){
                if($("form#order input[name$='ccardname']").val()=="") {
                    alert("Kredi kart\u0131 üzerindeki ismi yazınız.");
                    return false;
                }else if($("form#order input[name$='cvv2']").val()=="") {
                    alert("Kredi kart\u0131nın güvenlik numarasını yazınız.");
                    return false;
                }else if (!checkCreditCard ($('#ccardno').val(),$('#ccardtype').val())){
                    alert (ccErrors[ccErrorNo]);
                    return false;
                }else {
                    return true;
                }
            }
            //alert($("input[name$='kindpay']:checked").val());bank_info
            return false;
        });
    });
</script>


<div id="pageRankContent">
    <div class="baslikBar">DOMAIN KAYIT SAYFASI</div>
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
            <td width="380" height="30" align="center" class="basliklar"><p>Alan Adı</p></td>
            <td width="188" align="center" class="basliklar"><p>Fiyat</p></td>
            <td width="188" align="center" class="basliklar">Toplam Fiyat</td>
        </tr>
        <!-- BEGIN: rows -->
        <tr>
            <td height="28" align="center" bgcolor="#FFFFFF" class="icerikTd"><U>{domain_name}</U></td>
            <td align="center" bgcolor="#FFFFFF" class="icerikTd"><p>{domain_price}</p></td>
            <td align="center" bgcolor="#FFFFFF" class="icerikTd"><strong>{domain_price_tl}</strong></td>
        </tr>
        <!-- END: rows -->
        <tr>
            <td class="icerikTd" height="28"></td>
            <td colspan="2" class="icerikTd"><span class="pricetotal">Toplam :</span> <span class="pricetotalp">{totalPrice}</span></td>
        </tr>
    </table>
	<form name="order" id="order" action="payment_3d.php" method="POST" enctype="multipart/form-data">
        <div id="registerMain">
            <div class="odemeSecenekleri">
                <p class="blackRepeat">Ödeme Seçenekleri</p>

                <div class="odemeler">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="122">
								<label>
									<input align="absbottom" type="radio" name="kindpay" value="1" id="cc_open1"/>
									&nbsp;Banka Havalesi
								</label></td>
                            <td width="45"></td>

                            <td width="122">
								<label>
                                    <input type="radio" name="kindpay" value="2" id="cc_open2"/>
									&nbsp;Mail Order
								</label></td>
                            <td width="25"></td>

                            <td width="122">
								<label>
                                    <input type="radio" name="kindpay" value="3" id="cc_open3"/>
									&nbsp;Kredi Kartı
								</label></td>
                            <td width="25"></td>

                            <!--td width="22"><label>
                                    <input type="radio" name="kindpay" value="4" id="cc_open4"/>
                                </label></td>
                            <td>Kredi Kartı (3D)</td-->
                        </tr>
                    </table>
                </div>
            </div>
            <div class="odemeSecenekleri">
                <p class="orangeRepeat">Fatura Bilgileri</p>

                <div class="faturaBg"><table border="0" cellspacing="1" cellpadding="0">
                        <tr>
                            <td width="130" height="30">Firma / Şahıs Adı</td>
                            <td width="20" align="left">:</td>
                            <td><input type="text" name="firm_name" value="" class="formZone" style="width:400px; color:#340c00; height:18px; padding-top:1px"/></td>
                        </tr>
                        <tr>
                            <td height="30">Fatura Adresi</td>
                            <td width="20" align="left">:</td>
                            <td><input type="text" name="firm_adress" value="" class="reg_input" style="width:400px; color:#340c00; height:18px; padding-top:1px"/></td>
                        </tr>
                        <tr>
                            <td height="30">Vergi Dairesi</td>
                            <td width="20" align="left">:</td>
                            <td><input type="text" name="tax_office" value="" class="reg_input" style="width:400px; color:#340c00; height:18px; padding-top:1px"/></td>
                        </tr>
                        <tr>
                            <td height="30">Vergi No</td>
                            <td width="20" align="left">:</td>
                            <td><input type="text" name="tax_code" id="tax_code" value="" class="reg_input" style="width:400px; color:#340c00; height:18px; padding-top:1px"/></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="cc_bank" class="odemeSecenekleri">
                <p class="greyRepeat">Banka Havalesi</p>

                <div class="havaleBg">
                    <table border="0" cellspacing="1" class="tablo2">
                        <tr>
                            <td class="brdBottom" style="padding-left:10px;" width="25" bgcolor="#FBFBFB" >&nbsp;</td>
                            <td class="brdBottom" style="padding-left:10px;" width="200" height="30" bgcolor="#FBFBFB" ><strong>Banka Adı</strong></td>
                            <td class="brdBottom" width="100" align="center" bgcolor="#FBFBFB"><strong>Şube Kodu</strong></td>
                            <td class="brdBottom" width="350" align="center" bgcolor="#FBFBFB"><strong>Hesap Numarası</strong></td>
                            <td class="brdBottom" width="200" align="center" bgcolor="#FBFBFB"><strong>Hesap T&uuml;r&uuml;</strong></td>
                        </tr>
                        <!-- BEGIN: bank_info -->
                        <tr>
                            <td class="brdBottom" style="padding-left:10px;" ><input type="radio" name="bank_info" value="{bankID}" /></td>
                            <td class="brdBottom" style="padding-left:10px;" height="30" >{bankName}</td>
                            <td class="brdBottom" width="200" align="center">{bankoffice_code}</td>
                            <td class="brdBottom" align="center">{bankaccount_code}<br>{bankaccount_IBAN}</td>
                            <td class="brdBottom" align="center">{bankaccount_type}</td>
                        </tr>
                        <!-- END: bank_info -->
                    </table>
                </div>
            </div>
            <div id="cc_mailOrder" class="odemeSecenekleri">
                <p class="greyRepeat">Mail Order</p>

                <div class="havaleBg">
                    <table width="98%" border="0" cellspacing="1" class="tablo2">
                        <tr>
                            <td width="55" height="50" bgcolor="#FBFBFB" style="padding-left:10px;" ><img src="images/mailorderIco.gif" width="43" height="43" /></td>
                            <td style="padding-left:10px;" height="30" bgcolor="#FBFBFB" ><strong class="redLink"><a href="Mail_Order_Talimati.doc" target="_blank">{siteDomName} Mail Order Talimatı &raquo;</a></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="cc_cart" class="odemeSecenekleri">
                <p class="greyRepeat">Kredi Kartı</p>

                <div class="havaleBg">
                    <table border="0" cellspacing="1" cellpadding="0">
                        <tr>
                            <td class="brdBottom" width="130" height="30">Kart &uuml;zerindeki isim</td>
                            <td class="brdBottom" width="20" align="left">:</td>
                            <td class="brdBottom"><input type="text"  name="ccardname" value="" class="formZone" style="width:270px; color:#340c00; height:18px; padding-top:1px"/></td>
                        </tr>
                        <tr>
                            <td class="brdBottom" height="30">Tipi</td>
                            <td class="brdBottom" width="20" align="left">:</td>
                            <td class="brdBottom">
                                <select name="ccardtype" id="ccardtype" class="sLink">
                                    <option value="visa">Visa</option>
                                    <option value="mastercard">MasterCard</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="brdBottom" height="30">Kart Numarası</td>
                            <td class="brdBottom" width="20" align="left">:</td>
                            <td class="brdBottom"><input type="text" name="ccardno" id="ccardno" value="" class="reg_input" style="width:270px; color:#340c00; height:18px; padding-top:1px"/></td>
                        </tr>
                        <tr>
                            <td class="brdBottom" height="30">Son Kullanma Tarihi</td>
                            <td class="brdBottom" align="left">:</td>
                            <td class="brdBottom">
                                <select name="ccexpmonth" id="ccexpmonth" class="sLink">
                                    <!-- BEGIN: ccmonth -->
                                    <option value="{mval}">{mvall}</option>
                                    <!-- END: ccmonth -->
                                </select>
                                <select name="ccexpyear" id="ccexpyear" class="sLink">
                                    <!-- BEGIN: ccyear -->
                                    <option value="{mval2}">{mvall2}</option>
                                    <!-- END: ccyear -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td height="30">G&uuml;venlik Numarası</td>
                            <td align="left">:</td>
                            <td><input type="text" name="cvv2" id="cvv2" value="" class="reg_input" style="width:270px; color:#340c00; height:18px; padding-top:1px" maxlength="3"/></td>
                        </tr>
                    </table>
                </div>
            </div>
            <p align="right"><input type="image" src="images/devamm.gif" name="send" value="Devam" class="btn"/></p>
        </div>
		<div class="clear"></div>
    </form>
</div>
<div class="clear"></div>
<!-- END: main -->