<?php


include_once "dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database); 


mysqli_query($mysqli, "INSERT INTO messages VALUES (NULL, '".$_POST['text']."', 4, '".date("h:i:s")."', 0, 4)");

function formatMessageHTML($text, $authorid, $timestamp, $hidden, $mysqli) {
    
    $html_message = "";
    if($hidden == 1) return;
    if(!isset($text) || !isset($authorid) || !isset($timestamp)) return "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <a class='taglink' href='javascript:insert_mention(\"Unknown\");'><b class='user-name'>Unknown</b></a> Unknown<br></div>";
    if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg FROM users WHERE id=".$authorid)) {
        while ($row = $result->fetch_assoc()) {
    $html_message = "<div class='msgln'><span class='chat-time'>".$timestamp."</span> <a class='taglink' href='javascript:insert_mention(\"".$row["username"]."\");'><b class='user-name' style='color: ".$row["namecolor_fg"]."; background-color: ".$row["namecolor_bg"]."'>".$row["username"]."</b></a> ".$text."<br></div>";
        }
    }

    return $html_message;
}
?>