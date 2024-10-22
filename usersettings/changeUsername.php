<?php

session_start();

include_once "../func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);




if(isset($_SESSION['id']) && isset($_POST['newname'])) {
    $result = $mysqli->query("SELECT xtra FROM users WHERE id = '" . $_SESSION['id'] . "'");
    if ($result->num_rows > 0) {
        // row not found, do stuff...
        $row = $result->fetch_assoc();
    
        if($row['xtra'] == 1) {
    
            $xtra = true;
    
        } else {
            $xtra = false;
        }
    }

    
    $namelength = strlen($_POST['newname']);
    if($namelength < 2 || $namelength > 32) {
        echo 'name too long/short';
        die();
    }
    $stmt = $mysqli->prepare('UPDATE users SET username=? WHERE id=?'); // UPDATE users SET username='nek is NOT sussy' WHERE id='fm2C63TPVk8NtdDOSI1v'
 $stmt->bind_param('ss', $_POST['newname'], $_SESSION['id']); // 's' specifies the variable type => 'string'

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