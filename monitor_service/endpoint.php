<?php
  public class mediaEndPoint {
    protected $params;
    protected $media;

    public function __construct($params, $media){
      $this->params = $params;
      $this->media = $media;
    }
  }
?>
