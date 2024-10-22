<?php
session_start();
include_once "./func/dbconnect.php";
include "./func/formatMessageHTML.php";

//echo "<style>".file_get_contents("style.css")."</style>";
/*function formatMessageHTML($text, $edited, $authorid, $timestamp, $hidden, $mysqli)
{

   $exploded_text = explode(" ", $text);
$exploded_text = array_values($exploded_text);
  foreach ($exploded_text as $key => $value) {
    //echo $value["from"] . " to " . $value["to"] . "<br>";
    if(substr( $value, 0, 7 ) === "http://" || substr( $value, 0, 8 ) === "https://") {
      //sendmsg_as_server('found tag at index '.$key.' and value '.$value);
      $replacements = array($key => '<a class="weblink" href="javascript:open_webpage_insecure(\''.$value.'\');">'.$value.'</a>');
      $exploded_text = array_replace($exploded_text, $replacements);
    }
  }

  $text = implode(" ", $exploded_text);

    $html_message = "";
    if ($hidden == 1) return;
    if (!isset($text) || !isset($authorid) || !isset($timestamp)) return "<div class='msgln'><span class='chat-time'>" . date("g:i A") . "</span> <a class='taglink' href='javascript:insert_mention(\"Unknown\");'><b class='user-name'>Unknown</b></a> Unknown<br></div>";
    if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg FROM users WHERE id=" . $authorid . " LIMIT 100")) {
        while ($row = $result->fetch_assoc()) {
            $tms = explode(":", $timestamp);
            unset($tms[2]);
            $filteredname = str_replace("<", "&lt;", $row['username']);
            $filteredname = str_replace(">", "&gt;", $filteredname);
            if($edited == 0) {
                $html_message = "<div class='msgln'><span class='chat-time'>" . join(":", $tms) . "</span> <a class='taglink' href='javascript:insert_mention(\"" . $filteredname . "\");'><b class='user-name' style='color: " . $row["namecolor_fg"] . "; background-color: " . $row["namecolor_bg"] . "'>" . $filteredname . "</b></a> " . $text . "<br></div>";
            } else {
                $html_message = "<div class='msgln'><span class='chat-time'>" . join(":", $tms) . "</span> <a class='taglink' href='javascript:insert_mention(\"" . $filteredname . "\");'><b class='user-name' style='color: " . $row["namecolor_fg"] . "; background-color: " . $row["namecolor_bg"] . "'>" . $filteredname . "</b></a> " . $text . " <span class='edited'>(edited)</span><br></div>";
            }
            
        }
    }

    

    return $html_message;
}*/

$mysqli = new mysqli("localhost", $username, $password, $database);




  
$newstyle = false;
/*if(isset($_GET['style']) || isset($_POST['style'])) {
if($_GET['style'] == true || $_POST['style'] == true) {
  if($_GET['newstyle'] == true || $_POST['newstyle'] == true) {
    echo "<style>" . file_get_contents("./betalook/newstyle.css") . "</style>";
  } else {
    echo "<style>" . file_get_contents("style.css") . "</style>";
  }
}
}*/


 
// Loop through colors array
/*foreach($_SESSION as $key => $value){
    echo $key . ": " . $value . "<br>";
}*/
/*$timestamp = "";
$result = $mysqli->query("SELECT timestamp FROM messages ORDER BY timestamp DESC LIMIT 1");
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $timestamp = $row['timestamp'];
} else {
  die();
}

while($timestamp) {
  $result = $mysqli->query("SELECT timestamp FROM messages ORDER BY timestamp DESC LIMIT 1");
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  if($row['timestamp'] != $timestamp) {
    break;
  } else {
    continue;
  }
}
sleep(1);
}*/


if($_SESSION['channelID'] != "") { // http://localhost/?serverid=1HDBgt8mNwfvueAYV9MU&channelid=2vSK8dlGg4jfAW6BzHRZ
$joined = false;
  $stmt = $mysqli->prepare("SELECT id FROM user_server_relation WHERE userid=? AND serverid=?");
  $stmt->bind_param('ss',$_SESSION['id'],$_SESSION['serverID']); // 's' specifies the variable type => 'string'
 
  $stmt->execute();
 
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $joined = true;
  }

if($joined) {
  $stmt = $mysqli->prepare("SELECT * FROM messages WHERE hidden=0 AND recieverid=?");
  $stmt->bind_param('s',$_SESSION['channelID']); // 's' specifies the variable type => 'string'
 
  $stmt->execute();
 
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    if(!isset($row["text"]) || !isset($row["edited"]) || !isset($row['authorid']) || !isset($row['timestamp']) || !isset($row['hidden'])) {
      $row['authorid'] = "5zrWasq4YxTKwvntj9UO";
      $row['text'] = "Internal Error";
      $row['timestamp'] = date("h:i:s");
      $row['hidden'] = 0;
      $row['edited'] = 0;
    }
    echo formatMessageHTML($row["text"], $row["edited"], $row["authorid"], $row["timestamp"], $row["hidden"], $mysqli);
  }
} else {
  echo '<h1>yo don\'t tryna hack here</h1>';
}
/*if ($result = $mysqli->query("SELECT * FROM messages WHERE hidden=0 AND recieverid='".$_SESSION['channelID']."'")) {
    while ($row = $result->fetch_assoc()) {
            echo formatMessageHTML($row["text"], $row["edited"], $row["authorid"], $row["timestamp"], $row["hidden"], $mysqli);
                  
        }
        
    }
    $result->free();*/
  } else if($_SESSION['dmID'] != "") {
    
    $stmt = $mysqli->prepare("SELECT * FROM messages WHERE hidden=0 AND (recieverid=? OR recieverid=?) AND (authorid=? OR authorid=?)");
    $stmt->bind_param('ssss', $_SESSION['id'], $_SESSION['dmID'], $_SESSION['dmID'], $_SESSION['id']); // 's' specifies the variable type => 'string'
   
    $stmt->execute();
   
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      echo formatMessageHTML($row["text"], $row["edited"], $row["authorid"], $row["timestamp"], $row["hidden"], $mysqli);
    }

    /*if ($result = $mysqli->query("SELECT * FROM messages WHERE hidden=0 AND recieverid='".$_SESSION['id']."' OR recieverid='".$_SESSION['dmID']."'")) {
      while ($row = $result->fetch_assoc()) {
              
                    
          }
          
      }
      $result->free();*/
  }

?>