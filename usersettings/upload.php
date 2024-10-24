<?php
session_start();

if(!isset($_SESSION['id'])) {
  header("Location: ../login.php");
  die();
}

function logjs($message) {
  echo '<script>console.log("'.$message."');</script>";
}
$target_dir = "../avatars/";
//$filename = explode(".", basename($_FILES["file"]["name"]));
$target_file = $target_dir . $_SESSION['id'].".png";//.$filename[count($filename)-1];
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    logjs("File is an image - " . $check["mime"] . ".");
    $uploadOk = 1;
  } else {
    logjs("File is not an image.");
    $uploadOk = 0;
  }
}

// Check if file already exists
/*if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}*/

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  logjs("Sorry, your file is too large.");
  echo '<p>Sorry, your file is too large.</p>';
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "png") {
  logjs("Sorry, only PNG files are allowed.");
  echo '<p>Sorry, only PNG files are allowed.</p>';
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  logjs("Sorry, your file was not uploaded.");
  echo '<p>Sorry, your file was not uploaded.</p>';

  echo '<button onclick="goback();">Go back</button>';
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    //logjs("The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.");
    logjs("success");
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
  clearstatcache();

  echo '<script>setTimeout(() => { goback(); }, 1);</script>';
}
?>

<script>
  function goback() {
    window.location.replace("index.php");
  }
</script>