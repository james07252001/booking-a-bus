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

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['id']) && isset($_POST['table'])) {
    $id = (int) $_POST['id']; // Sanitize the ID
    $table = $conn->real_escape_string($_POST['table']); // Sanitize the table name

    $deleteSql = "DELETE FROM `$table` WHERE `id` = $id"; // Assuming `id` is the primary key
    if ($conn->query($deleteSql)) {
        echo "<p>Row with ID $id deleted successfully from $table.</p>";
    } else {
        echo "<p>Error deleting row: " . $conn->error . "</p>";
    }
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
        echo "<th style='background-color: #f2f2f2;'>Actions</th>"; // Add Actions column
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
            // Add delete button
            echo "<td>
                <form method='POST'>
                    <input type='hidden' name='id' value='" . sanitizeOutput($row['id']) . "'>
                    <input type='hidden' name='table' value='" . sanitizeOutput($tableName) . "'>
                    <button type='submit' name='delete' style='color: red;'>Delete</button>
                </form>
            </td>";
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
displayTable($conn, 'tblbook');
displayTable($conn, 'tblbus');
displayTable($conn, 'tblconductor');
displayTable($conn, 'tblschedule');
displayTable($conn, 'tblroute');
displayTable($conn, 'tbllocation');

$conn->close();
?>
