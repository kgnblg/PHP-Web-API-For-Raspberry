<?php
  class monitorEndPoint {
    protected $params;
    protected $media;

    public function __construct($params){
      $this->params = $params;
    }

    public function Run(){
      $paramList = explode('/', $this->params);
      if ($paramList[0] == "getstat") {
        $this->GetStatics();
      }
    }

    private function GetStatics(){
      echo json_encode("USER_SERVICE: RUNNING MEDIA_SERVICE: RUNNING MONITOR_SERVICE: RUNNING");
    }
  }
?>
