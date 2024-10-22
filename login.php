<?php
session_start();

if (isset($_SESSION['id'])) {
    header("Location: index.php");
    die();
}

include "paths.php";


include $GLOBALS['paths']->include.$GLOBALS['func']->dbconnect;
include $GLOBALS['paths']->include.$GLOBALS['func']->siteMeta;
include_once $GLOBALS['paths']->include.$GLOBALS['func']->minify_css;
echo '<style>' . minify_css(file_get_contents('newstyle.css')) . '</style>';

//echo random_id(20);
//echo '<style>' . minify_css(file_get_contents('oldstyle.css')) . '</style>';

function showError($message)
{
    echo '<center><span class="error">' . $message . '</span></center>';
}

$mysqli = new mysqli("localhost", $username, $password, $database);

if (isset($_POST['name'])) {
    if ($_POST['name'] != "" && $_POST['password'] != "") {




        $result = $mysqli->query("SELECT email, password, id, token, banned, banreason FROM users WHERE email = '" . $_POST['name'] . "'");
        if ($result->num_rows == 0) {
            // row not found, do stuff...
            echo '<center><span class="error">E-mail or password incorrect.</span></center>';
        } else {
            // do other stuff...
            //$userid = 0;
            //$usertoken = "";
            //if ($result = $mysqli->query("SELECT id, token FROM users WHERE email = '" . password_hash($_POST['name'], PASSWORD_DEFAULT) . "' AND password='" . password_hash($_POST['password'], PASSWORD_DEFAULT) . "'")) {


            //while ($row = $result->fetch_assoc()) {
            $row = $result->fetch_assoc();

            $hash = $row['password'];
            $hash = substr($hash, 0, 60);
            function console_log($message)
            {
                echo "<script>console.log('$message')</script>";
            }
            /*echo "POSTED PASS: ".$_POST['password'];
            echo "<br>DATABASE PASS: ".$hash."<br>";
            echo 'TEST: $2y$10$BiJxI4NoiMw5toTfcDv9C.vlnpkOoGHhUUDRNUB/co8tzAQRZxfni<br>';
            echo password_verify($_POST['password'], $row['password']) ? 'true' : 'false' . "<br>";
            echo  $hash === '$2y$10$BiJxI4NoiMw5toTfcDv9C.vlnpkOoGHhUUDRNUB/co8tzAQRZxfni' ? 'true' : 'false' . "<br>";
            echo "CUSTOM TEST: ";
            echo password_verify('subscribeneksodebe', '$2y$10$BiJxI4NoiMw5toTfcDv9C.vlnpkOoGHhUUDRNUB/co8tzAQRZxfni') ? 'true' : 'false';
            echo "</p>";
            console_log("POSTED PASS: ".$_POST['password']);
            console_log("DATABASE PASS: ".$hash);
            console_log("PASSVERIFY: " . (password_verify($_POST['password'], $row['password']) ? 'true' : 'false'));
            console_log(password_verify('haslo123', password_hash('haslo123', PASSWORD_DEFAULT)));*/
            if (password_verify($_POST['password'], $row['password'])) {

                $userid = $row['id'];
                $usertoken = $row['token'];
                $_SESSION['banned'] = $row['banned'];
                $_SESSION['banreason'] = stripslashes(htmlspecialchars($row['banreason']));
                $_SESSION['id'] = stripslashes(htmlspecialchars($userid));
                echo '<script>window.location.replace("index.php");</script>';
            } else {
                echo '<center><span class="error">E-mail or password incorrect.</span></center>';
                //}
            }
            $result->free();
            //}


        }
    } else {
        echo '<center><span class="error">Please fill in the fields</span></center>';
    }
}
?>

<title>Roomber</title>
<meta property="theme-color" content="<?php echo $themeColor; ?>">
    <meta property="og:title" content="<?php echo $siteTitle; ?>">
    <meta property="og:description" content="<?php echo $siteDescription; ?>">
    <meta property="og:url" content="<?php echo $siteUrl; ?>">
    <meta property="og:image" content="<?php echo $siteImage; ?>">

<body class="theme-dark">

    <div id="loginform">
        <p>Please log in to continue!<br><a class="register" href="register.php">Don't have an account?</a></p>

        <form action="login.php" method="post">
            <input type="text" name="name" id="name" class="name" placeholder="Email" />
            <input type="password" name="password" id="password" class="password" placeholder="Password" />
            <input type="submit" name="enter" id="enter" value="Log in" class="enter" />
        </form>
    </div>
</body>