<?php
session_start();

include_once "../func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);


// localhost/invite?id=pog&joinkey=pog
// http://3646fb33b610.ngrok.io
// dsflkjdsflkjdsflkjdsflkjdsflkjdsflkjdsfjlk
//file_put_contents("ips.txt", file_get_contents("ips.txt")."\n".$_SERVER['HTTP_X_FORWARDED_FOR']);

if(!isset($_GET['id'])) {
    header("Location: ../new");
    die();
} else {
    $result = $mysqli->query("SELECT serverid, inviterid FROM invites WHERE id = '" . $_GET['id'] . "'");
    if ($result->num_rows > 0) {
        $invitedata = $result->fetch_assoc();
    }
        $result = $mysqli->query("SELECT username FROM users WHERE id = '" . $invitedata['inviterid'] . "'");
        if ($result->num_rows > 0) {
            $inviterdata = $result->fetch_assoc();
        }

        $result = $mysqli->query("SELECT joinkey, name FROM servers WHERE id = '" . $invitedata['serverid'] . "'");
        if ($result->num_rows > 0) {
            $serverdata = $result->fetch_assoc();
        }

        if(isset($invitedata) && isset($inviterdata) && isset($serverdata)) {
        echo '
        <meta property="og:title" content="Roomber">
        <meta property="og:description" content="You\'ve been invited to '.$serverdata['name'].' by '.$inviterdata['username'].'">
        <meta property="og:url" content="">
        <meta property="og:image" content="http://'.$_SERVER['SERVER_NAME'].'/assets/img/roomber-logo.png">
        ';
            if(isset($_SESSION['id'])) {
                $result = $mysqli->query("SELECT id FROM user_server_relation WHERE userid = '" . $_SESSION['id'] . "' AND serverid = '". $invitedata['serverid'] ."'");
                if ($result->num_rows == 0) {
                    mysqli_query($mysqli, "INSERT INTO user_server_relation VALUES (NULL, '".$_SESSION['id']."', '".$invitedata['serverid']."', '".$serverdata['joinkey']."')");
                    echo "<p>Joined ".$serverdata['name'].". You may now exit this page.</p>";
                } else {
                    echo "<p>You have already joined ".$serverdata['name'].". You may now exit this page.</p>";
                }
        
        
            } else {
                header("Location: ../");
                die();
            }
        } else {
            header("Location: ../");
            die();
        }


}
