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
        window.open(href, windowname, 'width=600,height=500,scrollbars=yes');
        return false;
    }
	function offer_popup(mylink) {
        if (! window.focus)return true;
        var href;
        if (typeof(mylink) == 'string')
            href=mylink;
        else
            href=mylink.href;
        window.open(href, 'Teklif Ver', 'width=625,height=350,scrollbars=no');
        return false;
    }
    function goURL(id) {
        window.location='?aktif_sayfa='+id;
        //alert(id)
    }   
	function offer_actions(id, act){
		if(act=='delete_out' && confirm("Teklifi iptal etmek istediğinize emin misiniz?")){
			$.ajax({
        	    type: "POST",
        	    url: "../offer_actions.php",
        	    data: {offerid: id, action: act},
        	    success: function(data) {
					alert(data);
					location.reload();
        	    }
        	});
		}
		if(act=='accept' && confirm("Teklifi kabul etmek istediğinize emin misiniz?")){
			$.ajax({
				type: "POST",
				url: "../offer_actions.php",
				data: {offerid: id, action: act},
				success: function(data) {
					alert(data);
					location.reload();
				}
        	});
		}
	}
</script>
<div id="center-column">
    <!-- BEGIN: top_nav -->
    <div class="top-bar">

        <h1>{status}</h1>
    </div><br />
    <!-- END: top_nav -->
    <div class="table">
        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <table class="listing" cellpadding="0" cellspacing="0">
            <tr >
                <th class="first" width="15">id</th>
				<th>Ip No</th>
                <th>Teklif Sahibi</th>
                <th>Domain</th>
                <th>Fiyat</th>
                <th width="80">Sip. Tarihi</th>
                <th width="20">Detay</th>
                <th class="last" width="50">Onay</th>
            </tr>
            <!-- BEGIN: rows -->
            <tr class="{bg}">
                <td>{sira}</td>
				<td>{ipno}</td>
                <td>{costumer_id}</td>
                <td>{domName}</td>
                <td>{price} $</td>
                <td>{time}</td>
                <td><a href="#" onclick="return false;"><img src="img/edit-icon.gif" alt="detay" onclick="popup('offer_info.php?reofferid={offerid}', 'detay')"/></a></td>
                <td>
                    <!-- BEGIN: aktif -->
                    <p class="kabul_et" onclick="offer_actions({offerid}, 'accept');"><a href="javascript:void(0);"><span id="{offerid}">Kabul Et</span></a></p>
					<p class="teklif_ver" onclick="offer_popup('make_offer.php?offerid={offerid}');"><a href="javascript:void(0);"><span id="{offerid}">Teklif Ver</span></a><br></p>
                    <!-- END: aktif -->
                    <!-- BEGIN: cancel -->
                    <p class="iptal" onclick="offer_actions({offerid}, 'delete_out');"><a href="javascript:void(0);"><span id="{offerid}">İptal</span></a><br></p>
                    <!-- END: cancel -->
                    <!-- BEGIN: tasima -->
                    <p class="tasima" onclick="return false;"><a href="{orderid}" ><span id="{orderid}">Taşıma Tamamla</span></a><br></p>
                    <!-- END: tasima -->
                    <!-- BEGIN: odeme -->
                    <p class="odeme" onclick="return false;"><a href="{orderid}" ><span id="{orderid}">Ödeme bekleniyor</span></a><br></p>
                    <!-- END: aktif -->
                    <!--<a href="#" onclick="return false;"><img src="img/save-icon.gif" alt="detay" onclick="popup('order_act.php?orderid={orderid}', 'aktivate')"/></a>-->
                </td>
            </tr>
            <!-- END: rows-->
            <tr>
                <td colspan="3"></td>
                <td colspan="4"><span>Toplam sipariş adeti:</span>{t_num} </td>
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