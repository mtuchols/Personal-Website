<?php

use classes\db;

$date_of_event = $_REQUEST['date_of_event'];
$about = $_REQUEST['about'];
$title = $_REQUEST['title'];

$latitude = $_REQUEST['lat'];
$longitude = $_REQUEST['lng'];

$db = db::connect();
$sql = "INSERT INTO Events (`latitude`, `longitude`, `about`, `date_of_event`, `title`, `userhandle`)
    VALUES
    ($latitude, $longitude, '$about', '$date_of_event', '$title', '".$GLOBALS['user']->getHandle()."')";
if (!$db->query($sql)) {
    die("an unknown error occurred");
}

die("success");

?>