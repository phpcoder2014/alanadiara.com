<!-- BEGIN: main -->
<script type="text/javascript" src="js/creditcard.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#cc_cart').hide();
        $('#cc_bank').hide();
        $('#cc_mailOrder').hide();
        $("input[name$='kindpay']").click(function() {
            if($('#cc_open3').is(':checked')){
                $('#cc_bank').hide();
                $('#cc_mailOrder').hide();
                $('#cc_cart').show();
            }else if($('#cc_open4').is(':checked')){
                $('#cc_bank').hide();
                $('#cc_mailOrder').hide();
                $('#cc_cart').show();
            }else if($('#cc_open1').is(':checked')){
                $('#cc_cart').hide();
                $('#cc_mailOrder').hide();
                $('#cc_bank').show();
            }else {
                $('#cc_cart').hide();
                $('#cc_bank').hide();
                $('#cc_mailOrder').show();
            }
        });
        $('form#order').submit(function(){
            if($("form#order input[name$='firm_name']").val()=="") {
                alert("Fatura ad\u0131nı giriniz.");
                return false;
            }else if($("form#order input[name$='firm_adress']").val()=="") {
                alert("Fatura adresini giriniz.");
                return false;
            }else if($("form#order input[name$='tax_office']").val()=="") {
                alert("Vergi dairesini giriniz.");
                return false;
            }else if($("form#order input[name$='tax_code']").val()=="") {
                alert("Vergi numaras\u0131nı giriniz.");
                return false;
            }
            if(!$("input[name$='kindpay']").is(':checked')){
                alert("Ödeme tipini seçiniz.");
                return false;
            }
            firm_name=$("form#order input[name$='firm_name']").val();
            firm_adress=$("form#order input[name$='firm_adress']").val();
            tax_office=$("form#order input[name$='tax_office']").val();
            tax_code=$("form#order input[name$='tax_code']").val();
            if($("input[name$='kindpay']:checked").val()==1){
                if(!$("input[name$='bank_info']").is(':checked')){
                    alert("Havele yi hangi bankaya yapaca\u011fınızı seçiniz.");
                    return false;
                }else {
                    bank_info=$("input[name$='bank_info']:checked").val();
                    $("input[name$='send']").attr('disabled', 'disabled');
                    $.ajax({
                        type: "POST",
                        url: "odemeal.php",
                        data: { action: "bank",
                            firm_name : firm_name,
                            firm_adress: firm_adress,
                            tax_office : tax_office,
                            tax_code : tax_code,
                            bank_info : bank_info
                        },
                        success: function(data) {
                            //$('#basketWrap').html("("+data+")");
                            alert(data);
                            window.location='order_complate.php?kindpay=1';
                        }
                    });
                }
            }else if($("input[name$='kindpay']:checked").val()==2){
            $("input[name$='send']").attr('disabled', 'disabled');
                $.ajax({
                    type: "POST",
                    url: "odemeal.php",
                    data: { action: "mail_order",
                        firm_name : firm_name,
                        firm_adress: firm_adress,
                        tax_office : tax_office,
                        tax_code : tax_code
                    },
                    success: function(data) {
                        //$('#basketWrap').html("("+data+")");
                        alert(data);
                        window.location='order_complate.php?kindpay=2';
                    }
                });
            }else if($("input[name$='kindpay']:checked").val()==3){
                if($("form#order input[name$='ccardname']").val()=="") {
                    alert("Kredi kart\u0131 üzerindeki ismi yazınız.");
                    return false;
                }else if($("form#order input[name$='cvv2']").val()=="") {
                    alert("Kredi kart\u0131nın güvenlik numarasını yazınız.");
                    return false;
                }else {
                    alert("Ba\u011flantı Kurulamıyor.");
                    return false;
                }
            }else if($("input[name$='kindpay']:checked").val()==4){
                if($("form#order input[name$='ccardname']").val()=="") {
                    alert("Kredi kart\u0131 üzerindeki ismi yazınız.");
                    return false;
                }else if($("form#order input[name$='cvv2']").val()=="") {
                    alert("Kredi kart\u0131nın güvenlik numarasını yazınız.");
                    return false;
                }else {
                    alert("3d bilgileri al\u0131namadı.");
                    return false;
                }
            }
            //alert($("input[name$='kindpay']:checked").val());bank_info
            return false;
        });
    });
</script>

<div id="pageRankContent">
  <div class="baslikBar">DOMAIN KAYIT SAYFASI</div>
  <!-- BEGIN: havale_thanks --> 
  <div style="font-size:13px; color:#340c00; line-height:21px; border:1px solid #dedede; padding:20px; width:918px; background:white; float:left">
    <p style="font-size:15px; font-weight:bold; color:#900">Teşekkürler!..</p>
    <p class="shdwLine"></p>
    <p>Ödemeniz gereken tutar 65 $'dır </p>
    <p>Gerekli tutarı 3 iş günü içerisinde aşağıdaki hesapların birine "TURKTICARET.Net Yazılım Hizmetleri A.Ş." adına havale etmeniz gerekmektedir.</p>
    <br />
    <p><strong>ÖNEMLİ :</strong> İşlemlerin daha hızlı tamamlanabilmesi için lütfen;</p>
    <ul style="list-style-position:inside">
      <li>Havale yaparken "Gönderen" bölümünü açık ve net olarak doldurunuz. </li>
      <li>"Açıklama" bölümünde {siteDomName} Kullanıcı adınızı ( kayıtlı mail adresiniz) ve ödemeyi hangi ürün/hizmet için yaptığınızı belirtiniz. </li>
      <li>Ödemeyi yaptıktan sonra <span class="sLink2"><a href="#">Ödeme Bildirim Formunu</a></span> mutlaka doldurunuz.</li>
    </ul>
  </div>
  <p style="line-height:150px;">&nbsp;</p>
    <!-- END: havale_thanks --> 
      <!-- BEGIN: kredi_thanks --> 
  <div style="font-size:13px; color:#340c00; line-height:21px; border:1px solid #dedede; padding:20px; width:918px; background:white; float:left">
    <p style="font-size:15px; font-weight:bold; color:#900">Teşekkürler!..</p>
    <p class="shdwLine"></p>
    <p>Ödemeniz gereken tutar 65 $'dır </p>
    <p>Bu tutar kredi kartınızdan çekilmiştir. İşlemleriniz en kısa sürede tamamlanacaktır.  İşlemlerinizin durumunu “Alan Adı Merkezi” sekmesinden takip edebilirsiniz. </p>
    <br />
    <p><strong>ÖNEMLİ :</strong></p>
    <ul style="list-style-position:inside">
      <li>Kredi kartı hesap bildirim cetvelinizde ödemeniz firmamızın ticari ünvanı olan TURKTICARET.Net A.Ş. adına tahsil edilmiş olarak görünecektir.
 </li>
      <li><font color="#DC0A0F" style="font-weight:bold">62.248.36.85</font> no'lu Ip'den işlem yapmış olduğunuz kayıt altına alınmıştır. Kredi kartı ile ilgili bir usulsüz işlem gerçekleştirilmesi durumunda bu bilgi kullanılacaktır. 
 </li>
    </ul>
  </div>
  <p style="line-height:150px;">&nbsp;</p>
    <!-- END: kredi_thanks --> 
   <!-- BEGIN: mailorder_thanks -->   
  <div style="font-size:13px; color:#340c00; line-height:21px; border:1px solid #dedede; padding:20px; width:918px; background:white; float:left">
    <p style="font-size:15px; font-weight:bold; color:#900">Teşekkürler!..</p>
    <p class="shdwLine"></p>
    <p>Ödemeniz gereken tutar 65 $'dır </p>
    <p>Gerekli tutarı 3 iş günü içerisinde ödemeniz gerekmektedir.</p>
    <br />
    <p>Aşağıdaki "mail order ile ödeme" başlığına tıklayarak gelen sayfanın çıktısını alınız ya da aynısını oluşturunuz.<br />
Gerekli alanları doldurup, 0-224-224 95 20 no'lu faksa gönderiniz.<br />
Ödemeniz tahsil edilip, işleminiz tamamlanacaktır.<br /><br />

</p>
   <-- <span class="sLink2"><a href="#"> Mail Order ile Ödeme Sayfası </a></span> --> 
  </div>
  <p style="line-height:150px;">&nbsp;</p>
    <!-- END: mailorder_thanks --> 
</div>
<!-- END: main -->
