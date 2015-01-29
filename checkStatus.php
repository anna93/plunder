<?php

/**
 * Author: Shekhar Joshi
 * Country: India

 * Licensed under MIT license http://opensource.org/licenses/MIT
 * */

$memcache_obj = new Memcache;
$memcache_obj->connect('localhost', 11211);

echo json_encode(array('currentQS' => $memcache_obj->get($_GET['id'].'currentQS'),
    'status' => $memcache_obj->get($_GET['id'].'status')));

