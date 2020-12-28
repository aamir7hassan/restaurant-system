<?php
session_start();

define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '/src/Facebook/');

echo 'http://'.$_SERVER['SERVER_NAME'];

require_once __DIR__ . '/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1584457208480726',
  'app_secret' => 'b20f4706b9ed09c18ca28d35bbf1bf64',
  'default_graph_version' => 'v2.5',
]);


$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes']; // optional
$loginUrl = $helper->getLoginUrl('http://'.$_SERVER['SERVER_NAME'].'/facebook/login-callback.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';