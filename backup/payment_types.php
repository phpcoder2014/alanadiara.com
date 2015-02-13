<?php
session_start();
error_reporting(0);
include_once("includes/func.db.php");
include_once("includes/func.general.php");
include_once("includes/configuration.inc");
include_once("includes/variables.php");
header("Content-Type: text/html; charset=ISO-8859-9");
$userinfo=split("[|]+", $_SESSION['hst_usr_logged_in']);

if ($_POST['ptype']=="ccard") {
    ?>
<table cellspacing="0" cellpadding="2" class="normal">
    <tr>
        <td><label for="ccardname">Kart Üzerindeki İsim</label></td><td><input type="text" name="ccardname" id="ccardname" autocomplete="OFF" maxlength="32" size="32" class="normal" /></td>
    </tr>
    <tr>
        <td><label for="ccardtype">Tipi</label></td><td><select name="ccardtype" id="ccardtype" class="normal"><option value="visa">Visa</option><option value="mastercard">MasterCard</option></select></td>
    </tr>
    <tr>
        <td><label for="ccardno">Kart Numarası</label></td><td><input type="text" name="ccardno" id="ccardno" onpaste="return false;" ondrop="return false;" autocomplete="OFF" maxlength="16" size="17" class="normal" /></td>
    </tr>
    <tr>
        <td>Son Kullanma Tarihi</td><td><select name="ccexpmonth" id="ccexpmonth" class="normal"><?php for ($i=1;$i<=12;$i++) echo "<option value=\"$i\">".sprintf("%02d",$i)."</option>\n";?></select> <select name="ccexpyear" id="ccexpyear" class="normal"><?php for ($i=date("Y");$i<=(date("Y")+20);$i++) echo "<option value=\"$i\">".$i."</option>\n";?></select></td>
    </tr>
    <tr>
        <td><label for="cvv2">Güvenlik Numarası</label></td><td><input type="text" name="cvv2" id="cvv2" onpaste="return false;" ondrop="return false;" autocomplete="OFF" size="3" maxlength="3" class="normal" /></td>
    </tr>
    <tr>
        <td colspan="2" height="20"></td>
    </tr>
    <tr>
        <td align="right"><img src="images/visa_logo_50x16.gif" /></td><td><img src="images/mastercard_logo_50x29.gif" /></td>
    </tr>
</table>
<?php
}
else if ($_POST['ptype']=="mtransfer") {

echo "<div style=\"margin-left:30px;\">";
?>
<span class="normal">Gerekli tutarı 3 iş günü içerisinde aşağıdaki hesapların birine "OK.NET ULUSLARARASI ELEKTRONİK BİLGİLENDİRME HABERLEŞME VE YAZILIM HİZM. SAN. VE TİC.A.Ş. " adına havale etmeniz gerekmektedir.<br><br>
    <b>NOT: İşlemlerin daha hızlı tamamlanabilmesi için;<br>
    "Açıklama" bölümünde Ok.Net sipariş numaranızı belirtiniz.</b> <br>
    <br>
Ödeme yapabileceğiniz banka hesap numaralarımız;<br><br></span>

<?php
openDB();
$sql="SELECT * FROM ".TABLE_PREFIX."bank_accounts WHERE status='1' ORDER BY office_code ASC";
$cmd=mysql_query($sql);
$num=mysql_num_rows($cmd);
if ($num>0) {
    echo '<form name="mtrans_bank_form" id="mtrans_bank_form" action="#" method="POST">
    <table border="1" cellspacing="0" cellpadding="3" class="normal" bordercolor="#FFFFFF">
    <tr bgcolor="#FE4011"><th style="color:#FFFFFF;">Banka Adı</th><th style="color:#FFFFFF;">Hesap Türü</th><th style="color:#FFFFFF;">Şube Kodu</th><th style="color:#FFFFFF;">Hesap No.</th><th bgcolor="#FFFFFF">&nbsp;</th></tr>';
    while ($row=mysql_fetch_array($cmd)) {
        echo '<tr'; if ($i%2==0) echo ' bgcolor="#FAF9F8"'; echo '><td><b>'.$row['bank_name'].'</b></td><td align="center">'.$var_account_types[$row['account_type']].'</td><td align="center">'.$row['office_code'].'</td><td align="center">'.$row['account_code'].'</td><td><input type="radio" name="mtrans_bank" id="mtrans_bank-'.$row['id'].'" value="'.$row['id'].'"></td></tr>';
        $i++;
    }
    echo '</table></form>';
}
else echo 'Ödeme yababileceğiniz banka hesap numaraları şu an için uygun değil. Lütfen daha sonra tekrar deneyiniz.';
echo '</div>';
}
elseif ($_POST['ptype']=="mailorder") {
?>
<span class="normal">Aldığınız hizmetin ücretini aşağıda belirttiğimiz Mail Order Formu'nu doldurarak ödeyebilirsiniz. Formu doldurduktan sonra kimlik fotokopiniz ile birlikte tarafımıza faxlamanız (0224 224 95 20) işleminizin tamamlanması için yeterli olacaktır.
    Kredi Kartı ve kimlik bilgileriniz tarafımızda saklı kalacaktır.<br>
<div align="center"><table cellpadding="2" cellspacing="0"><tr><td><a href="dosya/OkNet_Mail_Order_Talimati.doc" style="text-decoration:none;" target="_blank"><img src="images/word.jpg" border="0" width="30" width="30" /></a></td><td class="normal"><a href="dosya/OkNet_Mail_Order_Talimati.doc" style="text-decoration:none;" class="normal" target="_blank"><b>Ok.Net Mail Order Talimatı</b></a></td></tr></table></div></span>
<?php
}
elseif ($_POST['ptype']=="hstcredit") {
echo '<div style="margin-left:30px;">';
openDB();
$sql="SELECT id, hst_credit FROM ".TABLE_PREFIX."users WHERE id='".$userinfo[0]."'";
$cmd=mysql_query($sql);
$num=mysql_num_rows($cmd);
if ($num==1) {
    $row=mysql_fetch_array($cmd);
    echo '<table border="0" cellpadding="5" cellspacing="0">
    <tr><td class="normal">Ödemenizi gerçekleştirmek için gereken Ok.Net Kredi : <span class="krm-link"><b>'.number_format(CalculateTotal(),2,'.',',').'</b></span></td></tr>
    <tr><td class="normal">Hesabınızda bulunan Ok.Net Kredi\'niz : <span class="krm-link"><b>'.number_format($row['hst_credit'],2,'.',',').'</b></span></td></tr>';
    if (CalculateTotal()<=$row['hst_credit']) echo '<tr><td class="normal"><label for="pay_via_credit">Ödemenizi Ok.Net Kredi\'nizi kullanarak gerçekleştirmek için tıklayınız</label> <input type="checkbox" name="pay_via_credit" id="pay_via_credit" value="true"></td></tr>';
    else echo '<tr><td class="normal">Ödemenizi Ok.Net Kredi\'nizi kullanarak gerçekleştirmek için yeterli krediniz bulunmamaktadır.</td></tr>';
    echo '</table>';
}
else echo "Hesabınıza ulaşılamadı";
echo '</div>';
}
?>
