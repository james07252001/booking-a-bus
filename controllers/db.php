<?php
    class Database{
        private $host = "localhost";
        private $db_name = "u510162695_bobrs";
        private $username = "u510162695_bobrs";
        private $password = "1Bobrs_password";
        public $conn;

        public function getConnection(){
            $this->conn = null;
            $this->conn = new mysqli($this->host,$this->username,$this->password,$this->db_name);
            return $this->conn;
        }
    }
?>