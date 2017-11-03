<?php
require_once "dbconnection.php";

  class mediaEndPoint {
    protected $params;
    protected $media;

    public function __construct($params, $media){
      $this->params = $params;
      $this->media = $media;
    }

    public function Run(){
      $paramDecode = explode('/',$this->params);
      if ($paramDecode[0] == "upload") {
        require_once "upload.php";
        $up = new UploadClass($this->media, $paramDecode[1], $paramDecode[2]);
      }
    }
  }
?>
