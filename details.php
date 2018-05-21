<?php
require("config.php");
$bt = qcache($database, "details".$_GET["id"], "beats", [
	"id",
	"beatname",
	"ownerid",
	"downloads",
	"upvotes",
	"plays",
	"beattext",
	"uploadtime",
	"songName",
	"songSubName",
	"authorName",
	"beatsPerMinute",
	"difficultyLevels",
	"img"
], [
	"id" => $_GET["id"]
]);
$pagetitle = "Beat Saver - " . $bt[0]["beatname"];
require("header.php");
if(empty($bt[0]["beatname"])){die("<br><br><br><br><br><br><br><br><center><h1>Song Not Found</h1></center>");}
?>
    <div class="container">
      <div class="row">
	<div class="col-md-1"><br><br><br><br></div>
        <div class="col-md-12">
          <h2><?php echo $bt[0]["beatname"]; ?></h2>
<table class="table">
  <tr>
    <th rowspan="5"><img src="<?php echo 'img/'.$bt[0]["id"].'.'.$bt[0]["img"]; ?>" alt="<?php echo $bt[0]["beatname"]; ?>" style="min-width: 20em; max-width: 20em;"></th>
    <th><small>Uploaded by: <?php echo ($database->select("users", "username", ["id" => $bt[0]["ownerid"]]))[0]; date_default_timezone_set('UTC'); echo "  on " . date(DATE_ATOM, $bt[0]["uploadtime"]);?><br>
                </small></th>
  </tr>
  <tr>
    <td>Song: <?php echo $bt[0]["songName"] . " - " . $bt[0]["songSubName"]; ?></td>
  </tr>
  <tr>
    <td><?php echo $bt[0]["beattext"]; ?></td>
  </tr>
  <tr>
    <td>Downloads: <?php echo number_format($bt[0]["downloads"]);?> || Finished: <?php echo number_format($bt[0]["plays"]);?>
<br>
<?php if(!empty($_SESSION["userdb"][0]["id"])){?><a href="vote.php?id=<?php echo $bt[0]["id"];?>"><?php } ?>
<button type="button" class="btn btn-default">
  <span class="glyphicon glyphicon-star" aria-hidden="true"></span> Stars <?php echo number_format($bt[0]["upvotes"]); ?>
</button>
<?php if(!empty($_SESSION["userdb"][0]["id"])){ ?> </a> <?php } ?><br><br>
    <p><a class="btn btn-default" href="<?php echo 'dl.php?id='.$bt[0]['id']; ?>" role="button">Download File</a></p><p>
</td>
  </tr>
</table>

<div id="disqus_thread"></div>
<script>

(function() { 
var d = document, s = d.createElement('script');
s.src = 'https://beatsaver.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
</p>
       </div>
      <hr>
    </div> <!-- /container -->
<?php require("footer.php");
