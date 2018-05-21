<?php
require("config.php");
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

$username = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $_POST["username"]));
$email = trim($_POST["email"]);
$resettoken = trim(file_get_contents("/proc/sys/kernel/random/uuid"));

if(strlen($resettoken) < 3){die("Internal System Error, Unable to make token");}
if(strlen($email) < 3){die("Internal System Error, Unable to read email address");}
if(strlen($username) < 3){die("Internal System Error, Unable to read username - Username must be longer then 3 letters after filtering");}

//check for username exists
$userdb = $database->select("users", [
	"username"
], [
	"username" => $username
]);

if(!empty($userdb[0]["username"])){die("Username Already Exists, Please Try Again");}

$emaildb = $database->select("users", [
        "email"
], [
        "email" => sha1(strtolower($email))
]);
if(!empty($emaildb[0]["email"])){die("Email Already Exists, Please Try Again");}

//insert main userrow
$database->insert("users", [
	"username" => $username,
	"email" => sha1(strtolower($email)),
	"resettoken" => $resettoken,
	"registered" => time()
]);
$message = "Welcome to BeatSaver\n\nPlease use the link below to set your password for the username: $username\n\nhttps://beatsaver.com/newpassword.php?token=$resettoken\n\n\n";

$from = new SendGrid\Email("BeatSaver", "no-reply@beatsaver.com");
$subject = "BeatSaver: New Password";
$to = new SendGrid\Email("$username", "$email");
$content = new SendGrid\Content("text/plain", $message);
$mail = new SendGrid\Mail($from, $subject, $to, $content);

$sg = new \SendGrid($SENDGRID_SECRET);

$response = $sg->client->mail()->send()->post($mail);
die("Thanks for registering!<br>Please check your email to set your password and login!");
}

$pagetitle = "BeatSaver - Register";
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
.form-signin input[type="username"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
<script src='https://www.google.com/recaptcha/api.js'></script>
   <div class="container">
      <form class="form-signin" method="POST">
        <h2 class="form-signin-heading">Create New Account</h2>
        <b>All form fields are required, below are some details on how your information is stored</b>
        <ul>
                <li>An email is dispatched to the provided email to set your password, it will be coming from no-reply@beatsaver.com with the Subject: BeatSaver: New Password <b>Hotmail,Live,Yahoo has known issues not accepting my mail</b></li>
                <li>E-Mail Addresses are stored as SHA1 in the database</li>
                <li>Usernames can only be letters and numbers, No spaces or special symbols, will be lowercased and limited to a max length of 16</li>
                <li>Passwords are stored using <a href="https://en.wikipedia.org/wiki/Bcrypt">BCrypt</a> with a cost of 14 and a random per user salt
        </ul>
        <h3>Email address</h3>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" required autofocus>
        <h3>Username</h3>
        <input type="username" id="inputPassword" class="form-control" placeholder="Username" name="username" required>
	<div class="g-recaptcha" data-sitekey="6LfDxFcUAAAAAISoTxWFoQQURSSCYwXCsSttvWqp"></div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button><br>
      </form>

    </div> <!-- /container -->

  </body>
</html>
