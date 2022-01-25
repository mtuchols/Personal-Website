<?php

namespace classes;

use mysqli;

// Static singleton class for database connections
class db {
    private static $hostname = "goproject.cjb1owqivmkg.us-east-2.rds.amazonaws.com";
    private static $user = "admin";
    private static $password = "finalproject";
    private static $database = "GOProject";

    private static $instance = null;

    private function __construct() {}

    public static function connect() {
        if (is_null(self::$instance)) {
            self::$instance = new mysqli(self::$hostname, self::$user, self::$password, self::$database);
        }
        return self::$instance;
    }
}

?>
