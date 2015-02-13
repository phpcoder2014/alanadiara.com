<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{domname} alanadiara.com En Fazla Aranan Alan adları</title>
        <meta name="Description" content="{description}" />
        <meta name="keywords" content="{keywords}" />
		<link href="js/style.css" rel="stylesheet" type="text/css" />
		<link href="js/Pager.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-1.4.min.js"></script>
        <script type="text/javascript" src="js/web.js"></script>
        <script type="text/javascript" src="js/swfobject.js"></script>
        <script type="text/javascript" src="js/jquery.pager.js"></script>
		<!--[if IE 7]><link href="js/ie7patch.css" rel="stylesheet" type="text/css" /><![endif]-->
		<script type="text/javascript">
			function add_basket(domainIDVal) {
				if(domainIDVal != '' && domainIDVal > 0) {
					$.ajax({
						type: "POST",
			            url: "add_basket.php",
			            data: { domainID: domainIDVal, action: "addToBasket"},
			            success: function(data) {
							$('#basketWrap').html("("+data+")");
							window.location='basket.php';
						}
					});
				}
			}
			$(document).ready(function(){
		      $(".tableYazi a span").click(function() {
		            var dom_IDVal = (this.id).split("_");
		            var dom_ID = dom_IDVal[1];
		            $.ajax({
		            type: "POST",
		            url: "add_basket.php",
		            data: { domainID: dom_ID, action: "domainHit"},
		            success: function(data) {
		               //alert(data);
		            }
		         });
		      });
		   });
		</script>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-28090931-1']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
    </head>
<body>
	<div id="main">
		{HEADER}
		{domain_search}
		{MAIN}
	</div>
	<!-- div style="width:728px;height:90px;margin:0 auto;">
		{literal}<script type="text/javascript" src="http://app.pubserver.adhood.com/6843,728,90"></script>
	</div -->
{FOOTER}
</body>
</html>
<!-- END: main -->