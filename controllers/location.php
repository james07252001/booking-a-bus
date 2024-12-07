<?php

    class Location
    {
        private $conn;
        private $table_name = "tbllocation";

        // Database Connection 
        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function getAll()
        {
            $query = "SELECT * FROM ".$this->table_name;
            $result = $this->conn->query($query);
            
            $data = array();
            while ($row = $result->fetch_assoc()) {
                   $data[] = $row;
            }
            return $data;
        }

        public function getById($id)
        {
            $query = "SELECT * FROM ".$this->table_name." WHERE id = '$id'";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            return $data;
        }

        public function getByLocation($name)
        {
            $query = "SELECT * FROM ".$this->table_name." WHERE location_name = '$name'";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            return $data;
        }
    }

    
?>