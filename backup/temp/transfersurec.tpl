<!-- BEGIN: main -->
<script type="text/javascript" src="js/popup.js"></script>
<script type="text/javascript" >
    $(document).ready(function(){
        var status=0;
        $('#tenks').hide();
        $('#error').hide();
        $('a.popup').click(function(){

            var mylink="popup.html";
            var windowname="InfoPazari";
            popup(mylink,windowname);

        });
        function popup(mylink, windowname)
        {
            if (! window.focus)return true;
            var href;
            if (typeof(mylink) == 'string')
                href=mylink;
            else
                href=mylink.href;
            window.open(href, windowname, 'width=650,height=450,scrollbars=yes');
            return false;
        }
        $(".transfer_s a img").click(function() {
            var productIDValSplitter = (this.id).split("_");
            var domainIDVal = productIDValSplitter[1];
            //alert(domainIDVal);
            if(status==0) {
                //alert(domainIDVal);
                $.ajax({
                    type: "POST",
                    url: "add_basket.php",
                    data: { domainID: domainIDVal, action: "addToTransfer"},
                    success: function(data) {
                        if(data=="-1") {
                            alert("Lütfen oturum açınız...");
                        }else if(data=="0"){
                            alert("Bu domain için transfer başvurusu devam etmektedir.");
                            status=1;
                        }else if(data=="1"){
                            $('#tenks').show();
                            $('#error').hide();
                            status=1;
                        }else if(data=="99") {
                            $('#error').hide();
                            $('#tenks').show();
                            status=0;
                        }else  {
                            alert(data);
                        }
                        //
                    }
                });
            }
        });
    });
</script>
<div id="sss_Content">
    <div class="baslik">ALAN ADI TRANSFER</div>
    <div class="sssaciklamasi">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><strong>Transfer etmek istediğiniz alan adı: </strong> <a href="javascript:;">{domain}</a></td>
				<?php var_dump({domain}); ?>
                <td width="30"> </td>
                <td class="transfer_s"><a href="javascript:;"><img src="images/tkodgonder.gif" border="0" alt="" id="dom_{domID}"/></a></td>
            </tr>
        </table>
        <br />
        <p  id="tenks">Teşekkürler.<br><br>
            Transfer kodu talebiniz alınmıştır. Transfer kodunuz 24 saat içinde kayıtlı mail adresinize gönderilecektir.<br>
        </p>
        <p  id="error">Hata Oluştu.<br><br>
            Transfer kodu talebinizde bir hata oluştu. Lütfen daha sonra yeniden deneyiniz.
            Sorun devam ederse lütfen <a href="mailto:{siteDomEmail}?subject=Transfer kodu">tıklayınız.</a><br>
        </p>
        <!-- BEGIN: domainTransferstatus -->
        <p >Bu domain için transfer başvurusu yapılmış.<br><br>
            Transfer Durumu: <b>{transfer_status}</b>
        </p>
        <!-- END: domainTransferstatus -->

        <br />
        <strong>Transfer yapacağınız alan adı aşağıdaki özelliklere sahip  olmalıdır.</strong><br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt='' > Alan adı tescil edildikten sonra en az 60 gün geçmiş olmalı<br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt=''> Alan adının tescil süresinin bitimine en az 10 gün kalmış olmalı<br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt=''> Alan adının kayıtlı olduğu firmaya borcunun olmaması<br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt=''> İdari yetkili (Admin Contact) mailinin çalışır durumda olması<br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt=''> Transfer isteğinde bulunan kişinin transfer edilecek alan adının sahibi olması.<br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt=''> Alan adlarının transfer etmek için Transfer Kilidi'nin açık olması gereklidir. Kilitli olan alan adlarını transfer için açtırınız.<br />
        <br />
        <strong>Alan Adı Transfer Süreci</strong><br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt=''> "Transfer Kodu Gönder" butonunu tıkladıkta sonra admin e-postasına tansfer kodu ( authorization code) gönderilecektir.
        <br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt=''> Transfer kodunuzu ( authorization code)  alan adınızın transfer edeceğiniz registrar firmasından iletiniz.
        <br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt=''> Transfer işleminiz alan adınızı transfer etmek istediğiniz diğer registrar firma tarafından takip edicelcektir.
        <br />
        <img src="images/tkodok.gif" border="0" align="absmiddle" alt=''> Transfer kodunuzun geçerlilik süresi 5 gündür. 5 gün içersinde transfer işlemlerinizi başlatmaz iseniz yeni bir transfer kodu talebinde bulunmanız gerekecektir.

        <p></p>
    </div>
</div>
<!-- END: main -->