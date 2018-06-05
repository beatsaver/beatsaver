<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- For Dev information, please see the wiki https://wiki.beatsaver.com/index.php?title=BeatSaver -->

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<title><?php echo $pagetitle; ?></title>
  </head>
  <body>

    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php"><img src="Beat_Saver_Logo_White.png" height="35em" style="margin-top: -7px;"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="topdl.php">Top Downloads</a></li>
	    <li><a href="topstar.php">Top Played</a></li>
            <li><a href="browse.php">Newest</a></li>
	    <li><a href="https://discord.gg/ZY8T8ky">Mod Discord</a></li>
	    <li><a href="searchhtml.php">Search</a></li>
	    <li><a href="https://scoresaber.com/">ScoreSaber</a></li>
	    <li><a href="https://github.com/Umbranoxio/BeatSaberModInstaller/releases">Mod Installer</a></li>
            <?php if(!empty($_SESSION["userdb"][0]["id"])){?><li><a href="upload.php">Upload</a></li><?php } ?>
            <?php if(empty($_SESSION["userdb"][0]["id"])){?><li><a href="login.php">Login / Register</a></li><?php } ?>
            <?php if(!empty($_SESSION["userdb"][0]["id"])){?><li><a href="profile.php"><?php echo $_SESSION["userdb"][0]["username"];?></a></li><?php } ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <div class="row">
	<div class="col-md-1"><br><br><br><br></div>
        <div class="col-md-12">
<div class="alert alert-info" role="alert"> Looking for devlopers to help improve BeatSaver! Check out the <a href=https://github.com/beatsaver/beatsaver>Github repo</a>. Complete Rewrites are welcome! Chat with me on the <a href=https://discord.gg/ZY8T8ky>Discord</a>, I'm Balsa.</div>
</div></div>
