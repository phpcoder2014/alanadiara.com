
	<div id="header"> <span class="logo"><img src="images/infoPazari.png" width="275" height="50" /></span> <span class="sptIcn"><img src="images/spt_icon.gif" /></span> <span class="sptCount">Sepet (0)</span>
		<div class="girisCountainer">
			<!-- BEGIN: form -->
			<form name="login" action="login.php" method="POST" enctype="multipart/form-data">
				<p class="hosgeldiniz"></p>
				<ul>
					<li>
						<input name="email" onclick="this.value='';this.style.color='#171717'" onblur="if(this.value==''){this.value='Kullanıcı Adı';this.style.color='#dedede'}" type="text" class="form" style="height:21px; width:152px; padding-top:3px; padding-left:4px;" value="Kullanýcý adý" />
					</li>
					<li>
						<input name="pass" onclick="this.value='';this.style.color='#171717'" onblur="if(this.value==''){this.value='Kullanıcı Adı';this.style.color='#dedede'}" type="password" class="form" style="height:21px; width:152px; padding-top:3px; padding-left:4px;" value="password" />
					</li>
					<li class="btn">
						<input type="image" src="images/girisBtn.gif" name="zbtn" value="Send" />
						<input type="hidden" value="Send" name="btn" />
					</li>
				</ul>
				<p class="settingsLink"><a href="#">Þifremi Unuttum</a> - <a href="#">Hemen Üye Ol</a> - <a href="facebook.php"><img src="images/facebook-connect-button.png" alt="Facebook ile Giriş"></a></span>
			</form>
			<!-- END: form -->
			<!-- BEGIN: start -->
			<p class="hosgeldiniz">HOÞGELDÝNÝZ <strong>Sedat BÝROL</strong></p>
			<ul>
				<li class="optionLink"><a href="account_management.php">Hesabım</a></li>
				<li class="optionLink"><a href="favori.php">Favorilerim </a></li>
				<li class="optionLink"><a href="search_result.php?searchvalue=&order=5&btn=Send">Premium </a>/ <a href="search_result.php?searchvalue=&order=6&btn=Send">Popüler </a>alan adları</li>
				<li class="btn"><a href="exit.php">Çıkış</a></li>
			</ul>
			<!-- END: start -->
		</div>
	</div>
	<div id="sorguContainer">
		<div class="sorguZone">
			<span class="sorguBaslik"><img src="images/domainSorgulama.png" width="245" height="27" /></span>
			<p class="aracizgi"><img src="images/spacer.gif" /></p>
      		<p class="sorgufield"><input onclick="this.value='';this.style.color='#171717'" onblur="if(this.value==''){this.value='Domain veya Anahtar Kelime';this.style.color='#dedede'}" name="sorgulama" type="text" class="sorguform" value="Domain veya Anahtar Kelime" /></p>
			<p class="samples">(örn. Css.info, Car, TouchScreen.info, Computers)</p>
			<p class="sorgulaBtn"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image6','','images/sorgulaBtna.gif',1)"><img src="images/sorgulaBtn.gif" alt="domain sorgula" name="Image6" width="136" height="31" border="0" id="Image6" /></a></p>
			<p class="aracizgi2"><img src="images/spacer.gif" /></p>
			<p class="detayliarama"><a href="#"><img align="absmiddle" src="images/whiteArrow.gif" /> Detaylý Arama</a></p>
		</div>
		<div class="flashZone">
			<p class="bannerimg"><img src="images/bannerimg.png" /></p>
		</div>
	</div>
	<div class="contentContainer">
		<div class="section1">
			<div>
				<div class="baslik1Bg">
					<div class="baslik1YaziBg">Pop&uuml;ler Alan Adlarý</div>
					<div class="aaBg">
						<div class="listRow">
							<div class="left w235 brown12-dark-bold mt7"><a href="#">sedatbirol.info</a></div>
							<div class="left w120 red13-dark-bold mt7">89,90 TL</div>
							<div class="left"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image11','','images/sepeteEklea.gif',1)"><img src="images/sepeteEkle.gif" name="Image11" width="96" height="23" border="0" id="Image11" /></a></div>
							<div class="clear"></div>
						</div>
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
								<li><a href="#">Eriþkin</a></li>
								<li><a href="#">Bilgisayar ve Ýnternet</a></li>
								<li><a href="#">Aile</a></li>
							</ul>
                			<ul class="left cats mr10">
                				<li><a href="#">Ýþ ve Finans</a></li>
                    			<li><a href="#">Eðitim</a></li>
                    			<li><a href="#">Yiyecek ve Ýçecek</a></li>
							</ul>
                			<ul class="left cats">
                				<li><a href="#">Otomotiv</a></li>
                    			<li><a href="#">Eðlence</a></li>
                    			<li><a href="#">Oyunlar</a></li>
                			</ul>
                			<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div id="footer">
	<div class="wrapper">
		<p class="links"><a href="#">Ana Sayfa</a> - <a href="#">SSS</a> - <a href="#">Ýletiþim</a> - <a href="#">Gizlilik Ýlkesi</a> - <a href="#">Üyelik Sözleþmesi</a></p>
		<p class="copyrights">Tüm Haklarý Saklýdýr © 2010 - alanadiara.com</p>
		<span class="logo"><img src="images/altLogo.png" width="194" height="39"  /></span>
	</div>
</div>
