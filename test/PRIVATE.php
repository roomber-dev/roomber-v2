<?php
session_start();
/*
if($_SESSION['id'] != 1) {
echo '<p>What are you doing here?</p>';
die();
}*/

/*$hashed = password_hash($_GET['pass'], PASSWORD_DEFAULT);

include_once "../func/dbconnect.php";
include_once "../func/random_id.php";
$mysqli = new mysqli("localhost", $username, $password, $database);

mysqli_query($mysqli, "UPDATE users SET password='".$hashed."' WHERE id='vhOADgWpH6q0i84jMaI7'");*/



 
// This function will return a random
// string of specified length

 

$str_random = random_id(20);
echo "ID: ". $str_random . '<br>';
echo "Length: " . strlen($str_random);

$str_random = random_id(50);
echo "<br>JOINKEY: ". $str_random . '<br>';
echo "Length: " . strlen($str_random);
//mysqli_query($mysqli, "")

/*if ($result = $mysqli->query("SELECT id FROM users")) {
    while ($row = $result->fetch_assoc()) {
        $randomid = random_id(20);
        mysqli_query($mysqli, "UPDATE users SET id='".$randomid."' WHERE id=".$row['id']);
    }
}*/



/*$to = "neksodebe@gmail.com";
$subject = "hmm";

$message = "
<script>
let apiKey = '1be9a6884abd4c3ea143b59ca317c6b2';
$.getJSON('https://ipgeolocation.abstractapi.com/v1/?api_key=' + apiKey, function(data) {
  console.log(JSON.stringify(data, null, 2));
});
</script>
";

// It is mandatory to set the content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers. From is required, rest other headers are optional
$headers .= 'From: <roomberapp@gmail.com>' . "\r\n";
$headers .= 'Cc: roomberapp@gmail.com' . "\r\n";

mail($to,$subject,$message,$headers);
*/

?>