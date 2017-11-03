<?php
require_once '/../servicelist.php';
  class monitorEndPoint {
    protected $params;
    protected $media;
    protected $servInstance;

    public function __construct($params){
      $this->params = $params;
      $this->servInstance = new ServiceList();
    }

    public function Run(){
      $paramList = explode('/', $this->params);
      if ($paramList[0] == "getstat") {
        $this->GetStatus();
      }
    }

    private function GetStatus(){
      $serviceStatus = array();
      foreach ($this->servInstance->getList() as $serviceName => $serviceDir) {
        require_once "/../" . $serviceDir . "/status.php";
        $instance = $serviceName . "Status";
        $createInstance = new $instance();
        $serviceStatus[$serviceName] = $createInstance->getStat();
      }

      $this->printStatus($serviceStatus);
    }

    protected function printStatus($statList){
      foreach ($statList as $key => $value) {
        echo json_encode($key . " SERVICE STAT: " . $value);
      }
    }
  }
?>
