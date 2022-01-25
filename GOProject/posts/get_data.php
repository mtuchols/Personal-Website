<?php

require_once("classes/Post.php");
$posts = Post::getAll();

$postData = array();
foreach ($posts as $post) {
    $postData[] = array(
        'id' => $post->getID(),
        'lat' => $post->getGeolocation()['lat'],
        'lng' => $post->getGeolocation()['lng'],
        'created_by' => $post->getHandle()
    );
}

header('Content-Type: application/json; charset=utf-8');
die(json_encode($postData));

?>