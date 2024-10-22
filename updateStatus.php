<?php 
session_start();

include "func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);

    if(isset($_POST['status']) && isset($_SESSION['id'])) {
        switch ($_POST['status']) {
            case "Online":
                $stmt = $mysqli->prepare("UPDATE users SET status='Online' WHERE id=?");
                $stmt->bind_param('s', $_SESSION['id']); // 's' specifies the variable type => 'string'
               
                $stmt->execute();
                echo 'set to online';
                break;
            case "Idle":
                $stmt = $mysqli->prepare("UPDATE users SET status='Idle' WHERE id=?");
                $stmt->bind_param('s', $_SESSION['id']); // 's' specifies the variable type => 'string'
               
                $stmt->execute();
                echo 'set to idle';
                break;
                
        }

    }

?>