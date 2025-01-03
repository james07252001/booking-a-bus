<?php
$host = '127.0.0.1';
$username = 'u510162695_bobrs';
$password = '1Bobrs_password';
$dbname = 'u510162695_bobrs';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = (int)$_GET['id'];
$table = $conn->real_escape_string($_GET['table']);

$result = $conn->query("SELECT * FROM `$table` WHERE `id` = $id");
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateFields = [];
    foreach ($_POST['values'] as $key => $value) {
        $column = $_POST['columns'][$key];
        $updateFields[] = "`$column` = '" . $conn->real_escape_string($value) . "'";
    }

    $updateSql = "UPDATE `$table` SET " . implode(', ', $updateFields) . " WHERE `id` = $id";
    if ($conn->query($updateSql)) {
        echo "Record updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<form method="POST">
    <?php foreach ($row as $key => $value): ?>
        <label><?= htmlspecialchars($key) ?></label>
        <input type="text" name="values[]" value="<?= htmlspecialchars($value) ?>">
        <input type="hidden" name="columns[]" value="<?= htmlspecialchars($key) ?>">
        <br>
    <?php endforeach; ?>
    <button type="submit">Save Changes</button>
</form>
