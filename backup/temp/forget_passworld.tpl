<!-- BEGIN: main -->
<!-- BEGIN:new_user -->
<script type="text/javascript">
    $(document).ready(function() {
        $('input#btn').hover(function() {
            $(this).css('cursor','hand');
            $(this).attr("src", "images/devamBtna.png");
        }, function() {
            $(this).attr("src", "images/devamBtn.png");
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
            if(hata !="") {
                alert(hata);
                return false;
            }else {
                return true;
            }
         
        });
    });
</script>
<div id="pageRankContent">
    <div class="baslik">ŞİFREMİ UNUTTUM</div>
    <div class="leftpart">
        <br />
        <form method="POST" enctype="multipart/form-data" name="new_user" id="new_user" action="">
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
                                <td class="tableYazinormal" width="150">E-posta <font color="#FF0000">*</font></td>
                                <td  align="left"><input style="height:20px; padding-top:3px; padding-left:2px; border:1px solid #c9c9c9" name="email" type="text" id="email" size="50" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>


                <!-- BEGIN: securecode -->
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
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="tableYazinormal" width="150">Resimde g&ouml;rd&uuml;ğ&uuml;n&uuml;z karakterleri giriniz.<br />
                                    Büyük küçük harfe duyarlıdır. <br/>Resmi yenilemek i&ccedil;in
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
                    <td height="13"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="1" bgcolor="#dfdfdf"></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" value="Send" name="btn" />
                        <input type="image" src="images/devamBtn.png" name="btnz" value="Send"  id="btn"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="rightpart formAciklama">
        <font color="#FF0000">*</font> <span class="big">GEREKLİ ALANLARI DOLDURUNUZ</span><br />
        <p>E-posta adresiniz kullanıcı adı olarak kullanılmaktadır. E-posta adresiniz  bir alanı adı satın almanız için üye girişi yapmanızı sağlar. {siteDomName} olarak üçüncü şahıslarla hiçbir şekilde e-posta adresinizi paylaşılmaz.  Sadece aktif e-posta adresiniz yeni hesabınızı doğrulamak için gereklidir.<br />
        </p>
        <p>
            Gizli soru ve cevap bilgileriniz hesap ayarları sayfasına girebilmeniz için önemlidir.  Eğer bu bilgileri doğru giremez iseniz şifre değişikliği yapmanız engellenecektir.
            <br />
            <br />
            Gizli soru ve cevap bilgilerinizi unuttu iseniz nüfus cüzdanı fotokopisi ile hatırlatma talebinde bulunabilirsiniz. <br />
            <br />
            Hatırlatma talebi için <a href="mailto:{siteDomEmail}?subject=Hatirlatma talebi">tıklayınız…</a>
        </p>
    </div>
</div>
<div class="clear"></div>
<!-- END: new_user -->
<!-- BEGIN: saved -->
<div id="kayit_ok_Content">
    <div class="baslik">ŞİFRENİZ KAYITLI MAİL ADRESİNİZE GÖNDERİLMİŞTİR!</div>
    <div class="aciklamasi">
        <p>{siteDomName} sitesine giriş yapabilmeniz için gerekli olan şifre bilginiz kayıtlı mail adresinize gönderilmiştir.</p>
        <p> Şifremi unuttum isteğinde bulunmadıysanız lütfen irtibata geçiniz. İrtibata geçmek için <a href="mailto:{siteDomEmail}?subject=sifre degisikligi">tıklayınız.</a> </p>
    </div>
</div>
<!-- END: saved -->
<!-- END: main -->