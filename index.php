<?php

/**
 * Author: Shekhar Joshi
 * Country: India

 * Licensed under MIT license http://opensource.org/licenses/MIT
 * */
register_shutdown_function('shutdownFunction');

function shutDownFunction() {
    $error = error_get_last();
    echo $error['message'] . '<br/>';
    if ($error['type'] == 1) {
        //do your stuff     
    }
}

ini_set('memory_limit', '-1');
set_time_limit(30000);


$memcache_obj = new Memcache;
$memcache_obj->connect('localhost', 11211);
$errors = 0;


$ch = curl_init();

// l1 stands for level1, it contains all the characters which may be required to
// generate all query strings. 
############## START: generate array containing a-z, 0-9 and ' '[space]######
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
############## END: generate array containing a-z, 0-9 and ' '[space]######

$seed = 'aaa';
$progress=0;
recurGenerateQueryString($seed, $l1, $ch, $memcache_obj);
echo $errors;
curl_close($ch);
?>


<?php

function getPlaces($query, $ch) {
    $url = "http://autocomplete.wunderground.com/aq?=jQuery17209440772472339225_1421813297012&query=" . $query . "&h=1&_=1421813470121";

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);

    $jsonRes = json_decode($res);
    if (is_object($jsonRes))
        return $jsonRes->RESULTS;
    $GLOBALS['errors'] ++;
}

function recurGenerateQueryString($str, $l1, $ch, $memcache_obj) {
    if ( strlen($str)-strlen($GLOBALS['seed']) >= 2) {
        $memcache_obj->set('status', ++$GLOBALS['progress'], MEMCACHE_COMPRESSED, 100);
    }

    $memcache_obj->set('currentQS', $str, MEMCACHE_COMPRESSED, 100);

    $res = getPlaces($str, $ch);

    if (count($res) < 20) {
        if (count($res) > 0) {
            foreach ($res as $r) {
                echo $r->name . "<br>";
            }
        }
    } else {
        foreach ($l1 as $t) {
            if (substr($str, -1) == ' ' && $t == ' ') {
                break;
            }
            $tempStr = $str . $t;
//            if ($tempStr == 'aaz') {
//                break;
//            }

            recurGenerateQueryString($tempStr, $l1, $ch, $memcache_obj);
        }
    }
}
