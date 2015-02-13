$(document).ready(function(){
    $(".productPriceWrapRight a img").click(function() {
        var productIDValSplitter = (this.id).split("_");
        var domainIDVal = productIDValSplitter[1];
        $.ajax({
            type: "POST",
            url: "add_basket.php",
            data: {
                domainID: domainIDVal,
                action: "addToBasket"
            },
            success: function(data) {
                $('#basketWrap').html("("+data+")");
                window.location='basket.php';
            }
        });
    });
    $(".favori a img").click(function() {
        if(usersession==0){
            alert("Lütfen Önce Oturum açın.");
            return;
        }
        var productIDValSplitter = (this.id).split("_");
        var domainIDVal = productIDValSplitter[1];
        //alert(domainIDVal);
        $.ajax({
            type: "POST",
            url: "add_basket.php",
            data: {
                domainID: domainIDVal,
                action: "add_fovori"
            },
            success: function(data) {
                $('#fovariteWrap').html("("+data+")");
                window.location='favori.php';
            }
        });
    });
    $('a.aramaKaydet').click(function(){
        if(usersession==0){
            alert("Lütfen Önce Oturum açın.");
            return;
        }
        var erd="{arama_kaydet}";
        var name="{searchvalue}";
        $.ajax({
            type: "POST",
            url: "add_basket.php",
            data: {
                domainID: erd,
                name: name,
                action: "addAramaKaydet"
            },
            success: function(data) {
                $('div#aramaKaydetDiv').hide();
            }
        });
    });
    $(".saveSearch a img").click(function() {
        var SerachIDval = (this.id).split("_");
        var SerachID = SerachIDval[1];
        $.ajax({
            type: "POST",
            url: "add_basket.php",
            data: {
                domainID: SerachID,
                action: "delAramaKaydet"
            },
            success: function(data) {
                //$('div#aramaKaydetDiv').hide();
                location.reload();
            }
        });
    });
//saveSearch
});