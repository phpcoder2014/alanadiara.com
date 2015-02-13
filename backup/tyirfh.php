<?php
header ("Content-Type:text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>
      <loc>http://alanadiara.com/</loc>
      <lastmod><?php echo date("Y") . "-" . date("m") . "-" . date("d");?></lastmod>
      <changefreq>daily</changefreq>
      <priority>0.8</priority>
   </url>
   <url>
      <loc>http://alanadiara.com/kategoriler.htm</loc>
      <lastmod><?php echo date("Y") . "-" . date("m") . "-" . date("d");?></lastmod>
      <changefreq>daily</changefreq>
      <priority>0.8</priority>
   </url>
<?php
for($i = 1; $i <= 33; $i++)
	{
	?>
	<url>
      <loc>http://alanadiara.com/kategoriler.htm?categori=<?php echo $i; ?></loc>
      <lastmod><?php echo date("Y") . "-" . date("m") . "-" . date("d");?></lastmod>
      <changefreq>daily</changefreq>
      <priority>0.8</priority>
	</url>
	<?php
	}
	
include_once('inc/dbMysql.php');

$db = new dbMysql();

$query = mysql_query("SELECT name FROM domain");
while($domain = mysql_fetch_assoc($query))
	{
	?>
	<url>
      <loc>http://alanadiara.com/<?php echo $domain["name"]; ?>.htm</loc>
      <lastmod><?php echo date("Y") . "-" . date("m") . "-" . date("d");?></lastmod>
      <changefreq>daily</changefreq>
      <priority>0.8</priority>
	</url>
	<?php
	}
?>
</urlset>