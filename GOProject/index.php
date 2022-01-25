<?php

session_start();

require_once("classes/db.php");
require_once("classes/Like.php");

$cssIncludes = array();
$jsIncludes = array();
$jsAsyncIncludes = array();

// Get body content
ob_start();
include "controller.php";
$bodyContent = ob_get_clean();

// Include the site template
if (array_key_exists('no_template', $_REQUEST) && (int)$_REQUEST['no_template'] === 1) {
    echo $bodyContent;
} else {
    include "template.php";
}

?>
