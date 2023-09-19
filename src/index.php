<?php

$request = explode('/', mb_strtolower($_SERVER['REQUEST_URI']));
$method = $_SERVER['REQUEST_METHOD'];

$redis = new Redis();
$redis->connect('redis');

switch(true){

  case $request[1] == "all":
    $all = [];
    $keys = $redis->keys('*');

    foreach($keys as $key){
      array_push($all, [
        'key' => (string)$key,
        'store_key' => (string)$key,
        'value' => (string)$redis->get($key)
      ]);
    }

    header('Content-Type: application/json');
    echo json_encode($all, JSON_UNESCAPED_UNICODE);
    break;
  

  case $request[1] == "get" && $request[2] != "":
    $value = $redis->get($request[2]);

    if (!$value){
      http_response_code(404);
    }

    header('Content-Type: application/json');
    echo json_encode([
      'key' => $request[2],
      'value' => $value
    ], JSON_UNESCAPED_UNICODE);
    break;


  case $request[1] == "delete" && $request[2] != "":
    $key = (string)urldecode($request[2]);
    $del = $redis->del($key);
    header('Content-Type: application/json');
    echo json_encode(['result' => $del], JSON_UNESCAPED_UNICODE);
    break;


  case $request[1] == "set" && $method == "POST":
    $data = json_decode(file_get_contents('php://input'));
    $key = (string)urldecode(trim($data->key));
    $store_key = (string)urldecode(trim($data->store_key));
    $value = (string)urldecode(trim($data->value));

    if ($store_key != ""){
      $redis->del($store_key);
    }

    $set = $redis->set($key, $value);

    header('Content-Type: application/json');
    echo json_encode(['result' => $set], JSON_UNESCAPED_UNICODE);
    break;


  default: 
    extract([
      'redis_verion' => $redis->info()['redis_version'],
      'php_verion' => phpversion()
    ]);
    ob_start();
    include 'view.php';
    echo ob_get_clean();
}