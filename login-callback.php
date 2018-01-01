<?php
session_start();
ini_set('display_errors', 1);
require_once __DIR__ . '/facebook-sdk-v5/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRedirectLoginHelper;


$fb = new Facebook\Facebook([
  'app_id' => 'App ID ของท่าน',
  'app_secret' => 'Secret ID ของท่าน',
  'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (isset($accessToken)) {
  // Logged in!
  $_SESSION['facebook_access_token'] = (string) $accessToken;

  // Now you can redirect to another page and use the
  // access token from $_SESSION['facebook_access_token']

  $response = $fb->get('/me?fields=id,name,gender,email,link', $accessToken);

  $user = $response->getGraphUser();
  echo'<pre>';
  print_r($user);
  echo'</pre>';

  //echo 'ID: ' . $user['id'];
  //echo 'Name: ' . $user['name'];
  //echo 'Gener: ' . $user['gener'];
  //echo 'Email: ' . $user['email'];
  //echo 'Link: ' . $user['link'];

	//เชื่อมฐานข้อมูล
	$objConnect = mysql_connect("localhost","root","") or die(mysql_error());
	$objDB = mysql_select_db("loginface");
	mysql_query("SET NAMES UTF8");


	// Check Exists ID
	$strSQL = "SELECT * FROM tb_facebook WHERE FACEBOOK_ID = '".$user['id']."' ";
	$objQuery = mysql_query($strSQL);
	$objResult = mysql_fetch_array($objQuery);
	if($objResult)
	{
		$_SESSION["strFacebookID"] = $user['id'];
		header("location:page1.php");
		exit();
	}
	else
	{
		// Create New ID

			$strPicture = "https://graph.facebook.com/".$user['id']."/picture?type=large";

			$strSQL ="  INSERT INTO  tb_facebook (FACEBOOK_ID	,NAME,EMAIL,PICTURE,LINK,CREATE_DATE) 
				VALUES
				('".trim($user['id'])."',
				'".trim($user['name'])."',
				'".trim($user['email'])."',
				'".trim($strPicture)."',
				'".trim($user['link'])."',
				'".trim(date("Y-m-d H:i:s"))."')";
			$objQuery  = mysql_query($strSQL);

			$_SESSION["strFacebookID"] = $user['id'];
			header("location:page1.php");
			exit();
	}

	mysql_close();
//สิ้นสุดการเชื่อมต่อ

}

?>
