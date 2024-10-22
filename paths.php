<?php
$globalPath = "http://" . $_SERVER['SERVER_NAME'] . "/";
$paths = new stdClass();

$paths->include = "func/";
$paths->assets = "assets/";
$paths->assetsIMG = "assets/img";
$paths->assetsJS = "assets/js";
$paths->assetsMP3 = "assets/sound";
$paths->avatars = "avatars/";
$paths->errHTTP = "err/";
$paths->old = "old/";
$paths->new = "new/";
$paths->beta = "beta/";
$paths->test = "test/";

$func = new stdClass();


$func->checkLoginDetails = "checkLoginDetails.php";
$func->dbconnect = "dbconnect.php";
$func->formatMessageHTML = "formatMessageHTML.php";
$func->generateRandomColor = "generateRandomColor.php";
$func->getChatMessages = "getChatMessages.php";
$func->getFormattedUsername = "getFormattedUsername.php";
$func->getUsernameHTML = "getUsernameHTML.php";
$func->loginForm = "loginForm.php";
$func->minify_css = "minify_css.php";
$func->random_id = "random_id.php";
$func->random_str = "random_str.php";
$func->registerForm = "registerForm.php";
$func->siteMeta = "siteMeta.php";
$func->sql_query = "sql_query.php";
$func->str_contains = "str_contains.php";




$GLOBALS['func'] = $func;
$GLOBALS['paths'] = $paths;
$GLOBALS['globalPath'] = $globalPath;



/*foreach ($GLOBALS as $key => $value) {
    echo "<br>" . $key;
}*/

