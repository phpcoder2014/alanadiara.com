<?
include("virtualpos.inc");
include_once("../func.db.php");
include_once("../configuration.inc");

function ExecutePayment ($ccardno,$exp_m,$exp_y,$cvv2,$total) { //returns true or false
	
	$date_full=date("YmdHis");
	$date_hour=date("H");
	$date_julian=str_repeat("0",(4-strlen(date("z")))).date("z");
	
	if (strlen($exp_y)!=2) $exp_y=substr($exp_y,-2);	
	if (strlen($exp_m)!=2) $exp_m=str_repeat("0",(2-strlen($exp_m))).$exp_m;
	
	$total=str_repeat("0",(18-strlen($total))).$total;
	
	$batch=GenerateBatchNo();
	$batch_nm=str_repeat("0",(4-strlen($batch['batch']))).$batch['batch'];
	$proccess_nm=str_repeat("0",(6-strlen($batch['proccess']))).$batch['proccess'];
	
	$sum=$date_full.$batch_nm.$proccess_nm.VP_TEB_SHOPID;
	$array=str_split($sum);
	$sum=array_sum($array);
	$checksum=str_repeat("0",(10-strlen($sum))).$sum;
	
	$post_data="PMesaj=".VP_TEB_SELLCODE.VP_TEB_SHOPID.VP_TEB_TERMINALID.$ccardno."   ".$exp_y.$exp_m.$cvv2.$total.$date_full.$batch_nm.$proccess_nm.$date_julian.$date_hour.$proccess_nm.$checksum;
	
	//echo "<br>len=".strlen(VP_TEB_SELLCODE.VP_TEB_SHOPID.VP_TEB_TERMINALID.$ccardno."   ".$exp_y.$exp_m.$cvv2.$total.$date_full.$batch_nm.$proccess_nm.$date_julian.$date_hour.$proccess_nm.$checksum);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_URL, VP_TEB_URL);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close ($ch);
	//return $data;
	
	$s=str_split($data);
	if ($s[0]==0 && $s[0]==0 && $s[0]==0) {
		$return=$s[66].$s[67].$s[68].$s[69].$s[70].$s[71].$s[72];
		if ($return!=0) {
			GenerateBatchNo("put",$return);
			return true;
		}
	}
	else return false;
}

function GenerateBatchNo ($action="get",$provision_code="") { //returns nothing, don't be used without function ExecutePayment
	openDB();
	if ($action=="get") {
		$sql="SELECT * FROM ".TABLE_PREFIX."vpos_teb ORDER BY id DESC LIMIT 1";
		$cmd=mysql_query($sql);
		$num=mysql_num_rows($cmd);
		if ($num>0) {
		$row=mysql_fetch_array($cmd);
		return array('batch'=>$row['batch_no'],'proccess'=>$row['proccess_no']);
		}
		else return array('batch'=>1,'proccess'=>1);
	}
	elseif ($action=="put") {
		$sql="SELECT * FROM ".TABLE_PREFIX."vpos_teb ORDER BY id DESC LIMIT 1";
		$cmd=mysql_query($sql);
		$num=mysql_num_rows($cmd);
		if ($num>0) {
		$row=mysql_fetch_array($cmd);
		if ($row['proccess_no']==999999) {
			$new_batch_no=$row['batch_no']+1;
			$new_proccess_no=1;
		}
		else {
			$new_batch_no=$row['batch_no'];
			$new_proccess_no=$row['proccess_no']+1;
		}
		//return array('batch'=>$new_batch_no,'proccess'=>$new_proccess_no);
		}
		else { $new_batch_no=1; $new_proccess_no=1; }
		$sql="INSERT INTO ".TABLE_PREFIX."vpos_teb VALUES ('','$new_batch_no','$new_proccess_no','$provision_code','".time()."');";
		$cmd=mysql_query($sql);
	}
	//return array('batch'=>1,'proccess'=>15);
}



//echo ExecutePayment("4402730004091474","8","08","085",85.96);


?>