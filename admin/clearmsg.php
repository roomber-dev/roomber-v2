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

            mysqli_query($mysqli, "DELETE FROM messages");
        }
}

?>