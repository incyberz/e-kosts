<main id='main' class='main'>
<?php

$a = $_SERVER['REQUEST_URI'];
$a= strpos($a, '?') ? $a : $a.='?';
$a= strpos($a, '&') ? $a : $a.='&';
$b = explode("?", $a);
$c = explode("&", $b[1]);
$parameter = $c[0];


if (file_exists("modul/$parameter.php")) {
    include "modul/$parameter.php";
} elseif ($parameter=='') {
    include "modul/main/dashboard.php";
} else {
    switch ($parameter) {
        case 'placeofstudy': include 'modul/place_of_study.php';
            break;
        case 'finalann': include 'modul/final_announcement.php';
            break;
        default:
            include "modul/na.php";
    }
}
?>
</main>