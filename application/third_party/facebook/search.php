<?php
session_start();

define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '/src/Facebook/');

require_once __DIR__ . '/src/Facebook/autoload.php'; 

$fb = new Facebook\Facebook([
  'app_id' => '1584457208480726',
  'app_secret' => 'b20f4706b9ed09c18ca28d35bbf1bf64',
  'default_graph_version' => 'v2.5',
]);

$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);

try {
  $response = $fb->get('/me');
  $userNode = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
//$userNode->getID()
echo 'Logged in as '. $userNode->getName().'<br/><br/>';


$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
$response = $fb->get('/search?q=world&type=event'); 

echo '<pre>';  var_dump($response); echo '</pre>';

