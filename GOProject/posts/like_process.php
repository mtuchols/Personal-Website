<?php

use classes\Like;

if ($_REQUEST['like_action'] === "remove") {
    try {
        $like = new Like($_REQUEST['post_id'], $GLOBALS['user']->getHandle());
        $like->delete();
        die("success");
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

try {
    $like = new Like();
    $like->setPostID($_REQUEST['post_id']);
    $like->setHandle($GLOBALS['user']->getHandle());
    $like->save();
    die("success");
} catch (Exception $e) {
    die($e->getMessage());
}

die("an unknown error occurred");

?>