<?php
include_once "../tools.php";
if ($_GET["type"] == "logout") {
    setcookie("access_token", null, 0, "/");
    header("Location: ../".$GLOBALS["index-page"]);
} else {
    $path = "https://github.com/login/oauth/authorize?client_id=${GLOBALS["client_id"]}&redirect_uri=${GLOBALS["redirect_uri"]}&scope=user:email&state=${GLOBALS["state"]}&allow_signup=true";
    header("Location: $path");
}


