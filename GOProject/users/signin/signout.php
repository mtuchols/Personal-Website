<?php

// Destroy session and redirect to the login page
session_unset();
header('Location: /');
exit;

?>