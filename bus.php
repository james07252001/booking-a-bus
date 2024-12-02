<?php
// Connect to database
$host = '127.0.0.1';
$username = 'u510162695_bobrs';
$password = '1Bobrs_password';  // Replace with the actual password
$dbname = 'u510162695_bobrs';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize output
function sanitizeOutput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Function to display table
function displayTable($conn, $tableName) {
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div style='margin-bottom: 20px;'>";
        echo "<h2>" . strtoupper($tableName) . " TABLE</h2>";
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        
        // Get field information for headers
        $fields = $result->fetch_fields();
        echo "<tr>";
        foreach ($fields as $field) {
            echo "<th style='background-color: #f2f2f2;'>" . sanitizeOutput($field->name) . "</th>";
        }
        echo "</tr>";
        
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                // Mask password for security
                if (strpos(strtolower($key), 'password') !== false) {
                    echo "<td>[MASKED]</td>";
                } else {
                    echo "<td>" . sanitizeOutput($value ?? "NULL") . "</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "0 results found in " . sanitizeOutput($tableName) . " table";
    }
}

// Display tables
displayTable($conn, 'tblpassenger');
displayTable($conn, 'tbluser');

$conn->close();
?>
