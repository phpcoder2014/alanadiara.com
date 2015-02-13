<!-- BEGIN: main -->
<div id="pageRankContent">
    <div class="baslikBar">KATEGORİDEKİ ALAN ADLARI</div>
    <!--<div>
        {siteDomName}’da 20 den fazla kategori ile aradığınız alan adını bulmanız daha kolay. Kategorilerimiz altında listelenen alan adlarını ilk siz keşfedin.<br />
        Alan adı satın alan herkese 100 TL değerinde Google AdWords deneme kuponu hediye.  Hem hayallerinizdeki alan adınına sahip olun hem de reklamınızı yapın.<br /><br />
    </div>-->
    <!-- BEGIN: kapat -->
    <table width="100%" border="0" cellpadding="5" cellspacing="2">
            <tr>
                <td colspan="6">
                    <form name="ewew" action="" method="get">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <select name="list" size="1" id="list">
                                        <!-- BEGIN: list_array -->
                                        <option value="{list_name}" {list_selected}>{list_name}</option>
                                        <!-- END: list_array -->
                                    </select>
                                </td>
                                <td width="15">&nbsp;</td>
                                <td>
                                    <input type="hidden" name="categori" value="{categori}"/>
                                    <input type="submit" name="button" id="button" value="Kayıt Listele"  style="border:1px solid #cccccc;background: #f1f1f1;padding:2px 5px;" />
                                </td>
                                <td width="15"></td>
                                <td>{tnum}</td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
            <tr>
                <td height="10" colspan="6" > </td>
            </tr>
    </table>
    <!-- END: kapat -->
    <div class="aramaLeft" style="width: 675px;float:left">
        <!-- BEGIN: kat -->
        <table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #cccccc">
            <tr>
                <td width="35" height="30" style="border-left:1px solid #cccccc">&nbsp;</td>
                <td class="basliklar" style="border-left:1px solid #cccccc"><p>Alan Adı</p></td>
                <!--<td align="center" class="basliklar">Hediye</td>-->
                <td align="center" class="basliklar"><p>Kategori</p></td>
                <td align="center" class="basliklar"><p>Fiyat</p></td>
                <td  align="center" class="basliklar">Sepet Durumu</td>
            </tr>
            <!-- BEGIN: rows -->
            <tr bgcolor="{bgcolor}">
                <td align="left"  class="icerikTd favori" height="30"><a href="add_fovarite.php?action=addfovarite&amp;domainID={domainID}" onclick="return false;"> <img src="images/favStar.png" alt="Favorilerime Ekle" name="imgFav_{domainID}" border="0" id="imgFav_{domainID}" title="Favorilerime Ekle"/> </a> </td>
                <td  class="icerikTd"><a href="{domNameLink}">{domName}</a></td>
                <!--<td align="center"  class="icerikTd">{aciklama}</td>-->
                <td align="center"  class="icerikTd"><span class="redLink">{catName}</span></td>
                <td align="center"  class="icerikTd"><p>{price}<strong></strong></p></td>
                <td align="center"  class="icerikTd">
					<!-- BEGIN: offer -->
						<a href="{domainOfferLink}" title="Teklif Ver" id="teklifver_but"><img src="images/teklifverbtn1.gif" name="Image14" width="96" height="23" border="0" id="imgTeklif_{domainID}" alt="Teklif Ver" /></a>
					<!-- END: offer -->
                    <!-- BEGIN: order -->
                    <div class="productPriceWrapRight">
						<!--<a href="add_basket.php?action=addToBasket&amp;domainID={domainID}" onclick="return false;"><img src="images/sepeteEkle.gif" alt="" border="0" id="imgSepet_{domainID}"/></a>-->
						<a href="javascript:void(0);" onclick="add_basket({domainID});"><img src="images/sepeteEkle.gif" name="Image11" width="96" height="23" border="0" id="imgSepet_{domainID}" alt="Sepete Ekle" /></a>
					</div>
					<!-- END: order -->
				</td>
            </tr>
            <!-- END: rows -->
            <!-- BEGIN: no_rows -->
            <tr>
                <td colspan="6" class="icerikTd" height="25">Kayıt Bulunamadı.</td>
            </tr>
            <!-- END: no_rows -->
        </table>
        <style type="text/css">
            #sayfalama {margin:10px 0;}
            #sayfalama a {line-height: 15px;margin:3px 1px;padding: 3px 8px; background: #f1f1f1;border:1px solid #cccccc}
        </style>
        <div id="sayfalama">{sayfalama}</div>
        <!-- END: kat -->
    </div>
    <div id="aramaRight">
        <!-- BEGIN: aramaSag -->
        <div id="detayli_arama">
            <div class="ozelArama">
			    <div style="margin-left:5px; margin-top:5px; color:#7f1d00; font-size:14px; font-weight:bold;">Kategoriler</div>
				<div style="margin-top:10px;">
					<ul class="left cats mr10">
						<!-- BEGIN: first_cat_block -->
						<li><a href="kategoriler.htm?categori={kategorilerID}&list=0&btnz=Ara" title="{kategoriler} Kategorisi">{kategoriler}</a></li>
						<!-- END: first_cat_block -->
					</ul>
					<ul class="left cats">
						<!-- BEGIN: second_cat_block -->
						<li><a href="kategoriler.htm?categori={kategorilerID}&list=0&btnz=Ara" title="{kategoriler} Kategorisi">{kategoriler}</a></li>
						<!-- END: second_cat_block -->
					</ul>
					<div class="clear"></div>
				</div>
			</div>
        </div>
        <!-- END: aramaSag -->

    </div>
</div>
<div class="clear"></div>
<!-- END: main -->