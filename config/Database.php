<?php

class Database {
        
//variable declaration
private $hostname;
private $dbname;
private $username;
private $password;
private $conn;

//connection function
public function connect(){
    //varialbe initialzation
    $this->hostname = "localhost";
    $this->dbname = "cashclue";
    $this->username = "root";
    $this->password = "";
    $this->conn = new mysqli($this->hostname,$this->username,$this->password,$this->dbname);
    if ($this->conn->connect_error) {
      print_r($this->connect_error);
      exit;

    }else
    {
        return $this->conn;
        
        
        
    }
}

}

?>