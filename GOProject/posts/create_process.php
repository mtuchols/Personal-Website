<?php

require_once("classes/Post.php");

try {
    $post = new Post();

    $post->setHandle($GLOBALS['user']->getHandle());
    $post->setGeolocation($_REQUEST['lat'], $_REQUEST['lng']);
    $post->setContent($_REQUEST['content']);
    $post->save();

    echo "success";
} catch (Exception $e) {
    echo $e->getMessage();
}

?>