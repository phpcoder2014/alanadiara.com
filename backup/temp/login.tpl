<!-- BEGIN: main -->
<script type="text/javascript">
   $(document).ready(function() {
      $('input#btn').hover(function() {
         $(this).css('cursor','hand');
         $(this).attr("src", "images/girisBtna.gif");
      }, function() {
         $(this).attr("src", "images/girissBtn.gif");
      });
      $('form#user_login').submit(function(){
         var hata="";
         var email=$("form#user_login input[name$='email']").val();
         if(email != 0)
         {
            if(!isValidEmailAddress(email))
            {
               hata +="* Lütfen E-mail adresini do\u011fru yaz\u0131n\u0131z.\n";
            }
         } else {
            hata +="* Lütfen E-mail adresini yaz\u0131n\u0131z.\n";
         }
         if($("input[name$='pass']").val()=="") {
            hata +="* Lütfen \u015fifrenizi yaz\u0131n\u0131z.\n";
         }
         if(hata !="") {
            alert(hata);
            return false;
         }else {
            return true;
         }
      });
   });
</script>
<div id="uyeContent">
  	<div class="baslik">&Uuml;YE GİRİŞİ</div>
   <div class="formZone">
      <form name="user_login" id="user_login" action="" method="POST" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
         <!-- BEGIN: mesaj -->
         <tr>
            <td>{mesaj}</td>
         </tr>
         <tr>
            <td height="13"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                     <td height="1" bgcolor="#dfdfdf"></td>
                  </tr>
               </table></td>
         </tr>
         <!-- END: mesaj -->
         <tr>
            <td height="25"><table width="100%" border="0" cellpadding="3" cellspacing="0">
                  <tr>
                     <td class="tableYazinormal">E-posta <font color="#FF0000">*</font></td>
                     <td  align="right"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="email" type="text" id="email" size="30" /></td>
                  </tr>
               </table></td>
         </tr>
         <tr>
            <td height="13"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                     <td height="1" bgcolor="#dfdfdf"></td>
                  </tr>
               </table></td>
         </tr>
         <tr>
            <td height="25"><table width="100%" border="0" cellpadding="3" cellspacing="0">
                  <tr>
                     <td class="tableYazinormal">Şifre <font color="#FF0000">*</font></td>
                     <td  align="right"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="pass" type="password" id="pass" size="30" /></td>
                  </tr>
               </table></td>
         </tr>
         <tr>
            <td height="13"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                     <td height="1" bgcolor="#dfdfdf"></td>
                  </tr>
               </table></td>
         </tr>
         <tr>
            <td style="padding-left:80px;" height="27">
				<input type="hidden" value="{back_url}" name="backurl" />
                <input type="hidden" value="Send" name="btn" />
				<div style="float:left;">
					<input type="image" src="images/loginBtn.png" name="btnz" value="Send" />
				</div>
				<div style="float:left; margin-left:10px;">
					<a href="facebook.php?from={back_url}"><img align="absmiddle" alt="Facebook ile Giriş" src="images/loginwithFBtn.png" width="98" height="27"></a>
				</div>
            </td>
         </tr>
         <tr>
            <td>
               <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                     <td width="45%" class="sLink"><a href="forget_passworld.php">Şifremi Unuttum </a></td>
                     <td width="10%"></td>
                     <td width="45%"></td>
                  </tr>
               </table>
            </td>
         </tr>
         
      </table>

   </form>
   </div>
   <div class="kayitolun">
      <p><span class="big">KAYIT OL !</span><br />
         Hemen ücretsiz bir <span class="uyelikolusturLink"><a href="#">üyelik oluşturun</a></span></p>
      <p><span class="big"><br />
            &Uuml;YELİK AVANTAJLARI</span><br /></p>
      <ul style="list-style-position:inside;"><li><a href="#">Alan adlarınızı   yönetin</a></li>
         <li><a href="#">Çok cazip   fiyatlarla alan adı tescil edin</a></li>
         <li><a href="#">Premium ve popüler alan adlarından size uygun olanı seçin</a></li>
         <li><a href="#">Kategori bazlı alan adı arayın</a></li>
      </ul>
   </div>
   <div class="bannerSag">
      {300x250}
   </div>
</div>
<!-- END: main -->