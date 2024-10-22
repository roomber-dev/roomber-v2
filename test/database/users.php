<html>
<body>
<?php
ini_set("display_errors", 0);
if (!isset($_GET["pass"])) {
    echo "No pass.";
    die();
} else {
    if ($_GET["pass"] != "neks") {
        echo "Wrong pass";
        die();
    }
}

include_once "dbconnect.php";
$mysqli = new mysqli("localhost", $username, $password, $database); 
$query = file_get_contents("query.sql");
// INSERT INTO `messages` (`id`, `text`, `authorid`, `timestamp`, `hidden`, `recieverid`) VALUES (NULL, 'AMRS_TEST_1', '1', '20:20:00', '0', '1');

echo '<table border="5" cellspacing="2" cellpadding="2"> 
      <tr> 
          <td> <font face="Arial">ID</font> </td> 
          <td> <font face="Arial">Username</font> </td> 
          <td> <font face="Arial">Namecolor (BG)</font> </td> 
          <td> <font face="Arial">Namecolor (FG)</font> </td> 
          <td> <font face="Arial">Email</font> </td> 
          <td> <font face="Arial">Password</font> </td> 
          <td> <font face="Arial">Token</font> </td> 
          <td> <font face="Arial">Admin?</font> </td> 
      </tr>';

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $field1name = $row["id"];
        $field2name = $row["username"];
        $field3name = $row["namecolor_bg"];
        $field4name = $row["namecolor_fg"];
        $field5name = "CENSORED";//$row["email"]; 
        $field6name = "CENSORED";//$row["password"]; 
        $field7name = "CENSORED";//$row["token"]; 
        $field8name = $row["admin"]; 

        echo '<tr> 
                  <td>'.$field1name.'</td> 
                  <td>'.$field2name.'</td> 
                  <td>'.$field3name.'</td> 
                  <td>'.$field4name.'</td> 
                  <td>'.$field5name.'</td> 
                  <td>'.$field6name.'</td> 
                  <td>'.$field7name.'</td> 
                  <td>'.$field8name.'</td> 
              </tr>';
    }
    $result->free();
} 
?>
</body>
</html>