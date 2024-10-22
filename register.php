<?php
session_start();

include "paths.php";

foreach ($GLOBALS['func'] as $key => $value) {
    include_once $GLOBALS['paths']->include . $value;
}
if(isset($_SESSION['id'])) {
    header("Location: index.php");
    die();
}

function showError($message) {
    echo '<center><span class="error">'.$message.'</span></center>';
}

$mysqli = new mysqli("localhost", $username, $password, $database);
echo '<style>' . minify_css(file_get_contents('newstyle.css')) . '</style>';

if(isset($_POST['email'])) {
$good = true;

$username = $_POST['username'];
if( strlen($username) < 3 || strlen($username)>20 ) {
    $good = false;
    showError("Username must have 3-20 characters!");
}

if(ctype_alnum($username)==false && strlen($username) != 0) {
    $good = false;
    showError("The username needs to be alphanumeric.");
}

$email = $_POST['email']; 
$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email)) {
    $good = false;
    showError("Wrong email");
}

// check password
$pass = $_POST['password'];
$repeat_password = $_POST['repeatpassword'];

if ((strlen($pass)<8) || (strlen($pass)>30)) {
    $good = false;
    showError("Password must have 8-30 characters!");
}

if($pass != $repeat_password) {
    $good = false;
    showError("Both passwords must be the same!");
} 

$result = $mysqli->query("SELECT id FROM users WHERE email='" . $email . "'");
if ($result->num_rows > 0) {
  // row not found, do stuff...
 $good = false;
 showError("An account with this e-mail already exists.");
}

$result = $mysqli->query("SELECT id FROM users WHERE username='" . $username . "'");
if ($result->num_rows > 0) {
  // row not found, do stuff...
 $good = false;
 showError("An account with this username already exists.");
}

if($good == true) {
    //echo "Account validated.";
    $hashpass = password_hash($pass, PASSWORD_DEFAULT);
    $token = random_str(50);
    $randomid = random_id(20);
    mysqli_query($mysqli, "INSERT INTO users (id, username, namecolor_bg, namecolor_fg, email, password, token, status, admin, hidden, banned, banreason, style) VALUES ('".$randomid."', '".$username."', '#546e7a', '#FFFFFF', '".$email."', '".$hashpass."', '".$token."', 'Online', 0, 0, 0, '', 'dark')");

    $result = $mysqli->query("SELECT id, token, banned, banreason FROM users WHERE token = '".$token."'");
if ($result->num_rows > 0) {
$row = $result->fetch_assoc();

$userid = stripslashes(htmlspecialchars($randomid));
$usertoken = $row['token'];
$_SESSION['id'] = stripslashes(htmlspecialchars($userid));
$_SESSION['banned'] = $row['banned'];
$_SESSION['banreason'] = stripslashes(htmlspecialchars($row['banreason']));
} else {
echo '<center><span class="success">Account created</span></center>';

}
$result->free();


}
}
?>
<head>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<title>Roomber</title>
<meta property="theme-color" content="<?php echo $themeColor; ?>">
    <meta property="og:title" content="<?php echo $siteTitle; ?>">
    <meta property="og:description" content="<?php echo $siteDescription; ?>">
    <meta property="og:url" content="<?php echo $siteUrl; ?>">
    <meta property="og:image" content="<?php echo $siteImage; ?>">

<body class="theme-dark">

<div id="registerform">
    <p>Please log in to continue!<br><a class="register" href="login.php">Already have an account?</a></p>
    
    <form action="register.php" method="post">
     <input type="text" name="username" id="username" class="username" placeholder="Username" /><br>
      <input type="text" name="email" id="email" class="email" placeholder="Email" /><br>
      <input type="password" name="password" id="password" class="password" placeholder="Password" /><br>
      <input type="password" name="repeatpassword" id="repeatpassword" class="repeatpassword" placeholder="Repeat Password" /><br>
      <label>
     <!-- <input type="checkbox" name="tos" /> I accept the Terms of Service -->
      </label><br>

      <center><div class="g-recaptcha" data-sitekey="6LdeksMbAAAAAJhmKqq7XHhy-UTZ3Z5uSN8M_n52"></div></center><br>
      <input type="submit" name="enter" id="enter" value="Create account" class="enter" />
    </form>
  </div>
</body>