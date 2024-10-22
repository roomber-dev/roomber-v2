<head>
    <title>Roomber - MBU</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>


    <script>
        function banuser(id, unban) {
            var clientmsg = $("#" + id).val();
                $.post("banuser.php", {
                    id: id,
                    reason: clientmsg,
                    unban: unban
                }, function(html) {
                    //$("#test").html(html);
                });
                $("#" + id).val("");
        }
    </script>
</head>

<!--<div id='test'></div>-->

<?php

include_once "../func/dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database);

session_start();
echo "<style>" . file_get_contents("style.css") . "</style>";
if (!isset($_SESSION['id'])) {
    echo "<script>alert('You have to be logged in to continue.');window.location = '../index.php'</script>";
} else {
    if ($result = $mysqli->query("SELECT id, username, namecolor_fg, namecolor_bg FROM users")) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>&nbsp;&nbsp;&nbsp;
            <b class='user-name' style='color: " . $row['namecolor_fg'] . "; background-color: " . $row['namecolor_bg'] . ";'>" . $row['username'] . "</b>
            
            &nbsp;
            
            <input type='text' placeholder='Ban reason' id='" . $row['id'] . "' />&nbsp;
            
            <a href='javascript:banuser(\"" . $row['id'] . "\", false);'><button>Ban</button></a>
            
            &nbsp;<a href='javascript:banuser(\"" . $row['id'] . "\", true);'><button>Unban</button></a>
            
            </p><br>";
        }
    }
}

?>

<body id="container">

</body>