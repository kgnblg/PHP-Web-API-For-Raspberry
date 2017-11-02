<?php
require_once "dbconnection.php";
    class userEndPoint {
    protected $params;
    protected $db;

    public function __construct($params){
      $this->params = $params;
      $this->db = (new Connection)->getConnection();
    }

    public function Run(){
      $paramList = explode('/', $this->params);
      if ($paramList[0] == "add") {
        $this->addUser(@$_POST['userName'], @$_POST['userEmail'], @$_POST['userPass']);
      }elseif ($paramList[0] == "del") {
        $this->removeUser($paramList[1]);
      }elseif ($paramList[0] == "auth") {
        $this->authendicateUser(@$_POST['userName'], @$_POST['userPass']);
      }
    }

    public function addUser($username, $email, $pass){
      $query = $this->db->prepare("INSERT INTO user (name, mail, password) VALUES(?,?,?)");
      $insert = $query->execute(array($username, $email, base64_encode($pass)));
      if ($insert) {
        http_response_code(200);
        echo json_encode("OK");
      }else{
        http_response_code(400);
        echo json_encode("ERR");
      }
    }

    public function removeUser($userId){
      $query = $this->db->prepare("DELETE FROM user WHERE id=?");
      $delete = $query->execute(array($userId));
      if ($delete) {
        http_response_code(200);
        echo json_encode("OK");
      }else{
        http_response_code(400);
        echo json_encode("ERR");
      }
    }

    public function authendicateUser($userName, $userPass){
      $userPass = base64_encode($userPass);
      $query = $db->query("SELECT * FROM user WHERE name=$userName AND password=$userPass", PDO::FETCH_ASSOC);
      if ($query->rowCount() > 0) {
        http_response_code(200);
        echo json_encode("OK");
      }else{
        http_response_code(400);
        echo json_encode("ERR");
      }
    }
  }
?>
