<!-- BEGIN: main -->
<div id="pageRankContent">
    <div class="baslikBar">ARAMA SONUÇLARI</div>
    <div id="aramaLeft">
        <p class="description">Aradığınız kritere ait toplam {arama_num} sonuç bulunmuştur.</p>
        <!-- BEGIN: list -->
        <table width="100%" border="0" cellpadding="5" cellspacing="2" class="searchResult">
            <tr>
                <th>&nbsp;</th>
                <th>Alan Adı</th>
                <th>Hediye</th>
                <th>Kategori</th>
                <th>Fiyat</th>
                <th>Sepet Durumu</th>
            </tr>
            <!-- BEGIN: rows -->
            <tr style="background-color: {trbgcolor}">
                <td><img src="images/favStar.png" border="0"></td>
                <td class="txtleft"><a href="{domnameLink}" class="domaktif"><strong>{domname}</strong></a></td>
                <td>{hediye}</td>
                <td width="180">{kategori}</td>
                <td width="70">{domain_price}</td>
                <td width="140">
					<!-- BEGIN: offer -->
						<a href="{domainOfferLink}" title="Teklif Ver" id="teklifver_but"><img src="images/teklifverbtn1.gif" name="Image14" width="96" height="23" border="0" id="imgTeklif_{domainID}" alt="Teklif Ver" /></a>
					<!-- END: offer -->
                    <!-- BEGIN: order -->
                    <div class="productPriceWrapRight">
                        <!--<a href="add_basket.php?action=addToBasket&domainID={domainID}" onClick="return false;"><img src="images/sepeteEkle.gif" alt="{domname}" title="{domname}" id='imgSepet_{domainID}' /></a>-->
						<a href="javascript:void(0);" onclick="add_basket({domainID});"><img src="images/sepeteEkle.gif" name="Image11" width="96" height="23" border="0" id="imgSepet_{domainID}" alt="Sepete Ekle" /></a>
                    </div>
                    <!-- END: order -->
                </td>
            </tr>
            <!-- END: rows -->
            <tr>
                <th colspan="6">
                    <!-- BEGIN: pager -->
                    <div id="pager">
                        <ul class="pages">
                            <li>Toplam Sayfa Sayısı: <strong>{topnum}</strong></li>
                            <li class="pgNext"><a href="{first_page}">&lt;&lt;</a></li>
                            <!-- BEGIN: rows -->
                            <li class="page-number {pgCurrent}"><a href="{pageLink}">{page}</a></li>
                            <!-- END: rows -->
                            <li class="pgNext pgEmpty"><a href="{last_page}">&gt;&gt;</a></li>
                        </ul>
                    </div>
                    <!-- END: pager -->
                </th>
            </tr>
        </table>
        <!-- END: list -->
    </div>
    <div id="aramaRight">
        {right}
    </div>
</div>
<div class="clear"></div>
<!-- END: main -->