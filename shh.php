<?php
class Database {
    private $host = "localhost";
    private $db_name = "u510162695_bobrs";
    private $username = "u510162695_bobrs";
    private $password = "1Bobrs_password";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

   
    // Method to display user data
    public function getUserData() {
        try {
            $sql = "SELECT * FROM tbluser";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo "<table border='1'>";
                echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone Number</th></tr>";
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No users found.";
            }
        } catch (PDOException $exception) {
            echo "Error fetching user data: " . $exception->getMessage();
        }
    }
}

// Usage
$db = new Database();
// To update the table structure
// $db->updateUserTable();

// To display the user data
$db->getUserData();
?>
