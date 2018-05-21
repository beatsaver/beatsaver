<?php
require("config.php");
$pagetitle = "BeatSaver - Forgot Password";
if(!empty($_POST["username"])){

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}
$post_data = http_build_query(
    array(
        'secret' => $CAPTCHA_SECRET,
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER['REMOTE_ADDR']
    )
);
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $post_data
    )
);
$context  = stream_context_create($opts);
$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
$result = json_decode($response);
if (!$result->success) {
    die('Gah! CAPTCHA verification failed. Please Try Again.. Unless you are a robot: 01000111 01101111 00100000 01000001 01110111 01100001 01111001 00100001');
}


$userdb = $database->select("users", [
        "username",
        "password",
        "id",
	"email"
], [
        "email" => sha1(strtolower($_POST["username"]))
]);
if(empty($userdb[0]["username"])){die("Email not registered in the System");}
$resettoken = trim(file_get_contents("/proc/sys/kernel/random/uuid"));
$database->update("users", [
        "resettoken" => $resettoken,
],[ "id" => $userdb[0]["id"] ]);
$email = $_POST["username"];
$username = $userdb[0]["username"];
$message = "Welcome to BeatSaver\n\nPlease use the link below to set your password for the username: $username\n\nhttps://beatsaver.com/newpassword.php?token=$resettoken\n\n\n";

$from = new SendGrid\Email("BeatSaver", "no-reply@beatsaver.com");
$subject = "BeatSaver: New Password";
$to = new SendGrid\Email("$username", "$email");
$content = new SendGrid\Content("text/plain", $message);
$mail = new SendGrid\Mail($from, $subject, $to, $content);

$sg = new \SendGrid($SENDGRID_SECRET);

$response = $sg->client->mail()->send()->post($mail);
die("Please check your email to set your password and don't forgot to check your spam folder!");

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
<script src='https://www.google.com/recaptcha/api.js'></script>
      <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Forgot Password</h2><br>Don't forget to check your spam!<br>
        <label for="inputEmail" class="sr-only">EMail Address</label>
        <input type="email" name="username" id="inputEmail" class="form-control" placeholder="Email Address" required autofocus>
	<div class="g-recaptcha" data-sitekey="6LfDxFcUAAAAAISoTxWFoQQURSSCYwXCsSttvWqp"></div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Send Email</button>
      </form>
    </div> <!-- /container -->

  </body>
</html>
