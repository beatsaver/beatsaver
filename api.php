<?php
require("config.php");
header('Content-Type: application/json');
$offset = (int) @$_GET["off"];

if(empty($_GET['mode'])){$_GET['mode'] = 'top';}

if(@$_GET['mode'] == 'top'){
$bt = qcache($database, "topdlapi".$offset, "beats", [
        "id",
        "beatname",
        "ownerid",
        "downloads",
        "upvotes", "plays",
        "beattext",
        "uploadtime",
        "songName",
        "songSubName",
        "authorName",
        "beatsPerMinute",
        "difficultyLevels",
        "img"
], [
        'LIMIT' => [$offset, 15],
        "ORDER" => ["downloads" => "DESC",]
]);
foreach($bt as $key => $row){
$bt[$key]["difficultyLevels"] = json_decode($bt[$key]["difficultyLevels"]);
$bt[$key]["uploader"] = (qcache($database, "user-".$bt[$key]["ownerid"], "users", "username", ["id" => $bt[$key]["ownerid"]]))[0];
}
echo json_encode($bt, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
}

if(@$_GET['mode'] == 'new'){
$bt = qcache($database, "newapi".$offset, "beats", [
        "id",
        "beatname",
        "ownerid",
        "downloads",
        "upvotes", "plays",
        "beattext",
        "uploadtime",
        "songName",
        "songSubName",
        "authorName",
        "beatsPerMinute",
        "difficultyLevels",
        "img"
], [
        'LIMIT' => [$offset, 15],
        "ORDER" => ["id" => "DESC",]
]);
foreach($bt as $key => $row){
$bt[$key]["difficultyLevels"] = json_decode($bt[$key]["difficultyLevels"]);
$bt[$key]["uploader"] = (qcache($database, "user-".$bt[$key]["ownerid"], "users", "username", ["id" => $bt[$key]["ownerid"]]))[0];
}

echo json_encode($bt, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
}

if(@$_GET['mode'] == 'star'){
$bt = qcache($database, "starapi".$offset, "beats", [
        "id",
        "beatname",
        "ownerid",
        "downloads",
        "upvotes", "plays",
        "beattext",
        "uploadtime",
        "songName",
        "songSubName",
        "authorName",
        "beatsPerMinute",
        "difficultyLevels",
        "img"
], [
        'LIMIT' => [$offset, 15],
        "ORDER" => ["upvotes" => "DESC",]
]);
foreach($bt as $key => $row){
$bt[$key]["difficultyLevels"] = json_decode($bt[$key]["difficultyLevels"]);
$bt[$key]["uploader"] = (qcache($database, "user-".$bt[$key]["ownerid"], "users", "username", ["id" => $bt[$key]["ownerid"]]))[0];
}
echo json_encode($bt, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
}

if(@$_GET['mode'] == 'plays'){
$bt = qcache($database, "playedapi".$offset, "beats", [
        "id",
        "beatname",
        "ownerid",
        "downloads",
        "upvotes", "plays",
        "beattext",
        "uploadtime",
        "songName",
        "songSubName",
        "authorName",
        "beatsPerMinute",
        "difficultyLevels",
        "img"
], [
        'LIMIT' => [$offset, 15],
        "ORDER" => ["plays" => "DESC",]
]);
foreach($bt as $key => $row){
$bt[$key]["difficultyLevels"] = json_decode($bt[$key]["difficultyLevels"]);
$bt[$key]["uploader"] = (qcache($database, "user-".$bt[$key]["ownerid"], "users", "username", ["id" => $bt[$key]["ownerid"]]))[0];
}
echo json_encode($bt, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
}



if(@$_GET['mode'] == 'details'){
if(empty($_GET['id'])){die("Empty ID");}
$bt = qcache($database, "detailsapi-".$_GET['id'], "beats", [
        "id",
        "beatname",
        "ownerid",
        "downloads",
        "upvotes", "plays",
        "beattext",
        "uploadtime",
        "songName",
        "songSubName",
        "authorName",
        "beatsPerMinute",
        "difficultyLevels",
        "img"
], [
        "id" => (int) $_GET["id"]
]);
foreach($bt as $key => $row){
$bt[$key]["difficultyLevels"] = json_decode($bt[$key]["difficultyLevels"]);
$bt[$key]["uploader"] = (qcache($database, "user-".$bt[$key]["ownerid"], "users", "username", ["id" => $bt[$key]["ownerid"]]))[0];
}
echo json_encode($bt, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
}


if(@$_GET['mode'] == 'hashinfo'){
if(empty($_GET["hash"])){die("NO HASH PROVIDED");}
if(strlen($_GET["hash"]) != 32){die("NO HASH PROVIDED");}
$hash = strtolower($_GET["hash"]);
$bt2 = qcache($database, "diffmapapi-".$hash, "diffmap",[
	"beatid",
], [
        'LIMIT' => "1",
	"hash" => $hash
]);
if(empty($bt2[0]["beatid"])){die(json_encode(array()));}

$bt = qcache($database, "diffmapapiinfo-".$bt2[0]["beatid"], "beats", [
        "id",
        "beatname",
        "ownerid",
        "downloads",
        "upvotes", "plays",
        "beattext",
        "uploadtime",
        "songName",
        "songSubName",
        "authorName",
        "beatsPerMinute",
        "difficultyLevels",
        "img"
], [
	'id' => $bt2[0]["beatid"]
]);
foreach($bt as $key => $row){
$bt[$key]["uploader"] = (qcache($database, "user-".$bt[$key]["ownerid"], "users", "username", ["id" => $bt[$key]["ownerid"]]))[0];
$bt[$key]["difficultyLevels"] = json_decode($bt[$key]["difficultyLevels"]);
}
echo json_encode($bt, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
}


if(@$_GET['mode'] == 'votekey'){
if(!empty($_POST["username"]) AND !empty($_POST["password"])){
$userdb = $database->select("users", [
        "username",
        "password",
        "id",
	"votekey"
], [
        "username" => strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $_POST["username"]))
]);
if(empty($userdb[0]["username"])){die("Username Not Found");}
if(empty($userdb[0]["password"])){die("Password not set, Use the Password Reset form to resend the email");}
if(!password_verify($_POST["password"], $userdb[0]["password"])){die("Invaild Password, Please go back and try again");}else{
if(empty($userdb[0]["votekey"])){
$token = trim(file_get_contents("/proc/sys/kernel/random/uuid"));
$database->update("users", [
        "votekey" => $token
], [
        "id" => $userdb[0]["id"]
]);

}else{$token = $userdb[0]["votekey"];}
echo json_encode($token);
die();
}
}
}


if(@$_GET['mode'] == 'usersongs'){
if(empty($_GET['id'])){die("Empty ID");}
$bt = qcache($database, "usersongsapi-".$_GET['id'], "beats", [
        "id",
        "beatname",
        "ownerid",
        "downloads",
        "upvotes", "plays",
        "beattext",
        "uploadtime",
        "songName",
        "songSubName",
        "authorName",
        "beatsPerMinute",
        "difficultyLevels",
        "img"
], [
        "ownerid" => (int) $_GET["id"]
]);
foreach($bt as $key => $row){
$bt[$key]["uploader"] = (qcache($database, "user-".$bt[$key]["ownerid"], "users", "username", ["id" => $bt[$key]["ownerid"]]))[0];
$bt[$key]["difficultyLevels"] = json_decode($bt[$key]["difficultyLevels"]);
}
echo json_encode($bt, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
}


if(@$_GET['mode'] == 'session'){
$user["username"] = @$_SESSION["userdb"][0]["username"];
$user["id"] = @$_SESSION["userdb"][0]["id"];
echo json_encode($user, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
}

if(@$_GET['mode'] == 'updateplay'){
if($_GET['token'] !== $playtoken){die('Invaild ID');}
$diffmap = $database->select("diffmap", [
        "beatid"
],[
	"hash"	=> strtolower($_GET['levelid'])
]);

$findlvl = $database->update("beats", [
        "plays[+]" => $_GET['count']
], [
        "id" => $diffmap[0]["beatid"]
]);
var_dump($diffmap);
var_dump($findlvl);

}

