<?php

// var_dump($_FILES['cropped_image']);
// exit;

$account = $GLOBALS['user'];

$userDir = "includes/images/users/".$account->getHandle();
if (!is_dir($userDir)) {
    mkdir($userDir, 0777, true);
}

$imageDir = $userDir."/photo";
if (!is_dir($imageDir)) {
    mkdir($imageDir, 0777, true);
}

// $image = $_FILES['blob'];

$filePath = $imageDir . "/image";
switch ($_FILES['cropped_image']['type']) {
    case "image/png":
        $filePath .= ".png";
        break;
    case "image/jpeg":
    case "image/jpg":
        $filePath .= ".jpg";
        break;
}

if (file_put_contents($filePath, file_get_contents($_FILES['cropped_image']['tmp_name']))) {
    die("success");
}

die("an unknown error occurred");

?>