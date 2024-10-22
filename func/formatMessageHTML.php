<?php

function formatMessageHTML($text, $edited, $authorid, $timestamp, $hidden, $mysqli)
{

  $messageid = "";
    $html_message = "";
    if ($hidden == 1) return;
    if (!isset($text) || !isset($authorid) || !isset($timestamp)) { // $text, $edited, $authorid, $timestamp, $hidden
      $authorid = "5zrWasq4YxTKwvntj9UO";
      $text = "Internal Error";
      $timestamp = date("h:i");
    }

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
  if ($result = $mysqli->query("SELECT * FROM emoji")) {
    while ($row = $result->fetch_assoc()) {

      $text = str_replace(':'.$row["code"].':', '<div class="emoji" style="vertical-align: middle;"><img class="emoji" src="'.$row["url"].'" alt="Emoji" /><span class="tooltiptext"><b>:'.$row["code"].':</b><br>'.$row["description"].'</span></div>', $text);

                  
        }
        
    }
    $result->free();

    $stmt = $mysqli->prepare('SELECT username, id, namecolor_fg, namecolor_bg, namecolor_bg2, xtra FROM users WHERE hidden=0');
    //$stmt->bind_param('s', $name); // 's' specifies the variable type => 'string'
   
    $stmt->execute();
   
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        // Do something with $row
        $xtrasecond = $row['namecolor_bg2'] == "" ? $row['namecolor_bg'] : $row['namecolor_bg2'];
        $xtragradient = $row['xtra'] == 1 ? "background-image: linear-gradient(to right, " . $row['namecolor_bg'] . ", " . $xtrasecond . "  );" : "background-color: " . $row['namecolor_bg'];
        $text = str_replace("@".$row['username'], "<a class='mention' style='color: ".$row['namecolor_fg']."; ".$xtragradient."' user-id='".$row['id']."' href='javascript:openProfile(\"".$row['id']."\");'>@".$row['username']."</a>", $text);
    }
    $text = chunk_split($text, 250, "\r\n");
  


    if(!isset($timestamp)) {
      $timestamp = date("h:i:s");
    }
    
    if ($result = $mysqli->query("SELECT id FROM messages ORDER BY id DESC LIMIT 1")) {
      while ($row = $result->fetch_assoc()) {
          global $messageid;
          // last available id
        $messageid = "not yet";

    if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg, namecolor_bg2, xtra, status FROM users WHERE id='" . $authorid . "'")) {
        while ($row = $result->fetch_assoc()) {
            $tms = explode(":", $timestamp);
            unset($tms[2]);
            $filteredname = str_replace("<", "&lt;", $row['username']);
            $filteredname = str_replace(">", "&gt;", $filteredname);
            $idle = $row['status'];
            $editedmessage = $edited==true ? " <span class='edited'>(edited)</span><br></div>" : "";
            $avatar = file_exists("avatars/".$authorid.".png")==true ? "avatars/".$authorid.".png" : "avatars/default/default.png";
            $yourmessage = "";//$_SESSION['id']==$authorid ? "style='float: right;'" : "";
            $xtrasecond = $row['namecolor_bg2'] == "" ? $row['namecolor_bg'] : $row['namecolor_bg2'];
            $xtragradient = $row['xtra'] == 1 ? "background-image: linear-gradient(to right, " . $row['namecolor_bg'] . ", " . $xtrasecond . "  );" : "background-color: ".$row['namecolor_bg'];
                $html_message = "<div class='message'>
                <span class='avatarname'>
                  <span class='avatarstatus'><img src='".$avatar."' class='avatar'><div class='statusdot ".$idle."'></div></span>
                  
                  <b class='user-name' style='color: " . $row["namecolor_fg"] . "; " . $xtragradient . "'>" . $filteredname . "</b>
                  </span>
                  <span class='chat-time'>" . join(":", $tms) . "</span>
                  <div class='msgln'>

                  ".$text.$editedmessage."

                  </div>
                  
                  <br></div>
                ";
                // $html_message = "<div class='msgln' ".$yourmessage."><p class='msgtext'><span class='chat-time'>" . join(":", $tms) . "</span> <a class='taglink' href='javascript:insert_mention(\"" . $filteredname . "\");'><b class='user-name' style='color: " . $row["namecolor_fg"] . "; background-color: " . $row["namecolor_bg"] . "'>" . $filteredname . "</b></a> " . $text . $editedmessage . "<br></p></div>";

            
        }
    }
  }
}
    

    return $html_message;
}