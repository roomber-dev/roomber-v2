<?php

session_start();

include_once "../func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);


if (isset($_SESSION['id']) && isset($_POST['email']) && isset($_POST['pass'])) {
    $ok = true;
    $stmt = $mysqli->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param('s', $_SESSION['id']); // 's' specifies the variable type => 'string'

    $stmt->execute();

    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['password'] == $_POST['pass']) $ok = false;
    }

    if ($ok) {

        $email = $_POST['email'];
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
            $ok = false;
            echo 'wrong email';
            die();
        }

        $stmt = $mysqli->prepare('UPDATE users SET email=? WHERE id=?'); // UPDATE users SET username='nek is NOT sussy' WHERE id='fm2C63TPVk8NtdDOSI1v'
        $stmt->bind_param('ss', $emailB, $_SESSION['id']); // 's' specifies the variable type => 'string'

        $stmt->execute();

        echo 'good';
    } else {
        echo 'wrong pass';
    }

    /*$result = $stmt->get_result();
 while ($row = $result->fetch_assoc()) {
     // Do something with $row
 }*/
} else {
    echo 'not logged in';
}
