<?php
    class Schedule
    {
        private $conn;
        private $table_name = "tblschedule";

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

        public function getByRouteId($routeId)
        {
            $query = "SELECT * FROM ".$this->table_name." WHERE route_id = '$routeId'";
            $result = $this->conn->query($query);
            
            $data = array();
            while ($row = $result->fetch_assoc()) {
                   $data[] = $row;
            }
            return $data;
        }

        public function getAll()
        {
            $query = "SELECT * FROM ".$this->table_name." ORDER BY schedule_date DESC, departure ASC";
            $result = $this->conn->query($query);
            
            $data = array();
            while ($row = $result->fetch_assoc()) {
                   $data[] = $row;
            }
            return $data;
        }

        public function findSchedule($routeId, $schedule_date)
        {
            $query = "SELECT * FROM ".$this->table_name." WHERE route_id = '$routeId' AND schedule_date = '$schedule_date'";
            $result = $this->conn->query($query);
            
            $data = array();
            while ($row = $result->fetch_assoc()) {
                   $data[] = $row;
            }
            return $data;
        }
    }
?>