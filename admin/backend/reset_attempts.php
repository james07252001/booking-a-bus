<?php
session_start();

// Reset login attempts and last attempt time
$_SESSION['login_attempts'] = 0;
$_SESSION['last_attempt_time'] = 0;

echo json_encode(['statusCode' => 200, 'title' => 'Login attempts reset']);
?>
