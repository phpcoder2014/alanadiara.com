<!-- BEGIN: main -->
<script type="text/javascript">
    function popup(mylink, windowname)
    {
        if (! window.focus)return true;
        var href;
        if (typeof(mylink) == 'string')
            href=mylink;
        else
            href=mylink.href;
        window.open(href, windowname, 'width=660,height=450,scrollbars=yes');
        return false;
    }
    function goURL(id) {
        window.location='order.php?aktif_sayfa='+id;
        //alert(id)
    }
    $(document).ready(function(){
        $("p.aktif_et a span").click(function() {
            if (confirm('Sipari\u015f\'i aktif etmek istediğinize eminmisiniz? ')) {
                var orderID = (this.id);
                
                $.ajax({
                    type: "POST",
                    url: "add_basket.php",
                    data: {orderID: orderID, action: "order_act"},
                    success: function(data) {
                        if(!data){
                            alert("Sipari\u015f Aktiflenemedi.");
                        }else {
                            //alert("Sipari\u015f Aktiflendi");
                            alert(data);
                            location.reload();
                        }
                    }
                });
            }else return false;
        });
        $("p.iptal a span").click(function() {
            if (confirm('Sipari\u015f\'i iptal etmek istediğinize eminmisiniz? ')) {
                var orderID = (this.id);
                $.ajax({
                    type: "POST",
                    url: "add_basket.php",
                    data: {orderID: orderID, action: "order_cancel"},
                    success: function(data) {
                        if(!data){
                            alert("Sipari\u015f Iptal edilemedi.");
                        }else {
                            alert("Sipari\u015f Iptal edildi.");
                            location.reload();
                        }
                    }
                });
            }else return false;
        });
        $("p.odeme a span").click(function() {
            if (confirm('Sipari\u015f\'i iptal etmek istediğinize eminmisiniz? ')) {
                var orderID = (this.id);
                $.ajax({
                    type: "POST",
                    url: "add_basket.php",
                    data: {orderID: orderID, action: "order_odemebekleniyor"},
                    success: function(data) {
                        if(!data){
                            alert("Islem gerçekleşmedi.");
                        }else {
                            alert("Sipari\u015f ödeme bekleniyor alanina taşındi.");
                            location.reload();
                        }
                    }
                });
            }else return false;
        });
    });
</script>
<div id="center-column">
    <!-- BEGIN: top_nav -->
    <div class="top-bar">

        <h1>Sipariş Detay {status}</h1>
    </div><br />
    <!-- END: top_nav -->
    <div class="table">
        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <table class="listing" cellpadding="0" cellspacing="0">
            <tr >
                <th class="first" width="15">id</th>
                <th>Müşteri</th>
                <th>Domain</th>
                <th width="80">Sip. Tarihi</th>
                <th class="last" width="80">Onay</th>
            </tr>
            <!-- BEGIN: rows -->
            <tr class="{bg}">
                <td>{sira}</td>
                <td>{costumer_id}</td>
                <td>{domID}</td>
                <td>{time}</td>
                <td>
                    <a href="#" onclick="popup('transfer_code.php?id={orderid}', 'detay');return false">Kod Gönder</a>
                </td>
            </tr>
            <!-- END: rows-->
            <tr>
                <td colspan="2"></td>
                <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Toplam başvuru adeti:</span> {t_num} </td>
            </tr>
        </table>
        <div class="select">
            <strong>Diğer Sayfalar: </strong>
            <select name="aktif_sayfa" onchange="goURL(this.value)">
                <!-- BEGIN: t_num -->
                <option value="{num_value}" {selected_num}> {num}</option>
                <!-- END: t_num -->
            </select>
        </div>
    </div>


</div>
<!-- END: main -->