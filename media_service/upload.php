<?php
  class UploadClass {
    protected $db;
    protected $media;
    protected $user_id;
    public $output;

    public function __construct($media, $user_id, $selection){
      $this->db = (new Connection)->getConnection();
      $this->media = $media;
      $this->user_id = $user_id;

      if ($selection == 0) {
        $output = $this->getNormalPhoto($this->media);
      }else{
        $output = $this->getFacePhoto($this->media);
      }
    }

    protected function dateTime()
    {
        return date('d.m.Y H:i:s');
    }

    protected function createFileName($getType){
      $currenttime = sha1($this->user_id.'+'.$this->dateTime());

      while (file_exists('/uploads/norm/'.$currenttime.'.'.$getType) || file_exists('/uploads/faces/'.$currenttime.'.'.$getType))
      {
          $currenttime = sha1($this->user_id.'+'.$this->dateTime());
      }

      return substr($currenttime, 0, 30).'.'.$getType;
    }

    protected function updatePhotoDatabase($data)
		{
            $query = $this->db->prepare("INSERT INTO media (tid,fotourl,tarih) VALUES(?,?,?)");
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
      print_r($data);
			if ($data != null) {
			    if ($data['error'] != 0)
			        echo "Error, Upload Problem";

                $data_type = explode('.',$data['name']);

                if ($data_type[1] != "jpg")
                    echo "Error, the file can be a jpeg or png file.";
                echo $data['tmp_name'];
                if (move_uploaded_file($data['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/uploads/norm/". $this->createFileName($data_type[1])) && $this->updatePhotoDatabase($this->createFileName($data_type[1])))
                {
                    return true;
                }else{
                    return false;
                }
			}
		}

		public function getFacePhoto($data)
    {
        if ($data != null) {
            if ($data['error'] != 0)
                echo "Error, Upload Problem";

            $data_type = explode('.',$data['name']);

            if ($data_type[1] != "jpg")
                echo "Error, the file can be a jpeg or png file.";

            if (copy($data['tmp_name'], "uploads/faces/". $this->createFileName($data_type[1])) && $this->updateFaceDatabase($this->createFileName($data_type[1])))
            {
                return true;
            }else{
                return false;
            }
        }
    }
  }
?>
