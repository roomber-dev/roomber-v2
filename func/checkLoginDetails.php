<?php
function checkLoginDetails($mysqli) {
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
            $hash = substr( $hash, 0, 60 );
            function console_log($message) {
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
            if(password_verify($_POST['password'], $row['password'])) {
                
                $userid = $row['id'];
                $usertoken = $row['token'];
                $_SESSION['banned'] = $row['banned'];
                $_SESSION['banreason'] = stripslashes(htmlspecialchars($row['banreason']));
                $_SESSION['id'] = stripslashes(htmlspecialchars($userid));
            } else {
                echo '<center><span class="error">E-mail or password incorrect.</span></center>';
        //}
            }
        $result->free();
    //}

   
}
}