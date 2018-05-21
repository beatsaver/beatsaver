<?php
require("config.php");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$data = qcache($database, "dl".$_GET["id"], "beats", [
	"id",
	"disabled"
], [
	"id" => $_GET["id"]
]);
if(!empty($data[0]["id"])){
if($data[0]["disabled"] == 1){die("Downloads have been disabled for this item");}
$m = new Memcached();
$m->addServer('localhost', 11211);
$key = "dl-".sha1($data[0]["id"].$_SERVER['REMOTE_ADDR']);

if(empty($m->get($key))){
$database->update("beats", [ "downloads[+]" => 1],["id" => $data[0]["id"]]);
$m->set($key, 100, 3600);
}

header("Location: files/" . $data[0]["id"] . ".zip");
die();

}else{
die("Download Not Found in Database");
}
