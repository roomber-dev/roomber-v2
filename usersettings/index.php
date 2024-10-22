<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<?php

session_start();

include_once "../func/dbconnect.php";
include_once "../func/minify_css.php";
$mysqli = new mysqli("localhost", $username, $password, $database);

//echo "<style>" . str_replace("\n", "", file_get_contents("../style.css")) . "</style>";
echo "<style>" . minify_css(file_get_contents("usersettings.css")) . "</style>";
if (!isset($_SESSION['id'])) die();

echo "<script>var userID = '" . $_SESSION['id'] . "';</script>";


function menu($mysqli)
{
    updateStyle($mysqli);
    if (isset($_GET['category'])) {
        echo '<div class="vertical-menu">';
        $category = $_GET['category'];
        switch ($category) {
            case "profile":
                echo '
                <a href="?category=myaccount">My account</a>
                <a href="#" class="active">Profile</a>
                <a href="?category=preferences">Preferences</a>
                </div><div id="content">';
                showProfile($_SESSION['id'], $mysqli);
                echo '</div>';
                break;
            case "preferences":
                echo '<a href="?category=myaccount">My account</a>
                <a href="?category=profile">Profile</a>
                <a href="#" class="active">Preferences</a>
                </div><div id="content">';
                showPreferences($_SESSION['id'], $mysqli);
                echo '</div>';
                break;

            case "myaccount":
                echo '<a href="#" class="active">My account</a>
                    <a href="?category=profile">Profile</a>
                    <a href="?category=preferences">Preferences</a>
                    </div><div id="content">';
                showMyAccount($_SESSION['id'], $mysqli);
                echo '</div>';
                break;
            default:
                echo "<script>window.location.replace('index.php?category=profile');</script>";
                break;
        }
    } else {
        echo "<script>window.location.replace('index.php?category=profile');</script>";
    }


    /* <p class="category">Name color</p>
      <div>
          <input type="color" id="bg" name="bg" value="#546e7a">
          <label for="bg">Background</label>
      </div>
      <div>
          <input type="color" id="fg" name="fg" value="#FFFFFF">
          <label for="fg">Foreground (Text)</label>
      </div> */
}



function showProfile($userid, $mysqli)
{

    $result = $mysqli->query("SELECT username, namecolor_bg, namecolor_bg2, namecolor_fg, xtra FROM users WHERE id = '" . $userid . "'");
    if ($result->num_rows > 0) {
        // row not found, do stuff...
        $row = $result->fetch_assoc();
        $avatar = file_exists("../avatars/" . $userid . ".png") == true ? "../avatars/" . $userid . ".png" : "../avatars/default/default.png";
        $xtrasecond = $row['namecolor_bg2'] == "" ? $row['namecolor_bg'] : $row['namecolor_bg2'];
        $xtrajs = $row['xtra'] == 1 ? '<script>const xtra = true</script>' : '<script>const xtra = false</script>';

        echo $xtrajs;
        $xtra = $row['xtra'] == 1 ? '<input type="color" id="bg" name="bg" value="' . $row['namecolor_bg'] . '">
        <label for="bg">Gradient Color #1</label><br>
        <input type="color" id="bg2" name="bg2" value="' . $xtrasecond . '">
        <label for="bg2">Gradient Color #2</label>&nbsp;<button class="buttonRemove" onclick="removeGradient();">Remove</button>' : '    <input type="color" id="bg" name="bg" value="' . $row['namecolor_bg'] . '">
        <label for="bg">Background</label>';

        $xtragradient = $row['xtra'] == 1 ? "background-image: linear-gradient(to right, " . $row['namecolor_bg'] . ", " . $xtrasecond . "  );" : "background-color: " . $row['namecolor_bg'];
        echo '<br><div class="stylishContainer"><br>


<div>
        ' . $xtra . '
</div>
<div>
    <input type="color" id="fg" name="fg" value="' . $row['namecolor_fg'] . '">
    <label for="fg">Foreground (Text)</label>
</div>



<div id="fren">
<div class="avata">
<div class="pfp-container">
<img class="pfp" src="' . $avatar . '">
<div class="cameraIcon"><img onclick="javascript:clickUpload();" src="../../assets/img/imageicon.png" class="iconCAM"></div>

</div>
</div>


<div class="tooltip">
<b class="user-name" style="color: ' . $row['namecolor_fg'] . '; ' . $xtragradient . '">' . $row['username'] . '</b>
<span class="tooltiptext">Double click to edit</span>
</div>
</div>
<p onclick="resetAvatar();" style="width: fit-content;">RESET AVATAR</a>

<form action="upload.php" method="post" enctype="multipart/form-data" class="hidden">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload" class="avatarFile hidden" accept=".png">
  <input type="submit" value="Upload Image" name="submit" id="sendButton" class="hidden">
</form></div>';
    }
}

function showPreferences($userid, $mysqli)
{
    echo '<br>';

    $result = $mysqli->query("SELECT style FROM users WHERE id = '" . $userid . "'");
    if ($result->num_rows > 0) {
        // row not found, do stuff...
        $row = $result->fetch_assoc();
        echo "<br><div class='stylishContainer'><p class='category' style='padding: 5px;'>Dark/Light mode</p>";
        if ($row['style'] == "dark") {
            //echo '<style>p { color: white; }</style>';
            echo '    <div id="modechanger">
            <label class="switch">
                <input type="checkbox" id="styleMode">
                <div class="slider"></div>
            </label>
        </div>';
        } else {
            // echo '<style>p { color: black; }</style>';
            echo '    <div id="modechanger">
                <label class="switch">
                    <input type="checkbox" checked id="styleMode" checked>
                    <div class="slider"></div>
                </label>
            </div>';
        }
        echo '</div>';
    }
}

function updateStyle($mysqli)
{
    $result = $mysqli->query("SELECT style FROM users WHERE id = '" . $_SESSION['id'] . "'");
    if ($result->num_rows > 0) {
        // row not found, do stuff...
        $row = $result->fetch_assoc();
        if ($row['style'] == "dark") {
            echo '<script>setTimeout(() => { $(".standoutText").css( "color", "white" ); }, 1);</script>';
            return 'white';
        } else {
            echo '<script>setTimeout(() => { $(".standoutText").css( "color", "black" ); }, 1);</script>';
            return 'black';
        }
    }
}

function obfuscate_email($email)
{
    $em   = explode("@", $email);
    $em[0] = str_repeat("*", strlen($em[0]));

    return implode("@", $em);
}

function showMyAccount($userid, $mysqli)
{
    $result = $mysqli->query("SELECT email FROM users WHERE id = '" . $userid . "'");
    if ($result->num_rows > 0) {
        // row not found, do stuff...
        $row = $result->fetch_assoc();

        echo '<script>var email = "' . $row['email'] . '"; var cemail = "' . obfuscate_email($row['email']) . '";</script>';
        echo '<br>

        <div class="stylishContainer extraWidth">
        <div id="contentInside">
        <br>
        <div class="userDetails">
        <span id="email">
        ' . obfuscate_email($row['email']) . '
        </span>
        
        &nbsp;&nbsp;
        
        <button class="reveal" id="revealEmail" onclick="revealEmail();">
        Show
        </button>
        <button class="reveal" onclick="changeEmail();">
        Edit
        </button>
        </div>
        <br>
        <button class="changePass" id="changePass" onclick="changePass();">
        Change password
        </button>
        </div>
        </div>


        <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Change E-mail</h2>
            </div>
            <div class="modal-body">
                
                <input type="text" name="email" class="inputStyle" id="newEmail" placeholder="New E-mail"><br>
                <input type="password" name="password" class="inputStyle moveTop" id="password" placeholder="Current password">
                <div class="modal-inside">
                <button id="changeEmailButton" class="moveTop">Done</button>
                </div>
            </div>
        </div>

    </div>

        <script>
        // Get the modal
        var modal = document.getElementById("myModal");


        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        function changeEmail() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        $( "#changeEmailButton" ).click(function() {
            var email = $("#newEmail").val();
            var pass = $("#password").val();
            $.post("changeEmail.php", {
                email: email,
                pass: pass
            }, function() {
                location.reload();
            });
          });

        </script>
        
        ';
    }
}

?>


<html>

<head>
    <title>Roomber</title>

    <script>
        function revealEmail() {
            document.getElementById("email").innerHTML = email;
            $("#revealEmail").attr("onclick", "unrevealEmail();");
            $("#revealEmail").html("Hide");
        }

        function unrevealEmail() {
            document.getElementById("email").innerHTML = cemail;
            $("#revealEmail").attr("onclick", "revealEmail();");
            $("#revealEmail").html("Show");
        }
        document.addEventListener('DOMContentLoaded', function() {




            /* var checkbox = document.querySelector('#styleMode');
             console.log(checkbox);
             if (checkbox != undefined && checkbox != null) {
                 checkbox.addEventListener('change', function() {
                     if (checkbox.checked) {
                         // do this
                         document.body.style.backgroundImage = "url('../../assets/img/bg_light.png')";
                         $("p").css("color", "black");
                         $("label").css("color", "black");

                         $.post("changeStyleMode.php", {
                             style: 'light'
                         });
                     } else {
                         // do that
                         document.body.style.backgroundImage = "url('../../assets/img/bg_dark.png')";
                         $("p").css("color", "white");
                         $("label").css("color", "white");
                         $.post("changeStyleMode.php", {
                             style: 'dark'
                         });
                     }
                 });
             }*/
            $('#styleMode').change(
                function() {
                    if ($(this).is(':checked')) {
                        // do this
                        document.body.style.backgroundImage = "url('../../assets/img/bg_light.png')";
                        $(".standoutText").css("color", "black");

                        $.post("changeStyleMode.php", {
                            style: 'light'
                        }, function(data) {
                            console.log("[DEBUG] STYLEMODE CHANGE RESPONSE:", data)
                        });
                    } else if (!$(this).is(':checked')) {
                        // do that
                        document.body.style.backgroundImage = "url('../../assets/img/bg_dark.png')";
                        $(".standoutText").css("color", "white");
                        $.post("changeStyleMode.php", {
                            style: 'dark'
                        }, function(data) {
                            console.log("[DEBUG] STYLEMODE CHANGE RESPONSE:", data)
                        });
                    }
                });

            const selectElement = document.getElementById("fileToUpload");
            if (selectElement != null) {
                selectElement.addEventListener('change', (event) => {
                    document.getElementById("sendButton").click();
                });
            }

            const bgElement = document.getElementById("bg");

            if (bgElement != null) {
                bgElement.addEventListener('change', (event) => {
                    var bgValue = $("input#bg").val();
                    if (xtra) {
                        var bg2Value = $("input#bg2").val();

                        var gradient = "linear-gradient(to right, " + bgValue + ", " + bg2Value + "  )";
                        $("b.user-name").css("background-image", gradient);
                        $("b.user-name").css("background-color", "");
                    } else {
                        $("b.user-name").css("background-color", bgValue);
                    }

                    $.post("changeBG.php", {
                        bg: bgValue
                    }, function(data) {
                        console.log("[DEBUG] BG RESPONSE: ", data)
                    });
                });
            }



            const bg2Element = document.getElementById("bg2");
            if (bg2Element != null) {
                bg2Element.addEventListener('change', (event) => {
                    var bgValue = $("input#bg").val();
                    var bg2Value = $("input#bg2").val();

                    var gradient = "linear-gradient(to right, " + bgValue + ", " + bg2Value + "  )";
                    $("b.user-name").css("background-image", gradient);
                    $("b.user-name").css("background-color", "");

                    $.post("changeBG2.php", {
                        bg2: bg2Value
                    }, function(data) {
                        console.log("[DEBUG] BG2 RESPONSE: ", data)
                    });
                });
            }

            const fgElement = document.getElementById("fg");
            if (fgElement != null) {
                fgElement.addEventListener('change', (event) => {
                    var fgValue = $("input#fg").val();

                    $("b.user-name").css("color", fgValue);

                    $.post("changeFG.php", {
                        fg: fgValue
                    }, function(data) {
                        console.log("[DEBUG] FG RESPONSE: ", data)
                    });
                });
            }

            let changed = false;
            const usernameChange = document.querySelector('b.user-name');
            if (usernameChange != null) {
                usernameChange.addEventListener('dblclick', function(e) {
                    console.log("[DEBUG] double clicked username");
                    const item = document.querySelector("b.user-name");

                    if (changed == false) {
                        item.innerHTML = "<input type='text' name='username' class='username' id='username' value='" + item.innerHTML + "'>";
                        changed = true;
                    } else {
                        if ($("input.username").val() == "" || $("input.username").val() == " ") {
                            item.innerHTML = "<?php
                                                $result = $mysqli->query("SELECT username FROM users WHERE id = '" . $_SESSION['id'] . "'");
                                                if ($result->num_rows > 0) {
                                                    // row not found, do stuff...
                                                    $row = $result->fetch_assoc();

                                                    echo $row['username'];
                                                }
                                                ?>";
                        } else {
                            var nameValue = $("input.username").val();
                            item.innerHTML = $("input.username").val();
                            $.post("changeUsername.php", {
                                newname: nameValue
                            });
                        }
                        changed = false;
                    }
                });
            }
            /*$("input.username").on('keyup', function(e) {
                console.log(e.keyCode);
                if (e.key === 'Enter' || e.keyCode === 13) {
                    item.innerHTML = $("input.username").val();
                    changed = false;
                }
            });*/

            $('body').on("keyup", 'input.username', function(e) {
                const item = document.querySelector("b.user-name");
                if (e.key === 'Enter' || e.keyCode === 13) {
                    var nameValue = $("input.username").val();


                    if (nameValue.length < 2 || nameValue.length > 32) {
                        alert("The username has to be between 2-32 characters");
                    } else {
                        item.innerHTML = $("input.username").val();
                        $.post("changeUsername.php", {
                            newname: nameValue
                        }, function(data) {
                            if (data == "good") {
                                console.log("[DEBUG] Successfully set username");
                            } else {
                                console.log("[ERROR] Failed to set username");
                                alert('Failed to set username');
                            }
                        });
                    }
                    changed = false;
                }
            });



        });



        function clickUpload() {
            document.getElementById("fileToUpload").click();
        }

        function resetAvatar() {
            $.post("resetAvatar.php");
            $(".pfp").attr("src", "../avatars/default/default.png");
        }

        function removeGradient() {
            $.post("removeGradient.php", function() {
                location.reload();
            });

        }

        function uploadPFP() {


            var fd = new FormData();
            var files = $('#file')[0].files;


            // Check file selected or not
            if (files.length > 0) {
                fd.append('file', files[0]);
                $.ajax({
                    url: 'upload.php',
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                        if (response == "1") {
                            $("#iconCAM").attr("src", "../avatars/" + userID + ".png");
                            alert("Succeed");
                        } else {
                            alert("Failed to upload file");
                        }
                    },
                });
            } else {
                alert("Please select a file.");
            }
        }
    </script>
</head>

<body <?php
        $result = $mysqli->query("SELECT style FROM users WHERE id = '" . $_SESSION['id'] . "'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "style='background-image: url(\"../../assets/img/bg_" . $row['style'] . ".png\") '";
        }
        ?>>
<p class="logout"><a id="exit" href="../">Go back</a></p>

    <?php

    menu($mysqli);

    ?>



</body>

</html>