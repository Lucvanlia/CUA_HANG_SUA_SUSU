<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Phần còn lại của file config.php


require_once __DIR__ . '/vendor/autoload.php';
require 'vendor/autoload.php'; // Đảm bảo đường dẫn đúng

$fb = new \Facebook\Facebook([
  'app_id' => '511847038124775', // Thay bằng App ID của bạn
  'app_secret' => '96c7bb09dfeb0101a41193ae2d7467e4', // Thay bằng App Secret của bạn
  'default_graph_version' => 'v16.0',
]);

$helper = $fb->getRedirectLoginHelper();
?>