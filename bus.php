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

// Handle add action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add']) && isset($_POST['table'])) {
    $table = $conn->real_escape_string($_POST['table']); // Sanitize the table name
    $columns = $_POST['columns'];
    $values = array_map(function($value) use ($conn) {
        return "'" . $conn->real_escape_string($value) . "'";
    }, $_POST['values']);

    $columnList = implode(', ', array_map(function($col) {
        return "`$col`";
    }, $columns));
    $valueList = implode(', ', $values);

    $insertSql = "INSERT INTO `$table` ($columnList) VALUES ($valueList)";
    if ($conn->query($insertSql)) {
        echo "<p>Record added successfully to $table.</p>";
    } else {
        echo "<p>Error adding record: " . $conn->error . "</p>";
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
                echo "<td>" . sanitizeOutput($value ?? "NULL") . "</td>";
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

    // Display add record form
    echo "<h3>Add Record to " . strtoupper($tableName) . "</h3>";
    $fields = $conn->query("DESCRIBE $tableName");
    echo "<form method='POST'>";
    echo "<input type='hidden' name='table' value='" . sanitizeOutput($tableName) . "'>";
    echo "<input type='hidden' name='add' value='1'>";
    while ($field = $fields->fetch_assoc()) {
        echo "<label for='" . sanitizeOutput($field['Field']) . "'>" . sanitizeOutput($field['Field']) . ":</label>";
        echo "<input type='text' name='values[]' id='" . sanitizeOutput($field['Field']) . "' required>";
        echo "<input type='hidden' name='columns[]' value='" . sanitizeOutput($field['Field']) . "'>";
        echo "<br>";
    }
    echo "<button type='submit'>Add Record</button>";
    echo "</form>";
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
displayTable($conn, 'tbldriver');

$conn->close();
?>
