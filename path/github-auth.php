<?php

include_once "../tools.php";

$code = $_GET["code"];
$state = $_GET["state"];
if ($state != $GLOBALS["state"])
    die ("Cross Site Attack!");
$result_string = CurlPost("https://github.com/login/oauth/access_token", [
    "client_id" => $GLOBALS["client_id"],
    "client_secret" => $GLOBALS["client_secret"],
    "code" => $code,
    "redirect_uri" => $GLOBALS["redirect_uri"],
    "state" => $GLOBALS["state"]
]);
$result_raw_list = explode("&", $result_string);
$result_params = array();
foreach ($result_raw_list as $item) {
    $value = explode("=", $item);
    if (count($value) < 2) continue;
    $result_params[$value[0]] = $value[1];
}
if ($result_params["scope"] != "user")
    die("Permission Denied.");
$result = CurlGet("https://api.github.com/user?access_token=${result_params["access_token"]}");
$result_obj = json_decode($result, true);
if (!key_exists("email", $result_obj))
    die("Permission Denied.");
$flag1 = $redis->setex("github::user::token::".$result_params["access_token"], 86400, $result_obj["id"]);
$flag2 = $redis->set("github::user::id::".$result_obj["id"], json_encode($result_obj));
if (!$flag1 || !$flag2)
    die(response("redis error."));
setcookie("access_token", $result_params["access_token"], time()+86400, "/");
header("Location: ../".$GLOBALS["index-page"]);