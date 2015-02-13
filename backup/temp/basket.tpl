<!-- BEGIN: main -->
<script type="text/javascript">
    $(document).ready(function(){
        $(".productPriceWrap a img").click(function() {
            var productIDValSplitter = (this.id).split("_");
            var domainIDVal = productIDValSplitter[1];
            //alert(domainIDVal);
            $.ajax({
                type: "POST",
                url: "add_basket.php",
                data: { domainID: domainIDVal, action: "delToBasket"},
                success: function(data) {
                    $('#basketWrap').html("("+data+")");
                    location.reload();
                }
            });
        });
        $('#deleteBasket').click(function (){
            if (confirm('Sepetinizi temizlemek istediğinize eminmisiniz?')) {
                $.ajax({
                    type: "POST",
                    url: "add_basket.php",
                    data: { action: "deleteBasket"},
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    });
</script>
<div id="pageRankContent">
    <div class="baslikBar">ALIŞVERİŞ SEPETİ</div>
	<div class="clear"></div>
    <!-- BEGIN: sepet -->
    <div id="registerMain" >
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td height="30" align="center" class="basliklar"><p>Alan Adı</p></td>
                <td width="188" align="center" class="basliklar"><p>Fiyat</p></td>
                <td width="188" align="center" class="basliklar">&nbsp;</td>
            </tr>
            <!-- BEGIN: rows -->
            <tr>
                <td height="28" align="center" bgcolor="#FFFFFF" class="icerikTd"><span class="tableYazinormal2"><strong>{domain_name}</strong></span></td>
                <td align="center" bgcolor="#FFFFFF" class="icerikTd"><span class="tableYazinormal2"><strong>{domain_price}</strong></span></td>
                <td align="center" bgcolor="#FFFFFF" class="icerikTd productPriceWrap">
                    <a href="add_basket.php?action=addToBasket&domainID={domainID}" onClick="return false;">
                        <img src="images/kaldirBtn.gif" id="imgSepet_{domainID}" alt=""/>
                    </a>
                </td>
            </tr>
            <!-- END: rows -->
            <!-- BEGIN: total -->
            <tr>
                <td class="icerikTd" height="28"></td>
                <td colspan="2" class="icerikTd"><span class="pricetotal">Toplam :</span> <span class="pricetotalp">{totalPrice}</span></td>
            </tr>
            <!-- END: total -->
        </table>
        <br/>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><a href="register.php" ><img src="images/odemeYap.gif" name="Image14" width="96" height="23" border="0" id="Image14" alt=""/></a></td>
                <td>&nbsp;</td>
                <td><a href="{alisveris_devam}" ><img src="images/devametBtn.gif" name="Image15" width="140" height="23" border="0" id="Image15" alt=""/></a></td>
                <td>&nbsp;</td>
                <td>
                    <a href="add_basket.php?action=deletebasket" onClick="return false;">
                        <img src="images/listeClear.gif"  width="100" height="23"  alt="" id="deleteBasket"/>
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <!-- END: sepet -->
    <!-- BEGIN: no_record -->
    <div class="sepet">
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td class="icerikTd" height="40">Sepetinizde domain bulunmamaktadır.</td>
            </tr>
            <tr>
                <td height="20"></td>
            </tr>
            <tr>
                <td><a href="{alisveris_devam}" ><img src="images/devametBtn.gif" name="Image15" width="140" height="23" border="0" id="Image15" alt=""/></a></td>
            </tr>
        </table>
    </div>
    <!-- END: no_record -->
    <p style="line-height:200px;">&nbsp;</p>
</div>
</div>
<div class="clear">

<!-- END: main -->
