<!-- BEGIN: main -->
<script type="text/javascript">
	function make_offer(domainIDVal) {
	
/*	var av = "error dsfsdfsdfsdf";
	var n = av.indexOf("error");
	alert(n);
	if(n > 0)
	{
		alert("hata var...");
	}
	else
	{
	alert("hata yok");}
	return false;*/
		if(domainIDVal == 'doregister') window.location.href='yonlen.php?from='+window.location;
		price_offer = $('#price_offer').val();
		ps = $('#ps').val();
		if((domainIDVal != '' && domainIDVal > 0) && (price_offer != '' && price_offer > 0)) {
			$.ajax({
				type: "POST",
	            url: "add_offer.php",
	            data: { domainID: domainIDVal, price: price_offer, user_ps:ps},
	            success: function(data) {
					if(data=='1'){
						alert('Teklifiniz alındı.');
						window.location.href='account_management.php?state=4';
					}else{
					alert("Teklifiniz "+data+"$'dan fazla olmalıdır.");
					}
					
					
				}
			});

	}

	}
</script>
<div id="pageRankContent">
    <div class="baslikBar">{domainName} ALAN ADI DETAYI</div>
    <div class="leftpart">
        <div class="rankbox">
			<div id="info_box"></div>
			<!-- BEGIN: offer_first -->
			<div id="teklifver_div">
				<table width="100%" border="0" cellpadding="5" cellspacing="2" class="yable">
					<tr bgcolor="#f1f1f1">
						<td align="center" colspan="3">{domainName} için <strong>Teklif Ver</strong></td>
					</tr>
					<tr>
						<td align="right" width="165">Teklifiniz: </td>
						<td><input type="text" name="price_offer" id="price_offer" value="{gelen_deger}"> $</td>
						<td><input type="button" name="send_offer" value="Teklif Ver" onclick="make_offer('{idDom}');"></td>
					</tr>
					<tr>
						<td align="right" width="165">Not: </td>
						<td colspan="2"><textarea name="ps" id="ps" cols="36" rows="2">{gelen_bilgi}</textarea></td>
					</tr>
				</table>
				<br />
			</div>
			<!-- END: offer_first -->
            <div>
                <table width="100%" border="0" cellpadding="5" cellspacing="2" class="yable">
                    <tbody>
                    <tr bgcolor="#f1f1f1">
                        <td width="220">Alan Adı</td>
                        <td>{domainName}</td>
                    </tr>
                    <tr bgcolor="#ffffff">
                        <td>Google'da Aranma Sayısı</td>
                        <td>{domainSearchRank}</td>
                    </tr>
					<tr bgcolor="#ffffff">
                        <td>Google'da Listelenme Sayısı</td>
                        <td>{domainPageRank}</td>
                    </tr>
                    <!-- tr bgcolor="#f1f1f1">
                        <td>Domain Kayıt Tarihi</td>
                        <td>{datacre}</td>
                    </tr -->
                    <!-- tr bgcolor="#f1f1f1">
                        <td>Alan adı Süre Bitiş Tarihi</td>
                        <td>{dataexp}</td>
                    </tr -->
                    <!-- tr bgcolor="#f1f1f1">
                        <td>Domain'in Yaşı</td>
                        <td>{datayears}</td>
                    </tr -->
                    <tr>
                        <td>Tescil edilen Firma</td>
                        <td>reg2c</td>
                    </tr>
                    <tr bgcolor="#f1f1f1">
                        <td>Alan adı Puan</td>
                        <td>{domainNamePuan}</td>
                    </tr>
                    <tr>
                        <td>Alan adı Hemen Al fiyatı</td>
                        <td>{domainNamePriceasd} $</td>
                    </tr>
                    <tr bgcolor="#f1f1f1">
                        <td>Alan adı Kategori</td>
                        <td>{domainNameCat}</td>
                    </tr>
                    <tr>
                        <td>Alan adı kullanım alanları</td>
                        <td>{domainNameInfo}</td>
                    </tr>
					<tr bgcolor="#f1f1f1">
                        <td>İlişkili olduğu kelime</td>
                        <td>{domainWordRelation}</td>
                    </tr>
                    <tr>
                        <td width="300" colspan="2" align="center" valign="bottom">
							<div class="productPriceWrapRight">
								<!-- BEGIN: buttons --> <!--onclick="add_basket({domainID});"-->
									<!-- BEGIN: offer_button -->
								<a href="javascript:void(0);" onclick="make_offer('{idDom}');" title="Sepete Ekle" id="sepeteekle_but">
									<img src="images/teklifVerBtn1.png" onmouseover="this.src='images/teklifVerBtn_a1.png'" onmouseout="this.src='images/teklifVerBtn1.png'" id="imgSepet_{domainID}" alt="Teklif Ver" /></a>
									<!-- END: offer_button -->
									
									<!-- BEGIN: order_button -->
								<a href="{domainteklifver}" title="Teklif Ver" id="teklifver_but">
									<img src="images/teklifVerBtn1.png" onmouseover="this.src='images/teklifVerBtn_a1.png'" onmouseout="this.src='images/teklifVerBtn1.png'" id="imgSepet_{domainID}" alt="Teklif Ver" />
                            	</a>
								
									<!-- END: order_button -->
								<!-- END: buttons -->
                        	</div>
						</td>
                    </tr>
                    </tbody>
                </table>
                <table>
                <tr>
                <!-- td>
                <script type="text/javascript" src="http://www.gmodules.com/ig/ifr?url=http%3A%2F%2Fwww.google.com%2Fig%2Fmodules%2Fgoogle_insightsforsearch_interestovertime_searchterms.xml&amp;up__property=empty&amp;up__search_terms={domainWordRelation}&amp;up__location=empty&amp;up__category=0&amp;up__time_range=3-m&amp;up__compare_to_category=false&amp;synd=open&amp;w=560&amp;h=350&amp;lang=tr&amp;title=Google+Arama+Trendleri&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js"></script>
                </td -->
                </tr>
                </table>
            </div>
			<!-- BEGIN: offer_div -->
			<div id="teklifver_div" style="display:none;">
				<br />
				<table width="100%" border="0" cellpadding="5" cellspacing="2" class="yable">
					<tr bgcolor="#f1f1f1">
						<td align="center" colspan="3">{domainName} için <strong>Teklif Ver</strong></td>
					</tr>
					<tr>
						<td align="right" width="165">Teklifiniz: </td>
						<td><input type="text" name="price_offer" id="price_offer"> $</td>
						<td><input type="button" name="send_offer" value="Teklif Ver" onclick="make_offer('{idDom}');"></td>
					</tr>
					<tr>
						<td align="right" width="165">Not: </td>
						<td colspan="2"><textarea name="ps" id="ps" cols="36" rows="2"></textarea></td>
					</tr>
				</table>
			</div>
			<script>
				$("#teklifver_but").click(function () {
					$("#teklifver_div").slideToggle("slow");
				});
			</script>
			<!-- END: offer_div -->
        </div>
    </div>
    <div class="rightpart">
	{right}
        <!-- BEGIN: kapat -->
        <div class="hit">
			<h3>En Cok Takip Edilenler</h3>
			<ul>
				<!-- BEGIN: hit_dom -->
				<li><a href="{hitNameLink}">{hitName}</a></li>
				<!-- END: hit_dom -->
			</ul>
        </div>
        <!-- END: kapat -->
        <br />
		<!-- div style="width:300px;height:250px;">{litetral}<script type="text/javascript" src="http://app.pubserver.adhood.com/6844,300,250"></script></div -->
        {300x250}
    </div>
</div>
<div class="clear">
</div>
<!-- END: main -->