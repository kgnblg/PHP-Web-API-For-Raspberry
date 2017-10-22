<?php
  $token = @$_GET['token'];
  $media = @$_FILES['photo'];
  $service = @$_GET['service'];
  $param = @$_GET['params'];

  if (empty($token)) {
    echo json_encode('Unauthorized Token!');
    exit();
  }


?>
