<html>
<body>
<?php
//ini_set("display_errors", 0);
if (!isset($_GET["pass"])) {
    echo "No pass.";
    die();
} else {
    if ($_GET["pass"] != "urmom231") {
        echo "Wrong pass";
        die();
    }
}

//echo '<style>' . file_get_contents('style.css') . '</style>';

include_once "dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database); 

if ($result = $mysqli->query("SELECT * FROM users")) {
    while ($row = $result->fetch_assoc()) {

              echo formatMessageHTML($row["username"], $row["id"], date("h:i:s"), 0, $mysqli);
    }
    $result->free();
} 


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


<!--<form name="message" action="">
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>-->

</body>

<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script>
            $(document).ready(function() {
              /*  $("#submitmsg").click(function() {
                    var clientmsg = $("#usermsg").val();
                    $.post("post.php", {
                        text: clientmsg
                    });
                    $("#usermsg").val("");
                    return false;
                });*/
            });
    </script>
</head>
</html>