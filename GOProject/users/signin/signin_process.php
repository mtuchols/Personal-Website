<?php

require_once("classes/Account.php");

$account = false;
try {
    $account = Account::getByEmail($_REQUEST['user']);
} catch (Exception $e) {
    try {
        // Try to find account with a matching handle
        $account = Account::getByHandle($_REQUEST['user']);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

if ($account) try {
    if (!$account->verifyPassword($_REQUEST['password'])) {
        throw new Exception("incorrect password");
    }
    $_SESSION['user'] = $account->getHandle();
    echo "success";
} catch (Exception $e) {
    echo $e->getMessage();
}

?>