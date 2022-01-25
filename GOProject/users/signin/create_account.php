<?php

require_once("classes/Account.php");

try {
    $account = new Account();

    $account->setName($_REQUEST['name']);
    $account->setEmail($_REQUEST['email']);
    $account->setHandle($_REQUEST['handle']);

    // Make sure the password and confirm password fields match
    if ($_REQUEST['password'] !== $_REQUEST['confirm_password']) {
        throw new Exception("Passwords don't match.");
    }
    $account->setPassword($_REQUEST['password']);

    $birthday = str_pad($_REQUEST['birth_year'], 4, "0") . "-" . str_pad($_REQUEST['birth_month'], 2, "0", STR_PAD_LEFT) . "-" . str_pad($_REQUEST['birth_day'], 2, "0", STR_PAD_LEFT);
    $account->setBirthday($birthday);
    $account->save();

    $_SESSION['user'] = $account->getHandle();
    echo "success";
} catch (Exception $e) {
    echo $e->getMessage();
}

?>