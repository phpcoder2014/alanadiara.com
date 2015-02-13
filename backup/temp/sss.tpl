<!-- BEGIN: main -->
<script type="text/javascript" src="js/popup.js"></script>
<script type="text/javascript" >
    $(document).ready(function(){
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
    });
</script>
<div id="sss_Content">
    <div class="baslik">SIKÇA SORULAN SORULAR</div>
    <div class="sssaciklamasi">
        <ul type="disc">
            <li><strong>{siteDomName} kimdir?<br /></strong>
                {siteDomName}, teknoloji tabanlı İnternet hizmetleri şirketi olan Statosfer'in bir parçasıdır.</li>
            <li><strong>Neden {siteDomName}'u tercih etmeliyim?<br /></strong>
                {siteDomName}, Premium ve Popüler alan adları ile çok farklı kategorilerde sizlere birçok seçenek sunmaktadır. Üstelik çok cazip fiyatlar ile .info uzantılı alan adına sahip olabilirsiniz.</li>
            <li><strong>Neden {siteDomName} güvenli? </strong><br />
                {siteDomName} Stratosfer E-Ticaret ve Yazilim Hizmetleri güvenilirliğini arkasına alarak satışlarına başlamıştır. Satış ve satış sonrası destek departmanları ile müşteri memnuniyeti ön planla tutulmuştur.</li>
            <li><strong>Premium alan adı nedir?</strong><br/>
            Popüler alan adları site ziyaretçileri tarafından en çok aranan ve tıklanan alan adlarından oluşmaktadır. Satın alacağınız .info uzantılı alan adını Popüler alan adı listesinden seçebilir en çok hit alan alan adlarına tek tık ile ulaşabilirsiniz.
            </li>
            <li><strong>Popüler alan adı nedir?</strong> <br/>
            Premium alan adları, alan adı pazarında bazı karakteristik özelliklere sahip, markalaşabilir isimler için kullanılır. Genellikle kısa, kolay hatırlanabilir ve birden fazla anlama sahip ya da jenerik isimler bu kategoride yer alır. Arama motorlarında en çok aranan kelimeler ve en yüksek sıralamaya sahip anahtar kelimeler de Premium özelliği taşır. Sizde isterseniz Premium alan adı listemizden arama yapabilir size uygun .info uzantılı alan adı tescil edebilirsiniz. 
            </li>
            <li><strong>{siteDomName}'a nasıl üye olunur?<br /></strong>
                {siteDomName}’da üyelik oluşturmak için gerekli olan tek şey aktif bir mail adresi. Üyelikle ilgili tüm sorularınız için
                <a href="mailto:{siteDomEmail}?subject=bilgi talebi">tıklayınız…</a>
            </li>
            <li><strong>{siteDomName}'dan alan adı nasıl satın alınır?<br /></strong>
                Üye girişi yaptıktan sonra ister kategorilerde ilgilendiğiniz alan adını arayın, isterseniz sizin için oluşturduğumuz Premium ve Populer alan adları listesinden seçiminizi yapın. Sepete ekleyin ve ödeme seçeneği seçip işlemi tamamlayın.
            </li>
            
        </ul>
        <ul type="disc">
            <li><strong>Nasıl ödeme yapabilirsiniz?<br />
                </strong>4 çeşit ödeme seçeneğimiz mevcuttur. Banka havalesi, kredi kartı, kredi kartı ( 3D ), mail order. Bu seçeneklerimizden sizin için uygun olanı seçip ödeme işleminizi tamamlayabilirsiniz.</li>
            <li><strong>Google AdWords kampanyası ne fayda sağlar? </strong><br />
                <font color="red">Kampanyamız 4 Şubat 2011 tarihinde son bulmuştur.</font><br />
                <!--Alan adı alım işlemlerinde tüm üyelerimize 100 TL değerinde
                Google AdWords deneme kuponu hediye ediyoruz.
                Google AdWords; anahtar kelimelere bağlı aramalarla
                Google ana sayfasında sağ tarafta Sponsor bağlantılar
                kısmında görülmesini sağlar.
                Google AdWords ile ilgili detaylı bilgi almak için <u>
                    <a href="googleadwords.php" target="_blank">tıklayınız.</a></u> --></li>
        </ul>
        <p>&nbsp;</p>
    </div>
</div>
<!-- END: main -->