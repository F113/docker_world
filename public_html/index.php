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

$start = microtime(true);
$result = $conn->query("CALL Step");
$result = $conn->query("select * from image order by counter desc");
$count = $result->num_rows;

$sec = "0";
//header("Refresh: $sec; url=". $_SERVER['PHP_SELF']);
echo microtime(true) - $start;
echo '<br>';

$html = '';

while ($particle = $result->fetch_assoc()) {
    var_dump($particle);
    echo '<br>';
    if (!isset($_SESSION['coords'][$particle['id']])) {
        $_SESSION['coords'][$particle['id']] = [
            $_SESSION['coords'][$particle['m1']][0] + (mt_rand(0, 1)*2 - 1),
            $_SESSION['coords'][$particle['m1']][1] + (mt_rand(0, 1)*2 - 1)
        ];
    }

    if ($particle['counter'] < 1) continue;
    $colors = [255,255,255];

    $html .= '<div class="p" style="top:'.$_SESSION['coords'][$particle['id']][0].'px; left:'.$_SESSION['coords'][$particle['id']][1].'px; background-color: rgb('.$colors.')"></div>';
    //echo implode(' ', $particle) . '<br>';
}

print_r($_SESSION);
?>


