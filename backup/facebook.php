<?php
	session_start();
	error_reporting(0);
	include_once('inc/dbMysql.php');
	include_once('inc/func.php');
	$app_id = "232893630107883";
	$app_secret = "92f6bc5b70b2e9d9a62a1db432ea6cda";
	$my_url = "http://www.alanadiara.com";
	$page_url = 'http://'.$_SERVER["SERVER_NAME"].'/facebook.php';
	$code = $_REQUEST["code"];

	if(empty($code)) {
		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=".$app_id."&redirect_uri=".urlencode($page_url)."&state=".$_SESSION['state']."&scope=email,user_photos,user_about_me,user_interests,user_likes,user_location,user_website,user_work_history";
		echo("<script> top.location.href='" . $dialog_url . "'</script>");
	}

	if($_REQUEST['state'] == $_SESSION['state']) {
		$token_url = "https://graph.facebook.com/oauth/access_token?"."client_id=".$app_id ."&redirect_uri=".urlencode($page_url)."&client_secret=".$app_secret."&code=".$code;
		$response = file_get_contents($token_url);
		$params = null;
		parse_str($response, $params);
		
		$graph_url = "https://graph.facebook.com/me?access_token=". $params['access_token'];
		$interest_url = "https://graph.facebook.com/me/interests?method=GET&metadata=true&format=json&access_token=". $params['access_token'];
		$likes_url = "https://graph.facebook.com/me/likes?method=GET&metadata=true&format=json&access_token=". $params['access_token']; 
		
		$user = json_decode(file_get_contents($graph_url));
		$db=new dbMysql();
		$num = $db->num_rows("select id from user where email='".$user->email."'");
		if(!$num > 0) {
			$location = '';
			$website = '';
			$quotes = '';
			$bio = '';
			$gender = '';
			$email = '';
			$name = '';
			$work = '';
			$likes = '';
			$interests = '';
			
			$get_interests = json_decode(file_get_contents($interest_url));

			if(isset($get_interests)) {
				if($get_interests) $interests = addslashes(serialize($get_interests));
			}
			
			$get_likes = json_decode(file_get_contents($likes_url));
			if(isset($get_interests)) {
				if($get_likes) $likes = addslashes(serialize($get_likes));
			}
			if(isset($user->work)) $work = addslashes(serialize($user->work));
			
			if(isset($user->name)) $name = $user->name;
			if(isset($user->email)) $email = $user->email;
			if(isset($user->gender)) $gender = $user->gender;
			if(isset($user->bio)) $bio = $user->bio;
			if(isset($user->quotes)) $quotes = $user->quotes;
			if(isset($user->website)) $website = $user->website;
			if(isset($user->location->name)) $location = $user->location->name;
			
			$code=createReqCode();
			$user_id = $db->insert("user", array("name"=>$user->name, "email"=>$user->email, "phone"=>'',"pass"=>'', "soru"=>'', "cevap"=>'', "sex"=>$user->gender, "status"=>1, "f_user_id"=>$user->id));
			$req_table = $db->insert("user_req", array("id"=>$code, "user_id"=>$user_id, "status"=>"1", "time"=>time()));			
			$f_user_id = $db->insert("f_user", array("id"=>$user->id, "user_id"=>$user_id, "name"=>$name, "email"=>$email, "gender"=>$gender, "about"=>$bio, "quotes"=>$quotes, "website"=>$website, "location"=>$location, "work"=>$work, "interests"=>$interests, "likes"=>$likes));
		}
		
		$db_user = $db->get_row("select id,email,name from user where email='".$user->email."'");

		$_SESSION['net_users']['id']=$db_user->id;
		$_SESSION['net_users']['name']=$db_user->name;
		$_SESSION['net_users']['email']=$db_user->email;
		$_SESSION['net_users']['facebook']="true";
		if($_SESSION['net_users']['url'] == "") {
			header('Location: account_management.php');
		} else {
			header('Location: '.$_SESSION['net_users']['url']);
		}
		
	} else {
		echo("Err: The state does not match. You may be a victim of CSRF.");
		header('Location: login.php');
	}
 ?>