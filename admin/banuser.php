<?php

include_once "../func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);

session_start();

if(!isset($_SESSION['id'])) {
    die();
} else {
    if ($result = $mysqli->query("SELECT admin, username, namecolor_bg, namecolor_fg FROM users WHERE id='" . $_SESSION['id'] . "'")) {
            $row = $result->fetch_assoc();

            if($row['admin'] == 0) {

                die();
            }

            if(isset($_POST['id'])) {
              /*  if(isset($_POST['reason'])) {
                 if($_POST['reason'] == "" || $_POST['reason'] == " ") {
                       die();
                 }
                 if(!isset($_POST['unban']) || $_POST['unban'] == false) {
                   
                 }
                }*/

                   

                
            

            if($_POST['unban'] == "true") {
                   mysqli_query($mysqli, "UPDATE users SET banned=0, banreason='' WHERE id='".$_POST['id']."'");
                } else if($_POST['unban'] == "false") {
                    mysqli_query($mysqli, "UPDATE users SET banned=1, banreason='".$_POST['reason']."' WHERE id='".$_POST['id']."'");
                }
                
            }
        }
}

?>