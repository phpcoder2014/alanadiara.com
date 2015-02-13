<!-- BEGIN: main -->

<script type="text/javascript">
    $(document).ready(function(){
        $('form#domainSearch').submit(function(){
            if($("form#domainSearch input[name$='search']").val()==""){
                alert("Lütfen aramak istediniz kelimeyi giriniz.");
                return false;
            }else if($("form#domainSearch input[name$='search']").val()=="Domain veya Anahtar Kelime"){
                alert("Lütfen aramak istediniz kelimeyi giriniz.");
                return false;
            }else return true;
        });
    });
    
</script>
<div id="sorguContainer">
    <div class="sorguZone">
		<form name="domainSearch"  id="domainSearch" action="detayli_arama.htm" method="post" enctype="multipart/form-data">
			<span class="sorguBaslik"><img src="images/domainSorgulama.png" width="245" height="27" alt="Alan adı sorgulama" /></span>
			<p class="aracizgi"><img src="images/spacer.gif" /></p>
  			<p class="sorgufield">
				<input onclick="this.value='';this.style.color='#171717'" onblur="if(this.value==''){this.value='Domain veya Anahtar Kelime';this.style.color='#dedede'}" name="domain" type="text" class="sorguform" value="Domain veya Anahtar Kelime" /></p>
			<p class="samples">(örn. Spor, Cep Telefonu, giysiler.net, farlar.net)</p>
			<p class="sorgulaBtn">
				<input type="hidden" value="Send" name="btn" />
				<a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image6','','images/sorgulaBtna.gif',1)"><input type="image" src="images/sorgulaBtn.gif" name="Image6" width="136" height="31" border="0" id="Image6" /></a>
			</p>
			<p class="aracizgi2"><img src="images/spacer.gif" /></p>
			<!--<p class="detayliarama"><a href="detayli_arama.htm"><img align="absmiddle" src="images/whiteArrow.gif" /> Detaylı Arama</a></p>-->
		</form>
	</div>
    <div class="flashZone" style="text-align: center">
    	<div class="bannerimg">
			<img src="../images/msg1.png" alt="15.000 e yakın alan adı sizi bekliyor.">
    	</div>
	</div>
</div>
<!-- END: main -->