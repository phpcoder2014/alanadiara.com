<!-- BEGIN: main --><script type="text/javascript">    $(document).ready(function(){        $('a[href="exit.php"]').click(function(){            if (confirm('Siteden çıkış yapmak üzeresiniz. Sepete eklediğiniz alan adı varsa sepetinizden çıkarılacaktır. Devam etmek istediğinize eminmisiniz?')) {                window.location='exit.php';            }else return false;        });        $.ajax({            type: "POST",            url: "add_basket.php",            data: { action: "getToBasket"},            success: function(data) {                $('#basketWrap').html("("+data+")");            }        });        <!-- BEGIN: login_sessionON -->        $.ajax({            type: "POST",            url: "add_basket.php",            data: { action: "getTofavori"},            success: function(data) {                $('#fovariteWrap').html("("+data+")");            }        });        <!-- END: login_sessionON -->    });</script><div id="header"> <span class="logo"><a href="index.htm"><img src="images/logo.png"  alt=""/></a></span> <span class="sptIcn"><img src="images/spt_icon.gif" /></span></span>    <div class="sptCount"><a href="basket.php">Sepet&nbsp;<span id="basketWrap" style="float: right">(0)</span></a></div>    <div class="girisCountainer">        <!-- BEGIN: start -->        <p class="hosgeldiniz">HOŞGELDİNİZ <strong>{user_name}</strong></p>		<p class="settingsLink optionLink"><a href="account_management.php">Hesabım</a>		<!--<a href="favori.php">Favorilerim <span id="fovariteWrap">(0)</span></a> - <a href="search_result.php?searchvalue=&order=5&btn=Send">Premium </a> / <a href="search_result.php?searchvalue=&order=6&btn=Send">Popüler </a>alan adları - --><a href="exit.php" style="float:right; margin-right:250px;">Çıkış</a></p>        <!-- END: start -->        <!-- BEGIN: form -->		<form name="login" action="login.php" method="POST" enctype="multipart/form-data">			<p class="hosgeldiniz"></p>				<div style="float:left; margin-left:28px; margin-right:5px;">					<input name="email" onclick="this.value='';this.style.color='#171717'" onblur="if(this.value==''){this.value='E-Mail Adresiniz';this.style.color='#171717'}" type="text" class="form" style="height:21px; width:152px; padding-top:3px; padding-left:4px;" value="E-Mail Adresiniz" />				</div>				<div style="float:left; margin-right:5px;">					<input name="pass" onclick="this.value='';this.style.color='#171717'" onblur="if(this.value==''){this.value='E-Mail Adresiniz';this.style.color='#171717'}" type="password" class="form" style="height:21px; width:152px; padding-top:3px; padding-left:4px;" value="password" />				</div>				<div style="float:left;">					<input type="image" src="images/girisBtn.gif" name="zbtn" value="Send" align="absmiddle" />					<input type="hidden" value="Send" name="btn" />				</div>				<div class="clear"></div>			<p class="settingsLink optionLink" style="margin-top:8px;"><a href="forget_passworld.php">Şifremi Unuttum</a> - <a href="new_user.php">Hemen Üye Ol</a> - <a href="facebook.php"><img align="absmiddle" src="images/facebook-connect-button.png" alt="Facebook ile Giriş"></a></span>		</form>		<!-- END: form -->    </div></div><!-- END: main -->