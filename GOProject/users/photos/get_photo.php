<?php

$photoURL = "includes/images/user-img-placeholder.png";
$type = "image/png";

if (
    array_key_exists('handle', $_REQUEST)
    && !empty($_REQUEST['handle'])
    && is_dir("includes/images/users/".$_REQUEST['handle']."/photo")
) {
    $files = scandir("includes/images/users/".$_REQUEST['handle']."/photo");
    $photoURL = $files[2];
    $extention = substr(strrchr($photoURL, "."), 1);
    switch ($extention) {
        case "gif": $type = "image/gif"; break;
        case "jpeg": 
        case "jpg": $type = "image/jpeg"; break;
    }
    $photoURL = "includes/images/users/".$_REQUEST['handle']."/photo/" . $photoURL;
}

header('Content-type:'.$type);
readfile($photoURL);
exit;

?>