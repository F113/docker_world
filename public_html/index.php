<?php
include(__DIR__ . '/db.php');

$start = microtime(true);
$result = $conn->query("CALL Step");
$result = $conn->query("select * from image order by counter asc");
$count = $result->num_rows;

if (isset($_GET['refresh']))
header("Refresh: ".(int)$_GET['refresh']."; url=". $_SERVER['REQUEST_URI']);
$time_db = microtime(true) - $start;

$html = '';
$start = microtime(true);
while ($particle = $result->fetch_assoc()) {
    if (!isset($_SESSION['coords'][$particle['id']])) {
        
        /* $a = 1;
        $new_coords = false;
        
        while (!$new_coords) {
            $new_coords = find_place($a, $_SESSION['coords'][$particle['m1']][0], $_SESSION['coords'][$particle['m1']][1]);
            $a++;
        }
        
        $_SESSION['coords'][$particle['id']] = $new_coords; */
        
        
        //var_dump($new_coords);
        
         
        $q0 = 0;
        $q1 = 0;
        $a = 0;
        
        while (($q0 == 0 && $q1 == 0) || in_array($image, $_SESSION['coords_busy']) ) {
            $a++;
            $q0 = mt_rand(-$a, $a);
            $q1 = mt_rand(-$a, $a);
            
            $new_x = $_SESSION['coords'][$particle['m1']][0] + $q0;
            $new_y = $_SESSION['coords'][$particle['m1']][1] + $q1;
            $image = (string)$new_x . '/' . (string)$new_y;
        }
        
        //print_r($image);
        
        $_SESSION['coords'][$particle['id']] = [
            $new_x,
            $new_y
        ];
        $_SESSION['coords_busy'][] = $image;
    }

    if ($particle['counter'] < 3) continue;
    $colors = [200-$particle['counter']*2, 200-$particle['counter']*2, 200-$particle['counter']*2];

    $html .= '<div data-id="'.$particle['id'].'" class="p" style="top:'.$_SESSION['coords'][$particle['id']][0].'px; left:'.$_SESSION['coords'][$particle['id']][1].'px; background-color: rgb('.implode(',', $colors).')"></div>';
    //echo implode(' ', $particle) . '<br>';
}
$time_php = microtime(true) - $start;

$count_coords = count($_SESSION['coords_busy']);

//echo $html;
//print_r($_SESSION);

/* function find_place($a, $x0, $y0) {
        
    $side = 2*$a;
    $x = $x0 - $a;
    $y = $y0 - $a;
    
    $n = 0;
    while ($n < $side) {
        $n++;
        $x++;
        if (!isBusy($x, $y)) return [$x, $y];
    }
    
    $n = 0;
    while ($n < $side) {
        $n++;
        $y++;
        if (!isBusy($x, $y)) return [$x, $y];
    }
    
    $n = 0;
    while ($n < $side) {
        $n++;
        $x--;
        if (!isBusy($x, $y)) return [$x, $y];
    }
    
    $n = 0;
    while ($n < $side) {
        $n++;
        $y--;
        if (!isBusy($x, $y)) return [$x, $y];
    }
    
    return false;
}

function isBusy ($x, $y) {
    $image = (string)$x.'/'.(string)$y;
    if (!in_array($image, $_SESSION['coords_busy'])) {
        $_SESSION['coords_busy'][] = $image;
        return false;
    } else {
        return true;
    }
} */


?>

<style>
  .p {width: 1px; height:1px; position:absolute;}
  .center{position:absolute; left:50%; top:50%;}
  .info {position:absolute; top:0; left:0; width:100%; padding:10px;  background-color: #fffded; border-bottom: 1px solid #ccc; /*display:none;*/}
</style>

<div class="info">
  <div>count: <?=$count?></div>
  <div>count_coords: <?=$count_coords?></div>
  <div>time_db: <?=$time_db?></div>
  <div>time_php: <?=$time_php?></div>  
</div>

<div class="center">
<?=$html?>
</div>
