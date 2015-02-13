<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Admin</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <style media="all" type="text/css">@import "css/all.css";</style>
        <script type="text/javascript" src="../js/jquery-1.4.min.js"></script>
        <style type="text/css">
            body {
                font-size: 12px;
            }
            #centerr {
                width: 500px;
                border:1px solid #477FAE;
                margin:0 auto;
                background: url('../images/logo.png') no-repeat 5% 95% #ffffff;
                margin: 40px auto;
            }
            h1{
                margin:3px;
                padding:10px 0;
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                background: #333333;
                color:white;
                border:1px solid #BFBFBF;
                border-top:5px solid black;
                font-family: sans-serif;
            }
            form {
                clear: both;
                margin:20px 3px 3px 3px;
            }
            label {
                width: 120px;
                float: left;
                text-align: right;
                padding-right: 35px
            }
            form div {float:left;width: 100%;clear: both;margin: 10px 0;}
            form div input.txt {width: 100%;}
        </style>
    </head>
    <body>
        <div id="centerr">
            <h1>YÖNETİM PANELİ</h1>
            <form name="login" action="login.php" method="post" enctype="multipart/form-data">
                <div>
                    <label>Kullanıcı Adı</label><input type="text" name="kad" value="" class="text" />
                </div>
                <div>
                    <label>Şifre</label><input type="password" name="pass" value="" class="text"/>
                </div>
                <div style="text-align: right">
                    <input type="hidden" value="Send" name="send" />
                    <input type="image" src="../images/girisBtna.gif"  name="log"  />
                </div>
            </form>
            <div style="clear: both"></div>
        </div>
    </body>
</html>
<!-- END: main -->