<?php
session_start();

include_once "../func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);

if(isset($_SESSION['id'])) {
    if(isset($_POST['style'])) {
        switch ($_POST['style']) {
            case 'white':
            case 'light':
                mysqli_query($mysqli, "UPDATE users SET style='light' WHERE id='".$_SESSION['id'] . "'");
                echo 'changed to light';
            break;

            case 'black':
            case 'dark':
                mysqli_query($mysqli, "UPDATE users SET style='dark' WHERE id='".$_SESSION['id'] . "'");
                echo 'changed to dark';
            break;
        }
    }
}

?>