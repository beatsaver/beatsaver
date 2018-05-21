<?php
$requirelogin = true;
require("config.php");

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->build();

$bt = $database->select("beats", [
        "id",
        "beatname",
        "ownerid",
        "downloads",
        "upvotes",
        "beattext",
        "uploadtime",
        "songName",
        "songSubName",
        "authorName",
        "beatsPerMinute",
        "difficultyLevels",
        "img"
], [
        "ownerid" => $_SESSION["userdb"][0]["id"],
	"id" => $_GET['id']
]);

if(!empty($bt[0]["id"])){
$database->delete("beats", [ "id" => $_GET['id'] ]);
$database->delete("votes", [ "beatid" => $_GET['id'] ]);
$database->delete("diffmap", [ "beatid" => $_GET['id'] ]);
unlink("files/".$bt[0]["id"].".zip");
unlink("img/".$bt[0]["id"].'.'.$bt[0]["img"]);
$params = [
    'index' => 'beats',
    'type' => 'beats',
    'id' => $bt[0]["id"]
];
$response = $client->delete($params);


}
header("Location: profile.php");
