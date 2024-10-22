<?php

    function command($arguments) {
        //if(!isset($arguments)) die();
        $message = join(" ", $arguments);
        $text_message = "<br><center><hr width='40%' /><br><div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='broadcast'>Broadcast</b> ".$message."<br></div><br><hr width='40%' /></center><br>";
        file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
    }
        