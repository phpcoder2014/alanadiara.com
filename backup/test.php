<?php 
exit;
mysql_connect("localhost", "alanadiara", "t6yH5gh6d4gsa");
mysql_select_db("alanadiara");

$domain_list = array();

$query = mysql_query("SELECT domain.id, domain.name, dom_cat.id_cat FROM domain, dom_cat WHERE dom_cat.id_dom = domain.id AND domain.enddate > " . time() . " AND domain.state = 0") or die(mysql_error());
if(mysql_num_rows($query) > 0)
	{
	while($data = mysql_fetch_array($query))
		{
		if(!isset($domain_list[$data["name"]]))
			$domain_list[$data["name"]] = array();
		$domain_list[$data["name"]][] = $data["id_cat"];
		}
	}
	
foreach($domain_list as $domain => $data)
	{
	echo $domain . ";" . implode(",", $data) . "<br>";
	}
?>