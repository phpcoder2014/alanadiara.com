<!-- BEGIN: main -->
<!-- BEGIN:new_user -->
<script type="text/javascript" src="js/jquery.numeric.pack.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('input#btn').hover(function() {
            $(this).css('cursor','hand');
            $(this).attr("src", "images/kaydolBtn.gif");
        }, function() {
            $(this).attr("src", "images/kaydolBtna.gif");
        });
        $('form#new_user').submit(function(){
            var hata="";
            var email=$("form#new_user input[name$='email']").val();
            if($("input[name$='name']").val()=="") {
                hata +="* Lütfen ad\u0131nızı yaz\u0131n\u0131z.\n";
            }
            if(email != 0)
            {
                if(!isValidEmailAddress(email))
                {
                    hata +="* Lütfen E-mail adresini do\u011fru yaz\u0131n\u0131z.\n";
                }
            } else {
                hata +="* Lütfen E-mail adresini yaz\u0131n\u0131z.\n";
            }
            if($("form#new_user input[name$='pass']").val()=="") {
                hata +="* Lütfen \u015fifrenizi yaz\u0131n\u0131z.\n";
            }else if($("form#new_user input[name$='pass']").val().length<6 || $("form#new_user input[name$='pass']").val().length>20){
                 hata +="* \u015eifreniz en az 6 en fazla 20 karekter arasında olmalıdır.";
            }else if($("form#new_user input[name$='pass']").val()!=$("input[name$='passt']").val()){
                hata +="* Lütfen \u015fifre ve onay \u015fifre alanlar\u0131n\u0131 aynı giriniz.\n";
            }
			if($("input[name$='phone']").val()==""){
                hata +="* Lütfen telefon yaz\u0131nız.\n";
            }
            if($("input[name$='soru']").val()==""){
                hata +="* Lütfen gizli sorunuzu yaz\u0131nız.\n";
            }
            if($("input[name$='cevap']").val()==""){
                hata +="* Lütfen gizli soru cevab\u0131nı yazınız.\n";
            }
            if($("input[name$='code']").val()==""){
                hata +="* Lütfen güvenlik resmini yaz\u0131nız.\n";
            }
            if(!$("input[name$='onay']").is(":checked")){
                hata +="* Lütfen üyelik sözle\u015fmesini onaylayınız.\n";
            }
            if(hata !="") {
                alert(hata);
                return false;
            }else {
                return true;
            }
         
        });
    });
	$(
            function()
            {
                $("input#phone").numeric("");
            }

        );
</script>
<div id="pageRankContent">
    <div class="baslikBar">KAYIT OLUN</div>
    <div class="leftpart" >
        <form method="POST" enctype="multipart/form-data" name="new_user" id="new_user" action="" style="margin: 20px">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <!-- BEGIN: mesaj -->
                <tr>
                    <td>
                        {mesaj}
                    </td>
                </tr>
                <!-- END: mesaj -->
                <tr>
                    <td height="25">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td class="tableYazinormal" width="150">Ad Soyad<font color="#FF0000">*</font></td>
                                <td  align="left"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="name" type="text" id="name" size="50" value="{n_name}" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="13"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td height="25">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td class="tableYazinormal" width="150">E-posta <font color="#FF0000">*</font></td>
                                <td  align="left"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="email" type="text" id="email" size="50" value="{n_email}"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="13"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table></td>
                </tr>
				<tr>
                    <td height="25">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td class="tableYazinormal" width="150">Telefon <font color="#FF0000">*</font></td>
                                <td  align="left"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="phone" type="text" id="phone" size="50" value="{n_phone}" maxlength="11"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="13"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td height="25">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td class="tableYazinormal" width="150">Şifre <font color="#FF0000">*</font></td>
                                <td  align="left"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="pass" type="password" id="pass" size="50" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="15"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td height="25">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td class="tableYazinormal" width="150">Onay Şifre <font color="#FF0000">*</font></td>
                                <td  align="left"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="passt" type="password" id="passt" size="50" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="13">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="25">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td class="tableYazinormal" width="150">Gizli Soru <font color="#FF0000">*</font></td>
                                <td  align="left"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="soru" type="text" id="soru" size="50" value="{n_soru}"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="13">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="25">
                        <table width="100%" border="0" cellpadding="3" cellspacing="0">
                            <tr>
                                <td class="tableYazinormal" width="150">Gizli Soru Cevabı <font color="#FF0000">*</font></td>
                                <td  align="left"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="cevap" type="text" id="cevap" size="50" value="{n_cevap}"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="13">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- BEGIN: securecode -->
                <tr>
                    <td height="25">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="tableYazinormal" width="150">Resimde g&ouml;rd&uuml;ğ&uuml;n&uuml;z karakterleri giriniz.<br />
                                    Büyük küçük harfe duyarlıdır.
                                    Resmi yenilemek i&ccedil;in
                                    <span class="tableYazi">
                                        <a tabindex="-1" style="border-style: none" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'securimage/securimage_show.php?sid=' + Math.random(); return false">tıklayın</a>
                                    </span>
                                </td>
                                <td align="left"><table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                                <img id="siimage" align="left" style="padding-right: 5px; border: 0" src="securimage/securimage_show.php?sid=<?php echo md5(time()) ?>" alt=""/>
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="5"></td>
                                        </tr>
                                        <tr>
                                            <td><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="code" type="text" id="code" size="46" /></td>
                                        </tr>
                                    </table></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- END: securecode -->
                <tr>
                    <td height="13">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><input type="checkbox" name="onay" value="ON" /></td>
                                <td width="12"></td>
                                <td class="tableYazinormal">
                                    <p><a href="uyelik_sozlesmesi.doc" target="_blank">&Uuml;yelik   S&ouml;zleşme</a> ve <a href="gizlilik_politikasi.doc">Gizlilik İlkesi</a> okudum. Kabul ediyorum.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="13"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" value="Send" name="btn" />
                        <input type="image" src="images/kaydolBtna.gif" name="btnz" value="Send"  id="btn"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div class="rightpart formAciklama" >
        <font color="#FF0000">*</font> <span class="big">GEREKLİ ALANLARI DOLDURUNUZ</span><br />
        <p>E-posta adresinizi bir alanı adı satın almanız için üye girişi yapmanızı sağlar. {siteDomName} olarak  üçüncü şahıslarla e-posta adresinizi paylaşmayız.   Ancak, aktif e-posta adresiniz yeni hesabınızı doğrulamak için   gereklidir.<br />
        </p>
        <p>Gizli soru ve cevap bilgileriniz hesap ayarları sayfasına girebilmeniz için önemlidir. Eğer bu bilgileri doğru giremez iseniz şifre değişikliği yapmanız engellenecektir. <br />
            <br />
            Gizli soru ve cevap bilgilerinizi unuttu iseniz nüfus cüzdanı fotokopisi ile hatırlatma talebinde bulunabilirsiniz. <br />
            <br />
            hatırlatma   talebi i&ccedil;in <a href='mailto:{siteDomEmail}?subject=Hatirlatma talebi'>tıklayınız&hellip;</a></p>
    </div>
	<div class="rightpart formAciklama" style="margin-top:10px;">
        <font color="#FF0000">*</font> <span class="big">FACEBOOK İLE GİRİŞ YAP</span><br />
        <p>Kayıt formunu doldurmadan Facebook hesabınız ile giriş yapabilirsiniz.<br />Facebook ile giriş için: <a href="facebook.php"><img width="98" height="27" align="absmiddle" src="images/loginwithFBtn.png" alt="Facebook ile Giriş"></a></p>
    </div>
</div>
<!-- END: new_user -->
<!-- BEGIN: saved -->
<div id="kayit_ok_Content">
    <div class="baslik">KAYIT İŞLEMİNİZ BAŞARIYLA GER&Ccedil;EKLEŞTİRİLMİŞTİR !</div>
    <div class="aciklamasi">
        <p>Sayın {username}, hesabınız oluşturuldu.Lütfen Hesabınızı etkinleştiriniz!</p>
        <p>Hizmetlerimize göstermiş olduğunuz ilgi için teşekkür ederiz.</p>
        <p>{siteDomName}  hizmetlerine erişebilmek için gelen maildeki onay linkini
            tıklayarak üyelik hesabınızı aktif etmeniz gerekmektedir.
        </p>
        <p>
            Bir kez doğrulama yaparak hesap yönetiminize erişilebilir, çok cazip fiyatlarla size en uygun
            alan adlarına sahip olabilirsiniz.
        </p>
    </div>
</div>
<!-- END: saved -->
<div class="clear"></div>
<!-- END: main -->