<!-- BEGIN: main -->
<link href="js/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/SpryTabbedPanels.js"></script>
<div class="contentContainer">
	<div class="section1">
		<div>
			<div class="baslik1Bg">
				<div class="baslik1YaziBg">Pop&uuml;ler Alan Adları</div>
				<div class="aaBg">
					<!-- BEGIN: tab1_rows -->
					<div class="listRow">
						<div class="left w235 brown12-dark-bold mt7"><a href="{domainNameLink}"><span id="dom_{domainID}">{domainName1}</span></a></div>
						<div class="left w120 red13-dark-bold mt7">{domain_price1}</div>
						<div class="left">
							<!-- BEGIN: offer -->
								<a href="{domainOfferLink}" title="Teklif Ver" id="teklifver_but"><img src="images/teklifverbtn1.gif" name="Image14" width="96" height="23" border="0" id="imgTeklif_{domainID}" alt="Teklif Ver" /></a>
							<!-- END: offer -->
							<!-- BEGIN: order -->
								<a href="javascript:void(0);" onclick="add_basket({domainID});" title="Sepete Ekle"><img src="images/sepeteEkle.gif" name="Image11" width="96" height="23" border="0" id="imgSepet_{domainID}" alt="Sepete Ekle" /></a>
							<!-- END: order -->
						</div>
						<div class="clear"></div>
					</div>
					<!-- END: tab1_rows -->
          		</div>
      		</div>
		</div>
	</div>
	<div class="section2">
		<div class="section1">
			<div>
				<div class="baslik2Bg">
					<div class="baslik2YaziBg">Kategoriler</div>
					<div class="aaBg">
						<ul class="left cats mr10">
							<!-- BEGIN: first_cat_block -->
							<li><a href="kategoriler.htm?categori={kategorilerID}&list=0&btnz=Ara" title="{kategoriler} Kategorisi">{kategoriler}</a></li>
							<!-- END: first_cat_block -->
						</ul>
            			<ul class="left cats mr10">
            				<!-- BEGIN: second_cat_block -->
							<li><a href="kategoriler.htm?categori={kategorilerID}&list=0&btnz=Ara" title="{kategoriler} Kategorisi">{kategoriler}</a></li>
							<!-- END: second_cat_block -->
						</ul>
            			<ul class="left cats">
            				<!-- BEGIN: third_cat_block -->
							<li><a href="kategoriler.htm?categori={kategorilerID}&list=0&btnz=Ara" title="{kategoriler} Kategorisi">{kategoriler}</a></li>
							<!-- END: third_cat_block -->
            			</ul>
            			<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<!-- END: main -->