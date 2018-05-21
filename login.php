<?php
require("config.php");
$pagetitle = "BeatSaver - Login";
if(!empty($_POST["username"]) AND !empty($_POST["password"])){
$userdb = $database->select("users", [
	"username",
	"password",
	"id"
], [
	"username" => strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $_POST["username"]))
]);
if(empty($userdb[0]["username"])){die("Username Not Found");}
if(empty($userdb[0]["password"])){die("Password not set, Use the Password Reset form to resend the email");}
if(!password_verify($_POST["password"], $userdb[0]["password"])){die("Invaild Password, Please go back and try again");}else{
$_SESSION["userdb"] = $userdb;
header("Location: index.php");
die();
}
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

      <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Username</label>
        <input type="username" name="username" id="inputEmail" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>
	<form class="form-signin" action="register.php">
        <a href="register.php"><button class="btn btn-lg btn-primary btn-block" type="submit">Register</button></a></form>
        <form class="form-signin" action="forgotpw.php">
        <a href="forgotpw.php"><button class="btn btn-primary btn-block" type="submit">Forgot Password</button></a></form>
    </div> <!-- /container -->

  </body>
</html>
