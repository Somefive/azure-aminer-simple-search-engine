<?php
include_once "tools.php";
$id = $_GET["id"];
$res = $redis->get("aminer::coauthors::$id");
if ($res != false) {
    die($res);
} else {
    die(json_encode([]));
}