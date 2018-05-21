<?php
require("config.php");
$pagetitle = "BeatSaver - Set New Password";

if(strlen($_GET["token"]) < 10){die("Missing Reset Token, Please check URL and try again");}

$userdb = $database->select("users", [
	"username",
	"id"
], [
	"resettoken" => $_GET["token"]
]);
if(empty($userdb[0]["username"])){die("Reset Token Not Found");}

if(!empty($_POST["password"])){
$options = [
    'cost' => 12,
];
$database->update("users", [
	"password" =>  password_hash($_POST["password"], PASSWORD_BCRYPT, $options),
	"resettoken" => ""
	],[
	"id" => $userdb[0]["id"]
	]);

header("Location: login.php");
die();
}
require("header.php");
?>
<style>
body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
   <div class="container">
      <form class="form-signin" method="POST">
        <h2 class="form-signin-heading">Please enter your new password for: <?php echo $userdb[0]["username"];?></h2>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputEmail" class="form-control" placeholder="Password" required autofocus>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Set Password</button>
      </form>

    </div> <!-- /container -->

  </body>
</html>
