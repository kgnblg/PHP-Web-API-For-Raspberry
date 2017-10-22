<?php
header('Content-Type: application/json');
define("DIR",__DIR__);
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", DIR . DS);

  $token = @$_GET['token'];
  $media = @$_FILES['photo'];
  $service = @$_GET['service'];
  $param = @$_GET['params'];

  if (empty($token) || $_SERVER['REQUEST_METHOD'] == "PUT" || $_SERVER['REQUEST_METHOD'] == "DELETE") {
    echo json_encode('Unauthorized Token or Undefined Request Method!');
    http_response_code(406);
    exit();
  }

  if (is_dir(ROOT . $service . "_service")) {
    require ROOT . $service . "_service/endpoint.php";
    $instance = $service . "EndPoint";

    if ($service == "media") {
      $instance = new $instance($param, $media);
    }else{
      $instance = new $instance($param);
    }

    $instance->Run();

  }else{
    echo json_encode('Undefined Service Name.');
    http_response_code(404);
  }

?>
