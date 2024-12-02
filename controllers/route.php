<?php
    class Route
    {
        private $conn;
        private $table_name = "tblroute";

        // Database Connection 
        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function getById($id)
        {
            $query = "SELECT * FROM ".$this->table_name." WHERE id = '$id'";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            return $data;
        }

        public function getByFromTo($from, $to)
        {
            $query = "SELECT * FROM ".$this->table_name." WHERE route_from = '$from' AND route_to = '$to'";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            return $data;
        }
    }
?>