<?php
die();
require('config.php');
foreach (glob("files/*.zip") as $filename) {
$_FILES["fileupload"]["tmp_name"] = "$filename";

$zip = zip_open($_FILES["fileupload"]["tmp_name"]);

if ($zip) {
//Search 1 -- Look for info.json
    while ($zip_entry = zip_read($zip)) {
        echo zip_entry_name($zip_entry) . PHP_EOL;
        if(strpos(zip_entry_name($zip_entry), "info.json") > 3){
        if (zip_entry_open($zip, $zip_entry, "r")) {
            $rawdata = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
            $json = json_decode($rawdata, TRUE);
            echo "Detected Info.json".PHP_EOL."Raw:".$rawdata.PHP_EOL."JSON: ";
            var_dump($json);
            zip_entry_close($zip_entry);
        }
        }
    }
}
zip_close($zip);

$rawlvldata = "";
var_dump($json["difficultyLevels"]);
foreach($json["difficultyLevels"] as $lvlkey => $lvl){
$zip = zip_open($_FILES["fileupload"]["tmp_name"]);
if ($zip) {
    while ($zip_entry = zip_read($zip)) {
	$path_parts = pathinfo(zip_entry_name($zip_entry));
        if(strtolower($path_parts['basename']) == strtolower($lvl["jsonPath"])){
	echo "Processing " . $lvl["jsonPath"] . PHP_EOL;
        if (zip_entry_open($zip, $zip_entry, "r")) {
             $lvldata = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
             $rawlvldata .= $lvldata;
             $lvldata = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $lvldata);


unset($diffnotes); unset($notetime); unset($notetype); unset($stats);

$diffnotes = json_decode($lvldata, TRUE);
foreach($diffnotes["_notes"] as $key => $row){
$notetime[] = $row["_time"];
$notetype[$row["_cutDirection"]] = @$notetype[$row["_cutDirection"]] + 1;
}

echo "Events: " . count($diffnotes["_events"]).PHP_EOL;
echo "Notes: " . count($diffnotes["_notes"]).PHP_EOL;
echo "Walls: " .count($diffnotes["_obstacles"]).PHP_EOL;
echo "Lenght:" .max($notetime).PHP_EOL;

$stats["time"] = max($notetime);
$stats["slashstat"] = $notetype;
$stats["events"] = count($diffnotes["_events"]);
$stats["notes"] = count($diffnotes["_notes"]);
$stats["obstacles"] = count($diffnotes["_obstacles"]);
$json["difficultyLevels"][$lvlkey]["stats"] = $stats;


            zip_entry_close($zip_entry);
        }
        }
    }
}
zip_close($zip);
}
$path_parts = pathinfo($filename);
$uid = $path_parts['filename'];
$database->insert("diffmap", [
        "beatid" => $uid,
        "hash"  => md5($rawlvldata),
        "diffmeta" => $json["difficultyLevels"]
]);

}
