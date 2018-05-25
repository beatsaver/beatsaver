<?php
require("config.php");
$offset = (int) @$_GET["off"];
$bt = qcache($database, "index".$offset, "beats", [
        "id",
        "beatname",
        "ownerid",
	"plays",
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
],[
        'LIMIT' => [$offset, 15],
        "ORDER" => ["id" => "DESC",]
]);
$pagetitle = "Beat Saver ";
require("header.php");
?>
    <div class="container">
      <div class="row">
	<div class="col-md-1"><br><br><br><br></div>
        <div class="col-md-12">
<?php $i = 0;
 foreach($bt as $brow) {
$i++;
if(!empty($brow['id'])){ ?>
          <h2><a href="details.php?id=<?php echo $brow['id']; ?>"><?php echo $brow["beatname"]; ?></a></h2>
<table class="table" style="table-layout:fixed;">
  <tr>
    <th rowspan="5" style="width: 15%;"><a href="details.php?id=<?php echo $brow['id']; ?>"><img src="<?php echo 'img/'.$brow["id"].'.'.$brow["img"]; ?>" alt="<?php echo $brow["beatname"]; ?>" style="min-width: 10em; max-width: 10em;"></a></th>
    <th><small>Uploaded by: <?php echo ( qcache($database, "user-".$brow["ownerid"], "users", "username", ["id" => $brow["ownerid"]]) )[0]; date_default_timezone_set('UTC'); echo "  on " . date(DATE_ATOM, $brow["uploadtime"]);?><br>
                </small></th>
  </tr>
  <tr>
    <td>Song: <?php echo $brow["songName"] . " - " . $brow["songSubName"]; ?></td>
  </tr>
  <tr>
    <td><?php echo substr($brow["beattext"], 0, 100); ?> ...</td>
  </tr>
  <tr>
    <td>Downloads: <?php echo number_format($brow["downloads"]);?> || Finished: <?php echo number_format($brow["plays"]);?>
<br>
<?php if(!empty($_SESSION["userdb"][0]["id"])){?><a href="vote.php?id=<?php echo $brow["id"];?>" class="pull-right"><?php } ?>
<button type="button" class="btn btn-default">
  <span class="glyphicon glyphicon-star" aria-hidden="true"></span> Stars <?php echo number_format($brow["upvotes"]); ?>
</button>
<?php if(!empty($_SESSION["userdb"][0]["id"])){ ?> </a> <?php } ?><br><br>
    <p><a class="btn btn-default" href="<?php echo 'dl.php?id='.$brow['id']; ?>" role="button">Download File</a></p><p>
</td>
  </tr>
</table>

<?php } } //END OF WHILE ?>
<?php if($i > 14){
?><a href="index.php?off=<?php echo $offset+15;?>" class="pull-right">
<button type="button" class="btn btn-default">
  <span class="glyphicon glyphicon-forward" aria-hidden="true"></span> Next Page (<?php echo $offset+15; ?>)
</button>
</a><?php }else{ ?>
<b>End of List</b>
<?php } ?>
       </div>
      <hr>
    </div> <!-- /container -->
<?php require("footer.php");
