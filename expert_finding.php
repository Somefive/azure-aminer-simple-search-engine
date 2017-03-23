<?php
include_once "tools.php";
$field = str_replace(" ","_", $_GET['domain']);
$res = $redis->get("aminer::interest::$field");
if ($res != false) {
    die($res);
}
else
    die(json_encode([]));