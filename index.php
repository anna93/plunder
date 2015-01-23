<!DOCTYPE html>
<!--
Author: Shekhar Joshi
Country: India
-->


<?php
register_shutdown_function('shutdownFunction');

function shutDownFunction() {
    $error = error_get_last();
    echo $error['message'] . '<br/>';
    echo $GLOBALS['count'];
    if ($error['type'] == 1) {
        //do your stuff     
    }
}

$count = 0;
ini_set('memory_limit', '-1');
set_time_limit(30000);
$ch = curl_init();
echo '<pre>';

for ($char = 'a';;) {
    $l1[] = $char;
    if ($char == 'z') {
        $char = '0';
        continue;
    }
    if ($char == '9')
        break;
    $char++;
}
$l1[] = ' ';

dummy('a', $l1, $ch);
echo $count . '<br/>';

curl_close($ch);
?>

<?php

function getCity($query, $ch) {
    $GLOBALS['count'] ++;
    $url = "http://autocomplete.wunderground.com/aq?=jQuery17209440772472339225_1421813297012&query=" . $query . "&h=1&_=1421813470121";
    //echo $url."<br>";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    return json_decode($res)->RESULTS;
    //return ([0,1,2,3,4,5,6,7,8,8,88,5,5,5,5,5,5,5,5,5,5]);
}

function dummy($str, $l1, $ch) {
    //echo $str . "<br>";
    $res = getCity($str, $ch);
    //echo count($res)."<br>";
    //exit();
    if (count($res) < 20) {
        if (count($res) > 0) {
            //echo '---------------------------------<br/>';
            foreach ($res as $r) {
                //echo "<br/>";
                echo $r->name . "<br>";
            }
            //echo '---------------------------------<br/>';
        }
    } else {
        foreach ($l1 as $t) {
            if (substr($str, -1) == ' ' && $t == ' ') {
                break;
                //return;
            }

            $tempStr = $str . $t;
            if ($tempStr == 'ab') {
                echo "$tempStr<br/>Salvation!<br/>";
                break;
            }

            dummy($tempStr, $l1, $ch);
        }
    }
}
