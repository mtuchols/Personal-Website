<?php

require_once("classes/Account.php");

$GLOBALS['user'] = false;
if (array_key_exists('user', $_SESSION) && is_string($_SESSION['user']) && strlen($_SESSION['user']) > 0) {
    $GLOBALS['user'] = new Account($_SESSION['user']);
}
$action = (array_key_exists('action', $_REQUEST) ? $_REQUEST['action'] : "");
$subaction = (array_key_exists('subaction', $_REQUEST) ? $_REQUEST['subaction'] : "");

if (!$GLOBALS['user']) {
    switch ($action) {
        case "create_account":
            require("users/signin/create_account.php");
            break;
        case "signin_process":
            require("users/signin/signin_process.php");
            break;
        default:
            require("users/signin/signin.php");
    }
} else {
    switch ($action) {
        case "posts":
            switch ($subaction) {
                case "create":
                    require("posts/create_process.php");
                    break;
                case "details":
                    require("posts/details.php");
                    break;
                case "like":
                    require("posts/like_process.php");
                    break;
                case "get_data":
                    require("posts/get_data.php");
                    break;
            }
            break;
        case "events":
            switch ($subaction) {
                case "get_events":
                    require("events/get_events.php");
                    break;
                case "add_event":
                    require("events/add_event.php");
                    break;
            }
            break;
        case "account":
            switch ($subaction) {
                case "edit_process":
                    require("users/edit_process.php");
                    break;
                case "get_photo":
                    require("users/photos/get_photo.php");
                    break;
                case "save_photo":
                    require("users/photos/save_photo.php");
                    break;
                case "signout":
                    require("users/signin/signout.php");
                    break;
                default:
                    require("users/view_account.php");
            }
            break;
        default:
            require("map/map.php");
    }
}

?>