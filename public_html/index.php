<?php
include(__DIR__ . '/db.php');

$start = microtime(true);
$result = $conn->query("CALL Step");
$result = $conn->query("select * from image order by counter asc");
$count = $result->num_rows;

// echo '<pre>';
// print_r($_SERVER);
// echo '</pre>';

if (isset($_GET['refresh']))
header("Refresh: ".(int)$_GET['refresh']."; url=". $_SERVER['REQUEST_URI']);
$time_db = microtime(true) - $start;

$html = '';
$start = microtime(true);
while ($particle = $result->fetch_assoc()) {
    if (!isset($_SESSION['coords'][$particle['id']])) {        
        $q0 = 0;
        $q1 = 0;

        while ($q0 == 0 && $q1 == 0) {
            $q0 = mt_rand(-1, 1);
            $q1 = mt_rand(-1, 1);
        }

        $_SESSION['coords'][$particle['id']] = [
            $_SESSION['coords'][$particle['m1']][0] + $q0,
            $_SESSION['coords'][$particle['m1']][1] + $q1
        ];
    }

    if ($particle['counter'] < 2) continue;
    $colors = [200-$particle['counter']*2, 200-$particle['counter']*2, 200-$particle['counter']*2];

    $html .= '<div data-id="'.$particle['id'].'" class="p" style="top:'.$_SESSION['coords'][$particle['id']][0].'px; left:'.$_SESSION['coords'][$particle['id']][1].'px; background-color: rgb('.implode(',', $colors).')"></div>';
    //echo implode(' ', $particle) . '<br>';
}
$time_php = microtime(true) - $start;

//echo $html;
//print_r($_SESSION);
?>

<style>
  .p {width: 1px; height:1px; position:absolute;}
  .center{position:absolute; left:50%; top:50%;}
  .info {position:absolute; top:0; left:0; width:100%; padding:10px;  background-color: #fffded; border-bottom: 1px solid #ccc;}
</style>

<div class="info">
  <div>count: <?=$count?></div>
  <div>time_db: <?=$time_db?></div>
  <div>time_php: <?=$time_php?></div>  
</div>

<div class="center">
<?=$html?>
</div>
