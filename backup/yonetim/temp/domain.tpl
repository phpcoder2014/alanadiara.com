<!-- BEGIN: main -->
<div id="center-column">
    <!-- BEGIN: top_nav -->
    <div class="top-bar">
        <a href="#" class="button">YENİ EKLE </a>
        <h1>DOMAIN</h1>
    </div><br />
    <form name="ara" action="domain.php?z=list" method="post">
        <div class="select-bar">
            <label>
                <input type="text" name="domName" id="domName" value="{domName}" />
				<input type="hidden" name="search" value="1" />
            </label>
			<label>
				<label><input type="checkbox" name="no_cat" value="1" {no_cat_select} />Kategorisizler</label>
				<label><input type="checkbox" name="yes_cat" value="1" {yes_cat_select} />Kategorililer</label>
			</label>
            <label>
                <input type="submit" name="Submit" value="  Ara  " />
            </label>
        </div>
    </form>
    <!-- END: top_nav -->
    <!-- BEGIN: cat_duzelt -->
    <form name="list" action="?z={z}&id={domain_ID}&aktif_sayfa={aktif_sayfa}&cat={category}&domname={domName}" method="POST" enctype="multipart/form-data">

        <div class="table">
            <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
            <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />

                <table class="listing" cellpadding="0" cellspacing="0">
                    <tr>
                        <th class="first" width="177"></th>
                        <th ></th>

                    </tr>
                    <tr class="">
                        <td>Domain Name</td>
                        <td>{domain_name}</td>
                    </tr>
                    <tr class="bg">
                        <td>Kategori</td>
                        <td>
                            <select name="dom_cat[]" id="cat" multiple="multiple" style="width: 100%;height: 350px" >
                                <option value="0">Seçiniz</option>
                                <!-- BEGIN: sel_rows -->
                                <option value="{cat_ID}" {selected}>{cat_name}</option>
                                <!-- END: sel_rows -->
                            </select>
                        </td>
                    </tr>
                    <tr class="">
                        <td>Alan adı kullanım alanları</td>
                        <td>
                            <textarea name="info" cols="60" rows="8">{domain_info}</textarea>
                        </td>
                    </tr>
                    <tr class="bg">
                        <td colspan="2" align="right"> <input type="submit" name="send" value="Gönder" /></td>
                    </tr>
                </table>
        </div>
    </form>
    <!-- END: cat_duzelt -->
    <!-- BEGIN: table -->
    <script type="text/javascript" src="../js/jquery.numeric.pack.js"></script>
    <script type="text/javascript">
        function goURL(id) {
            var domName=$('#domName').val();
            window.location='domain.php?z=list&domName='+domName+'&aktif_sayfa='+id;
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
				pricet_min =$('#ID_min_'+ID).val();
				tipt =$('#ID_type_'+ID).val();
                if(confirm("Fiyatı güncellemek istediğinize eminmisiniz?")){
                    $.ajax({
                        type: "POST",
                        url: "add_basket.php",
                        data: { domainID: ID, price:pricet, price_min:pricet_min, tip:tipt, action: "fiyatUpdate"},
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
        <a href="domain.php?z=list&aktif_sayfa={geri_sayfa}">GERİ </a>
        |
        <a href="domain.php?z=list&aktif_sayfa={ileri_sayfa}">İLERİ </a>
    </div>
    <div class="select2" style="text-align: right;background:#9097A9;margin-bottom: 3px;float: right;">
        <a href="#" onclick="return false;">TOPLAM: {toplamKayit} KAYIT | </a>
    </div>
    <div class="table">

        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <table class="listing" cellpadding="0" cellspacing="0">
            <tr >
                <th class="first" width="155">Domain Name</th>
                <th width="15">Puan</th>
                <th width="250">Fiyat</th>
                <th class="last">
                    <div style="float:left">Kategori</div>
                    <div style="float:right">
                        <strong>Diğer Sayfalar: </strong>
                        <select name="aktif_sayfa" onchange="goURL(this.value)">
                            <!-- BEGIN: t_num -->
                            <option value="{num_value}" {selected_num}> {num}</option>
                            <!-- END: t_num -->
                        </select>
                    </div>
                </th>
				<th width="40">Güncelle</th>
            </tr>
            <!-- BEGIN: rows -->
            <tr class="{bg}">
                <td class="first style1">{domain_name}</td>
                <td>{domain_puan}</td>
                <td class="priceChange">
					Minimum Teklif:<input type="text" name="min" value="{domain_min_price}" id="ID_min_{domain_ID}" style="width: 35px" maxlength="6"/>
                    |Satış Fiyatı:<input type="text" name="puan" value="{domain_price}" id="ID_{domain_ID}" style="width: 45px" maxlength="6"/>
					|Tip:<select name="tip" id="ID_type_{domain_ID}">
						<option value="0" {selected0}>Satış</option>
						<option value="1" {selected1}>Teklif</option>
						</select>
                </td>
                <td class="last">
                    <!--<a href="?z=cat&id={domain_ID}&aktif_sayfa={aktif_sayfa}"><img src="./img/edit-icon.gif" alt="" /></a>-->
                    <div class="productPriceWrap" style="cursor:pointer;"><a href="?z=cat&id={domain_ID}&aktif_sayfa={aktif_sayfa}&cat={category}&domname={domName}">{domain_cat}</a></div>
                </td>
				<td><input type="button" name="Update" value="UPDATE" id="{domain_ID}"/></td>
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
            <a href="domain.php?z=list&aktif_sayfa={geri_sayfa}">GERİ </a>
            |
            <a href="domain.php?z=list&aktif_sayfa={ileri_sayfa}">İLERİ </a>
        </div>
    </div>
    <!-- END: table -->
</div>
<!-- END: main -->