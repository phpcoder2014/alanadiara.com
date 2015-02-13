<!-- BEGIN: main -->
<script type="text/javascript">
   $(document).ready(function(){
      $(".productPriceWrap a img").click(function() {
         var productIDValSplitter = (this.id).split("_");
         var domainIDVal = productIDValSplitter[1];
         //alert(domainIDVal);return;
         $.ajax({
            type: "POST",
            url: "add_basket.php",
            data: { domainID: domainIDVal, action: "delToFavori"},
            success: function(data) {
               location.reload();
            }
         });
      });
      $(".productPriceWrapRight a img").click(function() {
         var productIDValSplitter = (this.id).split("_");
         var domainIDVal = productIDValSplitter[1];
         //alert(productIDVal);
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
<div id="kayitContent">
  	<div class="baslik">FAVORİLER</div>
   <!-- BEGIN: sepet -->
   <div class="sepet" >
      <table width="100%" border="0" cellpadding="5" cellspacing="0">
         <tr>
            <td height="30" align="center" class="basliklar"><p>Alan Adı</p></td>
            <td width="188" align="center" class="basliklar"><p>Fiyat</p></td>
            <td width="250" align="center" class="basliklar">&nbsp;</td>
         </tr>
         <!-- BEGIN: rows -->
         <tr>
            <td height="28" align="center" bgcolor="#FFFFFF" class="icerikTd">{domain_name}</td>
            <td align="center" bgcolor="#FFFFFF" class="icerikTd">{domain_price}</td>
            <td align="center" bgcolor="#FFFFFF" class="icerikTd">
                <table border="0" width="100%" cellpadding="1" cellspacing="1">
                    <tr>
                        <td class="productPriceWrap">
                            <a href="add_fovori.php?action=delttobasket&domainID={favID}" onClick="return false;">
                                <img src="images/kaldirBtn.gif" id="imgSepet_{favID}" alt=""/>
                            </a>
                        </td>
                        <td class="productPriceWrapRight">
                            <a href="add_basket.php?action=addToBasket&domainID={domainID}" onClick="return false;"><img src="images/sepeteEkle.gif" alt="" id="imgSepet_{domainID}"/></a>
                        </td>
                    </tr>
                </table>
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
   <!-- BEGIN: no_records -->
   <div class="sepet" >
       <br>
       <p style="padding: 10px">Favori domain bulunmamaktadır.</p>
   </div>
   <!-- END: no_records -->
</div>
<!-- END: main -->