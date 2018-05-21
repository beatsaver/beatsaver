<?php
require 'vendor/autoload.php';
header('Content-Type: application/json');
use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->build();
if(empty($_GET["q"])){die("Need search string");}
$params = [
    'index' => 'beats',
    'type' => 'beats',
    "size" => 100,
    'body' => [
        'query' => [
            'multi_match' => [
                "query" => $_GET["q"],
		"fields" => [ "beatname^3", "beattext", "difficultyLevels". "beatsPerMinute", "songName", "songSubName", "authorName" ]
            ]
        ]
    ]
];

$response = $client->search($params);
echo json_encode($response, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT);
