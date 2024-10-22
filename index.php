<?php
session_start();
include "paths.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}
if (isset($_GET['channelid'])) {
    $_SESSION['channelID'] = $_GET['channelid'];
} else {
    $_SESSION['channelID'] = "";
}

if (isset($_GET['serverid'])) {
    $_SESSION['serverID'] = $_GET['serverid'];
} else {
    $_SESSION['serverID'] = "";
}

if (isset($_GET['dmid'])) {
    $_SESSION['dmID'] = $_GET['dmid'];
} else {
    $_SESSION['dmID'] = "";
}



if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php"); //Redirect the user
}


foreach ($GLOBALS['func'] as $key => $value) {
    include_once $GLOBALS['paths']->include . $value;
}
/*include_once "func/random_id.php";
include_once "func/siteMeta.php";
include_once 'func/minify_css.php';
include_once "func/dbconnect.php";

include_once "func/formatMessageHTML.php";
include_once "func/loginForm.php";
include_once "func/registerForm.php";
include_once "func/siteMeta.php";
include_once "func/str_contains.php";
include_once "func/random_str.php";
include_once "func/minify_css.php";
include_once "func/random_id.php";
include_once "func/getUsernameHTML.php";*/


error_reporting(0);
ini_set('display_errors', 0);
$mysqli = new mysqli("localhost", $username, $password, $database);

if ($mysqli->connect_errno) {
    header('Location: problems.php?message=' . urlencode($mysqli->connect_error));
    exit();
}

/* check if server is alive */
if ($mysqli->ping()) {
    //printf ("Our connection is ok!\n");
} else {
    header('Location: problems.php?message=' . urlencode($mysqli->error));
    exit();
}

//echo random_id(20);
//echo '<style>' . minify_css(file_get_contents('oldstyle.css')) . '</style>';
echo '<style>' . minify_css(file_get_contents('newstyle.css')) . '</style>';

function logjs($message)
{
    echo '<script>console.log("' . $message . '");</script>';
}

logjs($GLOBALS['globalPath']);

echo '<script>var banned = false;</script>';

if ($result = $mysqli->query("SELECT banned, banreason FROM users WHERE id='" . $_SESSION['id'] . "'")) {
    $row = $result->fetch_assoc();

    $_SESSION['banned'] = $row['banned'];
    $_SESSION['banreason'] = $row['banreason'];
}

if ($_SESSION['banned'] == 1) {
    /*echo "<div class='banned'><center><h1>Banned</h1>
      <br>
      <h2>You have been banned for:</h2>
      <h3>".$_SESSION['banreason']."</h3>
      </center></div>
      ";*/

    echo '<script>var banned = true;</script>';
}

?>

<html>

<head>
    <title>Roomber</title>
    <meta property="theme-color" content="<?php echo $themeColor; ?>">
    <meta property="og:title" content="<?php echo $siteTitle; ?>">
    <meta property="og:description" content="<?php echo $siteDescription; ?>">
    <meta property="og:url" content="<?php echo $siteUrl; ?>">
    <meta property="og:image" content="<?php echo $siteImage; ?>">

    <style>

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script>
        // jQuery Document
        $(document).ready(function() {


            var modal = document.getElementById("insecurelinkpopup");


            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            var noButton = document.getElementsByClassName("cancelButton-insecurewebpage-open")[0];
            var yesButton = document.getElementsByClassName("yesButton-insecurewebpage-open")[0];
            noButton.onclick = function() {
                modal.style.display = "none";
            }
            yesButton.onclick = function() {
                window.open(insecurelink_url, '_blank').focus();
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            $("#submitmsg").click(function() {


                /* if (eastereggenabled == true) {
                     var audio = new Audio('./assets/sound/boom.mp3');
                     audio.play();
                 } else {
                     var audio = new Audio('./assets/sound/send.mp3');
                     audio.play();
                 }*/
                var clientmsg = $("#usermsg").val();

                if (clientmsg != "" && clientmsg != " ") {
                    $.post("post.php", {
                        text: clientmsg,
                        style: false
                    }, function(data, status, jqXHR) {
                        //console.log(data);
                        if (data != "") {
                            $("#sendSound").html(data);
                        }
                    });
                    $("#usermsg").val("");


                }
                return false;
            });
        });

        // ----------------------------------------- LOADING THE LOG MESSAGES ----------------------------------------------


        setTimeout(() => {
            if (banned == true) {
                $("#chat").html('<img src="../assets/img/banned.jpeg" class="banned">');
            } else {
                setInterval(loadLog, 1000);
            }
        }, 500);


        var active = true;

        function sendnotification(title, text) {
            Notification.requestPermission().then(function(result) {
                if (result == "granted") {
                    var img = 'assets/img/roomber-logo.png';
                    var notification = new Notification("New message!", {
                        body: text,
                        icon: img
                    });
                } else {
                    console.log('[NOTIFICATION] Attempted to send notification, failed');
                }

            });

        }

        function askforpermission() {
            Notification.requestPermission().then(function(result) {
                console.log(result);
            });
        }

        askforpermission();

        $(window).blur(function() {
            active = false;
            console.log('[STATUSUPDATE] ATTEMPT TO SET TO IDLE..')
            $.post("updateStatus.php", {
                status: "Idle"
            }, function(data) {
                console.log('[STATUSUPDATE] IDLE SET RESPONSE: ' + data);
            })
        });
        $(window).focus(function() {
            active = true;
            console.log('[STATUSUPDATE] ATTEMPT TO SET TO ONLINE..')
            $.post("updateStatus.php", {
                status: "Online"
            }, function(data) {
                console.log('[STATUSUPDATE] ONLINE SET RESPONSE: ' + data);
            })
        });

        loadLog();

        function loadLog() {
            try {
                var oldscrollHeight = $("body")[0].scrollHeight - 20; //Scroll height before the request
            } catch (e) {
                console.error(e);
            }
            var oldchat = $("#chat").html();
            //var newurl = document.getElementById("chat").setAttribute("src", "../refresh.php?style=true&newstyle=true");
            $.ajax({
                url: "refresh.php",
                cache: false,
                success: function(html) {
                    $("#chat").html(html + "<br><br><br><br>"); //Insert chat log into the #chatbox div
                    /*if (oldchat != $("#chat").html()) {
                        if(active == false) {
                        sendnotification("Roomber", "New message!");
                        }
                    }*/

                    console.log('[DEBUG] refreshed chat');
                    // Auto-scroll           
                    var newscrollHeight = $("body")[0].scrollHeight - 20; //Scroll height after the request
                    if (newscrollHeight > oldscrollHeight) {
                        $("body").animate({
                            scrollTop: newscrollHeight
                        }, 'normal'); //Autoscroll to bottom of div
                    }
                }
            });
        }

        function open_webpage_insecure(url) {
            var modal = document.getElementById("insecurelinkpopup");
            //if(confirm("Are you sure you want to open '"+url+"'? This site may be dangerous.")) {
            insecurelink_url = url;
            modal.style.display = "block";

            //
            // }
        }

        (function(a) {
            (jQuery.browser = jQuery.browser || {}).mobile = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))
        })(navigator.userAgent || navigator.vendor || window.opera);
        if (jQuery.browser.mobile) {
            window.location.replace("notcompatible");
        } else {
            console.log("[DEBUG] user is not on mobile device,", jQuery.browser.mobile);
        }

        let i = 0;
        let intrv = setInterval(() => {
            if (i > 2) return clearInterval(this);
            warningMessageConsole();
            i++
        }, 500);


        function warningMessageConsole() {


            console.log(
                "%cStop!",
                "color:red;font-family:system-ui;font-size:4rem;-webkit-text-stroke: 1px black;font-weight:bold"
            );
            console.log(
                "%cIf someone told you to copy paste something here, there's a 101% chance you're being scammed.\nLetting those dirty hackers access your account is not what you want, right?",
                "color:white;font-family:system-ui;font-size:1rem;-webkit-text-stroke: 0.5px black;font-weight:bold"
            )
        }

        /*if (document.addEventListener) {
            document.addEventListener('contextmenu', function(e) {
                alert("You've tried to open context menu"); //here you draw your own menu
                e.preventDefault();
            }, false);
        } else {
            document.attachEvent('oncontextmenu', function() {
                alert("You've tried to open context menu");
                window.event.returnValue = false;
            });
        }*/
    </script>



</head>

<body class="<?php
                if (isset($_SESSION['id'])) {
                    $result = $mysqli->query("SELECT style FROM users WHERE id = '" . $_SESSION['id'] . "'");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "theme-" . $row['style'];
                    }
                } else {
                    echo "theme-dark";
                }
                ?>">

    <div id="insecurelinkpopup" class="insecurelinkpopup">

        <!-- Modal content -->
        <div class="insecurelinkpopup-content">
            <span class="close">&times;</span>
            <p style='text-align: center;   font-size: large;'>Warning, this site might be malicious. Do you still want to continue?</p><br><br>
            <div class='buttonPlacementDiv-insecurelinkpopup-content'><a class="yesButton-insecurewebpage-open">Yes</a><a class="cancelButton-insecurewebpage-open">Cancel</a></div>



        </div>
    </div>
    <p class="settingsButton"><a id="settings" href="usersettings/">Settings</a></p>
    <p class="logout"><a id="exit" href="?logout">Log out</a></p>
    <!--<div class='overlay'>Selectable text</div>-->
    <div id='nav'>
        <ul id="servers">
            <li><a href="<?php if (isset($_GET['dm'])) {
                                echo 'index.php';
                            } else {
                                echo '?dm=true';
                            } ?>"> <img src='../assets/img/roomber-logo.png' width=60 style='user-select: none;'></a></li>
            <li class='vl'></li>
            <?php
            if (isset($_GET['dm']) && $_GET['dm'] == true) {
                if ($result = $mysqli->query("SELECT user1, user2 FROM friendship WHERE user1='" . $_SESSION['id'] . "' OR user2='" . $_SESSION['id'] . "'")) {
                    while ($row = $result->fetch_assoc()) {
                        if ($row['user1'] != $_SESSION['id']) {
                            $result2 = $mysqli->query("SELECT username, id FROM users WHERE id = '" . $row['user1'] . "'");
                        } else {
                            $result2 = $mysqli->query("SELECT username, id FROM users WHERE id = '" . $row['user2'] . "'");
                        }
                        //if ($result->num_rows > 0) {
                        while ($row = $result2->fetch_assoc()) {
                            //echo $row['name'];
                            $avatar = file_exists("avatars/" . $row['id'] . ".png") == true ? "avatars/" . $row['id'] . ".png" : "avatars/default/default.png";


                            if (isset($_GET['dmid']) && $_GET['dmid'] == $row['id']) {

                                echo '<li><a href=\'#\'><img class="servericon activeServer" src="' . $avatar . '"></a></li>';
                            } else {
                                echo '<li><a href=\'?dm=true&dmid=' . $row['id'] . '\'><img class="servericon" src="' . $avatar . '" width=60></a></li>';
                            }
                        }
                    }
                }
                $result->free();
            } else {
                if ($result = $mysqli->query("SELECT serverid FROM user_server_relation WHERE userid='" . $_SESSION['id'] . "'")) {
                    while ($row = $result->fetch_assoc()) {
                        $result2 = $mysqli->query("SELECT name, pictureURL, id FROM servers WHERE id = '" . $row['serverid'] . "'");
                        //if ($result->num_rows > 0) {
                        while ($row = $result2->fetch_assoc()) {
                            //echo $row['name'];
                            if (isset($_GET['serverid']) && $_GET['serverid'] == $row['id']) {

                                echo '<li><a href=\'#\'><img class="servericon activeServer" src="' . $row['pictureURL'] . '"></a></li>';
                            } else {
                                echo '<li><a href=\'?serverid=' . $row['id'] . '\'><img src="' . $row['pictureURL'] . '" width=60 class="servericon"></a></li>';
                            }
                        }
                    }
                }
                $result->free();
            }
            ?>
        </ul>
        <ul id="channels">
            <?php
            if (isset($_GET['dm'])) {
                $userList = array();
                if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg, id FROM users WHERE id <>'" . $_SESSION['id'] . "' AND hidden=0")) {
                    while ($row = $result->fetch_assoc()) {
                        $result2 = $mysqli->query("SELECT id FROM friendship WHERE (user1 = '" . $row['id'] . "' OR user2 = '" . $row['id'] . "') AND (user1 = '" . $_SESSION['id'] . "' OR user2='" . $_SESSION['id'] . "')");
                        if ($result2->num_rows > 0) {
                            $fren = " friendAvatar";
                            $frenInt = 1;
                        } else {
                            $fren = "";
                            $frenInt = 0;
                        }

                        $myObj = new stdClass();
                        $myObj->id = $row['id'];
                        $myObj->username = $row['username'];
                        $myObj->fren = $fren;
                        $myObj->frenInt = $frenInt;

                        array_push($userList, $myObj);
                    }

                    //arsort($userList, 1);
                    foreach ($userList as $key => $value) {

                        $filteredname = str_replace("<", "&lt;", $value->username);
                        $filteredname = str_replace(">", "&gt;", $filteredname);
                        $avatar = file_exists("avatars/" . $value->id . ".png") == true ? "avatars/" . $value->id . ".png" : "avatars/default/default.png";

                        if (isset($_GET['dmID']) && $_GET['dmID'] == $value->id) { // <div class="selectedChannel_top"></div>
                            echo "  <div class='selectedChannel_top'></div><span class='avatarname'>
                        <img src='" . $avatar . "' class='avatar" . $value->fren . "'>
                        " . getUsernameHTML($value->id, $mysqli) . "
                        </span><div class='selectedChannel_bottom'></div><br>";
                        } else {
                            echo "  <span class='avatarname'>
                        <img src='" . $avatar . "' class='avatar" . $value->fren . "'>
                        " . getUsernameHTML($value->id, $mysqli) . "
                        </span><br>";
                        }
                    }
                }
                $result->free();
            } else if (isset($_GET['serverid'])) {
                $result = $mysqli->query("SELECT name FROM servers WHERE id = '" . $_GET['serverid'] . "'");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo '<li>' . $row['name'] . '</li><hr style="border: 2px solid #a7a7a7;">';

                    if ($result = $mysqli->query("SELECT name, id FROM channels WHERE serverid='" . $_GET['serverid'] . "' ORDER BY channelOrder DESC")) {
                        while ($row = $result->fetch_assoc()) {
                            if (isset($_GET['channelid']) && $_GET['channelid'] == $row['id']) {
                                echo '<li><div class="selectedChannel_top"></div><a href=\'?serverid=' . $_GET['serverid'] . '&channelid=' . $row['id'] . '\'>#' . $row['name'] . '</a><div class="selectedChannel_bottom"></div></li>';
                            } else {
                                echo '<li><a href=\'?serverid=' . $_GET['serverid'] . '&channelid=' . $row['id'] . '\'>#' . $row['name'] . '</a></li>';
                            }
                        }
                    }
                    $result->free();
                }
            } else {
                echo '<li>No channels</li>';
            }
            ?>
        </ul>
    </div>
    <!--<iframe src="../refresh.php?style=true&newstyle=true" id='chat'>Your browser doesn't support iFrames.</iframe>-->


    <div id='chat'>
        <?php

        getChatMessages($mysqli);

        ?>
    </div>

    <div id='messagebox'>
        <form name="message" action="#">
            <input name="usermsg" type="text" id="usermsg" placeholder="Message" autocomplete="off" />
            <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
        </form>
    </div>

</body>

</html>