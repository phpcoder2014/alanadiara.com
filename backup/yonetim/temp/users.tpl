<!-- BEGIN: main -->
<div id="center-column">
    <!-- BEGIN: top_nav -->
    <div class="top-bar">
        <h1>KULLANICILAR</h1>
    </div><br />
    <!-- END: top_nav -->
    <!-- BEGIN: table -->
    <script type="text/javascript" src="../js/jquery.numeric.pack.js"></script>
    <script type="text/javascript">
		function wopen(fid){
			window.open ("http://www.infopazari.com/facebook_user_info.php?fid=" + fid,"Info","menubar=0,resizable=1,width=630,height=500,scrollbars=yes");
		}
		
        function goURL(id) {
            window.location='users.php?aktif_sayfa='+id;
            //alert(id)
        }

        $(document).ready(function(){
        $(function(){
            $("input[name$='puan']").numeric('.');
        });
            $(".productPriceWrap a span").click(function() {
                var productIDValSplitter = (this.id).split("_");
                var domainIDVal = productIDValSplitter[1];
                //alert(domainIDVal);
                if (confirm('Kayıtı silmek istedinize Eminmisiniz?')) {
                    $.ajax({
                        type: "POST",
                        url: "add_basket.php",
                        data: { domainID: domainIDVal, action: "delToBasket"},
                        success: function(data) {
                            //$('#basketWrap').html("("+data+")");
                            if(data=="OK"){
                                location.reload();
                            }else {
                                alert("Ba\u015farısız");
                            }
                        }
                    });
                }
            });
            $("input[name$='Update']").click(function(){
                ID=this.id;
                pricet =$('#ID_'+ID).val();
                if(confirm("Fiyatı güncellemek istediğinize eminmisiniz?")){
                    $.ajax({
                        type: "POST",
                        url: "add_basket.php",
                        data: { domainID: ID, price:pricet, action: "fiyatUpdate"},
                        success: function(data) {
                            if(data=="OK"){
                                alert('Fiyat Güncellendi...')
                            }else {
                                alert(data);
                            }
                        }
                    });
                }
            });
        });
    </script>
    <div class="select2" style="text-align: right;background:#9097A9;margin-bottom: 3px;float: right;">
        <a href="users.php?aktif_sayfa={geri_sayfa}">GERİ </a>
        |
        <a href="users.php?aktif_sayfa={ileri_sayfa}">İLERİ </a>
    </div>
    <div class="select2" style="text-align: right;background:#9097A9;margin-bottom: 3px;float: right;">
        <a href="#" onclick="return false;">TOPLAM: {toplamKayit} KAYIT | </a>
    </div>
    <div class="table">

        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <table class="listing" cellpadding="0" cellspacing="0">
            <tr >
                <th class="first" width="100">Kullanıcı Adı</th>
                <th width="20">E-Posta</th>
                <th width="40">Cinsiyet</th>
				<th width="60">Telefon</th>
                <th class="last" width="30">
                    <div style="float:left">Aktif</div>
                </th>
            </tr>
            <!-- BEGIN: rows -->
            <tr class="{bg}">
                <td class="first style1">{user_name}</td>
                <td>{email}</td>
                <td align="center">{sex}</td>
				<td>{phone}</td>
                <td>{status}</div>
                </td>
            </tr>
            <!-- END: rows -->
        </table>


        <div class="select">
            <strong>Diğer Sayfalar: </strong>
            <select name="aktif_sayfa" onchange="goURL(this.value)">
                <!-- BEGIN: t_num2 -->
                <option value="{num_value}" {selected_num}> {num}</option>
                <!-- END: t_num2 -->
            </select>
        </div>
        <div class="select2">
            <a href="users.php?aktif_sayfa={geri_sayfa}">GERİ </a>
            |
            <a href="users.php?aktif_sayfa={ileri_sayfa}">İLERİ </a>
        </div>
    </div>
    <!-- END: table -->
</div>
<!-- END: main -->