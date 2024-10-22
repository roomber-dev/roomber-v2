<?php
function command()
{
    file_put_contents("log.html", "");
    sendmsg_as_server("The chat has been cleared by <a class='taglink' href='javascript:insert_mention(\"".$_SESSION['name']."\");'><b class='user-name'>" . $_SESSION['name'] . "</b></a>");
}
