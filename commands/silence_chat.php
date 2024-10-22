<?php

    function command() {
    $silenced = file_get_contents('silenced.txt');
            if($silenced == 'false') {
file_put_contents("silenced.txt", "true");
        $cmd_message = "The chat has been silenced by <a class='taglink' href='javascript:insert_mention(\"".$_SESSION['name']."\");'><b class='user-name'>" . $_SESSION['name'] . "</b></a>";
            } else {
        file_put_contents("silenced.txt", "false");
        $cmd_message = "The chat has been unsilenced by <a class='taglink' href='javascript:insert_mention(\"".$_SESSION['name']."\");'><b class='user-name'>" . $_SESSION['name'] . "</b></a>";
    
            }
            sendmsg_as_server($cmd_message);
    }
        