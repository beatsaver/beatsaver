<?php
require("config.php");
$diffmap = $database->select("diffmap", [
        "beatid",
	"hash"
]);

foreach($diffmap as $row){
$count = file_get_contents("https://scoresaber.com/api.php?function=getPlays&param1=".strtoupper($row["hash"])."&key=7e78d0ab3f76cd31dd81a11eed934438");
echo $row["hash"] . " " . $count . PHP_EOL;
$findlvl = $database->update("beats", [
        "plays" => (int) $count
], [
        "id" => $row["beatid"]
]);

}
