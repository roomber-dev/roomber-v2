

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomber</title>
</head>
<body>
    <h1>We're currently experiencing technical difficulties. Check back in a second</h1>
    <?php

if(isset($_GET['message'])) {
    printf("<h2>Error message: ".$_GET['message']."</h2>");
}

?>
</body>
</html>