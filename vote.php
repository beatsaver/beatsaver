<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require("config.php");
if(empty($_SESSION["userdb"][0]["id"]) AND empty($_GET["token"])){die("No Login or Token Provided");}

$userid = $database->select("users", "id", [
        "votekey" => $_GET["token"],
]);

if(!empty($_SESSION["userdb"][0]["id"]) AND empty($_GET["token"])){ $userid = $_SESSION["userdb"][0]["id"];}

$data = $database->select("beats", [
	"id",
], [
	"id" => $_GET["id"]
]);
if(!empty($data[0]["id"])){
$vote = $database->select("votes", "id", [
	"userid" => $userid,
	"beatid" => $data[0]["id"]
]);
if(empty($vote[0])){
$database->update("beats", [ "upvotes[+]" => 1],["id" => $data[0]["id"]]);
$database->insert("votes", [
        "userid" => $userid,
        "beatid" => $data[0]["id"]
]);
}else{echo "Cannot vote again";}
header("Location: details.php?id=" . $data[0]["id"]);
die();
}else{
die("Not Found in Database");
}
