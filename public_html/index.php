<?php
$host = 'mysql';
$user = 'root';
$pass = 'rootpassword';
$db   = 'world';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$start = microtime(true);
$res = $conn->query("CALL Step");
$result = $conn->query("select * from image order by counter desc ");


$sec = "0";
//header("Refresh: $sec; url=". $_SERVER['PHP_SELF']);
echo microtime(true) - $start;
echo '<br>';

while ($particle = $result->fetch_assoc()) {
    echo implode(' ', $particle) . '<br>';
}


?>