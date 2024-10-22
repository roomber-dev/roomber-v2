<?php

session_start();

include_once "../func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);


if(isset($_SESSION['id'])) {
    $stmt = $mysqli->prepare('UPDATE users SET namecolor_bg2="" WHERE id=?'); // UPDATE users SET username='nek is NOT sussy' WHERE id='fm2C63TPVk8NtdDOSI1v'
 $stmt->bind_param('s', $_SESSION['id']); // 's' specifies the variable type => 'string'

 $stmt->execute();

 echo 'good';

 /*$result = $stmt->get_result();
 while ($row = $result->fetch_assoc()) {
     // Do something with $row
 }*/
} else {
    echo 'not logged in';
}
?>