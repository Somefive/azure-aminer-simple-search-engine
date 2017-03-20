<?php

include_once "tools.php";

if ($user == null)
    die("Permission Denied");

function getAuthor($id) {
    global $redis;
    $res = $redis->get("aminer::author::$id");
    if ($res != false)
        return json_decode($res, true);
    else
        return null;
}

if ($_GET["type"] == "interest" && $_GET["field"] != null) {
    $field = $_GET['field'];
    $res = $redis->get("aminer::interest::$field");
    if ($res != false) {
        $objs = json_decode($res, true);
        $newobjs = array();
        $size = count($objs);
        for($i = 0; $i < $size && $i < 10; $i += 1) {
            array_push($newobjs, getAuthor($objs[$i]["index"]));
        }
        die(json_encode($newobjs));
    }
} else if ($_GET["type"] == "coauthors" && $_GET["id"] != null) {
    $id = $_GET["id"];
    $res = $redis->get("aminer::coauthors::$id");
    if ($res != false) {
        $objs = json_decode($res, true);
        $newobjs = array();
        $size = count($objs);
        for($i = 0; $i < $size && $i < 5; $i += 1) {
            $author = getAuthor($objs[$i]["id"]);
            $author["cnt"] = $objs[$i]["cnt"];
            array_push($newobjs, $author);
        }
        die(json_encode($newobjs));
    }
}
die(json_encode([]));
