<!-- BEGIN: main -->
<link href="js/SpryTabbedPanels3.css"  rel="stylesheet" type="text/css" />
<script type="text/javascript">
    function popup(mylink, windowname)
    {
        if (! window.focus)return true;
        var href;
        if (typeof(mylink) == 'string')
            href=mylink;
        else
            href=mylink.href;
        window.open(href, windowname, 'width=660,height=450,scrollbars=yes');
        return false;
    }
	function offer_actions(id, act){
		if(act=='delete' && confirm("Teklifi iptal etmek istediğinize emin misiniz?")){
			$.ajax({
        	    type: "POST",
        	    url: "offer_actions.php",
        	    data: {offerid: id, action: act},
        	    success: function(data) {
					alert(data);
					location.reload();
        	    }
        	});
		}
	}
</script>
<script type="text/javascript">
    function popup2(mylink, kamp_detay)
    {
        if (! window.focus)return true;
        var href;
        if (typeof(mylink) == 'string')
            href=mylink;
        else
            href=mylink.href;
        window.open(href, kamp_detay, 'width=650,height=420,scrollbars=yes');
        return false;
    }
</script>
<div id="erd">
    <div class="baslik">HESAP YÖNETİMİ</div>
    <div class="">
        <div style="margin:5px 0;clear: both;height: 35px; float:left;">
            <ul class="tab_account">
			<!-- BEGIN: hosgeldiniz_tab -->
                <li {liclass1} ><a href="account_management.php">Hoşgeldiniz</a></li>
			<!-- END: hosgeldiniz_tab -->
			<!-- BEGIN: hesap_ayalari_tab -->
                <li {liclass2} ><a href="account_management.php?state=2">Hesap Ayarları</a></li>	
			<!-- END: hesap_ayalari_tab -->
                <li {liclass3} ><a href="account_management.php?state=3">Alan Adı Merkezi</a></li>
                <li {liclass4} ><a href="account_management.php?state=4">Tekliflerim</a></li>
                <li {liclass5} ><a href="account_management.php?state=5">Kayıtlı Aramalarım</a></li>
            </ul>
            <br/>
        </div>
        <!-- BEGIN: state1 -->
        <div id="hesapAyariMain" style="background: #f1f1f1;float:left;border:1px solid #cccccc;min-height: 430px">
            <table width="100%" cellpadding="1" cellspacing="1" border="0">
                <tr>
                    <td valign="top">
                        <div style="margin:10px">

                            <b>Merhaba {userName},</b> <br />
                            <br />
                            {siteDomName} üyesi olduğu için teşekkür ederiz!<br/>
                            <br />
                            {siteDomName} üzerinden kaliteli ticari odaklı kategorilerden ya da genel anahtar kelime ile arama yapabilirsiniz.
                            İsterseniz Premium ve Popüler alan adlarından alım yapabilir isterseniz arama yaparak size en uygun alan adını ve uygun alan adını hemen satın alın.<br/><br/>

                            Saygılarımızla, {siteDomName} Takımı.</div>
                    </td>
                    <td width="310" align="center"><div style="margin-top:10px">{300x250}</div></td>
                </tr>
            </table>
        </div>
        <!-- END: state1 -->

        <!-- BEGIN: state2 -->
        <div id="hesapAyariMain" style="background: #f1f1f1; float:left; border:1px solid #cccccc; min-height:430px;">
            <div class="cautionBar" >
                <h1 ><img src="images/caution.png" alt="" /></h1>
                <p class="yazi">{NOTE} </p>
            </div>
			<div class="clear"></div>
            <div class="lefterd">
                <!-- BEGIN: gizli_soru -->
                <form name="gizlisoru" action="?state=2" method="POST" enctype="multipart/form-data">
                    <table width="90%" border="0" cellpadding="3" cellspacing="0">
                        <!-- BEGIN: mesaj -->
                        <tr>
                            <td>{MESAJ}</td>
                        </tr>
                        <tr>
                            <td height="17">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td height="1" bgcolor="#e4e4e4"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- END: mesaj -->
                        <tr>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="130"><b>Gizli Soru</b></td>
                                        <td width="20"><b>:</b></td>
                                        <td><input name="soru" value="{soru}"type="text" class="icerikForm" id="soru" size="50" /></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td height="17">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td height="1" bgcolor="#e4e4e4"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="130"><b>Gizli Cevap</b></td>
                                        <td width="20"><b>:</b></td>
                                        <td><input name="cevap" value="{cevap}" type="text" class="icerikForm" id="cevap2" size="50" /></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td height="17"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td height="1" bgcolor="#e4e4e4"></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="130">&nbsp;</td>
                                        <td width="20">&nbsp;</td>
                                        <td>
                                            <input type="hidden" name="btn" value="Send" />
                                            <input type="image" src="images/devamBtn.png" name="btnz" />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </form>

                <!-- END: gizli_soru -->
                <!-- BEGIN: sifre_degistir -->
                <div >
                    <form name="formdegis" action="?state=2" method="POST">
                        <table width="90%" border="0" cellpadding="3" cellspacing="0">
                            <!-- BEGIN: mesaj -->
                            <tr>
                                <td>{MESAJ}</td>
                            </tr>
                            <tr>
                                <td height="17">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td height="1" bgcolor="#e4e4e4"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <!-- END: mesaj -->
                            <tr>
                                <td>
                                    <table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="130"><b>Eski Şifreniz</b></td>
                                            <td width="20"><b>:</b></td>
                                            <td><input name="epass" type="password" class="icerikForm" id="epass" size="50" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="17">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td height="1" bgcolor="#e4e4e4"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="130"><b>Yeni Şifreniz: </b></td>
                                            <td width="20"><b>:</b></td>
                                            <td><input name="newpass" type="password" class="icerikForm" id="newpass" size="50" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="17"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td height="1" bgcolor="#e4e4e4"></td>
                                        </tr>
                                    </table></td>
                            </tr>
                            <tr>
                                <td><table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="130"><b>Tekrar Yeni Şifreniz: </b></td>
                                            <td width="20"><b>:</b></td>
                                            <td><input name="newpass2" type="password" class="icerikForm" id="newpass2" size="50" /></td>
                                        </tr>
                                    </table></td>
                            </tr>
                            <tr>
                                <td height="17"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td height="1" bgcolor="#e4e4e4"></td>
                                        </tr>
                                    </table></td>
                            </tr>
                            <tr>
                                <td><table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="130">&nbsp;</td>
                                            <td width="20">&nbsp;</td>
                                            <td>
                                                <input type="hidden" name="btn" value="Degis" />
                                                <input type="image" src="images/degistirBtn.png" name="degistir"/>
                                            </td>
                                        </tr>
                                    </table></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <!-- END: sifre_degistir -->
            </div>
            <div class="righterd">
                {300x250}
            </div>
        </div>
        <!-- END: state2 -->

        <!-- BEGIN: state3 -->
        <div id="hesapAyariMain" style="background: #f1f1f1;float:left;border:1px solid #cccccc;min-height: 430px">
            <script src="js/SpryTabbedPanels2.js" type="text/javascript"></script>
            <div style="background:#ffffff; padding:10px;min-height: 430px">
                <div id="TabbedPanels2" class="TabbedPanels2" >
                    <ul class="TabbedPanelsTabGroup2">
                        <li class="TabbedPanelsTab2a" tabindex="0">Alan Adlarım</li>
                        <li class="TabbedPanelsTab3b" tabindex="0">Alım İşleminde Bekleyenler </li>
                    </ul>
                    <div class="TabbedPanelsContentGroup3">
                        <div class="TabbedPanelsContent3" >
                            <div class="acwrapper" style="float:left;clear: both">
                                <div style="font-size:11px;top:0;" class="tablo">
                                    <!-- BEGIN: alan_adlarim -->
                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <tr>
                                            <td  height="30" class="basliklar"><p>Alan Adı</p></td>
                                            <td width="100" align="center" class="basliklar"><p>Durum </p></td>
                                            <td width="100" align="center" class="basliklar"><p>Ödeme Şekli</p></td>
                                            <td width="80" align="center" class="basliklar"><p>Sipariş Kodu</p></td>
                                            <td width="110" align="center" class="basliklar"><p>İşlem Tarihi</p></td>
                                            <td width="110" align="center" class="basliklar"><p>Aktivasyon Tarihi</p></td>
                                            <td width="80" align="center" class="basliklar"><p>Fiyat</p></td>
                                        </tr>
                                        <!-- BEGIN: order_domain -->
                                        <tr >
                                            <td height="28" bgcolor="#FFFFFF" class="icerikTd">
                                                <U>{domain_name}</U><br />

                                                <a href="bilgi_guncelle.php?domainID={domID}" onClick="popup('bilgi_guncelle.php?domainID={domID}','bilgi_guncelle');return false;"> <span id="update_{domID}">Bilgi Güncelle</span></a>
                                                <a href="dns_degisikligi.php?domainID={domID}" onClick="popup('dns_degisikligi.php?domainID={domID}','bilgi_guncelle');return false;"><span id="dns_{domID}">DNS değişikliği</span></a>
                                                <a href="transfersurec.php?domainID={domID}">Transfer Et</a>
                                            </td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd"><font color="#b51700">{domain_state}</font></td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{kindpay_type} <br>{ccard_no}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{order_code}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{order_time}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{order_actTime}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd"><p>{domain_price} $</p></td>
                                        </tr>
                                        <!-- END: order_domain -->
                                    </table>
                                   
                                    <p style="font-size:12px; margin-top:10px;" class="keyLink">* Alan adı yönetiminizi yapabilmeniz için satın aldığınız alan adlarınızı aşağıdaki sitelerden birine taşımanız gerekmektedir. Lütfen size uygun olan bir site seçiniz. <a href="#"><br />
                                            Siteler ile ilgili bilgi alma için tıklayınız.</a></p>
                                    -->
                                    <!-- END: alan_adlarim -->
                                    <!-- BEGIN: no_record -->Alan adı bulunamadı.<!-- END: no_record -->

                                    <!-- BEGIN: google -->
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            $("a.googlee img").click(function() {
                                                var productIDValSplitter = (this.id).split("_");
                                                var domainIDVal = productIDValSplitter[1];
                                                
                                                $.ajax({
                                                    type: "POST",
                                                    url: "add_basket.php",
                                                    data: { domainID: domainIDVal, action: "google_act"},
                                                    success: function(data) {
                                                        alert(data);
                                                        location.reload();
                                                    }
                                                });
                                            });

                                        });
                                    </script><br>
                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <tr>
                                            <td  style="padding-left:15px">
                                                <ul style="">
                                                    <li> Aşağıda size hediye 100 TL değerinde Google AdWords deneme kuponlarınız listelenmektedir.
                                                    <li> Google AdWords reklam bütçesiniz için karşılığında verilecek olan kuponu <strong>01 Temmuz 2010</strong> tarihine kadar <a href="http://adwords.google.com" target="_blank">http://adwords.google.com</a> adresine giderek kullanabilirsiniz.
                                                    <li> Her bir güvenlik numarası için ayrı bir Google AdWords hesabı yaratmanız gerektiğini unutmayınız.<br />
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- BEGIN: g1 -->
									<br>
                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <tr>
                                            <td colspan="4"><strong>100 TL değerinde Google AdWords deneme kuponlarınız.  </strong></td>
                                        </tr>
                                        <tr>
                                            <td  height="30" class="basliklar" width="80" align="center"><p>Hizmet</p></td>
                                            <td width="150" align="center" class="basliklar"><p>Alan Adı</p></td>
                                            <td width="150" align="center" class="basliklar"><p>Kupon Kodu</p></td>
                                            <td width="150" align="center" class="basliklar"><p>Durumu</p></td>
                                        </tr>
                                        <!-- BEGIN: grow -->
                                        <tr >
                                            <td bgcolor="#FFFFFF" class="icerikTd" align="center" height="20">Alan adı</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{g_domain}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{g_pass}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">
                                                <!-- BEGIN: gaktif_et -->
                                                <a href="javascript:;" class="googlee"><img src="images/kullanilmadi.gif" border="0" alt="" id="img_{g_id}"/></a>
                                                <!-- END: gaktif_et -->
                                                <!-- BEGIN: gaktif_et2 -->
                                                <span>Kullanıldı</span>
                                                <!-- END: gaktif_et2 -->
                                            </td>
                                        </tr>
                                        <!-- END: grow -->
                                    </table>
                                    <!-- END: g1 -->
                                    <br>
                                    <!-- BEGIN: g2 -->
                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <tr>
                                            <td colspan="4"><strong><font color="red"> Kayıtlı hediye kuponunuz yoktur</font></strong></td>
                                        </tr>
                                    </table>
                                    <br />
                                    <!-- END: g2 -->

                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">

                                        <tr>
                                            <td><strong>Kampanya detayları için <a href="javascript:;" onClick="popup2('kampanyadetay.html','kamp_detay');return false;">tıklayınız…</a></strong></td>
                                        </tr>
                                    </table>
                                    <!-- END: google -->
                                    <!-- BEGIN: googleFin -->
                                    <table border="0" cellspacing="0" cellpadding="0" width="900">
                                        <tr>
                                            <td>
                                                <p>
                                                    <strong>
                                                        Henüz satın almış olduğunuz alan adı bulunmamaktadır.
                                                    </strong>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END: googleFin -->
                                </div>
                            </div>
                        </div>

                        <div class="TabbedPanelsContent3">
                            <div class="acwrapper">
                                <div class="tablo">
                                    <!-- BEGIN: odeme_bekleniyor -->
                                    <table border="0" cellspacing="0" cellpadding="0" >
                                        <tr>
                                            <td width="180" height="30" class="basliklar"><p>Alan Adı</p></td>
                                            <td width="130" align="center" class="basliklar"><p>Durum </p></td>
                                            <td width="140" align="center" class="basliklar"><p>Ödeme Şekli</p></td>
                                            <td width="100" align="center" class="basliklar"><p>Sipariş Kodu</p></td>
                                            <td width="110" align="center" class="basliklar"><p>İşlem Tarihi</p></td>
                                            <td width="110" align="center" class="basliklar"><p>Aktivasyon Tarihi</p></td>
                                            <td width="100" align="center" class="basliklar"><p>Fiyat</p></td>
                                        </tr>
                                        <!-- BEGIN: order_domain -->
                                        <tr >
                                            <td height="28" bgcolor="#FFFFFF" class="icerikTd"><U>{domain_name}</U></td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd"><font color="#b51700">{domain_state}</font></td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{kindpay_type}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{order_code}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{order_time}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{order_actTime}</td>
                                            <td align="center" bgcolor="#FFFFFF" class="icerikTd"><p>{domain_price} $</p></td>
                                        </tr>
                                        <!-- END: order_domain -->
                                    </table>
                                   
                                    <p style="font-size:12px; margin-top:10px;" class="keyLink">* Alan adı yönetiminizi yapabilmeniz için satın aldığınız alan adlarınızı aşağıdaki sitelerden birine taşımanız gerekmektedir. Lütfen size uygun olan bir site seçiniz. <a href="#"><br />
                                            Siteler ile ilgili bilgi alma için tıklayınız.</a></p>
                                  
                                    <!-- END: odeme_bekleniyor -->
                                    <!-- BEGIN: no_record2 -->
                                    Domain bulunamadı.
                                    <!-- END: no_record2 -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <script type="text/javascript">
                <!--
                
                var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
                
                //-->
            </script>
        </div>
        <!-- END: state3 -->
        <!-- BEGIN: state4 -->
        <script type="text/javascript">
            $(document).ready(function(){
                $(".productPriceWrap a img").click(function() {
                    var productIDValSplitter = (this.id).split("_");
                    var domainIDVal = productIDValSplitter[1];
                    $.ajax({
                        type: "POST",
                        url: "add_basket.php",
                        data: { domainID: domainIDVal, action: "delToFavori"},
                        success: function(data) {
                            $('#basketWrap').html("("+data+")");
                            location.reload();
                        }
                    });
                });
                $(".productPriceWrapRight a img").click(function() {
                    var productIDValSplitter = (this.id).split("_");
                    var domainIDVal = productIDValSplitter[1];
                    alert(productIDVal);
                    $.ajax({
                        type: "POST",
                        url: "add_basket.php",
                        data: { domainID: domainIDVal, action: "addToBasket"},
                        success: function(data) {
                            $('#basketWrap').html("("+data+")");
                            window.location='basket.php';
                        }
                    });
                });
            });
        </script>
        <div id="hesapAyariMain" style="background: #f1f1f1;float:left;border:1px solid #cccccc;min-height: 430px">
            <div class="favori">
                <!-- BEGIN: sepet -->
                <div class="sepet" >
                    <table width="100%" border="0" cellpadding="5" cellspacing="0">
                        <tr>
                            <td height="30" align="center" class="basliklar"><p>Alan Adı</p></td>
                            <td width="188" align="center" class="basliklar"><p>Fiyat</p></td>
							<td width="240" align="center" class="basliklar"><p>Durum</p></td>
                            <td width="180" align="center" class="basliklar">&nbsp;</td>
                        </tr>
                        <!-- BEGIN: rows -->
                        <tr>
                            <td height="28" align="center" bgcolor="#FFFFFF" class="icerikTd"><a href="{domainNameLink}">{domain_name}</a></td>
                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{domain_price}$</td>
							<td align="center" bgcolor="#FFFFFF" class="icerikTd">{offer_status}</td>
                            <td align="center" bgcolor="#FFFFFF" class="icerikTd">
								<a href="javascript:void(0);" onClick="offer_actions({domainID}, 'delete')">
									<img src="images/kaldirBtn.gif" alt="" name="imgSepet_{domainID}" border="0" id="imgSepet_{domainID}"/>
								</a>
							</td>
						</tr>
                        <!-- END: rows -->
                        <!-- BEGIN: total -->
                        <tr>
                            <td class="icerikTd" height="28"></td>
                            <td colspan="2" class="icerikTd"><span class="pricetotal"></span> <span class="pricetotalp">{totalPrice}</span></td>
                        </tr>
                        <!-- END: total -->
                    </table>
                    <br/>
                    <!-- BEGIN: rttt -->
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><a href="register.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage(this,'','images/odemeYapa.gif',1)"><img src="images/odemeYap.gif" name="Image14" width="96" height="23" border="0" id="Image14" alt=""/></a></td>
                            <td>&nbsp;</td>
                            <td><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image15','','images/devametBtna.gif',1)"><img src="images/devametBtn.gif" name="Image15" width="140" height="23" border="0" id="Image15" alt=""/></a></td>
                            <td>&nbsp;</td>
                            <td><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image16','','images/listeCleara.gif',1)"><img src="images/listeClear.gif" name="Image16" width="100" height="23" border="0" id="Image16" alt=""/></a></td>
                        </tr>
                    </table>
                    <!-- END: rttt -->
                </div>
                <!-- END: sepet -->
            </div>
        </div>
        <!-- END: state4 -->
        <!-- BEGIN: state5 -->
        <script type="text/javascript">
            $(document).ready(function(){
                $(".saveSearch a img").click(function() {
                    var SerachIDval = (this.id).split("_");
                    var SerachID = SerachIDval[1];
                    $.ajax({
                        type: "POST",
                        url: "add_basket.php",
                        data: { domainID: SerachID, action: "delAramaKaydet"},
                        success: function(data) {
                            //$('div#aramaKaydetDiv').hide();
                            location.reload();
                        }
                    });
                });
            });
        </script>
        <div id="hesapAyariMain" style="background: #f1f1f1;float:left;border:1px solid #cccccc;min-height: 430px">
            <div class="favori">
                <!-- BEGIN: no_record -->
                <p>Kayıtlı aramanız bulunmamaktadır.</p>
                <!-- END: no_record -->
                <!-- BEGIN: favori -->
                <p>Kaydettiğiniz aramalarınızın listesine bu sayfadan ulaşabilirsiniz.
                    Toplamda {num_save} kayıtlı aramanız listelenmektedir.<br />
                    <br />
                </p>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="padding-left:10px;"  height="35" class="basliklar"><p>Alan Adı</p></td>
                        <td  align="center" class="basliklar">Detay</td>
                        <td  align="center" class="basliklar">Kaldır</td>
                    </tr>
                    <tr bgcolor="{sbg}">
                        <td style="padding-left:10px;" height="30" bgcolor="#FFFFFF" class="icerikTd"><U><a href="{save_link}">{save_name}</a></U></td>
                        <td align="center" bgcolor="#FFFFFF" class="icerikTd"><span class="redLink"><a href="{save_link}">Detay</a></span></td>
                        <td width="100" align="center" bgcolor="#FFFFFF" class="icerikTd saveSearch">
                            <a href="sil_php?{sil_link}" onclick="return false"><img src="images/kaldirBtn.gif" alt="" border="0" id="slink_{sil_link}"/></a>                        </td>
                    </tr>
                </table>
                <!-- END: favori -->
            </div>
        </div>
        <!-- END: state5 -->

    </div>
</div>
<div class="clear"></div>
<!-- END: main -->