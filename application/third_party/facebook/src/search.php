<?php
session_start();

define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '/src/Facebook/');

require_once __DIR__ . '/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1376585479230595',
  'app_secret' => '2fb6b5fa6cbe30bed1e22146c337d5b8',
  'default_graph_version' => 'v2.5',
]);

$request = new FacebookRequest(
  $session,
  'GET',
  '/search',
  array (
    'type' => 'topic',
    'q' => 'clinton',
    'fields' => 'id,name,page',
  )
);
$response = $request->execute();
$graphObject = $response->getGraphObject(); echo '<pre>'; var_dump($graphObject); echo '</pre>';