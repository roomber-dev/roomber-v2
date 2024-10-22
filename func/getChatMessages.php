<?php

function getChatMessages($mysqli) {

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
  }