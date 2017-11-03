<?php
  class UploadClass {
    protected $db;
    protected $media;
    protected $user_id;

    public function __construct($media, $user_id, $selection){
      $this->db = (new Connection)->getConnection();
      $this->media = $media;
      $this->user_id = $user_id;

      if ($selection == 0) {
        $this->getNormalPhoto($this->media);
      }else{
        $this->getFacePhoto($this->media);
      }
    }

    protected function dateTime()
    {
        return date('d.m.Y H:i:s');
    }

    protected function createFileName($getType){
      $currenttime = sha1($this->user_id.'+'.$this->dateTime());

      while (file_exists('/media_service/uploads/norm/'.$currenttime.'.'.$getType) || file_exists('/media_service/uploads/faces/'.$currenttime.'.'.$getType))
      {
          $currenttime = sha1($this->user_id.'+'.$this->dateTime());
      }

      return substr($currenttime, 0, 30).'.'.$getType;
    }

    protected function updatePhotoDatabase($data)
		{
      $query = $this->db->prepare("INSERT INTO media (user_id,media_url,type,tarih) VALUES(?,?,?,?)");
      $insert = $query->execute(array($this->user_id, $data, 0, $this->dateTime()));
      if ($insert) {
        return true;
      }else{
        return false;
      }
		}

		protected function updateFaceDatabase($data)
		{
      $query = $this->db->prepare("INSERT INTO media (user_id,media_url,type,tarih) VALUES(?,?,?,?)");
      $insert = $query->execute(array($this->user_id, $data, 1, $this->dateTime()));
      if ($insert) {
          return true;
      }else{
          return false;
      }
		}

    public function getNormalPhoto($data)
		{
			if ($data != null) {
			    if ($data['error'] != 0){
            echo json_encode("There is an error while uploading photo.");
            http_response_code(417);
            exit();
          }

          $data_type = explode('.',$data['name']);

          if ($data_type[1] != "jpg"){
            echo json_encode("The file extension can be a jpeg file. Please check your image file.");
            http_response_code(415);
            exit();
          }

          if (copy($data['tmp_name'], "media_service/uploads/norm/". $this->createFileName($data_type[1])) && $this->updatePhotoDatabase($this->createFileName($data_type[1])))
          {
            echo json_encode("COMPLETE");
            http_response_code(200);
          }else{
            echo json_encode("ERR");
            http_response_code(400);
          }
			}else{
        echo json_encode("ERR");
        http_response_code(400);
      }
		}

		public function getFacePhoto($data)
    {
        if ($data != null) {
          if ($data['error'] != 0){
            echo json_encode("There is an error while uploading photo.");
            http_response_code(417);
            exit();
          }

          $data_type = explode('.',$data['name']);

          if ($data_type[1] != "jpg"){
            echo json_encode("The file extension can be a jpeg file. Please check your image file.");
            http_response_code(415);
            exit();
          }

          if (copy($data['tmp_name'], "media_service/uploads/faces/". $this->createFileName($data_type[1])) && $this->updateFaceDatabase($this->createFileName($data_type[1])))
          {
            echo json_encode("COMPLETE");
            http_response_code(200);
          }else{
            echo json_encode("ERR");
            http_response_code(400);
          }
        }else{
          echo json_encode("ERR");
          http_response_code(400);
        }
    }
  }
?>
