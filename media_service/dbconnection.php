<?php
  class Connection {
    public static function getConnection()
		{
			return new PDO("mysql:host=127.0.0.1;dbname=tez_mediaserv;charset=utf8", "root", "");
		}
  }
?>
