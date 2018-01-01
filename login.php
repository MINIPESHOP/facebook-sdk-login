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

$permissions = ['email', 'user_likes']; // optional

$loginUrl = $helper->getLoginUrl('http://45.76.146.70/login-callback.php', $permissions); //ที่อยู่ไฟล์ login-callback.php

echo '<a href="' . $loginUrl . '">Login !</a>';
?>
