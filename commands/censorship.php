<?php

    function command() {
    $censorship_enabled = file_get_contents('censorship_enabled.txt');
            if($censorship_enabled == 'false') {
file_put_contents("censorship_enabled.txt", "true");
        $cmd_message = "Censorship has been enabled by <a class='taglink' href='javascript:insert_mention(\"".$_SESSION['name']."\");'><b class='user-name'>" . $_SESSION['name'] . "</b></a>";
            } else {
        file_put_contents("censorship_enabled.txt", "false");
        $cmd_message = "Censorship has been disabled by <a class='taglink' href='javascript:insert_mention(\"".$_SESSION['name']."\");'><b class='user-name'>" . $_SESSION['name'] . "</b></a>";
    
            }
            sendmsg_as_server($cmd_message);
    }
        