<?php

function GenelMailTaslak ($title,$content) {
	return '
<html>
<head>
<title>'.$title.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-9">
</head>

<body bgcolor="#272727">
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="http://www.alanadiara.com/mail/top.gif" width="709" height="110" border="0" usemap="#Map" /></td>
  </tr>
  <tr>
    <td height="202" align="center" valign="top" bgcolor="#ffffff"><a href="#"><img style="border:2px solid #272727" src="http://www.alanadiara.com/mail/mainImage.jpg" width="682" height="183" /></a></td>
  </tr>
  <tr>
    <td width="672" style="background:white; padding:17px; padding-top:0;">
    <p style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; line-height:20px;">
        '.$content.'
    </p>
    </td>
  </tr>
  <tr>
    <td><img src="http://www.alanadiara.com/mail/footerLine.gif" width="709" height="5" /></td>
  </tr>
  <tr>
    <td valign="top" style="background:#0e0e0e; padding:15px;"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="100" valign="top"><table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="20" valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:white; text-decoration:none;"><a href="http://www.alanadiara.com" target="_blank" style="color:white; text-decoration:none;">http://www.alanadiara.com</a></td>
  </tr>
  <tr>
    <td height="25" valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#9a9a9a;"><a href="#" target="_blank" style="color:#9a9a9a; text-decoration:none;">Ana Sayfa</a> &ndash; <a href="#" target="_blank" style="color:#9a9a9a; text-decoration:none;">Hakkımızda</a> &ndash; <a href="#" target="_blank" style="color:#9a9a9a; text-decoration:none;">SSS</a> &ndash; <a href="#" target="_blank" style="color:#9a9a9a; text-decoration:none;">İletişim</a></td>
  </tr>
        </table></td>
        <td align="right" valign="top"><a href="http://alanadiara.com" target="_blank"><img src="http://www.alanadiara.com/mail/footerLogo.gif" width="163" height="37" border="0" /></a></td>
      </tr>
    </table></td>
  </tr>
</table>

<map name="Map" id="Map">
  <area shape="rect" coords="11,30,245,86" href="http://www.alanadiara.com" target="_blank" />
  <area shape="rect" coords="492,49,689,83" href="http://www.alanadiara.com" target="_blank" />
</map>
</body>
</html>
';
}
?>