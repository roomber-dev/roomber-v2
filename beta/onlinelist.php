<head>
    <meta property="og:title" content="XtraMessage BETA">
    <meta property="og:description" content="XtraMessage BETA features - Online list">
    <meta property="og:url" content="http://ba22dc42fa26.ngrok.io/beta/onlinelist.php">
    <!--<meta property="og:image" content=""> -->
</head>


<?php

echo "<style>" . file_get_contents("../oldstyle.css") . "</style>";
include_once "../func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);

/*$result = null;

if ($result = $mysqli->query("SELECT id FROM users WHERE hidden=0")) {
    while ($row = $result->fetch_assoc()) {
        echo formatUserHTML($row['id'], $mysqli);
    }
    $result->free();
}*/
echo "<h1>Online</h1>";
echo getUserListFormatted("online", $mysqli);
echo "<h1>Idle</h1>";
echo getUserListFormatted("idle", $mysqli);
echo "<h1>Offline</h1>";
echo getUserListFormatted("offline", $mysqli);


function formatUserHTML($id, $mysqli)
{



    if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg, status FROM users WHERE id=" . $id)) {
        while ($row = $result->fetch_assoc()) {
            $filteredname = str_replace("<", "&lt;", $row['username']);
            $filteredname = str_replace(">", "&gt;", $filteredname);
                $html_message = "<div class='onlineuser'><p class='onlineuser-info'> <a class='taglink' href='javascript:insert_mention(\"" . $filteredname . "\");'><b class='user-name' style='color: " . $row["namecolor_fg"] . "; background-color: " . $row["namecolor_bg"] . "'>" . $filteredname . "</b></a> " . $row['status'] . "<br></p></div>";

            
        }
    }

    

    return $html_message;
}

function getUserListFormatted($status, $mysqli) {
$html_message = null;
    if($status == "online") {
    if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg, status FROM users WHERE status='Online' OR status='Do Not Disturb' OR status='Idle' AND hidden=0")) {
        while ($row = $result->fetch_assoc()) {
            $filteredname = str_replace("<", "&lt;", $row['username']);
            $filteredname = str_replace(">", "&gt;", $filteredname);
                $html_message = "<div class='onlineuser'><p class='onlineuser-info'> <a class='taglink' href='javascript:insert_mention(\"" . $filteredname . "\");'><b class='user-name' style='color: " . $row["namecolor_fg"] . "; background-color: " . $row["namecolor_bg"] . "'>" . $filteredname . "</b></a> " . $row['status'] . "<br></p></div>";

            
        }
    }
} elseif($status == "offline") {
    if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg, status FROM users WHERE status='Offline' AND hidden=0")) {
        while ($row = $result->fetch_assoc()) {
            $filteredname = str_replace("<", "&lt;", $row['username']);
            $filteredname = str_replace(">", "&gt;", $filteredname);
                $html_message = $html_message . "<div class='onlineuser'><p class='onlineuser-info'> <a class='taglink' href='javascript:insert_mention(\"" . $filteredname . "\");'><b class='user-name' style='color: " . $row["namecolor_fg"] . "; background-color: " . $row["namecolor_bg"] . "'>" . $filteredname . "</b></a> " . $row['status'] . "<br></p></div>";

            
        }
    }
} elseif($status == "idle") {
    if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg, status FROM users WHERE status='Idle' AND hidden=0")) {
        while ($row = $result->fetch_assoc()) {
            $filteredname = str_replace("<", "&lt;", $row['username']);
            $filteredname = str_replace(">", "&gt;", $filteredname);
                $html_message = $html_message . "<div class='onlineuser'><p class='onlineuser-info'> <a class='taglink' href='javascript:insert_mention(\"" . $filteredname . "\");'><b class='user-name' style='color: " . $row["namecolor_fg"] . "; background-color: " . $row["namecolor_bg"] . "'>" . $filteredname . "</b></a> " . $row['status'] . "<br></p></div>";

            
        }
    }
}
    if(isset($html_message) || $html_message != "") {
        return $html_message;
    } elseif(!isset($html_message) || $html_message == "") {
        return "<h3>None</h3>";
    }
    
}

?>