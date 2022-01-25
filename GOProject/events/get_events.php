<?php

use classes\db;

try {
    $db = db::connect();
    $qLoad = "SELECT * FROM `Events`" ;
    $rLoad = $db->query($qLoad);
    $events = array();
    while ($event = $rLoad->fetch_assoc()) {
        $events[] = $event;
    }
    echo json_encode($events);
} catch (Exception $e) {
    echo $e->getMessage();
}

?>
