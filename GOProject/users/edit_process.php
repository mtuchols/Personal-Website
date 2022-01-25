<?php

$account = $GLOBALS['user'];

try {
    // Modify fields and save changes
    $account->setName($_REQUEST['name']);
    $account->setBio($_REQUEST['bio']);
    $birthday = str_pad($_REQUEST['birth_year'], 4, "0") . "-" . str_pad($_REQUEST['birth_month'], 2, "0", STR_PAD_LEFT) . "-" . str_pad($_REQUEST['birth_day'], 2, "0", STR_PAD_LEFT);
    $account->setBirthday($birthday);
    $account->save();
    echo "success";
} catch (Exception $e) {
    echo $e->getMessage();
}

?>