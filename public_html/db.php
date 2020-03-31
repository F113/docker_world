<?php
session_start();
$host = 'mysql';
$user = 'root';
$pass = 'rootpassword';
$db   = 'world';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
