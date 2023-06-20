<?php

$host = $_POST['loglib_host'];
$id = $_POST['loglib_id'];

// Create a data array
$data = array(
  'host' => $host,
  'id' => $id
);

// Convert data array to JSON
$jsonData = json_encode($data);

// Write JSON to file
$file = 'data.json';
file_put_contents($file, $jsonData);
// wp_redirect(home_url());
header('Location: ' . $_SERVER['HTTP_REFERER']);
// header("Location:". $rootUrl ."wp-admin/options-general.php?page=loglib-settings");
exit;
