<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script>
            function clearmsg() {
                $.post("clearmsg.php", {

                }, function(html) {
                    //$("#test").html(html);
                });
        }
</script>

<?php

include_once "../func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);

session_start();
echo "<style>" . file_get_contents("style.css") . "</style>";
if(!isset($_SESSION['id'])) {
    echo "<script>alert('You have to be logged in to continue.');window.location = '../index.php'</script>";
} else {
    if ($result = $mysqli->query("SELECT admin, username, namecolor_bg, namecolor_fg FROM users WHERE id='" . $_SESSION['id'] . "'")) {
            $row = $result->fetch_assoc();

            if($row['admin'] == 0) {
                echo "<center><h2>You have to be an admin in order to access this site</h2></center>";
                die();
            }

            echo "<p class='welcome'>Welcome, <b class='user-name' style='color: ".$row['namecolor_fg']."; background-color: ".$row['namecolor_bg'].";'>".$row['username']."</b></p><br>";
    }
}

updateStyle($mysqli);

function updateStyle($mysqli) {
    $result = $mysqli->query("SELECT style FROM users WHERE id = '" . $_SESSION['id'] . "'");
    if ($result->num_rows > 0) {
        // row not found, do stuff...
        $row = $result->fetch_assoc();
        if ($row['style'] == "dark") {
            echo '<script>setTimeout(() => { $("p").css( "color", "white" ); $("label").css( "color", "white" ); }, 1);</script>';
        } else {
            echo '<script>setTimeout(() => { $("p").css( "color", "black" ); $("label").css( "color", "black" ); }, 1);</script>';
        }
    }
}

?>
<title>Roomber - Admin</title>
<body id='container' <?php 
if(isset($_SESSION['id'])) {
    $result = $mysqli->query("SELECT style FROM users WHERE id = '" . $_SESSION['id'] . "'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "style='background-image: url(\"../assets/img/bg_".$row['style'].".png\") '";
        echo "found, is".$row['style'];
    }  
} else {
    echo "style='background-image: url(\"../assets/img/bg_dark.png\") '";
}
?>>
    <br>
   <a target="_blank" href="mbu.php"><button>Manage Banned Users</button></a>
   &nbsp;&nbsp;
   <button>Manage App Configuration</button>
   &nbsp;&nbsp;
   <button onclick="clearmsg();">Clear all messages</button>
</body>