<?php
session_start();


if(isset($_SESSION['id'])) {
    unlink("../avatars/".$_SESSION['id'].".png");
}

?>