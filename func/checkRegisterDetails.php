<?php

function checkRegisterDetails($mysqli) {
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