<?php
session_start();



if(isset($_SESSION['id']) and isset($_POST['text'])){
/*
    $filename = 'data.json';
    $data = file_get_contents($filename);
    $blocked_words = json_decode($data, true)["blocked_words"];
    $emojis = json_decode($data, true)["emojis"];
    $text_emojis = json_decode($data, true)["text_emojis"];
    $empty_chars = json_decode($data, true)["empty_chars"];
    $censorship_enabled = file_get_contents('censorship_enabled.txt');
    $silenced = file_get_contents('silenced.txt');
    $text = $_POST['text'];

    if (!function_exists('str_contains')) {
      function str_contains(string $haystack, string $needle): bool
      {
          return '' === $needle || false !== strpos($haystack, $needle);
      }
  }


    function sendmsg_as_server($message) {
      $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='user-name'>XtraMessage</b> ".$message."<br></div>";
      file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
    }
     
    /*if($text == '/clear') {
        if($_SESSION['name'] == 'neksodebe' || $_SESSION['name'] == 'mega') {
        file_put_contents("log.html", "");
        /*if($_SESSION['name'] == 'neksodebe') {
          sendmsg_as_server('The chat has been cleared by <b class="owner">'.$_SESSION['name'].'</b>.');
        } else {*/
          //sendmsg_as_server("The chat has been cleared by <a href='javascript:insert_mention(\"".$_SESSION['name']."\");'><b class='user-name'>".$_SESSION['name']."</b></a>.");
        //}
        
        /*}
        exit();
    }*/

    /*if(substr( $text, 0, 1 ) === "/") {
      if(file_exists('./commands/'.str_replace('/', '', $text).'.php')) {
      if($_SESSION['name'] == 'neksodebe') {
          //sendmsg_as_server('[DEBUG] ./commands/'.str_replace('/', '', $text).'.php');
          
              
              include './commands/'.str_replace('/', '', $text).'.php';
              if (!function_exists('command')) {
                //sendmsg_as_server('command(); does not exist');
                } else {
                  //sendmsg_as_server('command(); exists');
                  $cmdargs = explode(" ", $text_s2);
                  $cmdargs = array_values($cmdargs);
                  $cmdargs = array_shift($cmdargs);
                  command($cmdargs);
                }
              
                //file_put_contents("./log.html", "");

                die();
          }
      }
  }

    foreach ($empty_chars as $key => $value) {
        if($text == $value) exit();
      }

      


    
    
    
      // Convert JSON string to Array
  
  //print_r($data);        // Dump all data of the Array
  //echo $blocked_words[0]["from"]; // Access Array data
  $text_s2 = stripslashes(htmlspecialchars($text));
  if($censorship_enabled == 'true') {
 foreach ($blocked_words as $key => $value) {
    //echo $value["from"] . " to " . $value["to"] . "<br>";
 $text_s2 = str_replace(strtolower($value["from"]), strtolower(/*$value["to"]*//*str_repeat("*", strlen($value["to"]))), strtolower($text_s2));
  }
}

$exploded_text = explode(" ", $text_s2);
$exploded_text = array_values($exploded_text);
  foreach ($exploded_text as $key => $value) {
    //echo $value["from"] . " to " . $value["to"] . "<br>";
    if(substr( $value, 0, 1 ) === "@") {
      //sendmsg_as_server('found tag at index '.$key.' and value '.$value);
      $replacements = array($key => '<b class="user-name">'.$exploded_text[$key].'</b>');
      $exploded_text = array_replace($exploded_text, $replacements);
    }
  }

  $text_s2 = implode(" ", $exploded_text);


   foreach ($emojis as $key => $value) {
    //echo $value["from"] . " to " . $value["to"] . "<br>";
    $text_s2 = str_replace(':'.$value["code"].':', '<div class="emoji" style="vertical-align: middle;"><img class="emoji" src="'.$value["url"].'" alt="Emoji" /><span class="tooltiptext"><b>:'.$value["code"].':</b><br>'.$value["description"].'</span></div>', $text_s2);
  }

  foreach ($text_emojis as $key => $value) {
    //echo $value["from"] . " to " . $value["to"] . "<br>";
    $text_s2 = str_replace(':'.$value["code"].':', $value["emoji"], $text_s2);
  }

  if($_SESSION['name'] == "neksodebe" || $_SESSION['name'] == "mega") {
  if(str_contains($text_s2, "TEST")) {
    //$text_s2 = 'TEST<span class="tooltiptext"><b>'.$_SESSION['name'].'</b></span>';
    $text_s2 = str_replace('TEST', "<img src='./assets/img/xm_logo_full.png' width='100'></img>", $text_s2);
  }
}


$exploded_text = explode(" ", $text_s2);
$exploded_text = array_values($exploded_text);
  foreach ($exploded_text as $key => $value) {
    //echo $value["from"] . " to " . $value["to"] . "<br>";
    if(substr( $value, 0, 7 ) === "http://" || substr( $value, 0, 8 ) === "https://") {
      //sendmsg_as_server('found tag at index '.$key.' and value '.$value);
      $replacements = array($key => '<a class="weblink" href="javascript:open_webpage_insecure(\''.$value.'\');">'.$value.'</a>');
      $exploded_text = array_replace($exploded_text, $replacements);
    }
  }

  $text_s2 = implode(" ", $exploded_text);


// https://0a67ccc25b7e.ngrok.io/assets/img/logo_xm.png
//$emoji_example = '<div class="emoji"><img class="emoji" src="https://0a67ccc25b7e.ngrok.io/assets/img/logo_xm.png" alt="Emoji" /><span class="tooltiptext"><b>:logo:</b><br>The app logo</span></div>';
    //$text_s2 = str_replace(':logo:', $emoji_example, strtolower($text_s2));
  
  //$text_s2 = str_replace('@neksodebe', '<b class="owner">@neksodebe</b>', $text_s2);
  //$text_s2 = str_replace('@mega', '<b class="mega">@mega</b>', $text_s2);
 /* if($_SESSION['name'] == 'neksodebe') {
    $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <a href='javascript:insert_mention(\"".$_SESSION['name']."\");'><b class='owner'>".$_SESSION['name']."</b></a> ".$text_s2."<br></div>";   
  } elseif($_SESSION['name'] == 'mega') {
    $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <a href='javascript:insert_mention(\"".$_SESSION['name']."\");'><b class='mega'>".$_SESSION['name']."</b></a> ".$text_s2."<br></div>";
  } else {*/
    //$text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <a class='taglink' href='javascript:insert_mention(\"".$_SESSION['name']."\");'><b class='user-name'>".$_SESSION['name']."</b></a> ".$text_s2."<br></div>";   
  //}
   /* if($silenced == 'false' || $_SESSION['name'] == 'neksodebe' || $_SESSION['name'] == 'mega') {
    file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
    }*/


    if($_SESSION['banned'] == 1) die();

    if (!function_exists('str_contains')) {
      function str_contains(string $haystack, string $needle): bool
      {
          return '' === $needle || false !== strpos($haystack, $needle);
      }
  }


  $text = $_POST['text'];
  if(strlen($text) > 2000) die();

include "paths.php";
include_once $GLOBALS['paths']->include.$GLOBALS['func']->dbconnect;
$mysqli = new mysqli("localhost", $username, $password, $database); 

if($text == "" || !isset($text) || $text == " ") die();

echo "<script>
  var audio = new Audio('./assets/sound/send.mp3');
  audio.play();
</script>";

/*if(substr( $text, 0, 6 ) === "/clear" and $_SESSION['id'] == 1) {
  mysqli_query($mysqli, "DELETE FROM messages");
  die();
} else if(substr( $text, 0, 5 ) === "/edit" and $_SESSION['id'] == 1) {
  $explodedtext = explode(" ", $text);

  mysqli_query($mysqli, "UPDATE messages SET text='".$explodedtext[2]."', edited=1 WHERE id=".$explodedtext[1]);
  die();
}*/


/*if($_SESSION['name'] == 'mega') {
  mysqli_query($mysqli, "INSERT INTO messages VALUES (NULL, '".$text."', 0, 2, '".date("h:i:s")."', 0, 2)");
} else if($_SESSION['name'] == 'neksodebe') {
  mysqli_query($mysqli, "INSERT INTO messages VALUES (NULL, '".$text."', 0, 1, '".date("h:i:s")."', 0, 1)");
} else {*/
  $filteredtext = str_replace("<", "&lt;", $text);
  $filteredtext = str_replace(">", "&gt;", $filteredtext);
  //$filteredtext = str_replace("\"", "&quot;", $filteredtext);
  if($_SESSION['channelID'] != "") {
    $stmt = $mysqli->prepare("INSERT INTO messages (id, text, edited, authorid, timestamp, hidden, recieverid) VALUES (NULL, ?, 0, ?, ?, 0, ?)");
    $stmt->bind_param('ssss', $filteredtext, $_SESSION['id'], date("h:i:s"), $_SESSION['channelID']); // 's' specifies the variable type => 'string'
   
    $stmt->execute();
   
    //$result = $stmt->get_result();

  //mysqli_query($mysqli, "INSERT INTO messages VALUES (NULL, '".$filteredtext."', 0, '".$_SESSION['id']."', '".date("h:i:s")."', 0, '".$_SESSION['channelID']."')");
  } else if($_SESSION['dmID'] != "") {

    $stmt = $mysqli->prepare("INSERT INTO messages (id, text, edited, authorid, timestamp, hidden, recieverid) VALUES (NULL, ?, 0, ?, ?, 0, ?)");
    $stmt->bind_param('ssss', $filteredtext, $_SESSION['id'], date("h:i:s"), $_SESSION['dmID']); // 's' specifies the variable type => 'string'
   
    $stmt->execute();

    //mysqli_query($mysqli, "INSERT INTO messages VALUES (NULL, '".$filteredtext."', 0, '".$_SESSION['id']."', '".date("h:i:s")."', 0, '".$_SESSION['dmID']."')");
  }
//}

include_once $GLOBALS['paths']->include.$GLOBALS['func']->formatMessageHTML;




}
?>