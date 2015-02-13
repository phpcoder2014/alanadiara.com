<!-- BEGIN: main -->
<div id="center-column">
   <!-- BEGIN: top_nav -->
   <div class="top-bar">
      <a href="#" class="button">YENİ EKLE </a>
      <h1>DOMAIN</h1>
   </div><br />
   <form name="ara" action="{request_URI}" method="get">
   <div class="select-bar">
      <label>
         <input type="text" name="name" />
      </label>
      <label>
         <input type="submit" name="Submit" value="Search" />
      </label>
   </div>
    </form>
   <!-- END: top_nav -->
   <!-- BEGIN: cat_duzelt -->
   <form name="list" action="?z={z}&id={domain_ID}&aktif_sayfa={aktif_sayfa}" method="POST" enctype="multipart/form-data">

   <div class="table">
      <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
      <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
      <form name="cat_duzelt" method="POST" enctype="multipart/form-data" action="">

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
         <tr class="bg">
            <td colspan="2" align="right"> <input type="submit" name="send" value="Gönder" /></td>
         </tr>
      </table>
      </form>
   </div>
   </form>
   <!-- END: cat_duzelt -->
   <!-- BEGIN: table -->
   <script type="text/javascript">
       $(document).ready(function(){
           //alert("erd");
           //$("select").change(displayVals);
       });
       function goURL(id) {
           window.location='domain2.php?z=list&aktif_sayfa='+id;
           //alert(id)
       }

       $(document).ready(function(){
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
                           alert("Başarısız");
                       }

                   }
               });
               }
           })
       });
   </script>
   <div class="select2" style="text-align: right;background:#9097A9;margin-bottom: 3px;float: right;width: 100px">
          <a href="domain2.php?z=list&aktif_sayfa={geri_sayfa}">GERİ </a>
          |
          <a href="domain2.php?z=list&aktif_sayfa={ileri_sayfa}">İLERİ </a>
      </div>
   <div class="table">
       
      <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
      <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
      <table class="listing" cellpadding="0" cellspacing="0">
          <tr >
              <th class="first" width="200">Domain Name</th>
              <th width="40">Puan</th>
              <th width="40">Fiyat</th>
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
          </tr>
         <!-- BEGIN: rows -->
         <tr class="{bg}">
            <td class="first style1">{domain_name}.info</td>
            <td>{domain_puan}</td>
            <td>{domain_price}</td>
            <td class="last">
               <a href="?z=cat&id={domain_ID}&aktif_sayfa={aktif_sayfa}"><img src="./img/edit-icon.gif" alt="" /></a>
               <div class="productPriceWrap">{domain_cat}</div>
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
          <a href="domain2.php?z=list&aktif_sayfa={geri_sayfa}">GERİ </a>
          |
          <a href="domain2.php?z=list&aktif_sayfa={ileri_sayfa}">İLERİ </a>
      </div>
   </div>
   <!-- END: table -->
   <!-- BEGIN: table_last -->
   <div class="table">
      <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
      <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
      <table class="listing form" cellpadding="0" cellspacing="0">
         <tr>
            <th class="full" colspan="2">Header Here</th>
         </tr>
         <tr>
            <td class="first" width="172"><strong>Lorem Ipsum</strong></td>
            <td class="last"><input type="text" class="text" /></td>
         </tr>
         <tr class="bg">
            <td class="first"><strong>Lorem Ipsum</strong></td>
            <td class="last"><input type="text" class="text" /></td>
         </tr>
         <tr>
            <td class="first""><strong>Lorem Ipsum</strong></td>
            <td class="last"><input type="text" class="text" /></td>
         </tr>
         <tr class="bg">
            <td class="first"><strong>Lorem Ipsum</strong></td>
            <td class="last"><input type="text" class="text" /></td>
         </tr>
      </table>
      <p>&nbsp;</p>
   </div>
   <!-- END: table_last -->
</div>
<!-- END: main -->